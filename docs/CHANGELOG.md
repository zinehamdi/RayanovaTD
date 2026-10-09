# 📋 Journal des Modifications (CHANGELOG)

Toutes les modifications notables apportées à ce projet seront documentées dans ce fichier.
Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/).

---

## [Unreleased] - 2026-10-02

### Phase 08 : Company Intro Hero & Navbar Architecture Overhaul
- **Added (Company Intro Hero Section) :** Création du composant `<x-company-intro-hero>` placé en haut de la page d'accueil avant le slider de produits.
  - Arrière-plan cinématique d'exportation photoréaliste Ultra-HD (`public/images/hero/company_export_hero.jpg`) avec gestion automatique du cache-busting `?v=timestamp`.
  - Titre principal géant sur 2 lignes : Ligne 1 `RAYANOVA T.D` (typographie serif impériale blanche), Ligne 2 `INTERNATIONAL TRADE & AGRO-EXPORT` / `التجارة الدولية والتصدير الزراعي المتوسطي` en dégradé d'or pur.
  - Bandeau inférieur horizontal intégré contenant 4 cartes d'indicateurs d'exportation clés (Ports de chargement Rades/Sfax, Incoterms 2020, Accréditations EU BIO/FDA/ISO 22000, Modes logistiques FCL/LCL/Flexitank).
- **Added (Company Hero CMS in Admin Dashboard) :** 
  - Migration de base de données `create_company_intro_heroes_table` et modèle Eloquent `App\Models\CompanyIntroHero` avec casts multilingues et accesseurs localisés.
  - Contrôleur d'administration `Admin\CompanyHeroManagementController` (`/admin/company-hero`) et vue `resources/views/admin/company_hero/edit.blade.php`.
  - Téléversement d'image de fond d'exportation depuis le disque dur par Drag & Drop avec prévisualisation en direct et persistance dans `public/images/hero/`.
- **Changed (Header & Navbar Re-architecture) :**
  - **Logo & Brand Vertical Center :** Restructuration de la marque dans la navbar avec l'icône de logo en haut et le texte `RAYANOVA T.D` centré juste en dessous (`flex-col items-center justify-center`).
  - **Full-Width Navigation Grid :** Élargissement de la barre de navigation sur toute la largeur de l'écran (`max-w-[1700px] w-full`) et répartition espacée des liens de navigation (`justify-evenly`, `text-base sm:text-lg font-black`) avec micro-interactions au survol.
- **Fixed (CSS Stacking Context) :** Correction du contexte d'empilement CSS (z-index) du Hero pour garantir la visibilité totale et sans voile excessif de l'image de fond sur tous les navigateurs.

## [Unreleased] - 2026-10-01

### Phase 07 : Vivid Luxury Design Overhaul & Internationalisation Dashboard (Mise à jour)
- **Added (Arabic & Multilingual Dashboard) :** Intégration complète de la langue **Arabe 🇸🇦** dans le Back-Office d'administration avec prise en charge native du **RTL (`dir="rtl"`)** et de la typographie arabe (`Cairo`), ainsi que l'anglais 🇬🇧 et le français 🇫🇷.
- **Added :** Sélecteur de langue interactif dans la barre latérale et sur l'écran de login (`/admin/lang/{locale}`) via le middleware `SetAdminLocale`.
- **Added :** Fichiers de traductions exhaustifs `lang/ar/admin.php`, `lang/en/admin.php`, `lang/fr/admin.php`.
- **Changed (Admin Dashboard) :** Élimination totale du dark mode dans tout le Back-Office d'administration au profit du thème **Warm Vivid Luxury** (fond ivoire `#F8F5EE` / `#FEFCF7`, cartes blanches avec bordures 2px or et ombres dorées, sidebar vert impérial `#0B251A` à accents dorés).
- **Changed (Typography & Accessibility) :** Agrandissement significatif de toutes les polices d'écriture du dashboard (`text-[10px]` / `text-xs` supprimés au profit de `text-sm`, `text-base`, `text-lg` et titres `text-3xl` / `text-4xl`), avec contrastes élevés et inputs généreux pour une lisibilité maximale.
- **Added (Product Catalog CMS in Dashboard) :** Création du module complet de gestion du catalogue de produits (`/admin/products`) permettant d'ajouter, modifier et supprimer des produits avec téléversement de photos transparentes depuis le disque, sélection de catégorie, titres/descriptions trilingues (AR/EN/FR), types d'emballage et formats de contenance pour le Wheel Picker.
- **Added (Hero Slide Media Upload from Disk) :** Remplacement de la saisie manuelle de chemin texte par un sélecteur de fichier/téléversement direct depuis le disque avec glisser-déposer (Dropzone), prévisualisation instantanée (image/vidéo), calcul automatique de taille de fichier et stockage sécurisé dans le disque `public/storage/slides`.
- **Validation :** Build Vite recompilé avec succès et 100% des tests automatisés (86 assertions) validés.

