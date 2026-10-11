#!/usr/bin/env bash
# ==============================================================================
# 🚀 Rayanova T.D — Automated Production Deployment Script for Hostinger (SSH)
# ==============================================================================
# Usage on Server:
#   chmod +x deploy.sh
#   ./deploy.sh
#
# Remote execution from local terminal:
#   ssh -p 65002 uXXXXXXX@your-server-ip "cd /path/to/project && ./deploy.sh"
# ==============================================================================

set -eo pipefail

# 🎨 Color definitions
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# 🕒 Timestamp helper
timestamp() {
    date "+%Y-%m-%d %H:%M:%S"
}

log_info() {
    echo -e "${CYAN}[$(timestamp)] ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}[$(timestamp)] ✅ $1${NC}"
}

log_warn() {
    echo -e "${YELLOW}[$(timestamp)] ⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}[$(timestamp)] ❌ $1${NC}"
}

echo -e "${BLUE}${BOLD}"
echo "=================================================================="
echo "      ⚓ RAYANOVA T.D — HOSTINGER PRODUCTION DEPLOYMENT ENGINE     "
echo "=================================================================="
echo -e "${NC}"

DEPLOY_START_TIME=$(date +%s)

# ------------------------------------------------------------------------------
# 1. Environment & Binaries Discovery
# ------------------------------------------------------------------------------
log_info "Discovering environment and executable binaries..."

# Detect PHP CLI (Handle Hostinger CloudLinux /opt/alt/ paths and standard aliases)
if [ -n "$PHP_BIN" ] && command -v "$PHP_BIN" >/dev/null 2>&1; then
    PHP_EXEC="$PHP_BIN"
elif [ -x "/opt/alt/php84/usr/bin/php" ]; then
    PHP_EXEC="/opt/alt/php84/usr/bin/php"
elif [ -x "/opt/alt/php85/usr/bin/php" ]; then
    PHP_EXEC="/opt/alt/php85/usr/bin/php"
elif [ -x "/opt/alt/php83/usr/bin/php" ]; then
    PHP_EXEC="/opt/alt/php83/usr/bin/php"
elif command -v php8.4 >/dev/null 2>&1; then
    PHP_EXEC="php8.4"
elif command -v php8.3 >/dev/null 2>&1; then
    PHP_EXEC="php8.3"
elif command -v php8.2 >/dev/null 2>&1; then
    PHP_EXEC="php8.2"
elif command -v php >/dev/null 2>&1; then
    PHP_EXEC="php"
else
    log_error "No compatible PHP binary found! Ensure PHP 8.2+ is installed and in PATH."
    exit 1
fi

PHP_VERSION=$($PHP_EXEC -r 'echo PHP_VERSION;')
log_success "Using PHP: $PHP_EXEC (Version: $PHP_VERSION)"

# Detect Composer
if [ -n "$COMPOSER_BIN" ] && command -v "$COMPOSER_BIN" >/dev/null 2>&1; then
    COMPOSER_EXEC="$COMPOSER_BIN"
elif [ -f "/usr/local/bin/composer" ]; then
    COMPOSER_EXEC="$PHP_EXEC /usr/local/bin/composer"
elif command -v composer >/dev/null 2>&1; then
    COMPOSER_EXEC="$PHP_EXEC $(command -v composer)"
elif [ -f "./composer.phar" ]; then
    COMPOSER_EXEC="$PHP_EXEC ./composer.phar"
else
    log_error "Composer not found! Please install composer or download composer.phar."
    exit 1
fi

log_success "Using Composer: $COMPOSER_EXEC"

# Detect Node / NPM (Optional on server if building assets locally)
HAS_NPM=false
if command -v npm >/dev/null 2>&1; then
    HAS_NPM=true
    NPM_VERSION=$(npm -v)
    log_success "Using NPM: $(command -v npm) (Version: $NPM_VERSION)"
else
    log_warn "NPM not found on server. Ensure Vite assets are pre-built and present in public/build."
fi

# ------------------------------------------------------------------------------
# 2. Check Prerequisites
# ------------------------------------------------------------------------------
if [ ! -f ".env" ]; then
    log_error ".env file is missing! Copy .env.production.example to .env and configure it before deploying."
    exit 1
