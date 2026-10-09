# 🌿 RAYANOVA T.D — Digital B2B Export Platform & CRM
## Document d'Architecture Logicielle & Cahier des Charges Technique Maître (Master Blueprint)

---

### 1. 📌 Vision & Positionnement Stratégique
**Rayanova T.D (Trading & Distribution)** est une société tunisienne d'exportation de produits agroalimentaires de haute qualité (Huile d'olive extra-vierge, Dattes Deglet Nour, Tomates séchées, Épices, Sel marin, Conserves de thon et sardines).

Contrairement à un site e-commerce B2C classique avec prix fixes et paiement par carte bancaire, Rayanova fonctionne sur un modèle **B2B International (Commodities & Packaged Goods)** où :
- Les prix sont fluctuants et dépendent des volumes, incoterms (FOB, CIF, CFR), types de conditionnement et ports de destination.
- L'expérience client doit allier **luxe méditerranéen**, **rigueur technique/alimentaire** (acidité, polyphénols, certifications BIO, ISO, FDA), et **fluidité commerciale (RFQ -> Négociation -> Contrat -> Facturation)**.

---

### 2. 🏛️ Choix Technologiques Recommandés (Stack Technique)

| Composant | Technologie Recommandée | Justification Senior Full-Stack |
| :--- | :--- | :--- |
| **Framework Backend** | **Laravel 11.x** | Robustesse, architecture MVC propre, Form Requests, Queues, Mailables, Signed URLs natifs. |
| **Frontend & Réactivité** | **Livewire 3 + Alpine.js** ou **Inertia.js (Vue 3)** | Réactivité SPA sans rechargement pour le sélecteur circulaire (Wheel Picker) et le panier de devis, tout en préservant un SSR/SEO impeccable. |
| **Styling & Design System** | **Tailwind CSS + Animated Morpho-Glass (Glassmorphism de Luxe)** | Effet verre dépoli multi-couches (`backdrop-blur-xl`, liserés irisés, orbes lumineuses animées en arrière-plan). Palette organique agroalimentaire prestigieuse : Vert olive profond (`#1B3B2B`), Or liquide / Miel ambré (`#D4AF37`), Terre cuite & Harissa (`#B84A28`), Lin naturel & Crème (`#FBF8F2`), Ardoise obsidian (`#101512`). |
| **Gestion des Médias & Hero** | **Dynamic CMS Hero Slider (géré depuis le Dashboard)** | Contrôle total par l'Admin des images, vidéos, titres et slides du Hero sans toucher au code. |
| **Base de Données** | **MySQL 8.0+** ou **PostgreSQL** | Support natif des colonnes JSON indexées pour les attributs nutritionnels dynamiques et les spécifications de conditionnement. |
| **Moteur PDF** | **barryvdh/laravel-dompdf** ou **spatie/laravel-pdf** | Génération de devis officiels, contrats pro-forma et factures commerciales en haute résolution (A4 print-ready). |
| **Gestion des Rôles** | **Spatie Laravel-Permission** | Gestion stricte Super Admin (seul autorisé à supprimer) vs Admin (gestion, devis, édition). |
| **Mailing & Inbound** | **Laravel Mailables + Resend / Mailgun / Postmark** | Envoi d'emails transactionnels stylisés + Inbound webhooks pour capturer les réponses directes des clients. |
| **Internationalisation** | **Laravel Localization (mcamara/laravel-localization)** | URLs préfixées (`/ar/`, `/en/`, `/fr/`) avec balises `hreflang` et fallback automatique. |

---

### 3. 🧩 Modules Fonctionnels & Architecture Métier

#### 3.1. Front-Office : Vitrine Interactive & UX de Luxe
1. **Header & Navigation Multilingue :** Sélecteur de langue (AR, EN, FR) avec bascule instantanée et direction RTL/LTR automatique.
2. **Catalogue & Fiche Produit Interactive (Dual-Pane) :**
   - **Côté Visuel :** Galerie HD, vidéo de récolte/production, badges de certification (BIO, Halal, ISO 22000).
   - **Côté Données Techniques :** Tableau dynamique des valeurs organoleptiques et chimiques (ex: Acidité < 0.2%, Polyphénols > 450 mg/kg, Indice de peroxyde).
3. **Le Sélecteur Circulaire de Conditionnement (iOS Alarm Dial Picker) :**
   - Sélecteur tactile et fluide inspiré de la roue iOS pour choisir :
     - **Mode :** `Vente en vrac (Bulk / صب)` (Flexitank 24,000L, Fûts 200L, IBC 1000L) ou `Conditionné (Bottled / معلب)` (Bouteilles verre Dorica/Marasca 250ml, 500ml, 750ml, 1L, Bidons métal 3L, 5L).
     - **Personnalisation :** Choix de capsule, étiquetage personnalisé (Private Label) ou marque Rayanova.
