# Rayanova T.D. — Cinematic UI Redesign: Master Implementation Specification

> **Status:** PLAN READY — implementation NOT started.
> **Audience:** Implementation agent with limited context (Gemini 3.8 Flash) + human reviewers.
> **Companion file:** `docs/UI_REDESIGN_PROGRESS.md` (read it, then update it after every phase).
> **Audit date:** 2026-10-08 · Audited against the live code in this repository + current screenshots.

**How to use this document (implementation agent):**
1. Read this file, then `docs/UI_REDESIGN_PROGRESS.md`.
2. Look at the actual code before you edit anything. This plan describes intent and file paths; the code is the source of truth for markup.
3. Continue from `NEXT REQUIRED STEP` in the progress file. Never redo completed phases.
4. Run phases in the order given. Each phase lists its files, acceptance criteria and rollback.
5. Never touch anything listed in §26 (Backend protection).
6. After each phase run `npm run build` and `php artisan test`, check `/ar`, `/en` and `/fr`, then update the progress file.

---

## 1. Executive summary

Rayanova already has a strong, recognisable **dark maritime export identity**: a port and vessel photo, a steel/charcoal palette, cyan accents, dark glass, and premium product photography. The problems are not with the brand. They are with **composition, separation and discipline**:

- **One shared background for everything.** A single fixed maritime photo (`#maritime-parallax-bg`) sits behind every section, and every section is `bg-transparent`. Hero, bento, products, traceability and certifications all run together into one long, undifferentiated surface.
- **The hero is overloaded.** Above the fold there are about 12 competing elements: announcement bar, a 96 px navbar, 4 pills, H1, paragraph, an accreditation box with 4 chips, 3 CTAs, a 4-card grid with its own header and footer notes, a bottom metrics rail and a floating WhatsApp button.
- **The product section is a configuration form, not a showroom.** Every product is rendered as a full card with about 10 controls stacked vertically (`product-wheel-picker`). The `@wheel.prevent` handler **hijacks page scrolling** whenever the cursor passes over the size drum.
- **The animation layer is half-dead and wasteful.** Reveal classes are forced visible with `!important`, so the IntersectionObserver does nothing visible. About 330 lines of parchment/page-curl CSS belong to an orphaned component. The maritime background runs an **infinite `requestAnimationFrame` loop** even when idle.
- **Arabic display typography is broken.** `font-serif` maps to `Cinzel → Playfair Display`. Neither font has Arabic glyphs, so every Arabic H1/H2/H3 falls back to an arbitrary system font.
- **Legacy light-theme CSS is still active.** `.vivid-card-steel` (white gradient), `.glass-card-3d` (white 96%) and `.glass-panel-clean` (white) conflict with dark utilities on the same element.

**Target:** a *Mediterranean olive-oil export editorial experience*:
- one dominant focal point per scene
- sections that stack like **layered glass pages** using a single CSS scroll-driven system with a no-JS fallback
- a product **showroom** where the photograph is the hero
- a compact, accessible packaging/size selector that never captures the scroll wheel
- a disciplined semantic palette: gold = value, olive = origin, cyan = interaction only

**Scope:** visual refinement, composition, motion, consistency and performance only. No backend, schema, route, RFQ or cart logic changes.

---

## 2. Current-state repository audit

### 2.1 Stack (verified)
Laravel 11 · PHP 8.2 · Livewire 4.4 · Tailwind CSS 3.4 (`tailwind.config.js`) · Vite 6 · Alpine.js (bundled with Livewire) · mcamara Laravel Localization (ar/en/fr, `dir` set on `<html>`) · SQLite dev.

### 2.2 Layouts — which one is active
| File | Status | Evidence |
|---|---|---|
| `resources/views/components/layouts/app.blade.php` (283 lines) | **ACTIVE.** Used by `home.blade.php` and `rfq/create`, `rfq/review`, `rfq/success` via `<x-layouts.app>` | `x-layouts.app` resolves to `components/layouts/app.blade.php` |
| `resources/views/layouts/app.blade.php` (286 lines) | **LEGACY DUPLICATE, no references found.** Older copy (max-w-7xl, h-22 nav, no favicon block) | `grep` finds no `@extends('layouts.app')` or `x-app-layout` |

- **Decision:** edit only `components/layouts/app.blade.php`. Leave the legacy file untouched (do not delete it; deletion needs approval because Livewire full-page components could resolve it via config).
- `$companyHero` is shared with every view by `app/Providers/AppServiceProvider.php` (view composer). The layout uses `$companyHero?->image_url` for the background.

### 2.3 Homepage section order (actual, `resources/views/home.blade.php`, 525 lines)
| # | Section | Anchor | Lines | Background |
|---|---|---|---|---|
| 1 | `<x-company-intro-hero>` (only if `$companyHero->is_active`) | — | 4–9 | transparent over maritime |
| — | hairline divider | — | 8, 287, 322, 477, 522 | — |
| 2 | B2B Executive Bento Grid: 4 tiles (Lab/COI, Maritime Bulk, OEM Private Label, Trade Corridors) | *(no id)* | 12–284 | `bg-transparent` |
| 3 | Products: header + `@foreach($products)` → `<div id="product-{id}"><x-product-wheel-picker/></div>` | `#products` | 290–319 | `bg-transparent` |
| 4 | Supply-chain timeline: 4 stage cards (Harvest, Extraction, Lab, Port) | `#terroir` | 325–474 | `bg-transparent` |
| 5 | Certifications: DB-driven `$certificates` grid (only section using `reveal-*` classes) | `#certifications` | 480–519 | `bg-transparent` |
| — | Footer, quote-cart drawer, floating WhatsApp (in layout) | — | layout | `bg-black/75` |

There is **no final CTA section**. The navbar links to `/`, `#products`, `#terroir` and `#certifications`; the bento section has no anchor.

### 2.4 Components
| File | Role | Notes |
|---|---|---|
| `components/company-intro-hero.blade.php` (536 lines) | Hero with Alpine state (`products`, `activeIdx`, `albumOpen`, `openAlbum`, `closeAlbum`, `next`, `prev`, `goToProduct`). **State A** = album viewer (`x-show="albumOpen"`, lines 205–339). **State B** = 2-column intro + 4 product cards (lines 341–490). Bottom metrics rail (lines 494–533). | Album products are **hard-coded PHP arrays** (lines 8–93), partly synced with DB titles (lines 96–118). The match uses a loose `sort_order` fallback. Hero respects `show_banner` / `show_badge` (recent admin feature — keep). |
| `components/product-wheel-picker.blade.php` (488 lines) | Per-product configurator: packaging cards, 3D size drum, specs, logistics hint, qty, private label, add-to-cart | See §15. |
| `components/hero-slider.blade.php` (198 lines) | Slide carousel | **ORPHAN:** not rendered anywhere. `$slides` is passed to the hero, but the hero never uses it. Admin slide management (incl. `show_badge`) therefore has no public output. This is documented here; restoring it is a product decision (§26). |
| `livewire/quote-cart.blade.php` (141 lines) | RFQ drawer (desktop slide-over, mobile bottom sheet), `$wire.isOpen`, listens to `toggle-quote-cart` | Visual polish only. |

### 2.5 CSS (`resources/css/app.css`, 800 lines)
| Lines | System | Verdict |
|---|---|---|
| 1 | Google Fonts `@import` (Cairo, Cinzel, Outfit, Playfair) | Keep, but move to `<link>` in the layout (render-blocking `@import`). |
| 13–29 | Global `will-change: transform, opacity` on 12 selectors incl. every vivid card | **Remove.** Permanent `will-change` on many large elements wastes GPU memory. |
| 32–48 | `.shadow-luxury-3d` (light-theme shadow values) | Replace with tokenised elevation. |
| 51–72, 97–105 | `.glass-card-3d`, `.glass-panel-clean`: **white 96% backgrounds** | Legacy light theme → delete after migration. |
| 75–94 | `.shadow-electric-glow`, `.shadow-gold-glow` (**the "gold" glow is actually cyan**) | Rename/replace. |
| 108–130 | `.text-gradient-*` (**`gold` is cyan, `olive` is grey**) | Misleading names → replace with semantic tokens. |
| 136–163 | `.icon-glow-*` (light pastel) | Unused on dark UI → delete if no references remain. |
| 169–175 | `.bg-section-*` all forced `transparent` | Root cause of section merging → replace with the scene system (§19). |
| 181–189 | `.reveal-up/.reveal-scale/.reveal-left/.reveal-right/.reveal-child { opacity:1 !important; transform:translateY(0) !important }` | **Reveals are disabled.** The JS adds `.is-revealed`, but no CSS reacts to it. Dead → replace (§22). |
| 192–246 | float, breathe, shimmer, hero-enter, border-animated-gold | Keep `hero-*-enter` (rename into the motion system). Remove float/breathe/border-shift from production UI. |
| 313–327 | `.specular-shine` (infinite sweep), Ken Burns keyframes | Specular shine is used on product image + CTA (infinite animation) → remove. Ken Burns has no consumer in active views. |
| 330–341 | `.wheel-cylinder*` | Removed together with the drum (Phase 7). |
| 344–364 | wave separator, accent line | Unused → delete if grep confirms. |
| 367–380 | cyan gradient scrollbar | Retint to neutral steel + gold thumb hover. |
| 383–394 | `.btn-interactive` | Merge into the button system. |
| 400–461 | `.vivid-card-*`: **declared after `@tailwind utilities`, so they override Tailwind background utilities**. `.vivid-card-steel` = white gradient, used on the dark product card together with `bg-slate-900/60` (conflict) | Delete after Phase 6. |
| 467–799 | Parchment scroll, page curl, unroll/roll transitions, light beams (~330 lines) | Only consumer is the orphan `hero-slider`. **Dead for the homepage.** Move to a clearly marked legacy block in Phase 12. Delete only if `hero-slider` stays orphaned (needs approval). |

### 2.6 JS (`resources/js/app.js`, 130 lines)
| Lines | Behaviour | Verdict |
|---|---|---|
| 7 | `scroll-behavior: smooth` set via JS | Move to CSS, guarded by `prefers-reduced-motion: no-preference`. |
| 13–46 | IntersectionObserver adds `.is-revealed`, staggers `.reveal-child`, runs counters | **Keep the observer** (single reveal engine). Restore CSS for `.is-revealed`. |
| 49–68 | Counter animation (`data-count-to`) | Keep (no current consumers; harmless). |
| 71–84 | Global `pointerdown` writes inline `transform: scale(.98)` on any button/rounded link | **Remove.** Conflicts with Tailwind `hover:scale-*` / `active:scale-*` and Alpine transitions, and fights CSS transforms. Use CSS `:active`. |
| 87–101 | `[data-parallax]` scroll listener (rAF-throttled) | No `data-parallax` consumers in active views → remove the listener, or keep it behind a guard that only attaches when elements exist. |
| 104–129 | Maritime background: scroll listener + **unconditional infinite rAF loop** with lerp | **Replace** with a loop that idles: run rAF only while `|target − current| > 0.1`, start on scroll, and pause when the hero leaves the viewport. Disable under reduced motion. Limit the travel to the hero scene (§21). |

### 2.7 Assets (`public/images`)
- `backgrounds/global_logistics_export.jpg` and `backgrounds/maritime_export_vessel.png` are both **289,502 bytes** (probably the same image) — keep one.
- `hero/`: `company_export_hero.jpg` (947 KB), `olive-grove-tunisia.jpg` (1.2 MB), `oasis-dates.jpg` (1.15 MB).
- `products/`: 11 JPGs of 385–823 KB, including **unused** Marasca bottle shots (`marasca_studio_clean.jpg`, `marasca_bottle_label_gold.jpg`, `marasca_gold_frame_vertical.jpg`, `rayanova_tresor_showcase.jpg`). These are ideal showroom imagery for bottled sizes (250 ml–1 L).
- None of the images use `loading="lazy"`, `decoding="async"`, `width`/`height` or modern formats.

