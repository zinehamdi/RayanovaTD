# 🎨 Module 04 — Design System & Vivid Luxury UI/UX

> **⚠️ DOCUMENT CRITIQUE — Le prochain agent DOIT lire ce fichier EN ENTIER avant de toucher au CSS, JS ou Blade.**

---

## 0. Résumé Exécutif pour l'Agent Suivant

### Ce qui a été fait :
1. **Remplacement complet de la palette "dark mode"** par un thème **lumineux, chaud et vivide** (fond ivoire `#FEFCF7`, accents ambrés/dorés/émeraude).
2. **Création d'un système de classes CSS utilitaires** dans `resources/css/app.css` (à l'intérieur de `@layer utilities`) pour :
   - Arrière-plans de sections colorés et visibles
   - Cartes avec bordures dorées et ombres profondes
   - Icônes avec halos lumineux animés
   - Animations de scroll (reveal), shimmer, breathe, float
3. **Ajout d'un IntersectionObserver JS** dans `resources/js/app.js` pour déclencher les animations au scroll.
4. **Application de toutes les classes** dans `home.blade.php`, `app.blade.php` et `product-wheel-picker.blade.php`.
5. **Build Vite** compilé avec succès (CSS ≈ 85 kB, JS ≈ 53 kB).

### Règles Absolues :
- **❌ PAS de mode sombre / dark mode** — Le client l'a explicitement rejeté.
- **❌ PAS de couleurs pâles/near-white** — Elles sont INVISIBLES à l'écran. Si un `#FFF...` apparaît dans un background, c'est trop pâle.
- **❌ PAS de particules dorées** — Rejeté comme "trop pour un site B2B".
- **✅ Couleurs VIVIDES et VISIBLES** — Les arrière-plans doivent être clairement teintés (ambre `#FDEAB8`, vert sauge `#C8DCC7`, etc.).
- **✅ Animations réactives au scroll** — Chaque section doit avoir des éléments qui apparaissent au défilement.
- **✅ Hover effects sur toutes les cartes** — translateY, scale, border-color change, shadow intensification.

---

## 1. Direction Artistique : Luxe Agroalimentaire Méditerranéen Lumineux

L'identité visuelle de **Rayanova T.D** allie la noblesse des produits de la terre tunisienne (huile d'olive d'exception, dattes de Tozeur, or rouge et épices) à une interface **vivide, chaude et premium** sur fond ivoire lumineux.

**Mots-clés du style visuel :** Premium B2B, Warm Luxury, Mediterranean Glow, Gold & Emerald, Vivid & Alive.

---

## 2. Palette Chromatique Officielle (Color Tokens)

### 2.1 Tokens Tailwind (`tailwind.config.js`)
```css
:root {
  /* Vert Olive Profond (Nature & Terroir) */
  --color-olive-dark: #102219;
  --color-olive-primary: #1B3B2B;
  --color-olive-light: #2D5841;

  /* Or Liquide & Ambre (Pureté & Prestige) */
  --color-gold-primary: #D4AF37;
  --color-gold-warm: #C29B38;
  --color-amber-glow: #F3C64F;

  /* Terre Cuite Méditerranéenne */
  --color-terracotta: #B84A28;
  --color-crimson-deep: #8E2F14;

  /* Neutres */
  --color-linen-cream: #FBF8F2;
  --color-sand-soft: #EFE7DA;
  --color-obsidian-charcoal: #101512;
}
```

### 2.2 Couleurs Vivides RÉELLES utilisées dans le CSS

> **⚠️ IMPORTANT : Les couleurs ci-dessous ont été calibrées pour être VISIBLES à l'écran. Ne pas les diluer.**

| Contexte | Hex Clair | Hex Sombre | Utilisation |
|---|---|---|---|
| Section Warm (ambre) | `#FFF3D4` | `#FDEAB8` | `bg-section-warm` |
| Section Olive (sauge) | `#E0EDDF` | `#C8DCC7` | `bg-section-olive` |
| Section Gold Glow | `#FFF6D9` | `#FCEAB0` | `bg-section-gold-glow` |
| Section Terroir | `#F5ECD9` | `#EBDDBC` | `bg-section-terroir` |
| Carte Gold | `#FFF3D4` | `#FDEAB8` | `vivid-card-gold` |
| Carte Emerald | `#D1FAE5` | `#BBF7D0` | `vivid-card-emerald` |
| Icône Gold | `#F5E6A3` | `#EDD070` | `icon-glow-gold` |
| Icône Emerald | `#A7F3D0` | `#6EE7B7` | `icon-glow-emerald` |
| Icône Terracotta | `#FDDCB5` | `#FDB880` | `icon-glow-terracotta` |