4. **Panier de Devis B2B (Quote Cart) :**
   - Ajout de multiples références avec leurs configurations spécifiques de conditionnement.
   - Mini-tiroir latéral réactif (Slide-over drawer).
5. **Formulaire RFQ (Request for Quotation) :**
   - Coordonnées de l'importateur : Raison sociale, Pays, N° TVA/Tax ID, Contact (Nom, Email, WhatsApp/Tél).
   - Modalités commerciales : Incoterm souhaité (FOB, CIF, CFR), Port de livraison finale, Type de transport (Maritime FCL/LCL, Aérien), Calendrier de livraison estimé.
6. **Architecture Mobile-First & Ergonomie Tactile :**
   - Conception 100% responsive adaptée aux acheteurs internationaux sur smartphone.
   - Adaptation du Wheel Picker tactile (touch swipe), fiches produits empilées avec onglets de specs, et panier de devis en "Bottom Sheet" ancrée au bas de l'écran avec validation au pouce.
   - Respect strict des cibles tactiles minimales de 48px et inversion fluide en mode RTL (Arabe).

---

#### 3.2. Moteur de Devis & CRM de Négociation (Sales Pipeline)

```
[Visiteur envoie RFQ]
         │
         ▼
[Email Confirmation Auto au Client + Notification Email au Super Admin]
         │
         ▼
[Admin ouvre le Deal dans le Dashboard & saisit les prix unitaires / conditions]
         │
         ▼
[Système envoie l'offre chiffrée par Email avec Magic Link sécurisé + Devis PDF]
         │
         ├─────────────────────────────────────────┐
         ▼                                         ▼
[Option A: Client clique Magic Link]     [Option B: Client répond par email]
   - Ouvre page sécurisée (Signed URL)      - Inbound Webhook capture la réponse
   - Peut modifier quantités/specs          - Message attaché au fil du deal
   - Ou clique sur "Confirmer l'offre"      - Statut passe à "Client Replied"
         │                                         │
         └─────────────────┬───────────────────────┘
                           │
                           ▼
[Offre Confirmée -> Déclenchement du Générateur de Contrat]
                           │
                           ▼
[Génération du Contrat Officiel (PDF) + Envoi avec instructions DHL & RIB]
                           │
                           ▼
[Génération de la Facture Commerciale Pro-forma]
```

---

