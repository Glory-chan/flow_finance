# FlowFinance

Application de gestion financiere personnelle developpee dans le cadre du projet pedagogique ESTIAM 2024/2025.

## Apercu

FlowFinance est une application mobile Flutter qui permet aux utilisateurs de :

- Consulter leur solde et leurs transactions en temps reel
- Gerer leurs cartes bancaires de maniere visuelle
- Analyser leurs depenses avec des graphiques interactifs
- S'inscrire et se connecter de maniere securisee (email + verification OTP)

## Captures d'ecran

> A venir

## Technologies utilisees

| Technologie | Usage |
|-------------|-------|
| Flutter 3.x | Framework UI cross-platform |
| Dart 3.x | Langage de programmation |
| go_router | Navigation declarative |
| flutter_riverpod | Gestion d'etat |
| fl_chart | Graphiques financiers |
| Google Fonts | Police Poppins |
| intl | Formatage FR (monnaie, dates) |
| equatable | Comparaison de modeles |

## Architecture du projet

lib/
├── main.dart                        # Point d'entree
├── app.dart                         # Widget racine
├── core/
│   ├── constants/
│   │   ├── app_colors.dart          # Palette de couleurs
│   │   ├── app_styles.dart          # Typographie et espacements
│   │   └── app_strings.dart         # Textes de l'application
│   ├── router/
│   │   └── app_router.dart          # Configuration navigation
│   ├── theme/
│   │   └── app_theme.dart           # Theme Material 3
│   └── utils/
│       ├── formatters.dart          # Formatage monnaie et dates
│       └── validators.dart          # Validation formulaires
├── data/
│   ├── models/
│   │   ├── transaction_model.dart   # Modele transaction
│   │   ├── card_model.dart          # Modele carte bancaire
│   │   └── user_model.dart          # Modele utilisateur
│   └── mock/
│       └── mock_data.dart           # Donnees de developpement
├── features/
│   ├── auth/
│   │   └── screens/
│   │       ├── welcome_screen.dart  # Ecran de bienvenue
│   │       ├── login_screen.dart    # Connexion
│   │       ├── register_screen.dart # Inscription
│   │       └── otp_screen.dart      # Verification email
│   ├── home/
│   │   ├── screens/
│   │   │   └── home_screen.dart     # Tableau de bord
│   │   └── widgets/
│   │       ├── period_tab_bar.dart  # Onglets de periode
│   │       └── transaction_item.dart# Item de transaction
│   ├── cards/
│   │   └── screens/
│   │       └── cards_screen.dart    # Gestion des cartes
│   ├── analytics/
│   │   └── screens/
│   │       └── analytics_screen.dart# Analyses et graphiques
│   └── settings/
│       └── screens/
│           └── settings_screen.dart # Parametres
└── shared/
└── widgets/
├── main_scaffold.dart       # Scaffold + navigation
├── app_button.dart          # Boutons reutilisables
└── app_text_field.dart      # Champ de saisie custom


## Fonctionnalites

### Authentification
- Ecran de bienvenue avec choix connexion / inscription
- Inscription en plusieurs etapes avec verification OTP par email
- Connexion avec option "se souvenir de moi"
- Connexion via Google (a venir)

### Tableau de bord
- Affichage du solde total
- Filtrage des transactions par periode (Jour / Semaine / Mois / Annee)
- Liste des transactions avec code couleur (vert = revenu, rouge = depense)
- Icones par categorie de transaction

### Cartes bancaires
- Affichage visuel des cartes en pile (style portefeuille)
- Detail du solde par carte
- Actions rapides (Virement, Releve, Bloquer)
- Ajout de nouvelles cartes (a venir)

### Analyses
- Graphique d'evolution du solde sur 6 mois
- Graphique des depenses par categorie
- Resume Revenus / Depenses / Taux d'epargne

### Parametres
- Carte de profil utilisateur
- Gestion du compte (profil, securite, notifications)
- Preferences et facturation
- Export des donnees
- Zone de danger (suppression de compte)

## Installation

### Prerequis
- Flutter SDK 3.x
- Dart SDK 3.x
- Android Studio ou VS Code
- Un emulateur Android ou appareil physique

### Lancer le projet

```bash
# Cloner le depot
git clone https://github.com/Glory-chan/flow_finance.git
cd flow_finance

# Installer les dependances
flutter pub get

# Lancer sur emulateur
flutter run

# Lancer sur Chrome
flutter run -d chrome
```

## Prochaines etapes

- [ ] Integration Firebase Auth (connexion reelle)
- [ ] Connexion Google Sign-In
- [ ] API bancaire (Budget Insight / Plaid)
- [ ] Notifications push
- [ ] Mode sombre
- [ ] Tests unitaires et tests d'integration
- [ ] Publication Play Store

## Auteurs

Projet realise dans le cadre du projet pedagogique ESTIAM 2024/2025.

## Licence

Ce projet est a usage educatif uniquement.