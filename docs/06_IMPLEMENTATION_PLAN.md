# 🗺️ Module 06 — Plan d'Exécution Complet & Feuille de Route Détaillée (Master Implementation Plan)

Ce plan découpe la construction intégrale de la plateforme **Rayanova T.D** en **6 Phases chronologiques**, avec les livrables précis, les commandes d'exécution et les critères d'acceptation.

---

## 🧭 Vue d'Ensemble des 6 Phases

| Phase | Nom de la Phase | Objectif Principal | Livrable Clé |
| :---: | :--- | :--- | :--- |
| **01** | **Socle Technique & Scaffolding** | Initialiser Laravel 11, configurer Tailwind, Livewire 3, et l'i18n. | Application bootable, multilingue AR/EN/FR avec switch LTR/RTL. |
| **02** | **Modélisation & Données Réalistes** | Migrations, Modèles Eloquent avec casts JSON, et Seeders de prestige. | Base de données prête avec les produits authentiques (Huile d'olive, Dattes, etc.). |
| **03** | **Design System Morpho-Glass & Vitrine** | Intégrer l'effet verre dépoli animé, Hero dynamique et le Wheel Picker. | Vitrine de luxe réactive avec le sélecteur circulaire façon alarme iOS. |
| **04** | **Moteur RFQ & Magic Links B2B** | Formulaire de devis/échantillons, panier latéral et liens signés. | Cycle complet : Demande de devis ➔ Email brandé ➔ Lien sans mot de passe. |
| **05** | **Back-Office Commercial & Contrats** | Dashboard Admin, Hero CMS, cotation des devis, génération de Contrats PDF. | Pipeline Kanban des deals + Générateur de contrats officiels et factures. |
| **06** | **SEO Export, Marketing & Hardening** | OpenGraph, balisage Schema.org, WhatsApp desk, optimisation cache & sécurité. | Plateforme prête pour la production internationale et les acheteurs mondiaux. |

---

## 📋 Détail Opérationnel par Phase

### 🔹 Phase 01 : Socle Technique & Environnement
1. **Création du projet Laravel 11** dans le répertoire courant avec Composer.
2. **Installation des dépendances fondamentales :**
   - `livewire/livewire` (Réactivité instantanée sans rechargement).
   - `spatie/laravel-permission` (Gestion des rôles Super Admin vs Admin).
   - `mcamara/laravel-localization` (Routage multilingue propre : `/ar`, `/en`, `/fr`).
   - `barryvdh/laravel-dompdf` (Génération des documents A4 haute résolution).
   - `spatie/laravel-activitylog` (Audit trail de chaque action commerciale).
3. **Configuration du Design System :**
   - Installation et configuration de Tailwind CSS avec notre palette agroalimentaire de luxe (`#1B3B2B`, `#D4AF37`, `#B84A28`, etc.).
   - Création du layout maître unifié supportant nativement `dir="rtl"` pour l'arabe et `dir="ltr"` pour l'anglais et le français.

---

### 🔹 Phase 02 : Modélisation des Données & Seeders Agroalimentaires
1. **Création des Migrations :**
   - `hero_slides` (Titres multilingues, médias HD, ordre de tri, switch actif).
   - `categories` & `products` (Champs JSON pour `nutritional_specs`, `logistics_specs`, `allowed_packaging_types`, `allowed_sizes`).
   - `product_certificates` (Certifications BIO, ISO 22000, FDA, analyses COI).
   - `quotation_requests` & `quotation_items` (Gestion de la State Machine des deals).
   - `quotation_messages` (Fil de négociation / emails entrants).
   - `contracts` & `invoices` (Contrats de vente internationale et factures pro-forma).
2. **Création des Modèles Eloquent & Casts :**
   - Casts automatiques `array` / `json` pour les spécifications techniques.
3. **Seeder de Démonstration Réaliste :**
   - Huile d'olive extra vierge BIO (variétés Chétoui et Chemlali, acidité < 0.2%, polyphénols > 480 mg/kg).
   - Dattes Deglet Nour de Tozeur (Branchées, Ravat, Dénoyautées).
   - Tomates séchées au soleil marin et Huile d'olive.
   - Harissa artisanale de Cap Bon et Épices traditionnelles.
   - Thon rouge et Sardines de Méditerranée à l'huile d'olive.
   - Comptes d'accès : **Super Admin** (accès total + suppression) et **Admin Commercial**.

---

### 🔹 Phase 03 : Vitrine de Prestige & Composants Interactifs
1. **Effet Morpho-Glass (Glassmorphism de Luxe) :**
   - Classes utilitaires `.glass-card`, `.glass-navbar`, et liserés dorés subtils.
   - Orbes lumineuses en arrière-plan avec animations CSS fluides (`@keyframes float`).
2. **Hero Slider Dynamique :**
   - Composant réactif affichant les slides pilotés depuis la base de données.
   - Typographie Serif de prestige (`Cinzel` / `Playfair Display`) combinée à `Outfit` / `Cairo` (pour l'arabe).
3. **Le Sélecteur Circulaire de Conditionnement (Wheel Picker façon iOS) :**
   - Composant interactif fluide développé avec Alpine.js.
   - Sélection tactile ou à la molette des contenances (250ml, 500ml, 750ml, 1L, 5L, Flexitank 24 000L).
   - Synchronisation visuelle avec l'affichage de l'emballage (Bouteille Marasca, Dorica, Bidon métal, Vrac).
4. **Calculateur Logistique Palette & Conteneur :**
   - Indication instantanée du cubage et du nombre de cartons/palettes pour remplir un conteneur 20ft ou 40ft FCL.
5. **Panier de Devis Réactif (Quote Drawer) :**
   - Tiroir latéral glissant permettant d'accumuler plusieurs produits et configurations avant de finaliser la demande.

---

### 🔹 Phase 04 : Moteur de Devis (RFQ) & Système de Magic Links
1. **Formulaire RFQ Professionnel :**
   - Informations d'entreprise (Raison sociale, Pays, N° TVA, Contact, WhatsApp).
   - Spécifications export (Incoterm : FOB/CIF, Port de destination, Mode de transport).
   - Option *"Demande de pack d'échantillons"* (Sample Request via DHL).
2. **Système de Notification Transactionnel :**
   - Email de confirmation luxueux avec identité visuelle Rayanova envoyé au client.
   - Email d'alerte immédiat au Super Admin et notification dans le Dashboard.
3. **Magic Link Sécurisé (`URL::signedRoute`) :**
   - Génération d'une URL cryptographiquement signée.
   - Page dédiée permettant au client étranger de visualiser la cotation chiffrée, d'ajuster les volumes, ou de valider l'accord d'un simple clic sans mot de passe.
4. **Capture des Réponses Email (Inbound Mail Flow) :**
   - Possibilité pour le client de répondre directement à l'email pour poser des questions techniques.

---

### 🔹 Phase 05 : Back-Office Commercial & Moteur de Contrats (✅ Terminée)
1. **Tableau de Bord Administrateur (Modern Dark / Glass Accent) :**
   - Indicateurs clés (Nombre de requêtes, pays demandeurs, statut des deals, pipeline financier).
   - Pipeline Kanban des cotations (`resources/views/admin/quotations/index.blade.php`).
2. **Hero & Media CMS :**
   - Interface pour ajouter, réordonner, masquer et modifier les slides du Hero en trilingue (`/admin/slides`).
3. **Module de Cotation :**
   - Saisie du prix unitaire par ligne, choix de la devise (EUR, USD, GBP, TND), fret maritime, assurance et date de validité.
4. **Générateur de Contrats Internationaux (PDF) :**
   - Génération automatique du contrat de vente officiel bilingue conforme Incoterms 2020 (`/admin/contracts/{id}/pdf`).
5. **Générateur de Factures Commerciales Pro-forma :**
   - Émission instantanée au format PDF haute définition (`/admin/invoices/{id}/pdf`).
6. **Contrôle d'Accès Strict :**
   - Le bouton "Supprimer" n'apparaît que pour le Super Admin ; contrôle strict via Spatie RBAC et policy.

---

### 🔹 Phase 06 : SEO International, Marketing & Hardening (✅ Terminée)
1. **Optimisation SEO Multilingue :**
   - Balises `hreflang` automatiques pour Google International (`ar`, `en`, `fr`, et `x-default`).
   - Balises OpenGraph dynamiques pour LinkedIn, WhatsApp, Facebook.
   - Données structurées Schema.org (`Organization`, `PostalAddress`, `ContactPoint`).
2. **Accélérateurs de Conversion :**
   - Bouton WhatsApp Export Desk flottant avec message pré-rempli ciblé (Mobile & Desktop).
3. **Qualité & Tests Automatisés :**
   - 60 assertions automatisées dans `tests/Feature` (Auth, RFQ, Magic Links, Contracts, Invoices, CMS, RBAC) avec un taux de réussite de 100%.

---

## 🏆 État du Projet
**Toutes les phases du Blueprint (Phases 01 à 06) sont 100% terminées, testées et validées.**
