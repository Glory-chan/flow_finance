import 'package:firebase_auth/firebase_auth.dart';
import 'package:google_sign_in/google_sign_in.dart';

class AuthService {
  AuthService._();

  static final FirebaseAuth _auth = FirebaseAuth.instance;
  static final GoogleSignIn _googleSignIn = GoogleSignIn();

  static Stream<User?> get authStateChanges => _auth.authStateChanges();
  static User? get currentUser => _auth.currentUser;

  /// Retourne le prenom de l'utilisateur connecte.
  static String get currentUserFirstName {
    final displayName = _auth.currentUser?.displayName ?? '';
    if (displayName.isEmpty) return 'Utilisateur';
    return displayName.split(' ').first;
  }

  /// Retourne le nom complet de l'utilisateur connecte.
  static String get currentUserFullName {
    return _auth.currentUser?.displayName ?? 'Utilisateur';
  }

  /// Retourne l'email de l'utilisateur connecte.
  static String get currentUserEmail {
    return _auth.currentUser?.email ?? '';
  }

  /// Inscription avec email, mot de passe, prenom et nom.
  static Future<String?> registerWithEmail({
    required String email,
    required String password,
    required String firstName,
    required String lastName,
  }) async {
    try {
      final credential = await _auth.createUserWithEmailAndPassword(
        email: email,
        password: password,
      );

      // Sauvegarder le nom complet dans Firebase
      await credential.user?.updateDisplayName('$firstName $lastName');

      // Envoyer l'email de verification
      await credential.user?.sendEmailVerification();

      return null;
    } on FirebaseAuthException catch (e) {
      return _handleAuthError(e.code);
    } catch (e) {
      return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }

  /// Connexion avec email et mot de passe.
  static Future<String?> signInWithEmail({
    required String email,
    required String password,
  }) async {
    try {
      final credential = await _auth.signInWithEmailAndPassword(
        email: email,
        password: password,
      );

      // Verifier que l'email est confirme
      if (credential.user != null && !credential.user!.emailVerified) {
        await _auth.signOut();
        return 'Veuillez verifier votre email avant de vous connecter.';
      }

      return null;
    } on FirebaseAuthException catch (e) {
      return _handleAuthError(e.code);
    } catch (e) {
      return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }

  /// Verifie si l'email est confirme.
  static Future<bool> checkEmailVerified() async {
    await _auth.currentUser?.reload();
    return _auth.currentUser?.emailVerified ?? false;
  }

  /// Renvoie l'email de verification.
  static Future<String?> resendVerificationEmail() async {
    try {
      await _auth.currentUser?.sendEmailVerification();
      return null;
    } on FirebaseAuthException catch (e) {
      return _handleAuthError(e.code);
    } catch (e) {
      return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }

  /// Met a jour le nom complet de l'utilisateur.
  static Future<String?> updateDisplayName(String fullName) async {
    try {
      await _auth.currentUser?.updateDisplayName(fullName);
      await _auth.currentUser?.reload();
      return null;
    } on FirebaseAuthException catch (e) {
      return _handleAuthError(e.code);
    } catch (e) {
      return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }

  /// Met a jour le mot de passe de l'utilisateur.
  static Future<String?> updatePassword(String newPassword) async {
    try {
      await _auth.currentUser?.updatePassword(newPassword);
      return null;
    } on FirebaseAuthException catch (e) {
      return _handleAuthError(e.code);
    } catch (e) {
      return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }

  /// Connexion avec Google.
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

  /// Supprime le compte de l'utilisateur connecte.
  static Future<String?> deleteAccount() async {
    try {
      await _auth.currentUser?.delete();
      return null;
    } on FirebaseAuthException catch (e) {
      if (e.code == 'requires-recent-login') {
        return 'Veuillez vous reconnecter avant de supprimer votre compte.';
      }
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
      case 'requires-recent-login':
        return 'Veuillez vous reconnecter avant de continuer.';
      default:
        return 'Une erreur est survenue. Veuillez reessayer.';
    }
  }
}