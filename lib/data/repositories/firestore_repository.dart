import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:firebase_auth/firebase_auth.dart';
import '../models/transaction_model.dart';
import '../models/card_model.dart';

/// Repository centralisant tous les acces Firestore.
/// Chaque utilisateur a ses donnees dans users/{uid}/...
class FirestoreRepository {
  FirestoreRepository._();

  static final FirebaseFirestore _db = FirebaseFirestore.instance;
  static final FirebaseAuth _auth = FirebaseAuth.instance;

  /// Retourne l'uid de l'utilisateur connecte.
  static String get _uid => _auth.currentUser!.uid;

  /// Reference vers le document utilisateur.
  static DocumentReference get _userDoc =>
      _db.collection('users').doc(_uid);

  /// Reference vers la collection transactions.
  static CollectionReference get _transactionsCol =>
      _userDoc.collection('transactions');

  /// Reference vers la collection cartes.
  static CollectionReference get _cardsCol =>
      _userDoc.collection('cards');

  // --- UTILISATEUR ---

  /// Cree le profil utilisateur dans Firestore apres inscription.
  static Future<void> createUserProfile({
    required String firstName,
    required String lastName,
    required String email,
  }) async {
    await _userDoc.set({
      'firstName': firstName,
      'lastName': lastName,
      'email': email,
      'totalBalance': 0.0,
      'createdAt': FieldValue.serverTimestamp(),
    });
  }

  /// Retourne les donnees du profil utilisateur.
  static Future<Map<String, dynamic>?> getUserProfile() async {
    final doc = await _userDoc.get();
    return doc.exists ? doc.data() as Map<String, dynamic> : null;
  }

  /// Met a jour le solde total de l'utilisateur.
  static Future<void> updateTotalBalance(double balance) async {
    await _userDoc.update({'totalBalance': balance});
  }

  // --- TRANSACTIONS ---

  /// Stream en temps reel des transactions de l'utilisateur.
  static Stream<List<TransactionModel>> transactionsStream() {
    return _transactionsCol
        .orderBy('date', descending: true)
        .snapshots()
        .map((snapshot) => snapshot.docs.map((doc) {
              final data = doc.data() as Map<String, dynamic>;
              data['id'] = doc.id;
              return TransactionModel.fromFirestore(data);
            }).toList());
  }

  /// Ajoute une nouvelle transaction.
  static Future<void> addTransaction(TransactionModel transaction) async {
    await _transactionsCol.add(transaction.toFirestore());
  }

  /// Supprime une transaction.
  static Future<void> deleteTransaction(String id) async {
    await _transactionsCol.doc(id).delete();
  }

  // --- CARTES ---

  /// Stream en temps reel des cartes de l'utilisateur.
  static Stream<List<CardModel>> cardsStream() {
    return _cardsCol
        .orderBy('createdAt', descending: false)
        .snapshots()
        .map((snapshot) => snapshot.docs.map((doc) {
              final data = doc.data() as Map<String, dynamic>;
              data['id'] = doc.id;
              return CardModel.fromFirestore(data);
            }).toList());
  }

  /// Ajoute une nouvelle carte.
  static Future<void> addCard(CardModel card) async {
    await _cardsCol.add(card.toFirestore());
  }

  /// Supprime une carte.
  static Future<void> deleteCard(String id) async {
    await _cardsCol.doc(id).delete();
  }

  /// Met a jour le solde d'une carte.
  static Future<void> updateCardBalance(String id, double balance) async {
    await _cardsCol.doc(id).update({'balance': balance});
  }
}