---

## 3. Current-state visual audit (from screenshots + markup)

1. **Chrome is heavy:** announcement bar (~50 px) + navbar (`min-h-[96px]`) ≈ 146 px before the hero, and both use `bg-black/60` with blur. That is about 16% of a 900 px viewport.
2. **Hero:** the H1 is small for a hero (`text-xl … xl:text-[2.2rem]`). The paragraph is 12–13 px. Seven different chip/pill styles are mixed. Three CTAs have equal weight, and the cyan gradient dominates. The 4 product cards are tiny (~140 px) with 9 px text.
3. **Album viewer (state A):** improved recently (the peeking card is visible), but the busy port photo stays fully visible behind it, so there is no isolation.
4. **WhatsApp float** is fixed at `right-6` in every locale and overlaps the hero metrics rail and album edge (visible in the latest screenshots). It has an infinite `animate-ping`.
5. **Monospace overuse:** almost every label, chip and metric uses `font-mono` uppercase, which reads as a "terminal" rather than an editorial page.
6. **Badge noise:** `animate-ping` / `animate-pulse` dots appear in ~16 places (layout 2, hero 3, home 5, picker 6), so constant motion competes with content.
7. **Products:** every product card is one very tall card with a white-gradient conflict (`vivid-card-steel`) under a translucent dark background. The section header copy advertises a "3D cylinder wheel picker" (UX jargon shown to buyers).
8. **Sections run together** because they are all transparent, use the same `py-24 sm:py-32`, the same centred header pattern (pill → serif H2 → paragraph), and the same `bg-slate-900/55 + backdrop-blur-xl + border-white/15` cards.
9. **Toast copy bug:** the picker's EN toast is French (`"Produit et conditionnement ajoutés…"` for every non-AR locale).

---

## 4. Existing strengths (preserve)
- Dark maritime identity, real port and vessel imagery, and a believable B2B export positioning.
- High-quality product photography (bulk/IBC, tin, dates, tuna, tomatoes, Marasca bottles).
- Rich, specific trade content: COI metrics, Flexitank capacities, transit times, Incoterms, OEM formats.
- Trilingual Blade with correct `dir` handling and logical properties in places (`start-*`, `text-start`).
- A working RFQ funnel: `add-to-cart` event → Livewire `quote-cart` → `/rfq` create/review/success.
- A recent admin feature: `show_banner` / `show_badge` toggles on the hero (must keep working).
- Album viewer concept (centre stage + peeking next card) — a good base for the isolated product viewer.

## 5. Current weaknesses (prioritised)
| # | Weakness | Impact |
|---|---|---|
| W1 | Scroll hijack: `@wheel.prevent` on the size drum (`product-wheel-picker.blade.php` line 366) | Critical UX |
| W2 | Shared transparent background → no section separation | High |
| W3 | Hero overload, no single focal point | High |
| W4 | Arabic headings fall back to a system font (Cinzel/Playfair have no Arabic) | High (AR is the primary locale) |
| W5 | Product section reads as a form; the photo is subordinate | High |
| W6 | Dead/disabled animation code; infinite rAF; global `will-change` | Medium (performance, maintainability) |
| W7 | Legacy light-theme classes override dark utilities | Medium |
| W8 | Cyan everywhere (CTAs, scrollbar, chips, glows, gradient text) | Medium |
| W9 | No modal isolation in the album viewer | Medium |
| W10 | WhatsApp float not RTL-aware, overlaps content, 4 duplicate WhatsApp entry points | Medium |
| W11 | Accessibility: drum items are clickable `div`s, no radio semantics, no visible focus styles, no reduced-motion handling | Medium |
| W12 | Unoptimised large images, no lazy loading | Medium |

---

## 6. Documentation conflicts

| Document | Says | Reality | Recommendation |
|---|---|---|---|
| `docs/04_DESIGN_SYSTEM_UI_UX.md` | "Vivid luxury", ivory `#FEFCF7`, emerald/gold, **"❌ NO dark mode — client explicitly rejected it"**, file sizes (app.css 440 lines, home 344 lines), class mapping | Site is fully **dark maritime**: steel/charcoal/cyan, dark glass. app.css 800 lines, home 525 lines. Light classes survive only as overriding leftovers. | Mark **§1–2, §4.2 B–D, §6 as HISTORICAL**. Still valid: §3 stack, §7 build pipeline ("always rebuild after CSS/JS"), §10 mobile-first principles (touch targets ≥ 44 px), the wheel-picker *data contract* in §9 (not its visual form). |
| `docs/04` §9 | Wheel picker is the specified selector | This plan replaces the drum with an accessible segmented/rail selector (§15) | Supersede after Phase 7. |
| `docs/04` §5 | JS = observer + counter + parallax | Also the maritime rAF engine and the global pointerdown transform | Historical. |
| `docs/00_MASTER_BLUEPRINT.md`, `docs/06_IMPLEMENTATION_PLAN.md` | Backend/workflow plans | Unaffected | Remain authoritative for backend. |

**Source-of-truth order for the UI:** current code + screenshots → this plan → `docs/04` (historical reference only).
**Follow-up (not in this task, needs instruction):** after Phase 13, either rewrite `docs/04_DESIGN_SYSTEM_UI_UX.md` from §8–§11 of this plan, or add a banner at its top pointing here. **Do not edit `docs/04` during implementation unless the user instructs it.** Record the change in `docs/CHANGELOG.md` only when the user approves documentation edits.

---

## 7. Final creative direction

**"Mediterranean Export Editorial — Stacked Glass Scenes"**

