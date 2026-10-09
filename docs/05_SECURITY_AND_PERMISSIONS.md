# 🔒 Module 05 — Sécurité, Rôles & Habilitations

## 1. Modèle d'Habilitation à Double Niveau (RBAC)
Pour répondre strictement aux directives de la direction de **Rayanova T.D** :

| Rôle | Création | Modification | Consultation | Cotation / Contrats | Suppression Définitive |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Super Admin** *(Propriétaire)* | ✅ | ✅ | ✅ | ✅ | ✅ **EXCLUSIF** |
| **Admin** *(Équipe Commerciale)* | ✅ | ✅ | ✅ | ✅ | ❌ **INTERDIT** |

### Implémentation Laravel :
- Politiques d'autorisation (`Policies`) avec barrière stricte :
  ```php
  public function delete(User $user, Product $product): bool
  {
      return $user->hasRole('super_admin');
  }
  ```
- Les boutons de suppression dans les interfaces Blade / Livewire sont conditionnés par `@can('delete', $model)`.

---

## 2. Sécurité des Magic Links (Cryptographie d'URLs)
Pour permettre aux importateurs étrangers de consulter et modifier leurs cotations sans avoir à créer de compte avec mot de passe :
- Utilisation de la méthode native `URL::temporarySignedRoute()`.
- La signature SHA-256 intègre :
  - L'identifiant unique du devis.
  - Le token de session client.
  - La date d'expiration de validité du devis.
- En cas d'altération de l'URL ou d'expiration, Laravel déclenche une exception `InvalidSignatureException` (Code HTTP 403 personnalisé avec message d'invitation à demander un renouvellement de cotation).

---

## 3. Sécurité des Webhooks Inbound (Réception d'Emails)
- Vérification systématique de la signature HMAC du fournisseur de messagerie (Mailgun, Postmark ou Resend) avant d'ingérer tout message dans la base de données.
- Nettoyage et assainissement (Sanitization HTML) pour neutraliser les injections XSS dans les corps de messages.

---

## 4. Protection des Données & Anti-Spam
- Rate Limiting strict sur les soumissions de demandes de devis (`throttle:5,1` : max 5 requêtes par minute par IP).
- Honeypot invisible pour bloquer les robots de spam sans gêner l'expérience utilisateur.
