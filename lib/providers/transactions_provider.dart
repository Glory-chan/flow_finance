import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/models/transaction_model.dart';
import '../data/repositories/firestore_repository.dart';
import 'auth_provider.dart';

/// Provider du stream des transactions en temps reel depuis Firestore.
/// Se reinitialise automatiquement quand l'utilisateur change.
final transactionsProvider =
    StreamProvider<List<TransactionModel>>((ref) {
  // Ecoute l'utilisateur connecte
  final user = ref.watch(currentUserProvider);

  // Si pas connecte retourne un stream vide
  if (user == null) {
    return const Stream.empty();
  }

  return FirestoreRepository.transactionsStream();
});

/// Provider des transactions filtrees par periode.
final filteredTransactionsProvider =
    Provider.family<List<TransactionModel>, int>((ref, periodIndex) {
  final transactions =
      ref.watch(transactionsProvider).asData?.value ?? [];
  final now = DateTime.now();

  switch (periodIndex) {
    case 0: // Jour
      return transactions
          .where((t) =>
              t.date.year == now.year &&
              t.date.month == now.month &&
              t.date.day == now.day)
          .toList();
    case 1: // Semaine
      final weekAgo = now.subtract(const Duration(days: 7));
      return transactions.where((t) => t.date.isAfter(weekAgo)).toList();
    case 2: // Mois
      return transactions
          .where((t) =>
              t.date.year == now.year && t.date.month == now.month)
          .toList();
    case 3: // Annee
      return transactions.where((t) => t.date.year == now.year).toList();
    default:
      return transactions;
  }
});

/// Provider du total des depenses du mois.
final totalExpensesProvider = Provider<double>((ref) {
  final transactions =
      ref.watch(filteredTransactionsProvider(2));
  return transactions
      .where((t) => t.isExpense)
      .fold(0.0, (sum, t) => sum + t.amount.abs());
});

/// Provider du total des revenus du mois.
final totalIncomeProvider = Provider<double>((ref) {
  final transactions =
      ref.watch(filteredTransactionsProvider(2));
  return transactions
      .where((t) => t.isIncome)
      .fold(0.0, (sum, t) => sum + t.amount);
});