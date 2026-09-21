import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/repositories/firestore_repository.dart';
import 'auth_provider.dart';

/// Provider des donnees du profil utilisateur depuis Firestore.
final userProfileProvider =
    FutureProvider<Map<String, dynamic>?>((ref) async {
  final user = ref.watch(currentUserProvider);

  if (user == null) return null;

  return FirestoreRepository.getUserProfile();
});

/// Provider du prenom de l'utilisateur.
final userFirstNameProvider = Provider<String>((ref) {
  final profile = ref.watch(userProfileProvider).asData?.value;

  // Priorite : Firestore -> Firebase Auth -> defaut
  if (profile != null && profile['firstName'] != null) {
    return profile['firstName'] as String;
  }

  final displayName =
      ref.watch(currentUserProvider)?.displayName ?? '';
  if (displayName.isNotEmpty) {
    return displayName.split(' ').first;
  }

  return 'Utilisateur';
});

/// Provider du nom complet de l'utilisateur.
final userFullNameProvider = Provider<String>((ref) {
  final profile = ref.watch(userProfileProvider).asData?.value;

  if (profile != null) {
    final firstName = profile['firstName'] as String? ?? '';
    final lastName = profile['lastName'] as String? ?? '';
    if (firstName.isNotEmpty) return '$firstName $lastName'.trim();
  }

  return ref.watch(currentUserProvider)?.displayName ?? 'Utilisateur';
});

/// Provider de l'email de l'utilisateur.
final userEmailProvider = Provider<String>((ref) {
  return ref.watch(currentUserProvider)?.email ?? '';
});