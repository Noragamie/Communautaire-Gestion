# Plateforme de Gestion Communautaire
## Analyse des Cas d'Utilisation — Document Final Corrigé
**Version** : 3.0 | **Statut** : Final

---

## 2. Analyse Détaillée des Cas d'Utilisation

---

### UC1 — S'inscrire sur la plateforme (Visiteur) 🔄

| Champ | Valeur |
|---|---|
| **Acteur** | Visiteur |
| **Déclencheur** | Souhait de créer un compte et soumettre son profil |
| **Précondition** | Aucune session active |
| **Postcondition étape 1** | Compte créé `INACTIF`, email de confirmation envoyé |
| **Postcondition étape 2** | Profil `BROUILLON` sauvegardé |
| **Postcondition étape 3** | Profil `EN_ATTENTE`, admin notifié |

**✅ Verdict : VALIDE (corrigé v3)**

> **Correction C4/C6/C7 — Stepper 3 étapes**
>
> UC1 couvre un **flux unique en 3 étapes progressives**. Le profil n'est soumis à validation qu'à l'étape 3. Les étapes précédentes sont sauvegardées automatiquement. La confirmation email (passage `INACTIF → ACTIF`) est couverte par UC23.

> **Justification & Source**
>
> - ✔ Champs du formulaire conformes au CDC (EB § B).
> - ✔ Statut `BROUILLON` → `EN_ATTENTE` après soumission complète.
> - ✔ Consentement RGPD explicite à l'étape 1 (contacts publics).
> - ✔ Consentement `contactVisible` à l'étape 3.
> - ✔ Upload de documents à l'étape 3 (Mod. A).
> - ✔ Inscription via Google (EB § B) — contourne l'étape email mais suit le même stepper.

**Scénario principal :**
1. Le visiteur remplit l'étape 1 (compte) et coche le consentement RGPD.
2. Le système crée le compte `INACTIF` et envoie l'email de confirmation (UC23).
3. Le visiteur confirme son email → compte `ACTIF` → session ouverte.
4. L'utilisateur remplit l'étape 2 (profil de base) → profil `BROUILLON` sauvegardé.
5. L'utilisateur remplit l'étape 3 (profil complet + documents) → profil `EN_ATTENTE`.
6. L'administrateur est notifié pour validation (UC7).

---

### UC2 — Se connecter

| Champ | Valeur |
|---|---|
| **Acteur** | UtilisateurEnregistre / AgentMunicipal / Administrateur |
| **Déclencheur** | Accès aux fonctionnalités authentifiées |
| **Précondition** | Compte existant avec statut `ACTIF` |
| **Postcondition** | Session active, redirection selon le rôle |

**✅ Verdict : VALIDE**

> **Justification & Source**
>
> - ✔ Authentification email/MDP conforme à Mod. F.
> - ✔ Connexion via Google mentionnée au CDC § B.
> - ✔ La précondition est désormais `ACTIF` (pas `VALIDE`) — un utilisateur avec profil en brouillon ou en attente peut se connecter.
> - ✔ Un compte `SUSPENDU` ou `SUPPRIME` ne peut pas se connecter.

---

### UC3 — Consulter son profil

| Champ | Valeur |
|---|---|
| **Acteur** | UtilisateurEnregistre |
| **Déclencheur** | Navigation vers « Mon Profil » |
| **Précondition** | Compte `ACTIF` (quel que soit le statut du profil) |
| **Postcondition** | Profil affiché avec son statut courant |

**✅ Verdict : VALIDE**

> **Justification & Source**
>
> - ✔ Un utilisateur avec profil `BROUILLON`, `EN_ATTENTE`, `VALIDE` ou `REJETE` peut consulter son propre profil.
> - ✔ L'utilisateur voit son statut de validation et le motif de rejet éventuel.
> - ✔ Différent de UC6 : UC3 = profil propre (vue privée) ; UC6 = profil d'un autre (vue publique).

---

### UC4 — Modifier son profil

| Champ | Valeur |
|---|---|
| **Acteur** | UtilisateurEnregistre |
| **Déclencheur** | Souhait de mettre à jour ses informations |
| **Précondition** | Compte `ACTIF`, profil en statut `VALIDE` |
| **Postcondition** | `DemandeModification` créée `EN_COURS` ; profil reste `VALIDE` |

