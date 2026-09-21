// Modele de fichier de configuration des cles API.
//
// Ce fichier est versionne (commit sur GitHub) et sert de reference.
// Il ne doit JAMAIS contenir de vraie cle.
//
// Marche a suivre :
// 1. Copier ce fichier sous le nom "api_keys.dart" dans le meme dossier.
// 2. Remplacer la valeur ci-dessous par ta vraie cle Gemini.
// 3. "api_keys.dart" est deja ignore par git (voir .gitignore), donc
//    ta cle ne sera jamais poussee sur GitHub.

class ApiKeys {
  ApiKeys._();

  /// Cle API pour l'API Gemini (Google AI Studio).
  /// Obtenue sur https://aistudio.google.com/apikey
  static const String geminiApiKey = 'REMPLACE_PAR_TA_CLE_GEMINI';
}