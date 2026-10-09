# 🏛️ Module 01 — Architecture Globale & Stack Technique

## 1. Vue d'Ensemble
L'application **Rayanova T.D** est un système hybride combinant :
- Une **Vitrine Internationale B2B Haute Définition** (SEO multilingue, réactivité SPA fluide, branding d'excellence).
- Un **Moteur de Cotation & CRM de Vente Export** (RFQ, Magic Links sans mot de passe, génération de contrats et factures A4).
- Un **Back-Office d'Administration Centralisé** (Gestion du catalogue, Hero CMS dynamique, gestion des accès à double niveau).

---

## 2. Patron d'Architecture Logicielle
Le projet adopte une architecture **Service-Action Pattern (Clean Laravel)** pour découpler la logique métier des contrôleurs HTTP :

```
app/
├── Http/
│   ├── Controllers/          # Contrôleurs HTTP légers (Délèguent aux Actions)
│   ├── Requests/             # Form Requests avec validation stricte
│   └── Middleware/           # Localisation, Signed Route verification, Role checks
├── Models/                   # Modèles Eloquent avec Casts JSON & Relations
├── Actions/                  # Classes d'action à responsabilité unique
│   ├── Quotations/
│   │   ├── CreateQuotationRequestAction.php
│   │   ├── PriceQuotationAction.php
│   │   ├── ConfirmQuotationAction.php
│   │   └── IssueContractAction.php
│   └── Media/
│       └── ReorderHeroSlidesAction.php
├── Services/                 # Services techniques externes
│   ├── Pdf/                  # Moteur de rendu PDF pour Contrats et Factures
│   ├── Mail/                 # Envoi transactionnel avec templates brandés
│   └── Currency/             # Conversion dynamique et gestion des cours
└── Livewire/ / View/         # Composants réactifs (Wheel Picker, Quote Cart, Hero)
```

---

## 3. Matrice des Dépendances Techniques

| Domaine | Package / Outil | Version cible | Rôle |
| :--- | :--- | :--- | :--- |
| **Core** | `laravel/framework` | `^11.0` | Cœur du framework |
| **Frontend Réactif** | `livewire/livewire` + `alpinejs` | `^3.0` | Réactivité sans SPA lourde |
| **Permissions** | `spatie/laravel-permission` | `^6.0` | Rôles Super Admin vs Admin |
| **Audit & Logs** | `spatie/laravel-activitylog` | `^4.0` | Journalisation des actions critiques |
| **Moteur PDF** | `barryvdh/laravel-dompdf` | `^3.0` | Génération Contrats / Factures |
| **Internationalisation** | `mcamara/laravel-localization` | `^2.0` | URLs localisées (`/ar/`, `/en/`, `/fr/`) |
| **Styles** | `tailwindcss` | `^3.4` | Système de styles utilitaires |
