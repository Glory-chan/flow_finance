import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/models/card_model.dart';
import '../data/repositories/firestore_repository.dart';
import 'auth_provider.dart';

/// Provider du stream des cartes en temps reel depuis Firestore.
final cardsProvider = StreamProvider<List<CardModel>>((ref) {
  final user = ref.watch(currentUserProvider);

  if (user == null) {
    return const Stream.empty();
  }

  return FirestoreRepository.cardsStream();
});

/// Provider de la carte selectionnee (index).
final selectedCardIndexProvider = StateProvider<int>((ref) => 0);

/// Provider de la carte actuellement selectionnee.
final selectedCardProvider = Provider<CardModel?>((ref) {
  final cards = ref.watch(cardsProvider).asData?.value ?? [];
  final index = ref.watch(selectedCardIndexProvider);

  if (cards.isEmpty || index >= cards.length) return null;
  return cards[index];
});

/// Provider du solde total de toutes les cartes.
final totalBalanceProvider = Provider<double>((ref) {
  final cards = ref.watch(cardsProvider).asData?.value ?? [];
  return cards.fold(0.0, (sum, card) => sum + card.balance);
});