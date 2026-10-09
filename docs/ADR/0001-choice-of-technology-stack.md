# 📜 ADR 0001 : Choix de la Stack Technique (Laravel 11, Livewire 3, Tailwind CSS)

- **Statut :** Accepté
- **Date :** 2026-09-29
- **Auteurs :** Équipe d'Ingénierie Rayanova & Senior Full Stack Architect

---

## Contexte
Rayanova T.D nécessite une plateforme hybride combinant une vitrine B2B internationale prestigieuse (avec composants interactifs comme le sélecteur circulaire de conditionnement) et un système CRM complet de négociation et de génération de documents officiels (contrats, factures). Le référencement naturel (SEO) multilingue et la rapidité de chargement sont capitaux pour conquérir les marchés étrangers.

## Décision
Nous choisissons la stack **Laravel 11.x + Livewire 3 + Alpine.js + Tailwind CSS** pour les raisons suivantes :
1. **Productivité et Cohésion :** Évite la dispersion entre un backend API et un frontend SPA séparé, tout en offrant la fluidité réactive d'une SPA grâce à Livewire 3 et Alpine.
2. **SEO Natif :** Rendu côté serveur (SSR) sans complexité de serveur Node.js externe, garantissant une indexation immédiate par Googlebot et les moteurs internationaux.
3. **Sécurité & Outils Natifs :** Support natif des URLs signées (`URL::signedRoute`), des queues de travaux asynchrones pour l'envoi d'emails et la génération de PDF lourds.
4. **Maintenance à Long Terme :** Une seule base de code unifiée en PHP 8.3+, réduisant drastiquement les coûts de maintenance.

## Conséquences
- Les développeurs travaillent dans un écosystème unifié.
- Les composants très dynamiques (ex: Wheel Picker) sont gérés avec légèreté par Alpine.js côté client sans requête réseau inutile.
- Le suivi des versions et des dépendances reste centralisé dans `composer.json` et `package.json`.