## [Unreleased] - 2026-09-30

### Phase 07 : Vivid Luxury Design Overhaul — Vitrine Publique
- **Changed :** Abandon total du mode sombre au profit d'un thème lumineux, chaud et vivide sur fond ivoire `#FEFCF7`.
- **Added :** Système de classes CSS utilitaires vivides dans `resources/css/app.css` :
  - **4 arrière-plans de sections** visiblement colorés (`bg-section-warm` ambre `#FFF3D4→#FDEAB8`, `bg-section-olive` sauge `#E0EDDF→#C8DCC7`, `bg-section-gold-glow` doré `#FFF6D9→#FCEAB0`, `bg-section-terroir` sable `#F5ECD9→#EBDDBC`).
  - **2 variantes de cartes vivides** (`vivid-card-gold` ambre chaud, `vivid-card-emerald` vert menthe) avec bordures 2px dorées/émeraude, ombres multi-couches profondes, et hover translateY + glow intensifié.
  - **3 conteneurs d'icônes lumineux** (`icon-glow-gold/emerald/terracotta`) avec backgrounds dégradés saturés, bordures 2px solid, et halos de 0.50 opacité.
  - **6 classes d'ombres 3D** (`shadow-luxury-3d`, `shadow-gold-glow`, `glass-card-3d`, `shadow-pedestal-3d`, etc.) avec glow doré visible.
  - **2 textes dégradés** (`text-gradient-gold`, `text-gradient-olive`) pour les titres premium.
- **Added :** Système d'animations de scroll dans `resources/js/app.js` :
  - `IntersectionObserver` natif avec `threshold: 0.08` et `rootMargin: -30px`.
  - **5 classes reveal** (`reveal-up`, `reveal-scale`, `reveal-left`, `reveal-right`, `reveal-child`) déclenchées par classe `.is-revealed`.
  - **Staggered children** avec délai de 100ms entre chaque enfant.
  - **Counter animation** avec easing cubique, supportant `data-count-to`, `data-count-suffix`, `data-count-prefix`.
  - **Parallax scroll** via `data-parallax` avec `requestAnimationFrame`.