**✅ Verdict : VALIDE**

> **Décision R4 — Cycle de vie des modifications**
>
> 1. Le profil **reste `VALIDE`** pendant la revue — visibilité publique non interrompue.
> 2. Les modifications créent une `DemandeModification` : `EN_COURS` → `VALIDEE | REFUSEE`.
> 3. Si validée : modifications appliquées au profil.
> 4. Si refusée : profil revient à son état antérieur, motif envoyé.
>
> **UC4 inclut également la gestion de `contactVisible`** : l'utilisateur peut choisir de masquer ses contacts (email + téléphone) à tout moment, conformément au droit de retrait RGPD.

---

### UC5 — Rechercher des profils *(la « loupe »)* 🔄

| Champ | Valeur |
|---|---|
| **Acteur** | **Visiteur**, UtilisateurEnregistre |
| **Déclencheur** | Saisie dans la barre de recherche ou activation de filtres |
| **Précondition** | Au moins un profil `VALIDE` en base |
| **Postcondition** | Liste filtrée des profils valides correspondant aux critères |

**✅ Verdict : VALIDE (corrigé v3)**

> **Correction C1 — Accès public complet**
>
> La notion de « vue limitée » pour les visiteurs est **supprimée**. Le CDC ne mentionne aucune restriction. Tout visiteur accède à la liste complète des profils validés avec tous les filtres disponibles.

> **Justification & Source**
>
> - ✔ Objectif général CDC : « Faciliter la recherche et la consultation des profils. »
> - ✔ Module B : recherche simple + avancée sans restriction d'accès.
> - ✔ Filtres : secteur, métier, niveau, localisation, catégorie.

---

### UC6 — Consulter un profil détaillé 🔄

| Champ | Valeur |
|---|---|
| **Acteur** | **Visiteur**, UtilisateurEnregistre |
| **Déclencheur** | Sélection d'un profil dans UC5 ou UC15 |
| **Précondition** | Profil en statut `VALIDE` |
| **Postcondition** | Page détaillée affichée : bio, compétences, expériences, contacts (si `contactVisible = true`) |

**✅ Verdict : VALIDE (corrigé v3)**

