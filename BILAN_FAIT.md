# Bilan — Ce qui a été fait
**Plateforme de Gestion Communautaire | Mise à jour : 2026-03-26**

---

## ✅ UC2 — Se connecter
- Authentification email / mot de passe fonctionnelle
- Redirection automatique selon le rôle (admin → `/admin`, opérateur → `/mon-espace`, visiteur → `/`)
- Blocage des comptes suspendus (`CheckActive` middleware + vérification dans `LoginController`)
- Blocage si email non vérifié (message explicite, redirection vers la page de connexion)

---

## ✅ UC3 — Consulter son profil
- L'opérateur accède à son profil via `/mon-espace/profil`
- Affichage du statut courant (`EN_ATTENTE`, `VALIDE`, `REJETE`, `SUSPENDU`)
- Affichage du motif de rejet si applicable

---

## ✅ UC7 — Valider ou rejeter un profil
- L'admin peut **approuver**, **rejeter** (avec motif) et **suspendre** un profil
- Email automatique envoyé à l'opérateur à chaque changement de statut
- Notification in-app (cloche) créée pour approbation et rejet
- Admin notifié par email ET notification in-app à chaque nouveau profil soumis

---

## ✅ UC10 — Exporter les données
- Export Excel des profils (via `maatwebsite/excel`)
- Export PDF des profils (via `barryvdh/dompdf`)

---

## ✅ UC21 — Réinitialiser son mot de passe
- Lien « Mot de passe oublié ? » sur la page de connexion
- Formulaire email → envoi d'un lien sécurisé (token, durée 1h, throttle 1/min)
- Page de réinitialisation avec nouveau mot de passe
- Même message affiché que l'email existe ou non (sécurité)
- Redirection vers la connexion avec message de succès

---

## ✅ UC13 — Se déconnecter
- Déconnexion via POST `/deconnexion`, session invalidée, redirection vers l'accueil

---

## ✅ UC15 — Parcourir l'annuaire
- Page annuaire avec liste des catégories et compteurs de profils validés
- Navigation par catégorie → liste des profils validés dans la catégorie

---

## ✅ UC16 — Consulter les actualités économiques
- Page publique `/actualites` listant les actualités publiées par date
- Accessible à tous (visiteurs et utilisateurs connectés)

---

## ✅ UC22 — S'abonner à la newsletter (Visiteur)
- Formulaire d'abonnement accessible sans compte
- Email de bienvenue envoyé automatiquement à l'abonnement
- Token de désabonnement généré et lien inclus dans chaque email
- Réabonnement géré (si l'abonné s'était désabonné, le mail de bienvenue est renvoyé)

---

## ✅ UC23 — Confirmer son email
- `User` implémente `MustVerifyEmail` → email de vérification envoyé automatiquement à l'inscription
- Page `/email/verify` avec bouton "Ouvrir ma messagerie" (`mailto:`) et lien "Renvoyer l'email"
- Renvoi limité à 6 fois par minute (`throttle`)
- Connexion bloquée si email non vérifié (message explicite)
- Routes opérateur protégées par le middleware `verified`

---

## 🟡 UC1 — S'inscrire sur la plateforme *(partiel)*
- Inscription en 1 étape (formulaire nom, email, mot de passe)
- Rôle `operateur` attribué par défaut à l'inscription
- Création et soumission de profil en une seule étape via `/mon-espace/profil/creer`
- Upload de documents (CV, photo, docs légaux)
- **Manque** : stepper 3 étapes, statut `INACTIF` à la création, consentement RGPD

---

## ✅ UC4 — Modifier son profil
- L'opérateur peut modifier son profil via `/mon-espace/profil/modifier`
- Upload / suppression de documents
- Profil `VALIDE` → crée une `DemandeModification` au lieu d'écraser directement — profil reste visible publiquement
- Blocage si une demande est déjà en cours d'examen
- Profil non validé → mise à jour directe (comportement existant)
- Admin compare côte à côte version actuelle vs demandée
- Approbation → changements appliqués, email envoyé
- Refus → motif envoyé par email, fichiers temporaires supprimés, profil intact

---

## 🟡 UC5 — Rechercher des profils *(partiel)*
- Recherche par nom, bio, secteur d'activité
- Filtre par catégorie
- **Manque** : filtres par niveau d'étude, localisation

---

## ✅ UC6 — Consulter un profil détaillé
- Page de profil public accessible à tous (visiteurs inclus)
- Seuls les profils `VALIDE` sont accessibles
- Champ `contact_visible` (toggle dans le formulaire) — si désactivé, email et téléphone remplacés par "Contact non public"

---

## ✅ UC8 — Gérer les comptes utilisateurs
- L'admin peut activer / désactiver un compte (`is_active`)
- L'admin peut **suspendre** un compte sans le supprimer (`is_suspended`, séparé de `is_active`)
- L'admin peut supprimer un compte (sauf admin) — sessions invalidées immédiatement
- Consultation des logs de connexion par utilisateur
- Profil masqué de l'annuaire et inaccessible en direct si compte suspendu (scope `approved` + `show`)

---

## 🟡 UC9 — Consulter les statistiques *(partiel)*
- Dashboard avec compteurs : total profils, profils validés, en attente, utilisateurs
- **Manque** : filtrage par période, statistiques de visites, acteur `AgentMunicipal`

---

## 🟡 UC11 — Gérer les catégories *(partiel)*
- CRUD complet pour les catégories (création, modification, suppression)
- Slug auto-généré
- **Manque** : règle d'intégrité sur la suppression (catégorie avec profils associés)

---

## 🟡 UC14 — Gérer son mot de passe *(partiel)*
- Modification du mot de passe depuis les paramètres (`/admin/parametres` ou `/mon-espace`)
- **Manque** : email de confirmation après changement de mot de passe

---

## 🟡 UC17 — Gérer les annonces économiques *(partiel)*
- Admin peut créer, modifier, publier, supprimer des annonces
- Email envoyé aux utilisateurs actifs à la publication
- Notification in-app envoyée aux utilisateurs actifs à la publication
- Opérateur peut consulter les annonces publiées
- **Manque** : rôle `AgentMunicipal` (seul l'admin gère les annonces actuellement)

---

## ✅ UC12 — Abonnement newsletter pour utilisateurs enregistrés
- Toggle newsletter dans `/mon-espace/parametres`
- Table `newsletters` liée à `users` via `user_id` (nullable)
- Liaison automatique à l'inscription si abonnement anonyme existant
- Email de bienvenue envoyé si nouvel abonnement
- Page admin `/admin/newsletter` : stats (actifs, désabonnés, avec compte, anonymes, nouveaux ce mois) + liste paginée

---

## ✅ UC18 — Gérer la newsletter
- Envoi automatique aux abonnés à la publication d'une actualité
- Email de bienvenue à l'abonnement (UC22)
- Page admin avec statistiques et liste des abonnés

---

## 🟡 UC19 — Journaliser les activités *(partiel)*
- Journalisation des connexions / déconnexions (`AuthLog`)
- Consultation des logs par utilisateur dans l'admin
- **Manque** : journalisation des autres actions (approbation, rejet, modification, suppression)

---

## 🟡 UC20 — Gérer son compte *(partiel)*
- Page paramètres : modification du nom et email
- Modification du mot de passe
- Préférence de notification (`notify_new_profile` pour admin)
- **Manque** : email de confirmation après changement d'email

---
*Bilan généré à partir du code source et des UC v3.0*
