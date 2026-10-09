# 🗄️ Module 02 — Dictionnaire de Données & Schéma de Base de Données

## 1. Vue d'Ensemble des Relations (Diagramme Logique)

```
[users] ──<hasMany>── [activity_log]
[hero_slides] (Autonome, contrôlé par Dashboard)
[categories] ──<hasMany>── [products]
[products] ──<hasMany>── [quotation_items]
[product_certificates] >──< [products] (Many-to-Many via JSON ou table pivot)

[quotation_requests] ──<hasMany>── [quotation_items]
[quotation_requests] ──<hasMany>── [quotation_messages] (Audit / Fil de négociation)
[quotation_requests] ──<hasOne>── [contracts]
[quotation_requests] ──<hasMany>── [invoices]
```

---

## 2. Définition Détaillée des Tables

### Table : `hero_slides`
Gérée directement par l'administrateur depuis le dashboard pour animer la vitrine d'accueil.
- `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `title` : JSON (ex: `{"ar": "...", "en": "...", "fr": "..."}`)
- `subtitle` : JSON
- `badge_text` : JSON (ex: `{"en": "100% Organic Extra Virgin"}`)
- `cta_text` : JSON (ex: `{"en": "Request a Quote", "fr": "Demander un devis"}`)
- `cta_url` : VARCHAR(255)
- `media_type` : ENUM('image', 'video') DEFAULT 'image'
- `media_path` : VARCHAR(500)
- `overlay_opacity` : DECIMAL(3, 2) DEFAULT 0.45
- `glass_card_content` : JSON NULLABLE (Points clés sous verre dépoli)
- `sort_order` : INT DEFAULT 0
- `is_active` : BOOLEAN DEFAULT TRUE
- `timestamps`

### Table : `categories`
- `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `slug` : VARCHAR(100) UNIQUE
- `name` : JSON (ar, en, fr)
- `description` : JSON NULLABLE
- `image_path` : VARCHAR(500)
- `banner_image_path` : VARCHAR(500) NULLABLE
- `sort_order` : INT DEFAULT 0
- `is_active` : BOOLEAN DEFAULT TRUE
- `timestamps`

### Table : `products`
- `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `category_id` : BIGINT UNSIGNED FOREIGN KEY -> categories(id) ON DELETE CASCADE
- `slug` : VARCHAR(150) UNIQUE
- `title` : JSON (ar, en, fr)
- `subtitle` : JSON NULLABLE
- `description` : JSON NULLABLE
- `featured_image` : VARCHAR(500)
- `gallery` : JSON (Tableau de chemins d'images)
- `nutritional_specs` : JSON (ex: `{"acidity": "< 0.2%", "polyphenol": "480 mg/kg", "peroxides": "< 10 meq/kg"}`)
- `logistics_specs` : JSON (ex: `{"gross_weight": "1.2kg", "units_per_box": 12, "boxes_per_pallet": 72}`)
- `allowed_packaging_types` : JSON (ex: `["bulk", "bottle_glass", "tin_can"]`)
- `allowed_sizes` : JSON (ex: `["250ml", "500ml", "1L", "Flexitank 24000L"]`)
- `is_active` : BOOLEAN DEFAULT TRUE
- `meta_title` : JSON NULLABLE
- `meta_description` : JSON NULLABLE
- `timestamps`

### Table : `quotation_requests` (Les Dossiers / Deals B2B)
- `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `reference_number` : VARCHAR(50) UNIQUE (ex: `RFQ-2026-0001`)
- `request_type` : ENUM('quote', 'sample_request', 'private_label') DEFAULT 'quote'
- `company_name` : VARCHAR(255)
- `contact_name` : VARCHAR(255)
- `email` : VARCHAR(255)
- `phone` : VARCHAR(50) NULLABLE
- `whatsapp` : VARCHAR(50) NULLABLE
- `country` : VARCHAR(100)
- `tax_number` : VARCHAR(100) NULLABLE
- `incoterm` : ENUM('FOB', 'CIF', 'CFR', 'EXW') DEFAULT 'FOB'
- `destination_port` : VARCHAR(255)
- `shipping_method` : ENUM('sea_fcl', 'sea_lcl', 'air') DEFAULT 'sea_fcl'
- `currency` : VARCHAR(3) DEFAULT 'EUR'
- `status` : ENUM('new', 'quoted', 'client_viewed', 'client_modified', 'confirmed', 'contract_issued', 'closed', 'cancelled') DEFAULT 'new'
- `valid_until` : DATE NULLABLE (Date d'expiration de l'offre)
- `signed_token` : VARCHAR(100) UNIQUE (Pour Magic Link sans mot de passe)
- `last_viewed_at` : TIMESTAMP NULLABLE
- `client_notes` : TEXT NULLABLE
- `admin_notes` : TEXT NULLABLE
- `timestamps`

### Table : `quotation_items` (Lignes de devis)
- `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `quotation_request_id` : BIGINT UNSIGNED FOREIGN KEY -> quotation_requests(id) ON DELETE CASCADE
- `product_id` : BIGINT UNSIGNED FOREIGN KEY -> products(id) ON DELETE RESTRICT
- `packaging_type` : VARCHAR(100)
- `size` : VARCHAR(50)
- `quantity` : DECIMAL(12, 2)
- `unit` : VARCHAR(50) (units, cartons, pallets, liters, metric_tons)
- `offered_unit_price` : DECIMAL(12, 2) NULLABLE (Saisi par l'admin)
- `subtotal` : DECIMAL(14, 2) NULLABLE
- `is_private_label` : BOOLEAN DEFAULT FALSE
- `custom_branding_notes` : TEXT NULLABLE
- `timestamps`

### Table : `contracts` & `invoices`
- `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `quotation_request_id` : BIGINT UNSIGNED FOREIGN KEY -> quotation_requests(id)
- `document_type` : ENUM('proforma_contract', 'commercial_invoice')
- `document_number` : VARCHAR(100) UNIQUE
- `pdf_path` : VARCHAR(500)
- `payment_terms` : TEXT
- `bank_details` : JSON (IBAN, SWIFT, Nom de la banque)
- `required_documents` : JSON (Certificats requis pour douane)
- `dhl_tracking_number` : VARCHAR(100) NULLABLE
- `status` : ENUM('draft', 'sent', 'signed_returned', 'cancelled')
- `timestamps`

### Table : `company_intro_heroes` (Section Hero Présentation Entreprise & Export)
- `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `badge` : JSON (`ar`, `en`, `fr`)
- `title` : JSON (`ar`, `en`, `fr`)
- `subtitle` : JSON (`ar`, `en`, `fr`)
- `background_image` : VARCHAR(500) NULLABLE (Chemin vers l'image d'exportation)
- `stats` : JSON (Tableau des 4 cartes métriques : labels trilingues et valeurs)
- `primary_cta_text` : JSON (`ar`, `en`, `fr`)
- `primary_cta_url` : VARCHAR(255)
- `secondary_cta_text` : JSON (`ar`, `en`, `fr`)
- `secondary_cta_url` : VARCHAR(255)
- `whatsapp_number` : VARCHAR(50) NULLABLE
- `is_active` : BOOLEAN DEFAULT TRUE
- `timestamps`