#### 3.3. Gestion des Contrats & Facturation
- **Dynamic Contract Builder :**
  - Modèle juridique international standard (GATT/Incoterms 2020).
  - Variables injectées dynamiquement : Parties prenantes, description exacte des lots, prix FOB/CIF, coordonnées bancaires (IBAN/SWIFT), documents requis (Certificat phytosanitaire, EUR1, Certificat d'origine, Analyse COI).
  - Génération d'un PDF officiel haute résolution prêt à être paraphé, signé et scellé.
- **Facturation Commerciale :**
  - Facture Pro-forma et Facture Définitive générées à partir du même contrat avec numérotation légale unique séquentielle (`INV-2026-XXXX`).

---

#### 3.4. Back-Office (Tableau de Bord Administrateur)
- **Gestion des Accès à Double Niveau (RBAC) :**
  - `Super Admin (Propriétaire)` : Accès illimité, seul habilité à supprimer des produits, devis, utilisateurs ou historiques.
  - `Admin (Gestionnaire commercial)` : Création/modification des fiches produits, cotation des devis, échange avec les clients, génération des contrats.
- **Gestionnaire Dynamique du Hero Slider & Médias (Hero CMS) :**
  - Contrôle intégral des images/vidéos de fond du Hero depuis le Dashboard.
  - Ajout/modification/suppression de slides avec ordre de tri (drag & drop), titres et sous-titres en 3 langues (AR, EN, FR), badges et liens d'action (CTA).
  - Gestion des bannières d'en-tête de catégories et visuels de l'histoire du terroir.
- **Pipeline Kanban des Devis :**
  - Colonnes de statut : `Nouveau (New)` ➔ `Chiffré (Quoted)` ➔ `Négociation/Modifié` ➔ `Confirmé (Won)` ➔ `Contrat Envoyé` ➔ `Clôturé (Invoiced)`.
- **Catalogue & EAV / Attributs JSON :**
  - Capacité d'ajouter des spécifications nutritionnelles et techniques sans toucher au code.
- **SEO & Marketing Social :**
  - Prévisualisation OpenGraph (Facebook, LinkedIn, X, WhatsApp).
  - Boutons de génération de liens d'affiliation/partage avec UTM tags pré-intégrés.
- **Statistiques & Business Intelligence :**
  - Compteur de consultations par produit.
  - Répartition géographique des requêtes (pays demandeurs, ports de destination les plus fréquents).

---

### 4. 🌟 Modules Stratégiques à Fort Impact B2B (Export & Conversion)

#### 4.1. Module d'Échantillonnage International (Sample Request Flow)
- **Objectif :** Aucun importateur sérieux ne commande 1 conteneur (50k$-200k$) sans avoir testé le produit.
- **Fonctionnalité :** Bouton *"Demander un pack d'échantillons / Request a Sample"* sur chaque produit.
- **Workflow :** Saisie des coordonnées de l'entreprise, prise en charge forfaitaire du fret express (DHL / FedEx Express), génération automatique du bordereau d'expédition et suivi de dégustation.

#### 4.2. Centre de Certifications & Fiches Techniques (Lab COA & Compliance Hub)
- **Téléchargement sécurisé des analyses de laboratoire (CoA) :**
  - Huile d'olive : Certificat du Conseil Oléicole International (COI), taux d'acidité, peroxydes, profil en acides gras.
  - Certifications téléchargeables en PDF : BIO (Ecocert / USDA Organic), ISO 22000, HACCP, FDA Food Facility Registration Number (pour les acheteurs US), Halal / Kosher.

#### 4.3. Simulateur Logistique Palettes & Conteneurs (Container Loading Calculator)
- Calculateur interactif pour aider le directeur des achats :
  - Équivalence en **Palettes Euro (80x120)** ou **Palettes Standard (100x120)**.
  - Capacité d'un conteneur **20ft FCL** (ex: 10-11 palettes, ~21 tonnes) vs **40ft FCL**.
  - Spécifications **Bulk / Vrac** : Flexitank 24 000 Litres, IBC 1000L.

#### 4.4. Service Private Label & Marque Blanche (OEM Packaging)
- Section dédiée aux centrales d'achat et chaînes de distribution souhaitant conditionner sous leur propre marque :
  - Sélection des bouteilles (Dorica, Marasca, Antique, etc.), étiquettes sur-mesure, bouchons inviolables.
  - Upload direct des éléments graphiques du client via le formulaire de devis.

#### 4.5. Durée de Validité & Gestion des Devises (Quotation Expiry & Multi-Currency)
- Dans l'agri-business, les cours mondiaux de l'huile d'olive et des devises fluctuent.
- Tout devis émis porte une **durée de validité stricte** (ex: 7 ou 14 jours).
- Support multi-devises natif : **EUR (€)**, **USD ($)**, **GBP (£)**, **TND (DT)**.

#### 4.6. Accélérateurs de Conversion (B2B Lead Magnets)
- **WhatsApp B2B Export Desk :** Bouton de discussion instantanée avec message pré-rempli contenant le produit et la quantité consultée.
- **Téléchargement du Catalogue Général 2026 (PDF) :** Capture de lead (Raison sociale, Pays, Email, WhatsApp) avant le téléchargement.
- **Traçabilité & Terroir Tunisien :** Mise en valeur des terroirs (Chemlali, Chetoui pour l'huile ; Tozeur/Kébili pour les dattes Deglet Nour ; Cap Bon pour la harissa/tomates).

---

### 5. 🗄️ Architecture de la Base de Données (Schéma Enrichi)

#### `users` & `roles`
- `id`, `name`, `email`, `password`, `role` (`super_admin`, `admin`), `avatar`, `created_at`...

#### `hero_slides` (Gestion du Hero & Visuels depuis le Dashboard)
- `id`, `title` (JSON: `{ar, en, fr}`), `subtitle` (JSON: `{ar, en, fr}`), `badge_text` (JSON: `{ar, en, fr}`).
- `cta_text` (JSON: `{ar, en, fr}`), `cta_url`.
- `media_type` (`image`, `video`), `media_path`, `overlay_opacity` (défaut `0.45`).
- `glass_card_content` (JSON: points clés en vitrine avec effet Morpho-Glass).
- `sort_order` (integer), `is_active` (booléen), `created_at`...

#### `categories`
- `id`, `slug`, `name` (JSON: `{ar, en, fr}`), `description` (JSON), `image_path`, `banner_image_path`, `is_active`, `sort_order`.

#### `products`
- `id`, `category_id`, `slug`, `title` (JSON: `{ar, en, fr}`), `subtitle` (JSON), `description` (JSON).
- `featured_image`, `gallery` (JSON array).
- `nutritional_specs` (JSON: acidity, peroxides, polyphenol, origin, harvest_year, variety).
- `logistics_specs` (JSON: gross_weight, units_per_box, boxes_per_pallet, pallet_weight).
- `allowed_packaging_types` (JSON: `["bulk", "bottle_glass", "tin_can", "plastic_drum"]`).
- `allowed_sizes` (JSON: `["250ml", "500ml", "750ml", "1L", "3L", "5L", "1000L IBC", "24000L Flexitank"]`).
- `certificates` (JSON array des IDs de certificats associés).
- `meta_title` (JSON), `meta_description` (JSON), `og_image`.

#### `product_certificates`
- `id`, `name`, `issuer`, `badge_icon`, `pdf_file_path`, `valid_until`.

#### `quotation_requests` (Les Dossiers / Deals B2B)
- `id`, `reference_number` (ex: `RFQ-2026-0089`).
- `request_type` (`quote`, `sample_request`, `private_label`).
- `company_name`, `contact_name`, `email`, `phone`, `whatsapp`, `country`, `tax_number`, `website`.
- `incoterm` (`FOB`, `CIF`, `CFR`, `EXW`), `destination_port`, `shipping_method` (`sea_fcl`, `sea_lcl`, `air`).
- `currency` (`EUR`, `USD`, `GBP`, `TND`).
- `status` (`new`, `quoted`, `client_viewed`, `client_modified`, `confirmed`, `contract_issued`, `closed`, `cancelled`).
- `valid_until` (Date d'expiration de l'offre).
- `signed_token` (Hash unique sécurisé pour le Magic Link).
- `last_viewed_at` (Tracking d'ouverture du lien par le client).
- `admin_notes`, `client_notes`.

#### `quotation_items` (Lignes de devis)
- `id`, `quotation_request_id`, `product_id`.
- `packaging_type`, `size`, `quantity`, `unit` (`units`, `cartons`, `pallets`, `liters`, `metric_tons`).
- `offered_unit_price`, `subtotal`.
- `is_private_label` (booléen), `custom_branding_notes`.

#### `quotation_messages` (Fil de négociation / Thread)
- `id`, `quotation_request_id`, `sender_type` (`admin`, `client`), `sender_name`, `message_body`, `attachments` (JSON), `created_at`.

#### `contracts` & `invoices`
- `id`, `quotation_request_id`, `document_type` (`proforma_contract`, `commercial_invoice`).
- `document_number` (ex: `CNT-2026-0012` ou `INV-2026-0034`).
- `pdf_path`, `payment_terms`, `bank_details_id`, `required_documents` (JSON: Eur1, Phyto, Origin, etc.).
- `dhl_tracking_number`, `signed_copy_path`, `status`.

---

### 6. 🚀 Plan d'Exécution par Sprints (Feuille de Route Complète)

- **Sprint 1 : Socle Technique, Internationalisation & Modélisation**
  - Installation Laravel 11 + Livewire 3 + Alpine.js + Tailwind CSS.
  - Configuration trilingue (AR, EN, FR) avec support complet RTL/LTR.
  - Implémentation du schéma de base de données complet (Migrations, Seeders, Modèles).
- **Sprint 2 : Vitrine de Prestige & Sélecteur Circulaire de Packaging**
  - Pages d'accueil, terroirs, et catalogue de luxe.
  - Composant interactif "Wheel Picker" (façon alarme iPhone) pour les contenances et types de conditionnement.
  - Calculateur de chargement conteneurs/palettes.
  - Tiroir latéral de devis (Quote Cart).
- **Sprint 3 : Moteur de Devis, Échantillons & Magic Links Sécurisés**
  - Formulaires RFQ & Demande d'échantillons avec validation stricte.
  - Templates d'emails transactionnels de prestige Rayanova.
  - URLs signées cryptographiquement (`URL::signedRoute`) pour la consultation et modification du devis en un clic sans login.
- **Sprint 4 : Back-Office B2B, Négociation & Générateur de Contrats/Factures**
  - Dashboard Admin avec Pipeline Kanban des deals.
  - Interface de chiffrage par l'Admin avec support multi-devises et date d'expiration.
  - Générateur automatique de Contrats de vente internationale (PDF A4 print-ready) et Factures Pro-forma.
  - Gestion des rôles Spatie (Super Admin avec droit de suppression exclusif vs Admin).
- **Sprint 5 : Marketing B2B, Référencement (SEO) & Déploiement**
  - Intégration WhatsApp Business Export Desk & Téléchargement Catalogue PDF (Lead capture).
  - OpenGraph dynamique pour réseaux sociaux et balisage Schema.org pour les produits agroalimentaires.
  - Optimisation des performances, cache, audit de sécurité et mise en production.
