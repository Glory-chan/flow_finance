# FlowFinance

Application mobile de gestion financière personnelle, développée dans le cadre du projet pédagogique ESTIAM 2025/2026.

## Aperçu

FlowFinance est une application mobile Flutter qui permet aux utilisateurs de :

- Consulter leur solde et leurs transactions en temps réel (synchronisées via Firestore)
- Rechercher instantanément une transaction depuis l'accueil
- Gérer leurs cartes bancaires de manière visuelle (ajout, suppression, modification du solde)
- Analyser leurs dépenses avec des graphiques interactifs
- S'inscrire et se connecter de manière sécurisée (email + vérification OTP, ou Google Sign-In)
- Discuter avec **FlowBot**, un assistant financier conversationnel propulsé par l'API Gemini
- Basculer entre thème clair et thème sombre

Aucun backend applicatif dédié : l'app communique directement avec Firebase (Authentification, Cloud Firestore) et avec l'API Gemini pour le chatbot — une architecture *Backend as a Service*.

## Captures d'écran

> À venir

## Technologies utilisées

| Technologie | Usage |
|-------------|-------|
| Flutter 3.x | Framework UI cross-platform |
| Dart 3.x | Langage de programmation |
| go_router | Navigation déclarative |
| flutter_riverpod | Gestion d'état (StateNotifierProvider, ConsumerWidget) sur tous les écrans |
| firebase_auth | Authentification (email/mot de passe, Google Sign-In) |
| cloud_firestore | Base de données temps réel (transactions, cartes, profil par utilisateur) |
| google_sign_in | Connexion via compte Google |
| http | Appels à l'API Gemini (chatbot FlowBot) |
| shared_preferences | Persistance locale (préférence de thème) |
| fl_chart | Graphiques financiers |
| google_fonts | Police Poppins |
| intl | Formatage FR (monnaie, dates) |
| equatable | Comparaison de modèles |

## Fonctionnalités

### Authentification
- Écran de bienvenue avec choix connexion / inscription
- Inscription en plusieurs étapes avec vérification OTP par email
- Connexion avec option "se souvenir de moi"
- Connexion via Google Sign-In (implémentée ; empreinte SHA-1 enregistrée sur Firebase, validation de bout en bout à confirmer)

### Tableau de bord (Accueil)
- Affichage du solde total en temps réel (Firestore)
- Barre de recherche instantanée sur les transactions
- Filtrage des transactions par période (Jour / Semaine / Mois / Année)
- Liste des transactions avec code couleur (vert = revenu, rouge = dépense)
- Icônes par catégorie de transaction

### Cartes bancaires
- Affichage visuel des cartes en pile (style portefeuille)
- Ajout et suppression de cartes
- Modification du solde et consultation des détails par carte
- Écran dédié "Comptes bancaires" avec solde total consolidé

### Analyses
- Graphique d'évolution du solde
- Graphique des dépenses par catégorie
- Résumé Revenus / Dépenses / Taux d'épargne

### FlowBot — assistant IA
- Bouton flottant dédié sur l'écran d'accueil
- Suggestions de questions rapides pour démarrer la conversation
- Historique de conversation envoyé à chaque échange, indicateur de frappe animé
- Réponses en français via l'API Gemini (`gemini-2.5-flash`), avec un prompt système dédié à la finance personnelle

### Paramètres
- Profil utilisateur (Firebase)
- Mode sombre (persisté localement)
- Gestion des comptes bancaires
- Export des données
- Sécurité et notifications : interfaces prêtes, logique de sauvegarde à finaliser
- Zone de danger (suppression de compte)

## Architecture

Application Flutter (Riverpod, go_router)
│
├── Firebase Authentification (email/mdp, Google Sign-In)
├── Cloud Firestore (données temps réel, par utilisateur : users/{uid}/...)
└── API Gemini (requête HTTP directe pour FlowBot)


## Installation

### Prérequis
- Flutter SDK 3.x
- Dart SDK 3.x
- Android Studio ou VS Code
- Un émulateur Android ou appareil physique
- Un projet Firebase (Authentification + Cloud Firestore activés)
- Une clé API Gemini ([Google AI Studio](https://aistudio.google.com/apikey))

### Lancer le projet

```bash
# Cloner le dépôt
git clone https://github.com/Glory-chan/flow_finance.git
cd flow_finance

# Installer les dépendances
flutter pub get

# Configurer Firebase (génère lib/firebase_options.dart, ignoré par git)
flutterfire configure

# Configurer la clé API Gemini
cp lib/core/config/api_keys.example.dart lib/core/config/api_keys.dart
# puis remplacer la valeur par ta vraie clé dans ce fichier (ignoré par git)

# Lancer sur émulateur
flutter run

# Lancer sur Chrome
flutter run -d chrome
```

## Prochaines étapes

- [x] Intégration Firebase Auth (connexion réelle)
- [x] Migration complète vers Riverpod
- [x] Intégration Firestore (remplacement des données mockées)
- [x] Mode sombre
- [x] Chatbot IA (FlowBot / API Gemini)
- [ ] Confirmer le bon fonctionnement de Google Sign-In en production
- [ ] Finaliser la persistance des écrans Sécurité et Notifications
- [ ] Déplacer la clé API Gemini vers un backend proxy sécurisé
- [ ] Tests unitaires et tests d'intégration
- [ ] Développement de la version web FlowFinance
- [ ] Publication Play Store

## Auteurs

Projet réalisé dans le cadre du projet pédagogique ESTIAM 2025/2026.

## Licence

Ce projet est à usage éducatif uniquement.