> **Correction C2 — Suppression de la précondition « authentifié »**
>
> Le CDC Module B décrit la page détaillée sans aucune restriction d'accès. Tout visiteur peut consulter un profil validé.
>
> **Règle d'affichage des contacts** :
> - Si `contactVisible = true` (défaut) → email + téléphone affichés à tous.
> - Si `contactVisible = false` (choix de l'utilisateur) → contacts masqués, message « Contact non public ».
>
> Cette règle respecte à la fois le choix du commanditaire (contacts publics) et le RGPD (droit de retrait).

---

### UC7 — Valider ou rejeter un profil 🔄

| Champ | Valeur |
|---|---|
| **Acteur** | Administrateur |
| **Déclencheur** | Profil en statut `EN_ATTENTE` |
| **Précondition** | Session admin, profil `EN_ATTENTE` |
| **Postcondition** | Profil `VALIDE` ou `REJETE` avec motif |

**✅ Verdict : VALIDE (corrigé v3)**

> **Correction C5 — UC7 concerne uniquement `validerProfil`**
>
> La validation du compte (passage `INACTIF → ACTIF`) est désormais **automatique via UC23**. UC7 est recentré sur la **validation manuelle du profil** par l'administrateur :
>
> - L'admin examine : biographie, compétences, documents (CV, photo, docs légaux).
> - L'admin valide → profil `VALIDE` → visible publiquement.
> - L'admin rejette → profil `REJETE` + motif → utilisateur notifié → peut corriger et re-soumettre.
>
> **Méthodes backend** : `validerProfil(profilId)` et `rejeterProfil(profilId, motif)`.

---

### UC8 — Gérer les comptes utilisateurs 🔄

| Champ | Valeur |
|---|---|
| **Acteur** | Administrateur |
| **Déclencheur** | Accès au tableau de bord |
| **Précondition** | Session admin |
| **Postcondition** | Compte `ACTIF`, `SUSPENDU` ou `SUPPRIME` |

**✅ Verdict : VALIDE (corrigé v3)**

> **Correction C6 — Nouveaux statuts de compte**
>
> Le statut `SUSPENDU` est ajouté pour permettre à l'admin de bloquer temporairement un utilisateur sans le supprimer définitivement. La suspension rend le profil invisible publiquement (cascade automatique).
>
> **Méthodes backend** : `activerCompte()`, `suspendreCompte()`, `supprimerCompte()`.

---

### UC9 — Consulter les statistiques

| Champ | Valeur |
|---|---|
| **Acteur** | Administrateur, AgentMunicipal |
| **Déclencheur** | Accès à la section statistiques |
| **Précondition** | Session active (rôle admin ou agent) |
| **Postcondition** | Statistiques affichées avec filtrage par période |

**✅ Verdict : VALIDE**

> - ✔ Contenu statistique fidèle au CDC.
> - ✔ Filtrage par période pertinent.
> - ✔ AgentMunicipal ajouté comme acteur (EB § A).
> - **(!)** Statistique de visites (CDC § B) à intégrer.

---

### UC10 — Exporter les données

| Champ | Valeur |
|---|---|
| **Acteur** | Administrateur |
| **Déclencheur** | Clic sur « Exporter » depuis les statistiques |
| **Précondition** | Statistiques filtrées disponibles (UC9 exécuté) |
| **Postcondition** | Rapport PDF ou Excel généré et téléchargeable |

**✅ Verdict : VALIDE**
> Export explicitement requis dans Mod. D. UC10 *inclut* UC9.

---

### UC11 — Gérer les catégories

| Champ | Valeur |
|---|---|
| **Acteur** | Administrateur |
| **Déclencheur** | Accès à la gestion des catégories |
| **Précondition** | Session admin |
| **Postcondition** | Catégorie créée, modifiée ou supprimée |

**✅ Verdict : VALIDE**

> - **(!)** Suppression d'une catégorie avec profils associés : règle d'intégrité référentielle à spécifier (interdiction ou reclassification).

---

### UC12 — S'abonner à la newsletter (Utilisateur enregistré)

| Champ | Valeur |
|---|---|
| **Acteur** | UtilisateurEnregistre |
| **Déclencheur** | Accès aux paramètres de communication |
| **Précondition** | Compte `ACTIF` |
| **Postcondition** | Abonnement enregistré dans la liste de diffusion |

**✅ Verdict : VALIDE**

---

### UC13 — Se déconnecter

| Champ | Valeur |
|---|---|
| **Acteur** | Tous les utilisateurs authentifiés |
| **Déclencheur** | Clic sur « Se déconnecter » |
| **Précondition** | Session active |
| **Postcondition** | Session invalidée, redirection vers l'accueil public |

**🟡 Verdict : PARTIELLEMENT VALIDE**
> Comportement implicitement attendu, inclus dans UC20 (*include*).

---

### UC14 — Gérer son mot de passe

| Champ | Valeur |
|---|---|
| **Acteur** | UtilisateurEnregistre / Administrateur |
| **Déclencheur** | Accès à la section Sécurité |
| **Précondition** | Session active |
| **Postcondition** | Nouveau MDP enregistré (haché), confirmation par email |

**✅ Verdict : VALIDE**

---

### UC15 — Parcourir l'annuaire *(le « catalogue »)* 🔄

| Champ | Valeur |
|---|---|
| **Acteur** | **Visiteur**, UtilisateurEnregistre |
| **Déclencheur** | Accès à l'annuaire structuré par catégories |
| **Précondition** | Au moins un profil `VALIDE` |
| **Postcondition** | Profils affichés par catégorie, filtrés et triés — **accès complet** |

**✅ Verdict : VALIDE (corrigé v3)**

> **Correction C1 — Suppression de la notion de « vue limitée »**
>
> Tout visiteur accède à l'annuaire complet. La distinction visiteur/connecté sur la vue est supprimée.

---

### UC16 — Consulter les actualités économiques

| Champ | Valeur |
|---|---|
| **Acteur** | Visiteur / Tout utilisateur authentifié |
| **Déclencheur** | Accès à la section Actualités |
| **Précondition** | Au moins une actualité publiée |
| **Postcondition** | Actualités publiques affichées par ordre de date |

**✅ Verdict : VALIDE**

---

### UC17 — Gérer les annonces économiques

| Champ | Valeur |
|---|---|
| **Acteur principal** | AgentMunicipal |
| **Acteur secondaire** | Administrateur |
| **Déclencheur** | Accès à la section de gestion des annonces |
| **Précondition** | Session agent municipal ou admin |
| **Postcondition** | Annonce ou actualité créée, modifiée, publiée ou supprimée |

**✅ Verdict : VALIDE**

---

### UC18 — Gérer la newsletter

| Champ | Valeur |
|---|---|
| **Acteur principal** | Administrateur |
| **Acteur secondaire** | Système (envois automatiques) |
| **Déclencheur** | Rédaction manuelle OU événement déclencheur automatique |
| **Précondition** | Session admin (manuel) / action système (auto) |
| **Postcondition** | Newsletter envoyée, envoi enregistré |

**✅ Verdict : VALIDE**

---

### UC19 — Journaliser les activités

| Champ | Valeur |
|---|---|
| **Acteur** | Système (auto) / Administrateur (consultation) |
| **Déclencheur** | Automatique sur chaque action significative |
| **Précondition** | Système opérationnel |
| **Postcondition** | Action enregistrée dans le journal d'audit |

**✅ Verdict : VALIDE**

---

### UC20 — Gérer son compte

| Champ | Valeur |
|---|---|
| **Acteur** | UtilisateurEnregistre / Administrateur |
| **Déclencheur** | Accès aux paramètres généraux du compte |
| **Précondition** | Session active |
| **Postcondition** | Paramètres mis à jour |

**🟡 Verdict : PARTIELLEMENT VALIDE**
> UC agrégateur incluant UC13 et UC14 (*include*).

---

### UC21 — Réinitialiser son mot de passe 🔵

| Champ | Valeur |
|---|---|
| **Acteur** | Visiteur (MDP oublié) |
| **Déclencheur** | Clic sur « Mot de passe oublié » |
| **Précondition** | Email d'un compte existant |
| **Postcondition** | Email de réinitialisation envoyé ; nouveau MDP défini |

**🔵 Verdict : NOUVEAU UC**

---

### UC22 — S'abonner à la newsletter (Visiteur) 🔵

| Champ | Valeur |
|---|---|
| **Acteur** | Visiteur (non authentifié) |
| **Déclencheur** | Popup à l'ouverture OU lien en pied de page |
| **Précondition** | Aucune — accès sans compte |
| **Postcondition** | Email enregistré ; confirmation + token de désabonnement envoyé |

**🔵 Verdict : NOUVEAU UC**

---

### UC23 — Confirmer son email 🔵 *[NOUVEAU v3]*

| Champ | Valeur |
|---|---|
| **Acteur principal** | Système (automatique) |
| **Acteur secondaire** | UtilisateurEnregistre (clic sur le lien) |
| **Déclencheur** | Fin de l'étape 1 de UC1 (création du compte) |
| **Précondition** | Compte en statut `INACTIF`, token de confirmation valide |
| **Postcondition** | Compte passe en statut `ACTIF` ; session ouverte automatiquement |

**🔵 Verdict : NOUVEAU UC**

> **Justification de l'ajout (C5/C8)**
>
> La séparation de `validerCompte` et `validerProfil` impose de formaliser la confirmation email comme UC distinct. C'est le mécanisme par lequel le compte passe de `INACTIF` à `ACTIF` **sans intervention humaine**.
>
> **Scénario principal** :
> 1. Fin de l'étape 1 de UC1 → le Système génère un token unique (durée : 24h) et envoie l'email.
> 2. L'utilisateur clique le lien dans l'email.
> 3. Le Système vérifie le token (existence, expiration).
> 4. Compte passe `INACTIF → ACTIF`.
> 5. Session ouverte automatiquement → redirection vers l'étape 2 du stepper.
>
> **Scénario alternatif — Token expiré** :
> 1. L'utilisateur clique un lien expiré.
> 2. Le Système propose de renvoyer un email de confirmation.
> 3. Nouveau token généré, ancien invalidé.
>
> **Scénario alternatif — Non confirmation après 7 jours** :
> 1. Tâche automatique planifiée → compte `INACTIF` supprimé.
> 2. L'utilisateur peut se réinscrire avec le même email.

---

---

*Document final v3.0 — Cas d'Utilisation — Plateforme de Gestion Communautaire*
