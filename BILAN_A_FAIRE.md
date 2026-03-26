# Bilan — Ce qu'il reste à faire
**Plateforme de Gestion Communautaire | Priorités au 2026-03-26**


---

## 🟡 PRIORITÉ 3 — Amélioration significative

### UC5 — Filtres de recherche complets
- Ajouter les filtres manquants sur la page annuaire/recherche :
  - Filtre par **niveau d'étude** (`niveau_etude`)
  - Filtre par **localisation**
- Améliorer la recherche full-text (bio, compétences, expériences)

---

### UC9 — Statistiques avancées
- Ajouter le filtrage par période (semaine, mois, année, personnalisé) sur le dashboard
- Ajouter les statistiques de visites de pages (compteur de vues sur les profils)
- Ajouter les graphiques d'évolution (inscriptions, profils validés dans le temps)

---

### UC17 — Rôle AgentMunicipal
- Créer le rôle `agent` (entre opérateur et admin)
- L'agent peut créer, modifier, publier des annonces et des actualités
- L'agent n'a pas accès aux fonctions d'administration (comptes, catégories, exports)
- Middleware `role:agent` + interface dédiée

---

### UC20 — Email de confirmation après changement d'email
- Quand l'utilisateur change son email → envoyer un lien de confirmation au nouvel email
- L'ancien email reste actif jusqu'à confirmation

---

### UC1 — Stepper 3 étapes (inscription complète)
- Interface guidée en 3 étapes pour l'inscription
- Statut `BROUILLON` pour sauvegarder un profil partiellement rempli
- Le flux actuel (1 formulaire + vérification email) couvre déjà le besoin fonctionnel

---
*Priorités établies selon l'impact fonctionnel et les dépendances entre UC*