- **Added :** Animations CSS continues :
  - `animate-breathe` (pulsation d'échelle 1→1.12 + halo doré croissant).
  - `animate-shimmer` (balayage spéculaire `::after` pseudo-element).
  - `animate-float-slow/delayed` (flottement vertical doux).
  - Hero staggered enter (badge → title → subtitle → CTA → card avec délais échelonnés).
  - Ken Burns 1 & 2 pour les backgrounds du Hero slider.
- **Added :** Composants CSS spéciaux : `.wave-separator` (SVG ondulé), `.section-accent-line` (barre dorée 4px), `.specular-shine`, `.wheel-cylinder` (perspective 3D).
- **Added :** Scrollbar webkit dorée personnalisée.
- **Changed :** Application complète des classes vivides sur `home.blade.php`, `app.blade.php`, et `product-wheel-picker.blade.php`.
- **Fixed :** Correction des couleurs near-white invisibles (`#FFFEF7→#FFF3D4`, `#FEF9EC→#FDEAB8`, etc.) — les anciennes couleurs étaient indiscernables du blanc.
- **Docs :** Réécriture complète de `docs/04_DESIGN_SYSTEM_UI_UX.md` avec mapping exhaustif des classes, architecture, et instructions de continuation.

## [Unreleased] - 2026-09-29

### Phase 06 : SEO International, Marketing & Accélérateurs de Conversion (Terminée)
- **Added :** Balises SEO multilingues automatiques `<link rel="alternate" hreflang="..." />` pour toutes les langues officielles (`ar`, `en`, `fr`, et fallback `x-default`) dans le `<head>` maître.
- **Added :** Balises OpenGraph dynamiques et Twitter Cards pour un aperçu haut de gamme sur LinkedIn, WhatsApp et réseaux sociaux internationaux.
- **Added :** Données structurées Schema.org JSON-LD complètes (`Organization`, `PostalAddress`, `ContactPoint`) indexables par les moteurs de recherche mondiaux.
- **Added :** Bouton flottant tactile d'accès rapide WhatsApp Export Desk (`resources/views/components/layouts/app.blade.php`) avec effet de pulsation visuelle et message de devis pré-formaté.
- **Added :** Lien sécurisé direct d'accès au portail Back-Office dans le pied de page (`Export Desk Portal 🔒`).
- **Added :** Suite complète de tests automatisés (Feature & Unit) validant l'ensemble de la plateforme avec 60 assertions réussies à 100%.

### Phase 05 : Back-Office Commercial & Moteur de Contrats (Terminée)
- **Added :** Layout d'administration maître `resources/views/components/layouts/admin.blade.php` avec esthétique Dark Obsidian & Morpho-Glass, barre latérale d'outils, badges de rôles Spatie, et alertes de statut.
- **Added :** Module d'authentification sécurisé `AdminAuthController` et écran de connexion de prestige (`resources/views/admin/auth/login.blade.php`) avec redirection automatique des invités configurée dans `bootstrap/app.php`.
- **Added :** Tableau de bord exécutif `DashboardController` (`resources/views/admin/dashboard.blade.php`) avec 4 métriques de performance (valeur du pipeline chiffré, requêtes RFQ reçues, deals confirmés, contrats émis), répartition des pays importateurs et journal d'activité.
- **Added :** Pipeline de deals et cotations `QuotationManagementController` (`resources/views/admin/quotations/index.blade.php` et `show.blade.php`) avec onglets de filtrage Kanban par statut, recherche multi-critères, et bureau de tarification (Export Pricing Desk) permettant la saisie des prix unitaires, fret maritime, assurance, validité et devises (EUR, USD, GBP, TND).
- **Added :** Générateur de Contrats Internationaux `ContractManagementController` (`resources/views/admin/contracts/create.blade.php`) avec gestion des coordonnées bancaires tunisiennes (BIAT, IBAN, SWIFT), liste des documents douaniers obligatoires (EUR.1, Phyto, COI, B/L), et création automatique de la Facture Pro-Forma.
- **Added :** Modèles PDF haute définition A4 prêts pour l'impression avec `barryvdh/laravel-dompdf` : `resources/views/pdf/contract.blade.php` (Contrat de vente international conforme Incoterms 2020 avec sceau d'exportation) et `resources/views/pdf/invoice.blade.php` (Facture Pro-Forma avec décompte financier).
- **Added :** CMS de gestion du Hero Slider `HeroSlideManagementController` (`resources/views/admin/slides/index.blade.php`, `create.blade.php`, `edit.blade.php`) permettant aux administrateurs de créer, modifier et ordonner les bannières trilingues et statistiques de laboratoire en direct sans code.
- **Added :** Application stricte des permissions RBAC Spatie : Le bouton et l'action de suppression définitive des cotations et slides sont exclusivement réservés au Super Admin (`abort_unless(..., 403)` et `@can('delete_quotations')`), protégeant l'intégrité de l'audit commercial.
- **Added :** Suite complète de tests Feature automatisés `tests/Feature/AdminOperationsTest.php` validant l'authentification, le tableau de bord, le chiffrage, la génération des PDF de contrat et de facture, la gestion CMS et la restriction des suppressions (8 tests / 60 assertions validées à 100%).

### Phase 04 : Moteur de Devis (RFQ) & Magic Links Clients (Terminée)
- **Added :** Formulaire de soumission RFQ international multilingue (`resources/views/rfq/create.blade.php`) avec sélecteur de mode de transport (FCL / LCL / Aérien), incoterms (FOB, CIF, CFR, EXW), et gestion des exigences techniques.
- **Added :** Contrôleur `QuotationController` orchestrant le calcul de la référence séquentielle (`RFQ-2026-XXXX`), le vidage du panier session, l'envoi d'emails et la génération de tokens cryptographiques pour les Magic Links.
- **Added :** Mailables HTML et Plaintext personnalisés : `QuotationReceivedClientMail` (accusé de réception automatique) et `QuotationQuotedClientMail` (notification de prix officiel avec Magic Link).
- **Added :** Interface Magic Link client sécurisée (`/quote/review/{reference}?token=...`) permettant la consultation sans mot de passe, l'approbation en un clic avec horodatage, ou l'envoi de requêtes de négociation/modification intégrées au fil de discussion.
- **Added :** Page de succès interactive (`resources/views/rfq/success.blade.php`) avec récapitulatif des spécifications et état en direct.
- **Added :** Suite complète de tests automatisés Feature dans `tests/Feature/RfqFlowTest.php` validant à 100% l'affichage RFQ, la soumission avec panier, l'accès sécurisé par token, la confirmation client et la négociation.

