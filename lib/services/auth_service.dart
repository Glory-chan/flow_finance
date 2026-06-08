import 'package:firebase_auth/firebase_auth.dart';
import 'package:google_sign_in/google_sign_in.dart';

/// Service gérant toutes les operations d'authentification Firebase.
class AuthService {
  AuthService._();

  static final FirebaseAuth _auth = FirebaseAuth.instance;
  static final GoogleSignIn _googleSignIn = GoogleSignIn();

  /// Stream de l'etat de connexion de l'utilisateur.
  static Stream<User?> get authStateChanges => _auth.authStateChanges();

  /// Utilisateur actuellement connecte (null si deconnecte).
  static User? get currentUser => _auth.currentUser;

  /// Inscription avec email et mot de passe.
  /// Retourne null si succes, un message d'erreur sinon.
  static Future<String?> registerWithEmail({
    required String email,
    required String password,
  }) async {
    try {
      await _auth.createUserWithEmailAndPassword(
        email: email,
        password: password,
      );
      // Envoyer l'email de verification
      await _auth.currentUser?.sendEmailVerification();
      return null;
    } on FirebaseAuthException catch (e) {
      return _handleAuthError(e.code);
    } catch (e) {
      return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }

  /// Connexion avec email et mot de passe.
  /// Retourne null si succes, un message d'erreur sinon.
  static Future<String?> signInWithEmail({
    required String email,
    required String password,
  }) async {
    try {
      await _auth.signInWithEmailAndPassword(
        email: email,
        password: password,
      );
      return null;
    } on FirebaseAuthException catch (e) {
      return _handleAuthError(e.code);
    } catch (e) {
      return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }

  /// Connexion avec Google.
  /// Retourne null si succes, un message d'erreur sinon.
  static Future<String?> signInWithGoogle() async {
    try {
      final GoogleSignInAccount? googleUser = await _googleSignIn.signIn();
      if (googleUser == null) return 'Connexion annulee.';

      final GoogleSignInAuthentication googleAuth =
          await googleUser.authentication;

      final credential = GoogleAuthProvider.credential(
        accessToken: googleAuth.accessToken,
        idToken: googleAuth.idToken,
      );

      await _auth.signInWithCredential(credential);
      return null;
    } on FirebaseAuthException catch (e) {
      return _handleAuthError(e.code);
    } catch (e) {
      return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }

  /// Envoi d'un email de reinitialisation du mot de passe.
  static Future<String?> sendPasswordResetEmail(String email) async {
    try {
      await _auth.sendPasswordResetEmail(email: email);
      return null;
    } on FirebaseAuthException catch (e) {
      return _handleAuthError(e.code);
    } catch (e) {
      return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }

  /// Deconnexion de l'utilisateur.
  static Future<void> signOut() async {
    await _googleSignIn.signOut();
    await _auth.signOut();
  }

  /// Traduit les codes d'erreur Firebase en messages lisibles.
  static String _handleAuthError(String code) {
    switch (code) {
      case 'email-already-in-use':
        return 'Cet email est deja utilise.';
      case 'invalid-email':
        return 'L\'email n\'est pas valide.';
      case 'weak-password':
        return 'Le mot de passe est trop faible.';
      case 'user-not-found':
        return 'Aucun compte trouve avec cet email.';
      case 'wrong-password':
        return 'Mot de passe incorrect.';
      case 'too-many-requests':
        return 'Trop de tentatives. Reessayez plus tard.';
      case 'network-request-failed':
        return 'Erreur reseau. Verifiez votre connexion.';
      default:
        return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }
}