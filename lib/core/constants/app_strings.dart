class AppStrings {
  AppStrings._();

  static const String appName = 'FlowFinance';
  static const String appTagline = 'Reprenez le controle de vos finances';

  // Bienvenue
  static const String welcomeSignIn = 'Connexion';
  static const String welcomeSignUp = 'Inscription';

  // Connexion
  static const String loginEmail = 'Adresse email';
  static const String loginEmailHint = 'exemple@email.com';
  static const String loginPassword = 'Mot de passe';
  static const String loginPasswordHint = 'Votre mot de passe';
  static const String loginRememberMe = 'Se souvenir de moi';
  static const String loginForgotPassword = 'Mot de passe oublie ?';
  static const String loginButton = 'Se connecter';
  static const String loginWithGoogle = 'Continuer avec Google';
  static const String loginNoAccount = 'Pas encore de compte ?';
  static const String loginSignUp = 'S\'inscrire';
  static const String loginOr = 'ou';

  // Inscription
  static const String registerEmail = 'Adresse email';
  static const String registerEmailHint = 'exemple@email.com';
  static const String registerPassword = 'Mot de passe';
  static const String registerPasswordHint = 'Minimum 8 caracteres';
  static const String registerConfirmPassword = 'Confirmer le mot de passe';
  static const String registerConfirmPasswordHint = 'Repetez votre mot de passe';
  static const String registerButton = 'Inscription';
  static const String registerWithGoogle = 'Continuer avec Google';
  static const String registerHasAccount = 'Deja un compte ?';
  static const String registerSignIn = 'Se connecter';
  static const String registerOr = 'ou';

  // OTP
  static const String otpTitle = 'Verification';
  static const String otpSubtitle = 'Entrez le code a 6 chiffres envoye a';
  static const String otpResend = 'Renvoyer le code';
  static const String otpResendIn = 'Renvoyer dans';
  static const String otpVerifyButton = 'Verifier';
  static const String otpSeconds = 's';

  // Accueil
  static const String homeGreeting = 'Bonjour !';
  static const String homeBalance = 'Solde actuel';
  static const String homeTransactions = 'Transactions recentes';
  static const String homePeriodDay = 'Jour';
  static const String homePeriodWeek = 'Semaine';
  static const String homePeriodMonth = 'Mois';
  static const String homePeriodYear = 'Annee';
  static const String homeSeeAll = 'Voir tout';

  // Cartes
  static const String cardsTitle = 'Mes cartes';
  static const String cardsAddCard = 'Ajouter une carte';

  // Analyses
  static const String analyticsTitle = 'Analyses';
  static const String analyticsIncome = 'Revenus';
  static const String analyticsExpenses = 'Depenses';
  static const String analyticsSavingsRate = 'Taux d\'epargne';

  // Parametres
  static const String settingsTitle = 'Parametres';
  static const String settingsAccount = 'COMPTE';
  static const String settingsProfile = 'Profil';
  static const String settingsSecurity = 'Securite';
  static const String settingsNotifications = 'Notifications';
  static const String settingsBankAccounts = 'Comptes bancaires';
  static const String settingsPreferences = 'PREFERENCES';
  static const String settingsGeneral = 'General';
  static const String settingsBilling = 'Facturation';
  static const String settingsData = 'DONNEES';
  static const String settingsExport = 'Exporter mes donnees';
  static const String settingsLogout = 'Se deconnecter';
  static const String settingsDanger = 'ZONE DE DANGER';
  static const String settingsDeleteAccount = 'Supprimer mon compte';

  // Navigation
  static const String navHome = 'Accueil';
  static const String navAnalytics = 'Analyses';
  static const String navCards = 'Cartes';
  static const String navSettings = 'Reglages';

  // Erreurs
  static const String errorEmailRequired = 'L\'email est obligatoire';
  static const String errorEmailInvalid = 'L\'email n\'est pas valide';
  static const String errorPasswordRequired = 'Le mot de passe est obligatoire';
  static const String errorPasswordTooShort = 'Minimum 8 caracteres';
  static const String errorPasswordMismatch = 'Les mots de passe ne correspondent pas';
}