- **Mood:** night/blue-hour port, olive gold, cold steel. Premium, controlled, technical, Tunisian.
- **Composition:** each major section is a *scene*: a full-width rounded "page" with its own atmosphere, and a single dominant element (a headline, a product, or a timeline).
- **Hierarchy:** typography and whitespace create hierarchy. Boxes are reserved for interactive or data surfaces.
- **Motion:** slow, short-travel and transform/opacity only. Scenes recede (scale 0.97) as the next one rises over them. Nothing loops forever except the hero background drift, which pauses offscreen.
- **Reference interpretation (the user's screenshots):**
  - Loka Eksplore: oversized display headline over photography; a horizontal filter bar ("Location > Type > Destination") → reinterpret as a **packaging-format segmented bar**; an image row with captions → the **product rail**.
  - CHT: a fanned, depth-stacked card carousel with a centre card at full size and neighbours scaled/rotated, plus a circular thumbnail rail with an active ring → reinterpret as the **showroom size/product depth stage** and **thumbnail rail**. A gold pill primary CTA → the primary CTA style.
  - Not copied: travel branding, search box, destination cards, tourism layout.

---

## 8. Design tokens

Implement as CSS custom properties in `resources/css/app.css` (`:root`, inside `@layer base`) and expose them to Tailwind in `tailwind.config.js` with the `rgb(var(--token) / <alpha-value>)` pattern under a new `rn` namespace. Do not delete the existing `steel/electric/coolteal/olive/gold` scales in Phase 1. Migrate usages progressively.

```css
/* values as space-separated RGB channels */
--rn-bg-0: 5 8 12;        /* #05080C page base */
--rn-bg-1: 9 14 20;       /* #090E14 scene base */
--rn-bg-2: 14 21 29;      /* #0E151D surface */
--rn-bg-3: 21 31 42;      /* #151F2A elevated surface */
--rn-text-1: 244 241 234; /* #F4F1EA warm white – primary text */
--rn-text-2: 201 206 214; /* #C9CED6 secondary */
--rn-text-3: 138 148 163; /* #8A94A3 muted / metadata */
--rn-gold: 200 164 90;    /* #C8A45A premium/heritage */
--rn-gold-soft: 226 200 138; /* #E2C88A */
--rn-olive: 124 143 78;   /* #7C8F4E product/origin */
--rn-olive-deep: 46 56 30;/* #2E381E */
--rn-cyan: 63 193 201;    /* #3FC1C9 interaction/focus/active ONLY */
--rn-line: 255 255 255;   /* used with /0.08–0.16 alpha for borders */
--rn-success: 74 222 128;
--rn-danger: 248 113 113;

--rn-radius-sm: 10px; --rn-radius-md: 16px; --rn-radius-lg: 24px; --rn-radius-scene: 32px;
--rn-space-section: clamp(4.5rem, 9vw, 8rem);
--rn-ease-out: cubic-bezier(0.16, 1, 0.3, 1);
--rn-ease-inout: cubic-bezier(0.65, 0, 0.35, 1);
--rn-dur-fast: 160ms; --rn-dur-base: 320ms; --rn-dur-slow: 700ms;
--rn-shadow-1: 0 8px 24px -12px rgb(0 0 0 / .6);
--rn-shadow-2: 0 24px 48px -20px rgb(0 0 0 / .75);
--rn-shadow-scene: 0 -30px 60px -30px rgb(0 0 0 / .85);
```
- **Z-index scale:** background −30 · scenes 0–10 (incrementing) · sticky nav 40 · floating WhatsApp 40 · viewer overlay 60 · cart drawer 50 (existing) → **raise the viewer only, to 60; leave the cart at 50 and close the viewer before opening the cart.**
- **Container widths:** `--rn-container: 1280px` (content) and `1600px` (hero/nav). Replace the mixed `max-w-[1700px]`, `max-w-[1720px]`, `max-w-7xl`.

## 9. Typography system

**Fonts already loaded:** Cairo, Cinzel, Outfit, Playfair Display. **No new fonts.**

| Role | LTR (en/fr) | RTL (ar) | Size (clamp) | Weight / leading / tracking |
|---|---|---|---|---|
| DISPLAY (hero H1) | Playfair Display | Cairo | `clamp(2.5rem, 5.2vw, 4.75rem)` | 700 / 1.05 LTR, **1.25 AR** / −0.02em LTR, 0 AR |
| H1 (page) | Playfair Display | Cairo | `clamp(2rem, 4vw, 3.25rem)` | 700 / 1.1 (AR 1.3) |
| H2 (scene title) | Playfair Display | Cairo | `clamp(1.75rem, 3.2vw, 2.75rem)` | 700 / 1.15 (AR 1.35) |
| H3 (card/product) | Outfit | Cairo | `clamp(1.25rem, 1.8vw, 1.625rem)` | 600 / 1.25 (AR 1.45) |
| BODY | Outfit | Cairo | `1rem` (≥ 15 px on mobile); lead `1.125rem` | 400 / 1.65 (AR 1.85) |
| LABEL / EYEBROW | Outfit | Cairo | `0.75rem` | 600, uppercase **LTR only**, tracking 0.14em LTR, 0 AR |
| METADATA / DATA | `font-mono` (system) | Cairo (digits stay LTR) | `0.75–0.8125rem` | 500 — **only for numeric specs** (acidity, litres, transit days) |
| WORDMARK | Cinzel (keep only for "RAYANOVA") | Cinzel | — | — |

**Implementation rules:**
- Add `[dir="rtl"] .font-serif, [dir="rtl"] .font-display { font-family: Cairo, sans-serif; }` and `[dir="rtl"] .uppercase { text-transform: none; letter-spacing: 0; }` in `app.css` (`@layer base`). This alone fixes W4 site-wide.
- Add a Tailwind `fontFamily.display: ['"Playfair Display"', 'serif']`. Keep `serif` (Cinzel) only for the wordmark, and migrate headings from `font-serif` to `font-display` progressively.
- Never use `font-mono` for prose labels, nav, chips with words, or Arabic text.
- Minimum text size on public pages: 12 px for metadata and 14 px for any sentence. Remove `text-[8px]`, `text-[9px]` and `text-[10px]` from user-facing copy.
- Use one H1 per page (hero). Scene titles are H2; cards and products are H3.

## 10. Glass system

Four classes only, defined once in `app.css` (`@layer components`). Remove the ad-hoc `bg-slate-900/55 backdrop-blur-xl border-white/15` repetitions as each section is migrated.

| Class | Background | Blur | Border | Shadow | Hover | Active | Focus-visible |
|---|---|---|---|---|---|---|---|
| `.rn-glass-light` (chips, rail items) | `rgb(var(--rn-bg-2) / .45)` | 8px | `rgb(var(--rn-line) / .10)` | none | border /.18 | bg /.6 | 2px ring `--rn-cyan`, offset 2px |
| `.rn-glass` (cards, nav) | `rgb(var(--rn-bg-2) / .62)` | 14px | `/.12` | `--rn-shadow-1` | border /.20, translateY(−2px) | translateY(0) | same ring |
| `.rn-glass-dark` (panels on busy images, rail) | `rgb(var(--rn-bg-1) / .78)` | 16px | `/.10` | `--rn-shadow-2` | — | — | same ring |
| `.rn-glass-strong` (viewer, cart, menus) | `rgb(var(--rn-bg-1) / .92)` | 20px | `/.14` | `--rn-shadow-2` | — | — | same ring |

**Rules:**
- Blur is applied only over photography. Over solid scene backgrounds use the same class with `backdrop-filter: none` (add the modifier `.rn-glass--flat`).
- Maximum 2 nested glass layers. Never put glass inside glass inside glass.
- Text on glass must meet ≥ 4.5:1 contrast. Use `--rn-text-1` / `--rn-text-2`, never `--rn-text-3` for sentences.
- **Mobile (< 768 px):** cap blur at 8 px and remove blur from the repeated cards (performance).

## 11. Color system (semantic usage)

| Role | Token | Use for | Never use for |
|---|---|---|---|
| Page background | `--rn-bg-0` | `body` base under scenes | — |
| Scene background | `--rn-bg-1` + scene-specific atmosphere (§21) | each `.rn-scene` | — |
| Surface / elevated | `--rn-bg-2` / `--rn-bg-3` | cards, inputs, rail | — |
| Primary text | `--rn-text-1` | headings, key values | — |
| Secondary text | `--rn-text-2` | body copy | — |
| Muted | `--rn-text-3` | metadata, captions | body sentences |
| **Gold** | `--rn-gold`, `--rn-gold-soft` | primary CTA fill, grade badges, "Grade 01", premium dividers, active product index numbers | errors, links in prose |
| **Olive** | `--rn-olive`, `--rn-olive-deep` | origin/terroir tags, harvest stage, product-category chips, traceability scene accents | CTAs |
| **Cyan** | `--rn-cyan` | focus rings, active selector state indicator, live/status dots (max 1 per viewport), links on hover, progress | large fills, gradient headlines, scrollbars, default borders |
| Border | `--rn-line` at /.08 (default), /.14 (strong) | all hairlines | — |
| Success | `--rn-success` | "added to RFQ" toast, "Verified active" | decoration |
| CTA primary | gold gradient `--rn-gold-soft → --rn-gold`, text `--rn-bg-0` | Request RFQ, Add to RFQ | more than one per scene |
| CTA secondary | `.rn-glass-light` + `--rn-text-1`, cyan focus | Browse products, Open viewer | — |
| WhatsApp | existing emerald (brand-recognisable) | WhatsApp only | — |

**Budget:** per viewport at most 1 gold CTA, 1 cyan status signal, and olive/gold accents ≤ 10% of the area. **Remove the cyan→electric→coolteal gradient** from all buttons (layout cart button, hero RFQ, album CTA, picker CTA).

---

## 12. Navbar
**File:** `resources/views/components/layouts/app.blade.php` (lines 72–181).

**Target:**
- **Announcement bar:** reduce to `py-2`, `text-xs sm:text-sm`, a single line, no `animate-ping` (use a static gold dot). Hide it on scroll-down on mobile (CSS only: it is not sticky, so it simply scrolls away — keep it non-sticky).
- **Header:** `min-h-[72px]` (from 96). Use `.rn-glass-dark` while over the hero, and switch to `.rn-glass-strong` after 40 px of scroll via an Alpine `x-data="{ scrolled:false }"` with `@scroll.window.passive` (a single boolean; no new listener system).
- **Logo:** horizontal lockup (icon 40 px + wordmark in Cinzel 14 px) instead of the stacked icon over text, which saves height.
- **Links:** `text-[15px] font-medium text-rn-text-2`, hover `text-rn-text-1`. **Active state** = a 2px gold underline offset 6 px. The active anchor is detected by an IntersectionObserver on scenes (add to `app.js` §22, setting `aria-current="true"` on the matching link).
- **Add a link** for the bento scene once it gets `id="export"` (label: existing bento eyebrow text "Enterprise Export Architecture" / "البنية التحتية التصديرية والمواصفات"; the FR label must reuse existing FR strings if available, otherwise use the EN string and mark it TODO in the progress file — **do not invent marketing copy**).
- **Language switcher:** `.rn-glass-light`, 40 px height.
- **RFQ cart button:** secondary style (glass + gold count badge) instead of the cyan gradient. The navbar must be quieter than the hero CTA.
- **Mobile:** the hamburger toggles a full-height sheet (`.rn-glass-strong`, `inset-0 top-[header]`), with the links at 20 px and the language and WhatsApp buttons at the bottom. Add `aria-expanded`, `aria-controls`, focus trap and Esc to close. Lock body scroll while open (`overflow:hidden` on `html` via Alpine).
- **Must keep:** `$dispatch('toggle-quote-cart')`, the `cart-updated.window` counter binding, `LaravelLocalization` URLs, hreflang, JSON-LD.

## 13. Hero
**File:** `resources/views/components/company-intro-hero.blade.php`.

**Focal hierarchy (state B):**
1. **Eyebrow** (brand/context): one line — the `show_badge` badge text (keep the `@if($companyHero?->show_badge ?? true)` logic).
2. **DISPLAY H1** (`$companyHero->localized_title` fallback strings unchanged), max ~14 words per line, `max-w-[18ch]` LTR / `max-w-[16ch]` AR.
3. **Lead paragraph** (`localized_subtitle`), 17–18 px, `max-w-[52ch]`, no `line-clamp` on desktop.
4. **Primary CTA** = Request RFQ (gold). **Secondary CTA** = "Explore products" → `#products` (glass). The secondary label replaces the jargon "3D Drum Picker"; use existing strings `المنتجات والتعليب` / `Products & Packaging` / `Produits & Packaging` from the nav.
5. **Trust line:** the 4 accreditations become a single inline row of text items separated by `·` with a small gold check (no boxed panel).
6. **Product preview:** the 4 cards become a **depth-fanned card stack** (CHT-style) on desktop: the centre card at full size, the other three offset with `scale(.88)`, `translateX(±12–24%)`, no rotation > 4°. Hover or focus brings a card forward. A click calls the existing `openAlbum(idx)`. Mobile: horizontal snap rail (`scroll-snap-type: x mandatory`) with 72% wide cards.

**Remove from the hero viewport (content preserved elsewhere):**
- The banner pills row (lines 174–203). Keep the `show_banner` toggle working: when enabled, render the pills as a **single slim line inside the information rail** (§17) instead of a top row. Port/COI/Incoterms text is unchanged.
- The WhatsApp hero CTA. The floating button and the announcement bar already cover it.
- The "Key Export Commodities" header and "click any product" footnote around the cards (replace with the eyebrow on the stack, `aria-label`).
- The "Open Album" button (cards are self-explanatory; keep a visually subtle "View all" text button for keyboard users).

**Background:** the maritime photo stays the hero's own atmosphere (§21) with a left/right (`start`-side) gradient scrim: `linear-gradient(to inline-end, rgb(var(--rn-bg-0)/.88) 0%, rgb(var(--rn-bg-0)/.55) 45%, transparent 75%)` plus a bottom fade into the next scene.

**Layout:** `min-h-[calc(100svh-72px)]` desktop, with a maximum of 860 px. Do not force the rail and everything else into one viewport. Mobile: natural height, image cropped to `object-position: 60% center`.

**Entry motion:** the existing `hero-badge-enter / hero-title-enter / hero-subtitle-enter / hero-cta-enter / hero-card-enter` keyframes (currently unused in markup) → apply them to eyebrow/H1/lead/CTAs/stack, with travel reduced to 16 px and disabled under reduced motion.

## 14. Product showcase
**Files:** `resources/views/home.blade.php` (lines 289–319), `resources/views/components/product-wheel-picker.blade.php`.

**Recommended architecture: "Showroom stage + product rail"** (option B).
- **Section header:** eyebrow + H2 (existing strings "كتالوج التصدير الدولي 2026" / "Export Catalog …" and the H2). **Replace the paragraph** that mentions the "3D cylinder wheel picker" with the existing H2-adjacent copy minus the jargon. Remove only the sentence naming the wheel and keep the rest of the existing text. If no clean split exists, keep the paragraph and delete only the parenthetical "(3D Cylinder Wheel Picker)" / "3D cylinder dial wheel picker" wording.
- **Product rail** (top of the stage): horizontal list of all `$products` as thumbnail + short title, `role="tablist"`. The active item has a gold ring and the cyan focus ring for keyboard users. Arrow keys move between items. Mobile: horizontally scrollable with snap.
- **Stage:** all products stay rendered (each keeps its own Alpine state). Inactive products are hidden with `x-show` and `x-transition` (opacity + `translateY(12px)` + `scale(.98)`, 320 ms). **Keep every wrapper `id="product-{id}"`** and its `scroll-mt-28`, because the hero's `goToProduct(productId)` targets them.
- **Hero → showroom contract:** update `goToProduct(id)` in the hero (JS inside its `x-data`) to `window.dispatchEvent(new CustomEvent('showroom-select', { detail: { id } }))` *before* the scroll. The showroom wrapper `x-data` listens with `@showroom-select.window` and sets `activeId`. The existing highlight ring is kept.
- **Product layout inside the stage (desktop, 12 cols):**
  - **Cols 1–7 — Image stage:** the large product photo at aspect 4/5 (bottled/tin) or 4/3 (bulk), on a dark radial "pedestal" (`radial-gradient(60% 50% at 50% 60%, rgb(var(--rn-olive-deep)/.45), transparent)`). Faint index number "01" in gold at the top-start. Category eyebrow top-start. **No** text bars over the photo except one small grade chip.
  - **Cols 8–12 — Info column,** in this order: H3 product name → grade/origin line (subtitle + "🇹🇳 100% Tunisian Terroir" as text, not a chip) → 3–4 key specs as a compact definition list (`<dl>` 2×2, mono values) → packaging segmented control → size rail → quantity + unit (inline) → private-label checkbox → primary CTA "Add to RFQ".
  - The long description and full `nutritional_specs` grid go into a `<details>` disclosure ("Full specifications" / existing localized words if present). Content is preserved but no longer dominates.
  - The logistics hint stays as one muted line under the size rail.
  - Certificates: inline text list under the specs.
- **Mobile:** image first (aspect 1/1, max 70 svh), then name, specs, selectors and a **sticky bottom CTA bar** within the product card (`position: sticky; bottom: 12px`) showing "size · qty · Add to RFQ".
- **Image behaviour:** keep the `activeProductImage` getter logic intact. **Extend it only for bottled sizes**: when the packaging id contains `bottle`/`marasca`/`glass` and the category is olive oil, return `images/products/marasca_studio_clean.jpg` (existing asset). This is a UI-only fallback inside the same `@if(!Str::startsWith(...,'storage/'))` guard, so admin-uploaded images still take precedence.

**Fallback option A (lower risk, if B fails acceptance):** keep stacked products but use the new compact layout, `py-12` between products, and a sticky mini product index on desktop. Record in the progress file which option was implemented.

## 15. Product selector

**Decision:** replace the 3D cylinder drum with a **two-level horizontal selector**:
1. **Packaging format** = segmented control (`role="radiogroup"`, each option a `<button role="radio" aria-checked>`): icon emoji + localized `name_*`. One row; it scrolls horizontally on overflow with fade masks at the edges. `desc_*` is shown below as one muted line for the selected format only (not on every card).
2. **Size** = a horizontal **size rail** of pill chips (`role="radiogroup"`), each showing the size string. The selected chip uses gold fill and dark text, the others `.rn-glass-light`. Prev/next arrow buttons stay (44×44) for discoverability.

**Interaction rules:**
- **Remove** `@wheel.prevent="handleWheel($event)"` and the `handleWheel` method. **The wheel never changes size.**
- **Remove** vertical touch handlers (`handleTouchStart/Move/End`, `touchStartY`). Horizontal native scrolling of the rail replaces them.
- Keyboard: ←/→ (mirrored in RTL: use `dir`-aware logic) moves the selection inside a radiogroup, Home/End jump to the ends, Tab leaves the group (roving `tabindex`).
- Keep `selectPackaging`, `selectSize`, `nextSize`, `prevSize`, `vibrate`, `selectedSize`, `currentPackagingObj`, `activeProductImage`, quantity/unit defaults and `addToCart()` **unchanged in behaviour and payload**.
- **Depth effect lives on the product image, not on the controls:** on size change, animate the stage image with a 320 ms crossfade + `scale(.96 → 1)` + `translateY(8px → 0)` (Alpine `x-transition` on a `:key`-swapped `<img>`, or toggling a class). Bulk formats can use a slightly wider crop. No `rotateX`, no blur.
- Fix the toast bug: the EN/FR toast must use the correct language. Reuse the existing AR and FR strings and add an EN string in the same ternary style. Optionally move these into `lang/*` files — **only if `lang/*/` already has a frontend file; do not restructure translations.**

**Why not keep the 3D drum:** it captured the scroll wheel, used non-semantic clickable `div`s, its active pill was 2.5× larger than siblings (visual noise), it used `blur()` filters per frame, and it duplicated the dot indicators. Reliability > clarity > accessibility > performance > novelty.

## 16. Product modal / viewer
**File:** `company-intro-hero.blade.php` state A (lines 205–339).

**Target behaviour:**
- **Isolation:** when `albumOpen` is true, render a **fixed overlay** (`position: fixed; inset:0; z-index:60`) with backdrop `rgb(var(--rn-bg-0)/.82)` + `backdrop-filter: blur(6px) saturate(.8)`. The page and maritime background become quiet. On mobile use no blur and `/.92`.
- **Teleport:** use Alpine `x-teleport="body"` for the overlay so it escapes the hero's `overflow-hidden` and stacking context. (Alpine 3 bundled with Livewire 4 supports `x-teleport`; verify in the browser.)
- **Dialog semantics:** `role="dialog" aria-modal="true" aria-labelledby` (product title id). Move focus to the close button on open and return focus to the triggering card on close. **Esc** closes, ←/→ (dir-aware) = prev/next. Trap Tab inside. Lock page scroll (`document.documentElement.style.overflow='hidden'`), restored on close.
- **Composition (desktop ≥ 1024 px):** a centred `.rn-glass-strong` panel `min(1100px, 92vw)` × `min(640px, 84svh)` in 2 columns: **left/start 60% = large product image** (object-cover, no overlaid bars except the counter "01 / 04"), **right/end 40% = identity**: category eyebrow (olive), H2 title, grade badge (gold), certification line, 4 metrics as a 2×2 `<dl>`, primary CTA "Configure packaging & quantities" (existing strings) → `closeAlbum(); goToProduct(id)`.
- **Peeking next card:** keep the existing "next" peek (it was just fixed). Restyle it as a dimmed (`opacity .55`, `scale .9`) card outside the panel edge on the progression side (RTL: left, LTR: right), clickable → `next()`. Hide it below 1024 px; use a swipe-free prev/next pair of buttons instead.
- **Mobile:** full-screen sheet; image top 55 svh, details below, sticky CTA at the bottom, close at the top-end (44×44).
- **Preserve:** the `products` data array, `activeIdx`, `openAlbum`, `closeAlbum`, `next`, `prev`, `goToProduct`, the multilingual title/category/metrics fallbacks, and the hard-coded/DB-synced data logic (lines 8–118) **unchanged**.

## 17. Information rail
**File:** `company-intro-hero.blade.php` (lines 494–533).

**Target:** one coherent rail attached to the hero's bottom edge, not 4 floating boxes.
- **Container:** full-width `.rn-glass-dark`, single row, `divide-x` (logical: `divide-x rtl:divide-x-reverse`) hairlines `/.08`. Height ~72 px desktop.
- **Items (existing content, unchanged text):** Cold-Pressed Acidity `< 0.18% Extra Virgin` · Bulk Shipping Tank `24,000L Flexitank` · Deglet Nour Dates `Tozeur & Kebili Oases` · OEM Private Label `Bespoke Bottles & Tins`. When `show_banner` is enabled, prepend a 5th compact item: `Port of Radès & Sfax · COI · ISO 22000 · US FDA · ECOCERT · Incoterms 2020` (existing strings).
- **Item anatomy:** label (12 px, `--rn-text-3`, uppercase LTR only) above value (15 px, `--rn-text-1`, mono only for numbers). Small emoji icons are allowed; remove the boxed icon tiles.
- **Mobile:** horizontally scrollable with snap and edge fade masks, 2 visible items. No wrapping into a 2×2 box grid.
- **Avoid overlap:** the floating WhatsApp moves to `end-6` and gets `bottom: calc(env(safe-area-inset-bottom) + 24px)`. On ≥ 1024 px it collapses to an icon-only 56 px circle while the hero is in view (Alpine flag from the scene observer), so it never covers the rail.

## 18. Traceability / supply-chain and secondary sections

**Bento "Export Architecture" (home.blade.php lines 12–284):**
- Give the section `id="export"`. Keep all 4 tiles and all their content.
- Visual: tile backgrounds become `.rn-glass--flat` on the scene's solid background (no blur). Remove the 4 coloured top bars and replace them with a single 1px gold hairline on the active/hover tile only. Remove `animate-ping`/`animate-pulse` dots (≤ 1 static dot per tile).
- Metric values: the gold value colour for the hero metric (acidity) and `--rn-text-1` for the others. Remove the cyan/steel/electric rotation.
- Lower the internal noise: inner metric boxes use `--rn-bg-3` without borders.

**Traceability `#terroir` (lines 325–474):**
- Convert the 4 cards into a **horizontal timeline** on desktop: a connecting line (1px, olive gradient) with numbered nodes (gold numerals 01–04) above each card. Cards keep their images, titles, copy and footers.
- Card image `h-36` → `aspect-[4/3]`, `loading="lazy" decoding="async"` plus width/height attributes.
- Stage tag colours: harvest/extraction = olive, lab = gold, port = cyan (the only technical/logistics signal) — consistent with §11.
- Mobile: a vertical timeline (line on the `start` side) with cards stacked.
- Reveal: cards use `.reveal-up` within a `.reveal-stagger` container (restored in Phase 5).

**Certifications `#certifications` (lines 480–519):**
- Keep the DB loop and fields (`localized_name`, `issuer`, `valid_until`).
- Visual: compact 4-column "seal" cards, the shield icon in gold outline (no fill swap on hover), "Verified active" as a success-coloured text with a static dot (no ping).

**Final CTA scene (new markup, existing copy only):**
- A dark, conversion-focused scene before the footer: H2 reuses the hero `localized_title` *or* the footer "Private Label Solutions" heading (pick one and note it in the progress file), the primary CTA reuses `$companyHero->localized_primary_cta` (→ `/rfq`), and the secondary reuses "WhatsApp Export Desk". Background: `company_export_hero.jpg` at 20% opacity with a heavy scrim. **No new marketing sentences.**

## 19. Section architecture

**Recommended order (anchors kept):**
1. **Hero** (+ attached information rail) — maritime port scene.
2. **Products showroom** `#products` — moved up (main conversion; the hero's secondary CTA points here).
3. **Export architecture bento** `#export` — quality + logistics + OEM + corridors (proof after desire).
4. **Traceability** `#terroir`.
5. **Certifications** `#certifications`.
6. **Final CTA** (new, existing copy).
7. Footer (layout).

**Nav order:** Home · Products & Packaging · Export (new) · Traceability · Certifications.

**Scene wrapper (Blade markup pattern, applied in home.blade.php):**
```html
<section id="products" class="rn-scene rn-scene--showroom" data-scene>
  <div class="rn-scene__inner">…existing content…</div>
</section>
```
- `.rn-scene`: `position: relative; isolation: isolate; border-radius: var(--rn-radius-scene) var(--rn-radius-scene) 0 0; margin-top: calc(var(--rn-radius-scene) * -1); padding-block: var(--rn-space-section); box-shadow: var(--rn-shadow-scene); background: rgb(var(--rn-bg-1));` plus an increasing z-index via `:nth-of-type` or inline `style="--scene-z:2"` → `z-index: var(--scene-z)`.
- The first scene after the hero overlaps the hero's bottom fade by 32 px, so the page visibly sits *over* the port.
- Remove the hairline divider `<div>`s (lines 8, 287, 322, 477, 522). Scene edges replace them.
- Remove the giant watermark typography spans (opacity .02) — invisible noise that costs layout.
- **Body overflow fix (required for sticky/scroll-driven stability):** in the layout `<body>`, replace `overflow-x-hidden` with the CSS `overflow-x: clip` (add a utility `.overflow-x-clip` or a rule in `@layer base`). `overflow-x:hidden` makes the body a scroll container in some browsers and breaks `position: sticky` and view timelines.

## 20. Cinematic scroll choreography

**Mechanism:** **CSS scroll-driven animations** (`animation-timeline: view()`), progressive enhancement, **zero new JS, no new library**. Browsers without support (currently Firefox stable and older Safari) get the static scene stacking from §19, which already looks layered.

```css
@supports (animation-timeline: view()) {
  @media (prefers-reduced-motion: no-preference) and (min-width: 768px) {
    .rn-scene { animation: rn-scene-enter linear both, rn-scene-exit linear both;
                animation-timeline: view(), view();
                animation-range: entry 0% entry 70%, exit 0% exit 100%;
                transform-origin: 50% 0%; }
  }
}
@keyframes rn-scene-enter { from { transform: translateY(48px) scale(.955); opacity:.55; }
                            to   { transform: none; opacity:1; } }
@keyframes rn-scene-exit  { from { transform: none; opacity:1; }
                            to   { transform: translateY(-2%) scale(.97); opacity:.72; } }
```

**Choreography:**
- **Current scene** (exiting the top): scale 1 → 0.97, translateY 0 → −2%, opacity 1 → 0.72. **No blur on the scene element** (too expensive on large layers). Optionally a scrim pseudo-element `::after` with opacity 0 → .35 to "dim with depth".
- **Next scene** (entering from the bottom): starts at scale 0.955, translateY 48 px, opacity .55; reaches scale 1 / opacity 1 by 70% of its entry. Because its z-index is higher and it has a rounded top with a shadow, it reads as a page rising *over* the receding one.
- **Hero:** only has the exit animation (it never enters). The maritime parallax (§21) continues in parallel.
- **Tall scenes** (products, bento) are taller than the viewport. `view()` ranges are relative, so the exit only starts when the scene's bottom approaches the top. Verify the products scene does not visibly shrink while the user interacts with it. If it does, apply the exit only to `.rn-scene__inner > :last-child` or narrow the range to `exit 60% exit 100%`.
- **Mobile < 768 px:** disabled (static stacking only). **Reduced motion:** disabled.
- **Sticky variant (optional, desktop only, Phase 5b):** for the short scenes (certifications, final CTA) only, `position: sticky; top: 72px` can pin the previous scene while the next one covers it. Do **not** apply sticky to the products or bento scenes (interaction and tall content).

**Explicitly not allowed:** JS scroll listeners for scene transforms, GSAP/Locomotive/Lenis (no new dependency), rotation, blur on scenes, snapping/scroll-jacking, `scroll-snap-type` on the page.

## 21. Background strategy

Use existing assets only.

| Scene | Atmosphere | Asset | Treatment |
|---|---|---|---|
| Hero | Full cinematic port | `$companyHero->image_url` → fallback `backgrounds/global_logistics_export.jpg` (keep the admin-controlled source) | The fixed `#maritime-parallax-bg` remains, but **visually bounded to the hero**: following scenes are opaque. Inline-start scrim + bottom fade (§13). |
| Products showroom | Deep dark studio | none (CSS only) | `--rn-bg-0` + radial olive-deep glow behind the stage + a faint gold radial top-end. **Fully opaque** so the photography owns the scene. |
| Export bento | Port/cargo, technical | `hero/company_export_hero.jpg` | `background-image` at 10–14% opacity via a pseudo-element, `object-position: center`, plus a `--rn-bg-1` overlay. `loading` irrelevant (CSS) → use `image-set()` once WebP exists. |
| Traceability | Grove → port journey | `hero/olive-grove-tunisia.jpg` | Pseudo-element at 12% opacity, gradient mask fading to the bottom. Olive accent. |
| Certifications | Controlled laboratory | none | Solid `--rn-bg-1` with a subtle 1px grid pattern (CSS `background-image: linear-gradient` at /.03). |
| Final CTA | Dark conversion | `hero/company_export_hero.jpg` or `backgrounds/global_logistics_export.jpg` | 20% opacity + strong radial vignette. |
| Footer | Existing | — | Solid `--rn-bg-0` (drop the blur: nothing behind it once scenes are opaque). |

**Maritime engine changes (`resources/js/app.js` lines 104–129):**
- Run rAF only while animating (stop when `Math.abs(targetY-currentY) < 0.1`, restart on scroll).
- Map the parallax to **hero progress** (`scrollY / heroHeight`, clamped 0–1) instead of whole-page progress. Max travel ~80 px.
- An IntersectionObserver on the hero: when it is fully out of view, stop listening (`removeEventListener`) and reattach when it is visible again.
- `prefers-reduced-motion: reduce` → no transform at all.
- Keep `min-h-[115vh]` on the image (needed for travel) and set `fetchpriority="high"` and `decoding="async"` on it (it is the LCP candidate).

## 22. Motion system

**One reveal engine:** the existing IntersectionObserver in `app.js` (lines 13–46). No second system.

| Token | Value | Use |
|---|---|---|
| `--rn-dur-fast` 160 ms | hover, focus, press |
| `--rn-dur-base` 320 ms | selector changes, image swaps, viewer open |
| `--rn-dur-slow` 700 ms | reveals, hero entry |
| `--rn-ease-out` | all enters |
| `--rn-ease-inout` | image crossfades |

**Restore reveal CSS (replace app.css lines 181–189):**
```css
@media (prefers-reduced-motion: no-preference) {
  .reveal-up, .reveal-child { opacity:0; transform: translateY(16px);
    transition: opacity var(--rn-dur-slow) var(--rn-ease-out), transform var(--rn-dur-slow) var(--rn-ease-out); }
  .reveal-scale { opacity:0; transform: scale(.97); transition: inherit; }
  .reveal-up.is-revealed, .reveal-child.is-revealed, .reveal-scale.is-revealed { opacity:1; transform:none; }
}
```
**Safety net (must ship with it):** add `<html class="no-js">` in the layout → replace with `js` at the top of `app.js`, and scope the hidden state with `.js .reveal-up…`, so content is never invisible if JS fails. Also mark everything as revealed after 2.5 s as a timeout fallback.

**Allowed motion inventory (complete list):**
1. Hero entry stagger (once).
2. Maritime background drift (hero only).
3. Scene enter/exit (scroll-driven, §20).
4. Reveal-up/stagger on cards (once).
5. Product image swap (crossfade + scale) on packaging/size/product change.
6. Viewer open/close (opacity + scale .98 → 1, 320 ms).
7. Hover lift (−2 px) on cards and buttons; press `scale(.98)` via CSS `:active`.
8. One live status dot per viewport (`animate-pulse`, not `ping`).

**Remove:** `animate-ping` everywhere (≈16 places) except one hero status dot · `.specular-shine` · `.animate-shimmer` · `.animate-float-*` · `.animate-breathe` · `.border-animated-gold` · the global pointerdown transform · `group-hover:scale-108` on large images (use scale 1.03 max) · `hover:scale-105/110` on buttons (use translateY / brightness).

**Reduced motion:** a global rule
```css
@media (prefers-reduced-motion: reduce) { *,*::before,*::after { animation-duration:.01ms !important; animation-iteration-count:1 !important; transition-duration:.01ms !important; scroll-behavior:auto !important; } }
```

## 23. Responsive behavior

| Breakpoint | Behaviour |
|---|---|
| < 640 | Single column. Nav 64 px with hamburger. Hero: H1 `clamp` min 2.25 rem (AR 2 rem), one CTA full-width + secondary as a text link, card stack → snap rail. Info rail scrolls horizontally. Showroom: product rail scrolls, image 1:1, sticky in-card CTA bar. Viewer = full-screen sheet. Scenes static (no scroll-driven). WhatsApp = 56 px icon only. |
| 640–1023 | Two-column bento, timeline 2×2, showroom stacked (image above info). Viewer full-screen. Scenes static. |
| ≥ 1024 | Full layout, scroll-driven scenes, fanned card stack, 2-column viewer with peek card. |
| ≥ 1536 | Container caps (1280 / 1600). No further scaling of type beyond `clamp` maxima. |

**Global rules:** no horizontal overflow (test at 360 px) · `100svh` instead of `100vh` · touch targets ≥ 44×44 · inputs ≥ 16 px font (prevents iOS zoom; the qty input is currently `text-base`, keep it) · `env(safe-area-inset-*)` for fixed elements.

## 24. Accessibility
- **Selectors:** radiogroups with roving tabindex and `aria-checked` (§15). The rail is a `tablist` with `aria-controls` → product panels.
- **Viewer:** dialog semantics, focus management, Esc, scroll lock (§16).
- **Mobile menu:** `aria-expanded`, Esc, focus trap.
- **Focus:** global `:focus-visible { outline: 2px solid rgb(var(--rn-cyan)); outline-offset: 2px; border-radius: inherit; }`. Never `outline:none` without a replacement.
- **Contrast:** body text ≥ 4.5:1 on its actual background (test text over the hero photo with the scrim, and text on glass). Metadata ≥ 4.5:1 for anything meaningful.
- **RTL:** use logical properties (`start/end`, `ms/me`, `ps/pe`, `inset-inline-*`) for every new rule. Arrow icons mirror via `rtl:rotate-180`. Keyboard arrows are mirrored in RTL. Numbers/units stay LTR inside RTL text (`<bdi>` or `dir="ltr"` on spec values such as `< 0.18%`, `24,000L`).
- **Images:** meaningful `alt` (product title) and empty `alt=""` for decorative scene backgrounds.
- **Semantics:** one `h1`, scene `h2`s, card `h3`s, `<dl>` for specs, `<nav aria-label>`, `<main>` (exists).
- **Motion:** reduced-motion rules (§22). The scroll-driven scenes are already wrapped.
- **Live region:** the "added to RFQ" toast gets `role="status" aria-live="polite"`.

## 25. Performance
- Remove the global `will-change` block (app.css 13–29). Apply `will-change: transform` only during active animations (viewer open, image swap) and remove it afterwards.
- Replace the infinite maritime rAF loop (§21). Remove the unused `[data-parallax]` listener.
- Move the Google Fonts `@import` into the layout `<head>` as `<link rel="stylesheet">` after the existing preconnects. Trim weights to those used: Cairo 400/600/700/800, Outfit 400/500/600, Playfair 700, Cinzel 600.
- Images: add `loading="lazy" decoding="async"` and `width`/`height` to every below-fold `<img>` (timeline, picker, rail thumbnails, hero cards beyond the first). The hero background gets `fetchpriority="high"`. **Optional asset step (no new npm dependency):** produce WebP versions manually or via an existing system tool, keep the JPG fallbacks, and reference them with `<picture>`. Record it in the progress file if done.
- Backdrop-filter budget: at most ~6 blurred elements per viewport on desktop and ~2 on mobile. Scenes on solid backgrounds use no blur.
- Remove the blur filters from per-item transforms (old drum). Remove infinite shine animations.
- Dead CSS removal (Phase 12) should cut `app.css` by roughly 35–45%.
- Target metrics (Lighthouse mobile, `/ar`): LCP < 2.5 s locally-throttled, CLS < 0.05, no long tasks from scroll handlers, INP < 200 ms on selector interactions.

## 26. Backend protection

**Do not change (absolute):** database schema/migrations · models (`Product`, `CompanyIntroHero`, `HeroSlide`, `User`, …) · `app/Providers/AppServiceProvider.php` · controllers · routes · Livewire PHP classes (`app/Livewire/*`) · RFQ/quotation logic · authentication/permissions · `lang/*` structure · admin views and workflows · `.env` / secrets · tests' assertions.

**Frontend contracts that must survive unchanged:**
- The `add-to-cart` browser event payload: `{ productId, packagingType, size, quantity, unit, isPrivateLabel }`.
- `toggle-quote-cart` and `cart-updated` (with `detail.count`) events.
- `$product->getAvailablePackagings()` output usage (`id`, `name_*`, `desc_*`, `icon_emoji`, `badge`, `default_sizes`, `logistics_hint`).
- `id="product-{id}"` anchors, the `#products`, `#terroir` and `#certifications` anchors, and `/rfq` links.
- `$companyHero` fields: `is_active`, `show_banner`, `show_badge`, `localized_title`, `localized_subtitle`, `localized_badge`, `localized_primary_cta`, `image_url`, `whatsapp_number`.
- `LaravelLocalization` URL helpers, hreflang, JSON-LD and meta tags.

**BACKEND RISK / REQUIRES APPROVAL (documented, NOT to implement):**
1. `components/hero-slider.blade.php` is orphaned, so admin-managed `HeroSlide` records (incl. `show_badge`) are not shown publicly. Restoring or removing it is a product decision.
2. The hero album products are hard-coded (`company-intro-hero.blade.php` lines 8–93) with a loose DB match (`sort_order` fallback, lines 98–102) that can attach the wrong DB id to a card. A proper fix would move to DB-driven data (requires model/admin work).
3. WhatsApp numbers are hard-coded in the layout (`21698000000`) while the hero uses `$companyHero->whatsapp_number`. Unifying them is UI-only in Blade (allowed in Phase 2) **only if** it reads the existing field with the same fallback. Anything beyond that needs approval.
4. Deleting `resources/views/layouts/app.blade.php` (legacy) — needs approval.
5. Moving hard-coded UI strings to `lang/*` files — needs approval (translation structure).

---

## 27. Exact file-by-file changes

**FILE:** `resources/css/app.css`
- CURRENT RESPONSIBILITY: fonts import, Tailwind layers, all custom utilities, cards, glows, reveals, keyframes, drum, parchment/page-curl.
- WHY IT MUST CHANGE: disabled reveals, global will-change, light-theme leftovers that override utilities, misleading names, ~330 lines of dead parchment CSS, no tokens.
- WHAT SHOULD CHANGE: add `:root` tokens (§8), base typography + RTL rules (§9), the glass system (§10), `.rn-scene` + scroll-driven keyframes (§19–20), restored reveal CSS + reduced motion (§22), focus-visible, `overflow-x: clip` utility. Later: delete legacy classes once unreferenced (grep first), and move parchment/page-curl into a marked `/* LEGACY: hero-slider only */` block.
- WHAT MUST NOT CHANGE: `@tailwind` directives order; any class still referenced by admin views (grep `resources/views/admin` before deleting anything).
- RELATED FILES: `tailwind.config.js`, all Blade files listed below.
- RISK: MEDIUM · PRIORITY: REQUIRED

**FILE:** `tailwind.config.js`
- CURRENT RESPONSIBILITY: theme colours (steel, electric, coolteal, olive, gold), fonts, content paths.
- WHY: tokens must be usable as utilities.
- WHAT SHOULD CHANGE: add `colors.rn.*` mapped to CSS variables, `fontFamily.display`, `transitionTimingFunction.rn-out/rn-inout`, `borderRadius.scene`, `maxWidth.container/wide`.
- WHAT MUST NOT CHANGE: `content` globs; existing colour scales (admin uses them).
- RELATED FILES: `resources/css/app.css`. RISK: LOW · PRIORITY: REQUIRED

**FILE:** `resources/js/app.js`
- CURRENT RESPONSIBILITY: smooth scroll, reveal observer, counters, global press transform, generic parallax, maritime rAF engine.
- WHY: infinite rAF, conflicting inline transforms, unused parallax listener, missing `js` class, no active-nav detection.
- WHAT SHOULD CHANGE: replace `no-js` with `js` on `<html>`; remove JS smooth-scroll (CSS instead); keep the observer and add a 2.5 s fallback; remove the pointerdown block; guard/remove `[data-parallax]`; rewrite the maritime engine (§21); add one scene observer that sets `aria-current` on nav links and dispatches `rn-scene-change` (`detail.id`) for the WhatsApp compact state.
- WHAT MUST NOT CHANGE: `import './bootstrap'`; counter utility API (`data-count-to`).
- RELATED FILES: layout, home. RISK: MEDIUM · PRIORITY: REQUIRED

**FILE:** `resources/views/components/layouts/app.blade.php`
- CURRENT RESPONSIBILITY: `<head>` (SEO, hreflang, JSON-LD, Vite), fixed maritime background, announcement bar, sticky header + mobile menu, `<main>`, quote-cart, footer, floating WhatsApp.
- WHY: heavy chrome, cyan CTA in nav, non-RTL WhatsApp, `overflow-x-hidden`, fonts via CSS `@import`, background lacks priority hints.
- WHAT SHOULD CHANGE: `class="no-js"` on `<html>`; fonts `<link>`; body `overflow-x-clip` + `bg-rn-bg-0`; background `<img>` gets `fetchpriority="high" decoding="async" alt=""` (decorative); slim announcement bar; nav per §12; mobile menu a11y; footer solid bg; WhatsApp `end-6` + safe-area + compact state.
- WHAT MUST NOT CHANGE: meta/OG/hreflang/JSON-LD, `@vite`, `@livewireStyles/@livewireScripts`, `@livewire('quote-cart')`, `$dispatch('toggle-quote-cart')`, `cart-updated` counter binding, `route('admin.login')`, localized URLs.
- RELATED FILES: `app.js`, `app.css`, `livewire/quote-cart.blade.php`. RISK: MEDIUM · PRIORITY: REQUIRED

**FILE:** `resources/views/home.blade.php`
- CURRENT RESPONSIBILITY: page composition and section content.
- WHY: transparent sections, dividers, watermarks, order, product header jargon, missing final CTA, no bento anchor.
- WHAT SHOULD CHANGE: reorder (§19); wrap sections in `.rn-scene` with modifiers; remove dividers and watermarks; add `id="export"`; showroom wrapper with product rail + `@showroom-select.window` (§14); timeline layout (§18); certifications restyle; final CTA scene; lazy images.
- WHAT MUST NOT CHANGE: `@if($companyHero?->is_active ?? true)` guard; `$products`/`$certificates` loops and fields; `id="product-{id}"`; all existing copy except the removed wheel-jargon phrase.
- RELATED FILES: hero, picker, layout. RISK: MEDIUM · PRIORITY: REQUIRED

**FILE:** `resources/views/components/company-intro-hero.blade.php`
- CURRENT RESPONSIBILITY: hero intro, card grid, album viewer, bottom rail, Alpine state, product data prep.
- WHY: overload, no focal point, non-isolated viewer, jargon CTA, rail as 4 boxes.
- WHAT SHOULD CHANGE: §13 composition; fanned stack / mobile snap rail; viewer → teleported dialog (§16); rail (§17); `goToProduct` dispatches `showroom-select` before scrolling; entry animation classes.
- WHAT MUST NOT CHANGE: PHP data block (lines 1–119) logic; `show_banner` / `show_badge` conditions (relocation allowed, semantics preserved); Alpine method names/signatures; `/rfq` link; localized fallback strings.
- RELATED FILES: home (showroom listener), layout (WhatsApp compact). RISK: HIGH (largest component, user-tuned recently) · PRIORITY: REQUIRED

**FILE:** `resources/views/components/product-wheel-picker.blade.php`
- CURRENT RESPONSIBILITY: per-product configuration + add-to-cart.
- WHY: scroll hijack, form-like layout, inaccessible drum, white-gradient class conflict, infinite shine, wrong EN toast.
- WHAT SHOULD CHANGE: §14 layout; §15 selector; remove `handleWheel`, the touch handlers and the drum markup; `<details>` for full specs; toast fix + `role="status"`; bottled-image fallback; drop `vivid-card-steel`, `shadow-luxury-3d`, `specular-shine`.
- WHAT MUST NOT CHANGE: `@props(['product'])`; PHP header (lines 3–9); `addToCart()` payload; `selectPackaging` quantity/unit defaults; `activeProductImage` existing branches; the `storage/` guard. **Keep the filename** (renaming means touching home and docs; not worth the risk).
- RELATED FILES: `home.blade.php`, `livewire/quote-cart.blade.php` (event consumer). RISK: HIGH · PRIORITY: REQUIRED

**FILE:** `resources/views/livewire/quote-cart.blade.php`
- CURRENT RESPONSIBILITY: RFQ drawer UI bound to the Livewire component.
- WHY: visual consistency only (cyan borders, glass tokens).
- WHAT SHOULD CHANGE: `.rn-glass-strong`, gold primary button, focus-visible, `role="dialog" aria-modal` on the panel, Esc to close (`@keydown.escape.window="$wire.set('isOpen', false)"`).
- WHAT MUST NOT CHANGE: every `wire:*` directive, `$wire.isOpen`, method calls, item loops, form submission and routes.
- RELATED FILES: layout. RISK: MEDIUM · PRIORITY: OPTIONAL (Phase 9)

**FILE:** `resources/views/components/hero-slider.blade.php`
- CURRENT RESPONSIBILITY: none (orphan).
- WHAT SHOULD CHANGE: nothing. Leave it untouched.
- RISK: LOW · PRIORITY: — (see §26.1)

**FILE:** `resources/views/rfq/create.blade.php`, `rfq/review.blade.php`, `rfq/success.blade.php`
- CURRENT RESPONSIBILITY: RFQ funnel pages on the same layout.
- WHAT SHOULD CHANGE: verification only. They inherit the layout/tokens, so check readability after Phase 1–2 (they become solid `--rn-bg-0` pages without scenes; the background image still shows → acceptable). Only colour-class swaps if contrast breaks.
- WHAT MUST NOT CHANGE: forms, fields, `wire:`/`action` attributes, validation output.
- RISK: LOW · PRIORITY: REQUIRED (regression check)

**FILE:** `resources/views/layouts/app.blade.php`
- Legacy, unreferenced. **Do not edit.** RISK: — · PRIORITY: — (see §26.4)

## 28. Exact component-by-component changes (summary)

| Component | Remove | Add | Keep |
|---|---|---|---|
| Announcement bar | ping dot, `font-black` | static gold dot, `py-2` | text, WhatsApp link |
| Header | cyan cart gradient, stacked logo, 96 px | 72 px, scrolled state, active underline, `#export` link | links, lang dropdown, cart events |
| Mobile menu | — | full sheet, a11y, scroll lock | links |
| Hero intro | pills row (relocated), WhatsApp CTA, accreditation box, jargon CTA | display H1, lead, gold CTA, glass secondary, inline trust row, entry stagger | all strings, toggles |
| Hero cards | grid header/footer notes, 9 px text | fanned stack (desktop), snap rail (mobile) | `openAlbum(idx)`, data |
| Album viewer | in-flow placement, bars over image | teleported dialog, backdrop isolation, 2-col layout, focus mgmt | methods, peek card, metrics |
| Info rail | 4 boxed tiles, icon tiles | single divided rail, optional 5th banner item | content |
| Bento | top colour bars, pings, watermark | `id="export"`, flat glass, gold hairline hover | all tiles and copy |
| Showroom | per-product giant card stack | product rail, single stage, pedestal, `<details>` | anchors, data, events |
| Selector | 3D drum, wheel/touch handlers, dots | segmented packaging + size chips, keyboard | methods, payload |
| Timeline | `h-36` images | connector line, numbered nodes, lazy images | cards, copy |
| Certifications | ping, fill-swap hover | seal cards, success text | DB loop |
| Final CTA | — | new scene from existing copy | — |
| Footer | blur | solid bg | content, links |
| WhatsApp float | `right-6`, ping, scale-110 | `end-6`, safe-area, compact state | link/message |
| Quote cart | cyan accents | glass-strong, dialog a11y, Esc | all `wire:` logic |

---

## 29. Implementation phases

> After **every** phase: `npm run build` → `php artisan test` → visual check `/ar`, `/en`, `/fr` at 1440×900 and 390×844 → update `docs/UI_REDESIGN_PROGRESS.md`.
> Rollback is per-file: the project is **not under git** (the user stated this — do not run git commands). **Before editing a file in a phase, copy it to `storage/app/ui-redesign-backup/<phase>/<same relative path>`** (create the folder with `mkdir -p`). Restoring = copying back. List the backups in the progress file. (`storage/app` is git-ignored by Laravel defaults and never served publicly.)

### PHASE 1 — Design tokens / visual foundation
- **OBJECTIVE:** tokens, typography base, glass system, focus, reduced motion; zero layout change.
- **FILES:** `resources/css/app.css`, `tailwind.config.js`.
- **IMPLEMENTATION:** §8 variables in `@layer base`; Tailwind `rn` colours/fonts/easing/radius; §9 RTL font rule + `uppercase` neutralisation; §10 four glass classes + `--flat`; global `:focus-visible`; global reduced-motion rule; remove the global `will-change` block (lines 13–29); `.overflow-x-clip` utility.
- **DEPENDENCIES:** none.
- **ACCEPTANCE:** build passes; Arabic headings render in Cairo (DevTools computed font); no visual regressions besides fonts/focus rings; tests green.
- **RISKS:** RTL `uppercase` rule affecting admin views → scope it to `body:not(.admin)`, or check that admin uses its own layout (verify).
- **ROLLBACK:** restore both files from the backup.

### PHASE 2 — Global layout / navbar
- **OBJECTIVE:** quieter chrome, correct overflow, fonts link, RTL WhatsApp, mobile menu a11y.
- **FILES:** `components/layouts/app.blade.php`, `resources/js/app.js` (no-js/js swap, remove pointerdown + JS smooth scroll, scene/nav observer stub), `resources/css/app.css` (remove the `@import`).
- **IMPLEMENTATION:** §12, §17 WhatsApp part, §25 fonts, body `overflow-x-clip bg-rn-bg-0`, background img attributes.
- **DEPENDENCIES:** Phase 1.
- **ACCEPTANCE:** header ≤ 72 px; cart opens; counter updates after add-to-cart; language switch works on all 3 locales; WhatsApp sits at the inline-end in AR (left) and EN (right); mobile menu opens/closes with Esc and traps focus; no horizontal scroll at 360 px.
- **RISKS:** removing the `@import` without adding the `<link>` breaks fonts → do both in the same step.
- **ROLLBACK:** restore the 3 files.

### PHASE 3 — Hero composition
- **OBJECTIVE:** a single focal point; relocate secondary items; card stack; rail restyle.
- **FILES:** `components/company-intro-hero.blade.php`, `app.css` (hero scrim/stack classes).
- **IMPLEMENTATION:** §13, §17. Keep state A untouched in this phase (it is handled in Phase 8).
- **DEPENDENCIES:** Phase 1–2.
- **ACCEPTANCE:** above the fold on 1440×900 shows eyebrow, H1, lead, 2 CTAs, trust row and card stack only; `show_banner` off hides the banner item; `show_badge` off hides the eyebrow; clicking a card opens the album; AR/EN/FR line lengths are fine; test `AdminOperationsTest` still green.
- **RISKS:** breaking the Alpine `x-data` string (it is a long inline object) → edit markup outside `x-data` only in this phase.
- **ROLLBACK:** restore the hero file.

### PHASE 4 — Major section architecture
- **OBJECTIVE:** scene wrappers, order, backgrounds, dividers/watermarks removed, `#export`, final CTA.
- **FILES:** `home.blade.php`, `app.css` (`.rn-scene*`, scene atmosphere modifiers), layout (footer solid, nav `#export` link).
- **IMPLEMENTATION:** §18 (structure parts), §19, §21 backgrounds (CSS only).
- **DEPENDENCIES:** Phase 1–3.
- **ACCEPTANCE:** each scene is visibly distinct with an opaque background; the hero port image is not visible behind the products; anchors scroll correctly (`scroll-mt` with the 72 px header); the order matches §19; no content lost (diff the visible text count before/after: `grep -o` the Blade strings).
- **RISKS:** negative margin overlap hides content edges → verify padding; z-index vs sticky header.
- **ROLLBACK:** restore files.

### PHASE 5 — Cinematic layered-scroll system
- **OBJECTIVE:** scroll-driven scene enter/exit; restored reveals; maritime engine rewrite.
- **FILES:** `app.css`, `app.js`.
- **IMPLEMENTATION:** §20 keyframes in `@supports` + media guards; §22 reveal CSS with the `.js` scope + timeout fallback; §21 maritime engine (idle loop, hero-bound, IO pause, reduced motion). Optional 5b sticky for short scenes.
- **DEPENDENCIES:** Phase 4.
- **ACCEPTANCE:** in Chrome desktop the previous scene recedes (scale ≈ .97) as the next rises (from ≈ .955); Firefox shows static stacking without errors; reduced-motion emulation shows no motion; with the Performance panel idle (no scroll) there are **0 rAF callbacks per second**; content is visible with JS disabled.
- **RISKS:** the products scene shrinking during interaction → narrow the exit range (§20); jank on low-end GPUs → limit to ≥ 1024 px if needed.
- **ROLLBACK:** remove the `@supports` block and restore `app.js`.

### PHASE 6 — Product showcase redesign
- **OBJECTIVE:** a showroom with the product photo as hero; product rail; hero → showroom contract.
- **FILES:** `home.blade.php` (showroom wrapper + rail), `product-wheel-picker.blade.php` (layout only, selector untouched until Phase 7 — the drum may temporarily remain), `company-intro-hero.blade.php` (`goToProduct` dispatch only).
- **IMPLEMENTATION:** §14 option B (fallback A).
- **DEPENDENCIES:** Phase 4.
- **ACCEPTANCE:** one product visible at a time; rail switches products with mouse, keyboard and touch; the album CTA "Configure packaging…" selects and scrolls to the correct product; every product's add-to-cart still adds the right `productId`; the image dominates (≥ 55% of the stage width on desktop).
- **RISKS:** hidden (`x-show`) products and `scrollIntoView` targets → scroll to the showroom section, not the hidden wrapper; ensure the selection happens before the scroll.
- **ROLLBACK:** restore the 3 files.

### PHASE 7 — Product selector redesign
- **OBJECTIVE:** a reliable, accessible packaging + size selector; no scroll capture.
- **FILES:** `product-wheel-picker.blade.php`, `app.css` (remove `.wheel-cylinder*` once unused).
- **IMPLEMENTATION:** §15 in full, including the toast fix and the image swap transition.
- **DEPENDENCIES:** Phase 6.
- **ACCEPTANCE:** the mouse wheel over the selector scrolls the page; keyboard ←/→ (mirrored in RTL), Home/End work; screen reader announces "radio, n of m, checked"; quantity/unit defaults switch exactly as before for bulk/flexitank/carton/bucket; `add-to-cart` payload identical (verify in the Livewire cart: size, packaging, qty, unit, private label); the EN toast is English.
- **RISKS:** `x-for` keys and roving tabindex bugs → test every product's packaging list.
- **ROLLBACK:** restore the picker file + app.css.

### PHASE 8 — Product modal / viewer
- **OBJECTIVE:** isolated, accessible viewer.
- **FILES:** `company-intro-hero.blade.php` (state A), `app.css`.
- **IMPLEMENTATION:** §16.
- **DEPENDENCIES:** Phase 3, 6.
- **ACCEPTANCE:** opening dims and blurs the page; focus moves into the dialog; Esc/arrows work; scroll is locked and restored; the peek card is visible on the correct side in AR and EN; the CTA navigates to the right showroom product; no clipping at 1440×900, 1280×720 or 390×844.
- **RISKS:** `x-teleport` moving the node out of the hero `x-data` scope → the teleported template still shares the scope in Alpine 3 (verify). If not, keep it in place with `position: fixed`.
- **ROLLBACK:** restore the hero file.

### PHASE 9 — Information rail and secondary content
- **OBJECTIVE:** final rail polish, bento/timeline/certifications restyle, quote-cart visual pass.
- **FILES:** `home.blade.php`, `company-intro-hero.blade.php` (rail), `livewire/quote-cart.blade.php`, `app.css`.
- **IMPLEMENTATION:** §17, §18, quote-cart row in §27.
- **DEPENDENCIES:** Phase 4.
- **ACCEPTANCE:** ≤ 1 animated status dot per viewport; the timeline reads as a sequence; the cart works end-to-end (add → open → edit qty → remove → proceed to `/rfq`).
- **ROLLBACK:** restore files.

### PHASE 10 — Responsive behavior
- **OBJECTIVE:** dedicated mobile layouts.
- **FILES:** all touched Blade files + `app.css`.
- **IMPLEMENTATION:** §23 table.
- **ACCEPTANCE:** at 360, 390, 768 and 1024 widths: no horizontal overflow (`document.documentElement.scrollWidth === innerWidth`), touch targets ≥ 44 px, sticky in-card CTA works, viewer full-screen, rails scroll with snap.
- **ROLLBACK:** restore files.

### PHASE 11 — Accessibility / reduced motion
- **OBJECTIVE:** complete §24.
- **FILES:** as needed.
- **ACCEPTANCE:** keyboard-only pass through nav → hero → viewer → showroom → add-to-cart → cart; visible focus everywhere; axe/Lighthouse a11y ≥ 95 on `/ar` and `/en`; reduced-motion emulation is clean.
- **ROLLBACK:** restore files.

### PHASE 12 — Performance
- **OBJECTIVE:** §25.
- **FILES:** `app.css` (dead-code removal — grep every class across `resources/views/**` incl. admin before deleting), `app.js`, Blade `<img>` attributes, optional WebP assets.
- **ACCEPTANCE:** app.css ≥ 35% smaller; no infinite animations offscreen; Lighthouse mobile perf improved vs baseline (record both numbers); CLS < 0.05.
- **ROLLBACK:** restore files; delete any new WebP files.

### PHASE 13 — Regression / functional testing
- **OBJECTIVE:** prove nothing broke.
- **FILES:** none (tests only; adding a *new* feature test file for homepage rendering is allowed, but existing tests must not be modified).
- **ACCEPTANCE:** §31 checklist fully ticked; progress file set to `COMPLETE`; propose the docs/04 update to the user (do not perform it).

**Priority if budget runs short (highest impact first):** Phase 7 (scroll hijack) → 1 → 2 → 4 → 3 → 6 → 8 → 5 → 9 → 12 → 10 → 11 → 13. *Phase 7 can run right after Phase 1 because it is self-contained in the picker file. If you choose this, record it in the progress file.*

## 30. Acceptance criteria (global)
- Rayanova identity intact (logo, maritime hero, steel/dark palette, product imagery).
- The hero has one dominant focal point (the H1), with ≤ 2 CTAs and no overlapping floating elements.
- Arabic headings render in Cairo with line-height ≥ 1.25; EN/FR use Playfair for display.
- Each scene has its own opaque background; the products scene no longer reads as a continuation of the hero.
- The product photograph is the largest element in the showroom.
- The selector never intercepts the wheel or vertical touch scroll.
- Desktop scenes recede/rise via scroll-driven CSS; static fallback elsewhere; reduced motion respected.
- ≤ 1 looping status animation per viewport; no infinite rAF when idle.
- Four glass classes only; cyan used only for interaction/focus/status.
- The viewer is modal, isolated, keyboard-operable, and correct in RTL and LTR.
- No horizontal overflow at 360 px; touch targets ≥ 44 px.
- No console errors; `php artisan test` green.
- RFQ: add-to-cart → cart → `/rfq` → review → success works in all three locales.
- Backend files untouched (verify with the §32 file list).

## 31. Testing checklist
- [ ] `npm run build` succeeds, no Tailwind warnings for unknown classes.
- [ ] `php artisan test` (AdminOperationsTest, RfqFlowTest, ExampleTest) green.
- [ ] `/ar`, `/en`, `/fr` render; `<html dir>` correct; hreflang present.
- [ ] Admin `/admin/company-hero` toggles `show_banner` / `show_badge` and they still affect the hero.
- [ ] Hero cards open the viewer; prev/next/peek/Esc/close work; CTA lands on the correct product.
- [ ] Showroom rail switches all products; each packaging option for each product changes sizes and quantity/unit defaults as before.
- [ ] The wheel over the selector scrolls the page (desktop); vertical swipe over the selector scrolls the page (mobile emulation).
- [ ] Add-to-cart with private label on/off → cart shows the correct product/packaging/size/qty/unit/flag; counter badge updates.
- [ ] The cart proceeds to `/rfq`; submit a test RFQ → review → success.
- [ ] Mobile menu, language dropdown, WhatsApp links (number + prefilled text unchanged).
- [ ] Keyboard-only full journey; focus visible.
- [ ] Reduced-motion emulation; JS-disabled content visibility.
- [ ] Widths 360 / 390 / 768 / 1024 / 1440 / 1920: no overflow, no clipping.
- [ ] Chrome + Safari + Firefox (static fallback) smoke test.
- [ ] Lighthouse (mobile) before/after numbers recorded in the progress file.
- [ ] DevTools Performance: idle page shows no continuous rAF.

## 32. Rollback strategy
- **No git** (per user). Use per-phase file backups under `storage/app/ui-redesign-backup/<phase>/…` (copy before the first edit of each file in that phase).
- Each phase is independently revertible by restoring its listed files, then `npm run build`.
- CSS additions are namespaced (`rn-*`) and additive until Phase 12, so earlier phases can be reverted without touching later CSS.
- Never delete legacy CSS before Phase 12, and only after a repository-wide grep shows zero references.
- If a phase is partially done when the session ends: finish the current file to a consistent state *or* restore it from the backup, then document the state in the progress file (§ "Future progress rules" there).

## 33. Definition of Done
- All 13 phases are marked `completed` in `docs/UI_REDESIGN_PROGRESS.md`, with verification notes.
- §30 acceptance criteria met; §31 checklist ticked with evidence (screenshots saved to the artifacts directory or listed).
- No backend file changed (list of modified files matches §27).
- The progress file lists deviations (if any) with reasons.
- A proposal for updating `docs/04_DESIGN_SYSTEM_UI_UX.md` and `docs/CHANGELOG.md` has been presented to the user (not executed without approval).

## 34. Top 10 highest-impact improvements
1. **Kill the scroll hijack:** replace the 3D drum with an accessible segmented packaging + size rail (`product-wheel-picker`).
2. **Scene architecture:** opaque, distinct backgrounds per section; the maritime photo is bounded to the hero.
3. **Fix Arabic display typography** (Cairo for `font-serif` in RTL) — one CSS rule, site-wide win.
4. **Hero focus:** one H1, two CTAs, an inline trust row; banner/pills/WhatsApp CTA relocated.
5. **Product showroom:** a single stage with a product rail, the photograph as hero, specs in a disclosure.
6. **Isolated product viewer:** teleported modal dialog with backdrop dimming, focus management and the peek card.
7. **Layered glass-page scroll:** CSS scroll-driven scene enter/exit with static fallback and reduced-motion safety.
8. **Semantic colour discipline:** gold CTAs, olive origin, cyan only for interaction; the cyan gradient is removed from all buttons.
9. **Motion cleanup + performance:** restore reveals properly; remove pings/shine/float, global `will-change` and the infinite rAF loop.
10. **Chrome and rail:** a 72 px quiet navbar, a single divided information rail, and an RTL-correct WhatsApp float that never overlaps content.

---

## 35. Phase 14 — Full-Screen Cinematic Scene Choreography Specification

### 35.1 Design Intent & Viewport Choreography
The website operates as a cohesive sequence of full-screen cinematic chapters rather than an ordinary long webpage:
- **Desktop Scene Sizing:** Each major desktop scene occupies approximately one viewport (`min-height: 100svh`, with flex-column alignment and centered focal points).
- **Physical Glass Stacking & Elevation:** Each subsequent scene has a higher `--scene-z` depth layer and overlaps previous scenes with a deep elevation shadow (`box-shadow: 0 -25px 60px -15px rgba(0, 0, 0, 0.85), 0 -1px 0 0 rgb(var(--rn-line) / 0.18)`), top rounded border-radius (`var(--rn-radius-scene)`), and negative margin offset (`margin-top: -3.5rem` on desktop).
- **Outgoing Scene Dynamics:** Moves upward (`translateY(-45px)`), slightly scales down from 1.0 to approximately 0.97, decreases opacity to ~0.35, and visually recedes into the background with subtle atmospheric dimming.
- **Incoming Scene Dynamics:** Emerges from behind/under the outgoing scene, starts slightly smaller (`scale(0.95)`, `translateY(60px)`, `opacity: 0.65`), smoothly rises to 1.0 scale and 1.0 opacity, overlaps the prior scene with a clear depth hierarchy, and takes over the viewport.
- **Implementation Mechanism:** Native CSS scroll-driven animations via `animation-timeline: view()` and `@keyframes rn-scene-lifecycle` inside `@supports (animation-timeline: view())`, completely free from scroll hijacking or third-party JS animation libraries.

### 35.2 The 7 Viewport Chapters
1. **Scene 1: Hero Stage (`#hero`, `--scene-z: 10`)**
   - High-impact brand and product intro, CHT fanned card stack, primary RFQ CTA, bottom trust rail.
   - Overridden with zero top margin, zero top radius, and dedicated `rn-hero-exit` exit animation.
2. **Scene 2: Products Showroom (`#products`, `--scene-z: 20`)**
   - Atmosphere: `.rn-scene--showroom` (radial olive pedestal).
   - Horizontal export catalog product rail tablist (`role="tablist"`).
   - High-definition showroom stage (`x-product-wheel-picker`): 58% dominant photography pedestal, live interactive visual badge, segmented packaging radiogroup, horizontal size pill rail with keyboard support, quantity calculator, private label toggle, and Add to RFQ CTA.
3. **Scene 3: Chemical Purity & Lab Analysis Terminal (`#export`, `--scene-z: 30`)**
   - Atmosphere: `.rn-scene--bento` (calm export backdrop).
   - Col-Span-7: COI Laboratory Certified terminal with 4 live chemistry meters (Acidity < 0.18%, Polyphenols 520+ mg/kg, Peroxides < 6.2 meq, UV Absorbance K270 < 0.14) + ISO 17025 accredited CoA guarantee.
   - Col-Span-5: Organoleptic & Quality Assurance console (tasting panel score > 8.0, zero defect median, gas chromatography, cold extraction mechanical verification).
4. **Scene 4: Global Logistics & Bulk Freight Infrastructure (`#logistics`, `--scene-z: 40`)**
   - Atmosphere: `.rn-scene--logistics` (maritime cyan & gold ambient accents).
   - 3-Column Grid:
     - Col 1: Bulk & FCL capacities (Flexitank 24kL / 21.6 MT, 20ft FCL, 40ft High Cube, IBC tote tanks, nitrogen blanketing).
     - Col 2: Turnkey OEM & private label studio (Marasca/Dorica glass, litho metal tins, date boxes, multilingual compliance).
     - Col 3: Global trade corridors & fast transit (Marseille 36-48h, Rotterdam 5-7d, New York 14-18d, Jebel Ali 12-15d, Incoterms 2020).
5. **Scene 5: Farm-to-Port Traceability Route (`#terroir`, `--scene-z: 50`)**
   - Atmosphere: `.rn-scene--terroir` (olive grove heritage backdrop).
   - Desktop horizontal connecting route line + 4 sequential chapters: Terroir & Harvest, Cold Extraction & Sorting, Lab Certification & CoA, Port Dispatch.
6. **Scene 6: Accredited Certifications (`#certifications`, `--scene-z: 60`)**
   - Atmosphere: `.rn-scene--certifications` (controlled laboratory grid).
   - 4 accredited certification cards with gold outline shields, issuer verification, and calm Verified Active status.
7. **Scene 7: Final Conversion Curtain (`#final-cta`, `--scene-z: 70`)**
   - Atmosphere: `.rn-scene--cta` (centered spotlight).
   - High-priority Request Export RFQ action, direct WhatsApp desk trigger, Incoterms 2020 credentials.

---

## 36. Phase 15 — Visual Correction & Real Cinematic Scroll Specification

### 36.1 Side-by-Side Product Rail (Hero Showcase)
- **Elimination of Card Overlap:** Removed absolute positioning, rotation transforms, and playing-card fanning that caused physical cards to cover one another.
- **Horizontal Showcase Architecture:** Implemented a clean, side-by-side product rail on desktop (`grid grid-cols-2 xl:grid-cols-4 gap-3 xl:gap-3.5 w-full`).
- **Individual Legibility:** Every single card is individually distinct and readable with:
  - 4:3 product photography stage with index tag (`01`, `02`, `03`, `04`).
  - Category tag in olive uppercase typography.
  - Two-line clamped product title with tooltip.
  - Export badge/specification.
  - Interactive "Specs →" trigger opening the detailed product modal viewer.
- **Mobile Touch Rail:** Mobile devices use a touch-friendly horizontal rail (`snap-x snap-mandatory`, `overflow-x-auto`, no horizontal page overflow).

### 36.2 Unified Full-Bleed Cinematic Scene System
- **Standardized Outer Shape:** All 7 major scenes (`.rn-scene`) adhere to a unified full-bleed cinematic scene system (`border-radius: 0`, `margin-top: 0`, full edge-to-edge width).
- **Hairline Top Borders & Elevation:** Standardized top border (`border-top: 1px solid rgb(var(--rn-line) / 0.12)`) and deep upward shadow (`box-shadow: 0 -24px 48px -12px rgba(0, 0, 0, 0.75)`).
- **Internal Radius Freedom:** Internal component cards, modals, and terminals freely use modern border-radii (`rounded-2xl`, `rounded-3xl`) with crisp glassmorphism.

### 36.3 Hardware-Accelerated Cinematic Scroll Stacking
- **Desktop Sticky Staging:** On desktop (`min-width: 1024px`), `.rn-scene` employs `position: sticky; top: 0; min-height: 100svh;` with progressive `--scene-z` depth hierarchy (Hero `10`, Products `20`, Export Lab `30`, Logistics `40`, Terroir `50`, Certifications `60`, Final CTA `70`).
- **Real Card-Deck Stacking:** As the user scrolls, each incoming scene physically rises and slides over the pinned outgoing scene.
- **Scroll-Driven Choreography:**
  - **Outgoing Scene:** Scales down from 1.00 to 0.97, decreases opacity to ~0.40, dims with `brightness(0.75)`, and recedes behind the incoming scene.
  - **Incoming Scene:** Emerges from behind, starting at scale 0.95 and opacity 0.80, rising smoothly to scale 1.00 and opacity 1.00 to become the dominant scene.
- **Browser Compatibility:** Dual-engine architecture:
  - Native CSS `animation-timeline: view()` with `entry 0%..100%` and `exit 0%..100%` ranges.
  - JavaScript progressive enhancement fallback in `app.js` for non-timeline browsers.
  - `overflow-x: clip` on `body` ensures sticky positioning is never broken by ancestor scroll containers.

### 36.4 Controlled Tonal Atmospheres (Anti-Monotony Lighting)
- **Elimination of Monotonous Near-Black Void:** Replaced uniform near-black backgrounds with curated, high-contrast tonal atmospheres:
  - **Hero:** Deep maritime navy/steel with warm gold accents and original export imagery.
  - **Products Showroom:** Rich Mediterranean olive (`#0f1512` with `rgba(56, 78, 44, 0.42)` glow) and warm bronze highlights.
  - **Export Lab:** Precision technical slate/steel (`#0e1520` with `rgba(45, 212, 191, 0.12)` cyan glow).
  - **Logistics:** Industrial maritime blue-steel (`#0d1624` with `rgba(28, 64, 110, 0.42)` ocean gradient).
  - **Terroir:** Sun-drenched earth and Cap Bon grove warmth (`#141712` with `rgba(160, 128, 56, 0.22)`).
  - **Certifications:** Architectural graphite-steel matrix (`#11141c` with gold seal illumination).
  - **Final CTA:** Executive conversion portal (`#0c111a` with concentrated gold glow).
- **Glass Contrast:** Elevated `.rn-glass-card` background luminance (`rgba(24, 33, 47, 0.72)`) with 16px blur for razor-sharp readability.

### 36.5 Removal of Test Artifacts & Database Hygiene
- **Public View Sanitization:** Hero component automatically sanitizes test placeholders (`Test Hero Badge...`, `Test Company Hero Title...`) in non-test runtime environments, falling back to authentic brand copy.
- **Database Restoration:** Reset SQLite record 1 to authentic multilingual copy in AR, EN, and FR.
- **Test Suite Safety:** Updated `AdminOperationsTest.php`'s `finally` block to force immediate database restoration upon test completion, preventing test suite runs from polluting persistent storage.