---

## 3. Stack Technologique du Design

| Couche | Technologie | Fichier |
|---|---|---|
| CSS Framework | **Tailwind CSS v3** + Classes utilitaires custom | `resources/css/app.css` |
| Animations CSS | `@keyframes` natifs + transitions `cubic-bezier` | `resources/css/app.css` |
| Scroll Observer JS | `IntersectionObserver` API native | `resources/js/app.js` |
| Counter Animation | `requestAnimationFrame` avec easing cubique | `resources/js/app.js` |
| Parallax | `data-parallax` attribute + scroll listener | `resources/js/app.js` |
| Build Tool | **Vite** (`npm run build`) | `vite.config.js` |
| Fonts | Google Fonts : Cairo, Cinzel, Outfit, Playfair Display | import dans `app.css` ligne 1 |
| Components | Alpine.js + Livewire | Blade templates |

---

## 4. Architecture du CSS (`resources/css/app.css`)

Le fichier CSS est organisé en sections claires :

### 4.1 Structure du fichier (440 lignes)

```
Ligne   1     : @import Google Fonts
Lignes  3-5   : @tailwind directives (base, components, utilities)
Lignes  7-262 : @layer utilities { ... } — TOUTES les classes custom
Lignes 264-393: @keyframes & utilities hors @layer
Lignes 395-440: Vivid Card Variants (hors @layer pour specificity)
```

### 4.2 Classes Utilitaires Custom (à l'intérieur de `@layer utilities`)

#### A. Ombres 3D Luxe
| Classe | Effet |
|---|---|
| `.shadow-luxury-3d` | Ombre multi-couches profonde (olive + halo doré) |
| `.shadow-luxury-3d-hover:hover` | Ombre intensifiée + translateY(-7px) |
| `.shadow-pedestal-3d` | Ombre de "piédestal" pour produits |
| `.shadow-gold-glow` | Halo doré fort (opacité 0.55, très visible) |
| `.glass-card-3d` | Carte verre avec glow or + hover float |
| `.glass-panel-clean` | Panneau translucide avec backdrop-blur |

