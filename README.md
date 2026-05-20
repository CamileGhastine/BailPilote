# bailPilote

> Application web de gestion locative — version 1.0  
> Livraison prévue : 3 juillet 2026

---

## Présentation

**BailPilote** (anciennement *Simplex-immo*) est une application de gestion immobilière locative **gratuite**, conçue pour les propriétaires-bailleurs qui souhaitent gérer leurs biens sans passer par une agence.

Elle automatise les tâches récurrentes (quittances de loyer, réévaluation annuelle, déclaration d'impôts, génération de contrats…) et centralise la communication entre bailleur et locataire.

---

## Stack technique

| Composant | Technologie | Version |
|---|---|---|
| Backend | PHP | 8.4 |
| Framework | Symfony | 7.4 (LTS) |
| Base de données | MySQL | — |
| Serveur web | Nginx | — |
| Conteneurisation | Docker | — |

---

## Prérequis

- [Docker](https://www.docker.com/) et [Docker Compose](https://docs.docker.com/compose/) installés
- Git

---

## Installation

### 1. Cloner le dépôt

```bash
git clone  https://github.com/CamileGhastine/BailPilote.git
cd bailpilote
```

### 2. Corriger les permissions (indispensable)

Le montage de volume Docker écrase les permissions du dossier hôte. À exécuter une seule fois après le clonage :

```bash
sudo chown -R $(id -u):$(id -g) ./app
```

### 3. Démarrer les conteneurs

```bash
docker compose up -d
```

L'application est ensuite accessible sur [http://localhost](http://localhost).

---

## Rebuild de l'image PHP

À effectuer après toute modification du `Dockerfile` ou des dépendances :

```bash
docker compose down
docker compose build php
docker compose up -d
```

---

## Architecture de l'application

L'application est organisée en **4 lots** :

### Lot 1 — Vitrine
- Page d'accueil publique (actualités immobilières, présentation des services)
- Espace éditorial (articles, tutoriels, F.A.Q.)
- Enregistrement et connexion (confirmation par mail, token 24h)
- Forum communautaire *(optionnel)*

### Lot 2 — Espace bailleur
- Tableau de bord avec vue sur tous les biens
- Gestion des biens immobiliers (création, modification, photos, DPE/GES…)
- Gestion des locataires (ajout, invitation par mail, IRL affiché)
- Génération automatique de documents (quittances, contrats de bail, états des lieux)
- Messagerie bailleur ↔ locataire
- Calendrier et notifications d'actions (réévaluation de loyer, rappels…)
- Espace documents (téléversement / téléchargement)
- Question juridique intégrée

### Lot 3 — Espace locataire
- Vue lecture seule des informations du bien
- Messagerie locataire ↔ bailleur
- Téléchargement des quittances et documents
- Calendrier (échéances loyer, anniversaire bail…)
- Notifications d'actions

### Lot 4 — Back-office administration
- Espace rédactionnel (gestion articles, actualités, F.A.Q.) — accessible aux éditorialistes
- Gestion des rôles (admin, éditorialiste, juriste)

---

## Rôles utilisateurs

| Rôle | Accès |
|---|---|
| **Promeneur** | Contenu éditorial public, forum (anonyme) |
| **Bailleur** | Espace bailleur complet après inscription |
| **Locataire** | Espace locataire (invité par le bailleur) |
| **Rédacteur** | Création de contenu éditorial (back-office) |
| **Admin** | Gestion complète de l'application et des rôles |

---

## Services tiers (API)

- **INSEE** — Indice de référence des loyers (IRL)
- **impots.gouv.fr** — Vérification des avis d'imposition
- Référentiel des zones tendues
- Référentiel des communes en encadrement des loyers

---

## Sécurité

- Mots de passe hashés, protection brute-force
- Protection XSS, CSRF, injection SQL
- HTTPS via certificat **Let's Encrypt**
- Conformité **RGPD** (consentement cookies, droits d'accès/suppression, déclaration CNIL)

---

## Compatibilité

**Navigateurs** : Chrome 70+, Firefox 60+, Safari 14+, Opera 51+, Edge 90+  
**Appareils** : Mobile, tablette, ordinateur portable et bureau (responsive, bonnes pratiques W3C)

---

## Évolutions prévues

| Proposition | Priorité |
|---|---|
| Forum communautaire | Très fortement conseillé |
| Messagerie temps réel (protocole Mercure) | Non indispensable |
| Architecture Symfony API + React (mobile-ready) | Fortement conseillé |

---

## Livrables

- ✅ Application web fonctionnelle
- ✅ Code source (propriété du client)
- ✅ Documentation technique

---

*BailPilote © 2026*