fi

# ------------------------------------------------------------------------------
# 3. Enter Maintenance Mode (Zero-Downtime Buffer)
# ------------------------------------------------------------------------------
log_info "Activating maintenance mode..."
$PHP_EXEC artisan down --retry=60 || log_warn "Application was already in maintenance mode or artisan down skipped."

# Trap unexpected errors to ensure the site comes back UP
cleanup_on_failure() {
    log_error "Deployment encountered an error! Restoring application state..."
    $PHP_EXEC artisan up || true
    exit 1
}
trap cleanup_on_failure ERR

# ------------------------------------------------------------------------------
# 4. Fetch & Pull Latest Code from Git
# ------------------------------------------------------------------------------
log_info "Pulling latest changes from branch main..."
git fetch origin main
git reset --hard origin/main
log_success "Git synchronized with origin/main."

# ------------------------------------------------------------------------------
# 5. Install / Update Composer Dependencies
# ------------------------------------------------------------------------------
log_info "Installing PHP dependencies (--no-dev --optimize-autoloader)..."
$COMPOSER_EXEC install --no-dev --prefer-dist --optimize-autoloader --no-interaction --quiet
log_success "Composer dependencies installed successfully."

# ------------------------------------------------------------------------------
# 6. Build Frontend Assets (Vite)
# ------------------------------------------------------------------------------
if [ "$HAS_NPM" = true ]; then
    log_info "Building frontend assets with Vite..."
    if [ -f "package-lock.json" ]; then
        npm ci --prefer-offline --no-audit --quiet
    else
        npm install --no-audit --quiet
    fi
    npm run build
    log_success "Vite production assets compiled successfully."
else
    if [ -f "public/build/manifest.json" ]; then
        log_success "Using existing compiled assets in public/build."
    else
        log_warn "public/build/manifest.json not found! Please build assets locally with 'npm run build' and upload public/build."
    fi
fi

# ------------------------------------------------------------------------------
# 7. Database Migrations
# ------------------------------------------------------------------------------
log_info "Running database migrations (--force)..."
$PHP_EXEC artisan migrate --force
log_success "Database migrations executed."

# ------------------------------------------------------------------------------
# 8. Clear Stale Caches & Build Production Cache Stores
# ------------------------------------------------------------------------------
log_info "Optimizing caches (Config, View, Event, Route Clear)..."
$PHP_EXEC artisan optimize:clear
$PHP_EXEC artisan config:cache
$PHP_EXEC artisan route:clear
$PHP_EXEC artisan view:cache
$PHP_EXEC artisan event:cache
log_success "Production caches generated."

# ------------------------------------------------------------------------------
# 9. Verify Storage Symlink
# ------------------------------------------------------------------------------
log_info "Verifying storage symlink..."
$PHP_EXEC artisan storage:link || true
log_success "Storage symlink checked."

# ------------------------------------------------------------------------------
# 10. Fix File & Directory Permissions
# ------------------------------------------------------------------------------
log_info "Enforcing permissions on storage and bootstrap/cache..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
log_success "Permissions set."

# ------------------------------------------------------------------------------
# 11. Restart Queue Workers (if applicable)
# ------------------------------------------------------------------------------
log_info "Restarting background queue workers..."
$PHP_EXEC artisan queue:restart || true

# ------------------------------------------------------------------------------
# 12. Deactivate Maintenance Mode (Go Live!)
# ------------------------------------------------------------------------------
log_info "Bringing application back live..."
$PHP_EXEC artisan up
trap - ERR

DEPLOY_END_TIME=$(date +%s)
TOTAL_DURATION=$((DEPLOY_END_TIME - DEPLOY_START_TIME))

echo ""
echo -e "${GREEN}${BOLD}=================================================================="
echo "  🎉 DEPLOYMENT COMPLETED SUCCESSFULLY IN ${TOTAL_DURATION} SECONDS!  "
echo "  🌐 Rayanova T.D is LIVE and optimized for production.           "
echo -e "==================================================================${NC}"
echo ""