#### B. Texte Dégradé
| Classe | Rendu |
|---|---|
| `.text-gradient-gold` | Dégradé doré intense (#A87812 → #D4AF37 → #92690B) |
| `.text-gradient-olive` | Dégradé vert profond (#091D14 → #153A29) |

#### C. Conteneurs d'Icônes Lumineux (avec animation breathing)
| Classe | Background | Border | Shadow |
|---|---|---|---|
| `.icon-glow-gold` | `#F5E6A3 → #EDD070` | 2px solid `#D4AF37` | Halo doré 0.50 + ring 4px |
| `.icon-glow-emerald` | `#A7F3D0 → #6EE7B7` | 2px solid `#10B981` | Halo vert 0.50 + ring 4px |
| `.icon-glow-terracotta` | `#FDDCB5 → #FDB880` | 2px solid `#E27653` | Halo terra 0.50 + ring 4px |

#### D. Arrière-plans de Sections
| Classe | Gradient | Utilisation dans Blade |
|---|---|---|
| `.bg-section-warm` | `#FFF3D4 → #FDEAB8 → #FFF0C9` | Section "Pourquoi Rayanova" |
| `.bg-section-olive` | `#E0EDDF → #C8DCC7 → #D8E8D6` | Section Produits + Private Label |
| `.bg-section-gold-glow` | `#FFF6D9 → #FCEAB0 → #FFF6D9` | Section Certifications |
| `.bg-section-terroir` | `#F5ECD9 → #EBDDBC → #F3E9D3` | Section Terroir |

#### E. Animations de Scroll Reveal
| Classe | Animation | Mouvement |
|---|---|---|
| `.reveal-up` | Fade + translateY(52px) → 0 | Apparition du bas vers le haut |
| `.reveal-scale` | Fade + scale(0.90) + translateY(40px) → normal | Zoom in + montée |
| `.reveal-left` | Fade + translateX(-60px) → 0 | Apparition de la gauche |
| `.reveal-right` | Fade + translateX(60px) → 0 | Apparition de la droite |
| `.reveal-child` | Fade + translateY(44px) + scale(0.96) → normal | Staggered children |

> **Mécanisme :** La class `.is-revealed` est ajoutée par le JS `IntersectionObserver` quand l'élément entre dans le viewport. La transition CSS fait le reste.

#### F. Animations Continues
| Classe | Keyframe | Effet |
|---|---|---|
| `.animate-float-slow` | `floatSlow` 5s alternate | Flottement vertical doux (-9px) |
| `.animate-float-delayed` | `floatSlow` 5s delay 2.5s | Flottement décalé |
| `.animate-breathe` | `breatheGlow` 3s infinite | Scale 1→1.12 + halo doré pulsé |
| `.animate-shimmer` | `shine-sweep` 8s (pseudo `::after`) | Balayage lumineux spéculaire |
| `.border-animated-gold` | `borderShift` 4s | Pulsation de couleur de bordure |

#### G. Hero Staggered Enter
| Classe | Delay | Usage |
|---|---|---|
| `.hero-badge-enter` | 0s | Badge "Since XXXX" |
| `.hero-title-enter` | 0.12s | Titre H1 |
| `.hero-subtitle-enter` | 0.22s | Sous-titre |
| `.hero-cta-enter` | 0.32s | Bouton CTA |
| `.hero-card-enter` | 0.25s | Carte de statistiques |

### 4.3 Keyframes (`@keyframes` — hors @layer)

| Keyframe | Durée | Description |
|---|---|---|
| `floatSlow` | — | translateY(0) → translateY(-9px) |
| `heroFadeSlide` | — | opacity 0 + translateY(32px) → visible |
| `heroCardFloat` | — | opacity 0 + translateY(40px) + scale(0.95) → visible |
| `shine-sweep` | — | background-position -200% → 200% (balayage lumineux) |
| `breatheGlow` | — | scale(1) + shadow faible → scale(1.12) + shadow fort |
| `borderShift` | — | border-color #D4AF37 → #E4C358 → #B08E2B |
| `countUp` | — | opacity 0 + translateY(16px) → visible |
| `slideInRight` | — | opacity 0 + translateX(30px) → visible |
| `kenBurns1` | — | scale(1.0) → scale(1.09) + translate |
| `kenBurns2` | — | scale(1.07) → scale(1.0) + translate |

### 4.4 Composants CSS Spéciaux

| Classe | Usage |
|---|---|
| `.wheel-cylinder` | Perspective 3D pour le Drum Picker |
| `.wheel-cylinder-inner` | Transform-style preserve-3d pour le cylindre |
| `.wave-separator` + `svg` | Séparateur SVG ondulé entre sections |
| `.section-accent-line` | Barre décorative dorée dégradée (4px, opacity 1) |
| `.specular-shine` | Reflet spéculaire animé (shine-sweep 6s) |
| Custom scrollbar | Barre de défilement dorée webkit |

### 4.5 Vivid Card Variants (hors @layer — priorité CSS plus haute)

| Classe | Background | Border | Hover |
|---|---|---|---|
| `.vivid-card-gold` | `#FFF3D4 → #FDEAB8` | 2px solid gold 0.55 | translateY(-6px) + shadow 0.50 |
| `.vivid-card-emerald` | `#D1FAE5 → #BBF7D0` | 2px solid emerald 0.50 | translateY(-6px) + shadow 0.45 |

> **⚠️ Ces classes sont HORS `@layer` car les utilities Tailwind dans `@layer` ont la même spécificité et peuvent être overridden par des classes Tailwind inline. En les plaçant hors layer, elles ont une spécificité CSS supérieure.**

---

## 5. Architecture JS (`resources/js/app.js`)

### 5.1 IntersectionObserver pour Scroll Reveals (lignes 4-39)

```javascript
// Configuration
rootMargin: '0px 0px -30px 0px'  // Déclenche 30px avant le bord inférieur du viewport
threshold: 0.08                    // Déclenche quand 8% de l'élément est visible

// Sélecteurs observés
'.reveal-on-scroll, .reveal-up, .reveal-scale, .reveal-stagger, .reveal-left, .reveal-right'

// Comportement
- Ajoute la classe '.is-revealed' quand l'élément entre dans le viewport
- Pour les conteneurs staggered : révèle les enfants '.reveal-child' avec un délai de 100ms entre chaque
- Anime les compteurs '[data-count-to]' à l'intérieur de l'élément révélé
- Unobserve après révélation (une seule animation par élément)
```

### 5.2 Counter Animation (lignes 42-61)

```javascript
// Attributs data supportés
data-count-to="150"           // Valeur cible
data-count-suffix="+"         // Suffixe (ex: "+", "%", "M")
data-count-prefix="$"         // Préfixe (ex: "$", "€")

// Easing : cubic ease-out (1 - (1-t)³)
// Durée : 1800ms
```

### 5.3 Parallax Scroll (lignes 63-78)

```javascript
// Usage en Blade
<div data-parallax="0.15">...</div>

// Comportement
// Déplace l'élément verticalement proportionnellement au scroll
// Utilise requestAnimationFrame + flag ticking pour les performances
// Passive event listener sur scroll
```

---

## 6. Mapping Classes → Blade Templates

### 6.1 `resources/views/home.blade.php` (344 lignes)

| Section | Classes de Section | Classes d'Animation | Classes de Carte |
|---|---|---|---|
| Pourquoi Rayanova | `bg-section-warm` | `reveal-child` (staggered) | `vivid-card-gold`, `vivid-card-emerald` |
| Produits (Catalogue) | `bg-section-olive` | `reveal-up`, `reveal-scale` | — (contient ProductWheelPicker) |
| Terroir | `bg-section-terroir` | `reveal-left`, `reveal-right` | `vivid-card-gold`, `vivid-card-emerald` |
| Certifications | `bg-section-gold-glow` | `reveal-up`, `reveal-child` | `vivid-card-gold` |
| Private Label | `bg-section-olive` | `reveal-scale` | `vivid-card-gold`, `vivid-card-emerald` |

### 6.2 `resources/views/components/layouts/app.blade.php`

| Élément | Classes & Spécifications |
|---|---|
| `<body>` | `bg-[#FEFCF7]` (fond ivoire lumineux, texte slate-900) |
| Ambient Glow (BG) | Radial-gradient doré 14% + émeraude 6% (zero GPU cost) |
| Barre d'annonce | `bg-gradient-to-r from-[#0A2419] via-[#0E3324] to-[#0A2419]` (vert impérial) |
| Brand / Logo | Disposition verticale centrée : Icône 56px en haut + `RAYANOVA T.D` centré en dessous |
| Navbar Links | Pleine largeur `max-w-[1700px]`, `justify-evenly`, polices `text-base sm:text-lg font-black` |
| Panier Devis (Trigger) | Bouton or vif 3D avec micro-animation `hover:scale-105` et badge compteur réactif |
| Footer | Gradient `from-[#FEFCF7] to-[#FEF9EC]` + bordure dorée 2px + `section-accent-line` |

### 6.3 `resources/views/components/product-wheel-picker.blade.php`

| Élément | Classes Vivid Utilisées |
|---|---|
| Carte principale | `vivid-card-gold shadow-luxury-3d` |
| Image produit | `shadow-luxury-3d hover:shadow-luxury-3d-hover` |
| Specular shine | `specular-shine` sur les images |
| Packaging drums | `vivid-card-gold shadow-luxury-3d` |
| Prix total | `vivid-card-gold shadow-luxury-3d` |

### 6.4 `resources/views/components/company-intro-hero.blade.php` (Hero d'Introduction B2B)

| Élément | Classes & Spécifications |
|---|---|
| Conteneur Hero | `min-h-[750px] lg:min-h-[860px]`, fond noir cinématique avec bordure or inférieure |
| Image d'exportation | Photoréaliste Ultra-HD, `object-cover`, filtres de saturation et contraste calibrés |
| Titre Principal | Ligne 1 : `RAYANOVA T.D` (Serif blanc géant), Ligne 2 : Dégradé doré `INTERNATIONAL TRADE & AGRO-EXPORT` |
| Boutons d'Action | RFQ Or 3D + Catalogue Blanc prestige + WhatsApp Desk Émeraude |
| Bandeau Inférieur | 4 Cartes métriques B2B en grille horizontale (`grid-cols-2 lg:grid-cols-4`) avec glassmorphism noir |

---

## 7. Pipeline de Build

```bash
# Compilation des assets
npm run build

# Le build Vite compile :
# - resources/css/app.css → public/build/assets/app-XXXXX.css (≈85 kB gzip ≈14 kB)
# - resources/js/app.js   → public/build/assets/app-XXXXX.js  (≈53 kB gzip ≈20 kB)
# - public/build/manifest.json (mapping des noms hachés)

# ⚠️ TOUJOURS REBUILDER après modification CSS/JS
# Le serveur Laravel lit public/build/manifest.json pour résoudre @vite('...')
```

---

## 8. Instructions de Continuation pour le Prochain Agent

### 8.1 Si tu dois AJOUTER une nouvelle section :
1. Utilise une des classes `bg-section-*` pour le fond
2. Ajoute `reveal-up` ou `reveal-scale` sur les éléments principaux
3. Utilise `vivid-card-gold` ou `vivid-card-emerald` pour les cartes
4. Ajoute `animate-shimmer` sur les cartes premium
5. Ajoute `icon-glow-gold/emerald/terracotta` + `animate-breathe` sur les icônes
6. Sépare les sections avec un `wave-separator` SVG
7. **`npm run build`** après chaque modification

### 8.2 Si tu dois MODIFIER les couleurs :
- Les couleurs des classes `bg-section-*` sont dans `app.css` lignes **123-137**
- Les couleurs des `vivid-card-*` sont dans `app.css` lignes **399-439**
- Les couleurs des `icon-glow-*` sont dans `app.css` lignes **101-117**
- **NE PAS utiliser de couleurs near-white** (comme `#FFFEF7`, `#FEF9EC`, etc.) — le client les rejettera

### 8.3 Si tu dois AJOUTER une animation :
1. Définis le `@keyframes` dans `app.css` (hors `@layer`, après ligne 262)
2. Crée la classe d'animation dans `@layer utilities` (lignes 7-262)
3. Applique-la dans le Blade template
4. Si c'est une animation au scroll, ajoute le sélecteur dans le `querySelectorAll` du JS (ligne 37 de `app.js`)

### 8.4 Erreurs Fréquentes à Éviter :
- **Classes dans `@layer` overridden** : Si une classe custom est ignorée par Tailwind inline, déplace-la hors de `@layer` (comme `vivid-card-*`)
- **Oublier `npm run build`** : Les changements CSS/JS ne s'appliquent PAS sans rebuild
- **Browser cache** : Si le CSS ne change pas visuellement, forcer Ctrl+Shift+R ou vérifier le hash dans `manifest.json`
- **Tailwind purge** : Si une classe custom est utilisée uniquement via binding Alpine `x-bind:class`, Tailwind peut la purger. Ajouter la classe dans `safelist` de `tailwind.config.js`

---

## 9. Spécifications du Sélecteur Circulaire (Wheel Picker)

- Composant réactif en **Alpine.js**
- Classes 3D : `.wheel-cylinder` (perspective: 900px) + `.wheel-cylinder-inner` (preserve-3d)
- Permet de sélectionner les contenances : `250ml`, `500ml`, `750ml`, `1L`, `5L`, `Flexitank 24 000L`
- Carte englobante : `vivid-card-gold shadow-luxury-3d`
- Effet haptique visuel (bounce transition)
- Affichage synchronisé de l'image produit

---

## 10. 📱 Architecture Mobile-First & Ergonomie Tactile

### A. Principes Fondamentaux Mobile B2B
1. **Zones Tactiles :** Minimum `48px x 48px` (Apple HIG & WCAG AAA)
2. **Boutons CTA :** Placés dans la "Thumb Zone" (bas d'écran)
3. **Fiche Produit :**
   - *Desktop :* Côte-à-côte (Image | Specs)
   - *Mobile :* Empilé vertical avec carrousel d'images
4. **Tiroir de Devis (Quote Cart) :**
   - *Desktop :* Volet latéral 420px
   - *Mobile :* Bottom Sheet avec `sticky bottom-0 pb-safe`
5. **Navigation :** Menu hamburger plein écran avec backdrop-blur-2xl
6. **RTL (Arabe) :** Inversion automatique des paddings, alignements et glissements
