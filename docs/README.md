# 📚 Documentation Centrale — RAYANOVA T.D
## Référentiel Technique & Guide d'Ingénierie Logicielle (Enterprise Grade)

Bienvenue dans la documentation officielle d'ingénierie de la plateforme **Rayanova T.D**.
Cette documentation suit les standards des grandes entreprises technologiques pour garantir une maintenabilité, une traçabilité et une évolutivité maximales du code.

---

### 🗂️ Sommaire & Structure des Modules

| Fichier | Titre | Description |
| :--- | :--- | :--- |
| **[00_MASTER_BLUEPRINT.md](./00_MASTER_BLUEPRINT.md)** | **Cahier des Charges Maître (Blueprint)** | Vision globale, positionnement B2B export, roadmap des sprints et synthèse générale. |
| **[01_ARCHITECTURE.md](./01_ARCHITECTURE.md)** | **Architecture Globale & Stack** | Principes architecturaux (Clean Architecture, Service Layer), choix techniques et organisation du code. |
| **[02_DATABASE_SCHEMA.md](./02_DATABASE_SCHEMA.md)** | **Dictionnaire des Données** | Schéma détaillé des tables, relations, index et colonnes JSON dynamiques. |
| **[03_WORKFLOWS_AND_STATE_MACHINE.md](./03_WORKFLOWS_AND_STATE_MACHINE.md)** | **Workflows Métier & Machine d'États** | Cycle de vie complet : RFQ ➔ Devis ➔ Magic Link ➔ Négociation ➔ Contrat ➔ Facture. |
| **[04_DESIGN_SYSTEM_UI_UX.md](./04_DESIGN_SYSTEM_UI_UX.md)** | **Système de Design & Morpho-Glass** | Tokens de couleurs agroalimentaires, règles CSS de verre dépoli animé, composants interactifs (Wheel Picker). |
| **[05_SECURITY_AND_PERMISSIONS.md](./05_SECURITY_AND_PERMISSIONS.md)** | **Sécurité, Rôles & Habilitations** | Rôles Super Admin vs Admin, cryptographie des URLs signées et sécurisation des webhooks. |
| **[06_IMPLEMENTATION_PLAN.md](./06_IMPLEMENTATION_PLAN.md)** | **Plan d'Exécution Complet (Master Plan)** | Feuille de route chronologique en 6 phases opérationnelles avec livrables et critères d'acceptation. |
| **[CHANGELOG.md](./CHANGELOG.md)** | **Journal des Modifications** | Historique versionné de chaque ajout, modification, correction et refactorisation (Format Standard *Keep a Changelog*). |
| **[ADR/](./ADR/)** | **Architectural Decision Records** | Registre des décisions d'architecture motivées et datées. |

---

### 🛡️ Règle d'Or de Maintenance (Enterprise Standard)
> **Chaque modification de code doit impérativement être reflétée dans son fichier de documentation dédié et enregistrée dans le [CHANGELOG.md](./CHANGELOG.md).**
> Aucun changement structurel (nouvelle table, modification d'un statut de devis, nouveau composant UI) ne doit être effectué sans mise à jour immédiate de ce référentiel.