### Phase 03 : Vitrine de Prestige & Composants Interactifs (Terminée)
- **Added :** Layout maître `resources/views/components/layouts/app.blade.php` avec fond animé de verre dépoli Morpho-Glass, orbes lumineuses dynamiques (`float-slow`, `float-reverse`, `pulse-glow`), et support multilingue AR/EN/FR RTL & LTR.
- **Added :** Composant réactif `HeroSlider` affichant les slides pilotées depuis la base de données avec badge de prestige, typographie Serif (`Cinzel`), et carte de statistiques de laboratoire COI.
- **Added :** Composant interactif `ProductWheelPicker` combinant une vue Dual-Pane sur Desktop avec le sélecteur circulaire de contenance (Wheel Picker façon alarme iOS) en Alpine.js pour les contenances (250ml à Flexitank 24 000L), calculateur logistique de conteneur FCL/palettes, et option Private Label (OEM).
- **Added :** Panier de devis réactif Livewire `QuoteCart` prenant en charge un volet coulissant (Slide-over) sur Desktop et une feuille inférieure tactile (Bottom Sheet) avec zone de sécurité sur Mobile.
- **Added :** Contrôleur `HomeController` et vue d'accueil `home.blade.php` avec les sections Terroirs de Tunisie, Certifications internationales, et Private Label.
- **Added :** Compilation Vite 100% propre (CSS 47.6 kB, JS 51.5 kB en 2.2s).

### Phase 02 : Modélisation des Données & Seeders Agroalimentaires (Terminée)
- **Added :** Création des 8 tables et migrations (`hero_slides`, `categories`, `product_certificates`, `products`, `quotation_requests`, `quotation_items`, `quotation_messages`, `contracts`, `invoices`).
- **Added :** Implémentation des Modèles Eloquent avec casts JSON automatiques (`nutritional_specs`, `logistics_specs`, `allowed_packaging_types`, `allowed_sizes`) et accesseurs multilingues (`getLocalizedTitleAttribute`, etc.).
- **Added :** Trait `HasRoles` configuré sur `User` avec séparation stricte des rôles (Super Admin avec droit de suppression exclusif vs Admin Commercial).
- **Added :** `RayanovaDatabaseSeeder` complet peuplé avec les terroirs tunisiens authentiques (Huile d'olive Chétoui/Chemlali BIO, Dattes Deglet Nour de Tozeur, Tomates séchées, Thon de Méditerranée, certifications COI/BIO/FDA/ISO, slides Hero et deal de démonstration B2B).
- **Added :** Exécution et validation sans aucune erreur (`db:seed` 100% opérationnel).

### Phase 01 : Socle Technique & Scaffolding (Terminée)
- **Added :** Installation et configuration de Laravel 11.57.0 (PHP 8.5+).
- **Added :** Intégration de Livewire + Alpine.js, Spatie Permissions, Laravel Localization, DomPDF et Spatie Activitylog.
- **Added :** Configuration de Tailwind CSS avec la palette agroalimentaire de luxe (Olive `#1B3B2B`, Or `#D4AF37`, Terre Cuite `#B84A28`, Lin `#FBF8F2`, Obsidienne `#101512`).
- **Added :** Importation des polices d'excellence (`Cinzel`, `Outfit`, `Cairo`, `Playfair Display`) et création des classes utilitaires Morpho-Glass (`.glass-light`, `.glass-dark`, `.glass-olive`, `.border-gold-glow`).
- **Added :** Spécifications complètes de l'architecture Mobile-First & Touch UX (cibles 48px, Bottom Sheet pour le panier devis, touch swipe pour le Wheel Picker, onglets empilés et support RTL).
- **Added :** Configuration multilingue officielle (Arabe RTL `ar_TN`, Anglais LTR `en_US`, Français LTR `fr_FR`) et enregistrement des alias de middleware dans `bootstrap/app.php`.
- **Added :** Compilation réussie des assets Vite pour la production.
