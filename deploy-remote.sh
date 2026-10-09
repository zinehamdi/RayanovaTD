#!/usr/bin/env bash
# ==============================================================================
# 🚢 Rayanova T.D — 1-Command Remote Deployment from Local Mac to Hostinger
# ==============================================================================
# Usage:
#   1. Set your SSH credentials below (or pass them as environment variables).
#   2. Run from your Mac terminal:
#      bash deploy-remote.sh
# ==============================================================================

set -eo pipefail

# ⚙️ Configuration (Edit these with your Hostinger SSH details from hPanel)
SSH_USER="${HOSTINGER_SSH_USER:-uXXXXXXX}"
SSH_HOST="${HOSTINGER_SSH_HOST:-YOUR_SERVER_IP}"
SSH_PORT="${HOSTINGER_SSH_PORT:-65002}"
REMOTE_PROJECT_PATH="${HOSTINGER_REMOTE_PATH:-~/domains/rayanovatd.com/rayanova}"

# Colors
GREEN='\033[0;32m'
CYAN='\033[0;36m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BOLD='\033[1m'
NC='\033[0m'

echo -e "${CYAN}${BOLD}"
echo "=================================================================="
echo "   🚢 RAYANOVA T.D — INITIATING REMOTE DEPLOYMENT TO HOSTINGER   "
echo "=================================================================="
echo -e "${NC}"

if [ "$SSH_USER" = "uXXXXXXX" ] || [ "$SSH_HOST" = "YOUR_SERVER_IP" ]; then
    echo -e "${YELLOW}⚠️  Notice: Please configure your SSH credentials inside this file or set environment variables:${NC}"
    echo "    export HOSTINGER_SSH_USER=\"your_ssh_username\""
    echo "    export HOSTINGER_SSH_HOST=\"your_server_ip\""
    echo "    export HOSTINGER_SSH_PORT=\"65002\""
    echo "    export HOSTINGER_REMOTE_PATH=\"~/domains/rayanovatd.com/rayanova\""
    echo ""
    read -rp "Enter Hostinger SSH Username (e.g. u123456789): " INPUT_USER
    read -rp "Enter Hostinger Server IP or Domain: " INPUT_HOST
    read -rp "Enter SSH Port (Default: 65002): " INPUT_PORT
    read -rp "Enter Remote Project Path (Default: ~/domains/rayanovatd.com/rayanova): " INPUT_PATH

    SSH_USER="${INPUT_USER:-$SSH_USER}"
    SSH_HOST="${INPUT_HOST:-$SSH_HOST}"
    SSH_PORT="${INPUT_PORT:-65002}"
    REMOTE_PROJECT_PATH="${INPUT_PATH:-$REMOTE_PROJECT_PATH}"
fi

# Step 1: Ensure local Git is up to date and clean
echo -e "${CYAN}==> Checking local Git status...${NC}"
if ! git diff --quiet || ! git diff --cached --quiet; then
    echo -e "${YELLOW}You have uncommitted local changes. Committing before deploy...${NC}"
    git add .
    git commit -m "chore: pre-deployment automated sync $(date '+%Y-%m-%d %H:%M:%S')"
fi

echo -e "${CYAN}==> Pushing latest code to GitHub origin/main...${NC}"
git push origin main
echo -e "${GREEN}✅ Local code pushed to GitHub.${NC}"

# Step 2: Trigger remote deployment via SSH
echo -e "${CYAN}==> Connecting to Hostinger ($SSH_USER@$SSH_HOST:$SSH_PORT) and executing deploy.sh...${NC}"
ssh -p "$SSH_PORT" "$SSH_USER@$SSH_HOST" "cd $REMOTE_PROJECT_PATH && bash deploy.sh"

echo ""
echo -e "${GREEN}${BOLD}=================================================================="
echo "   🎉 REMOTE DEPLOYMENT COMPLETED!                                "
echo "==================================================================${NC}"
echo ""
