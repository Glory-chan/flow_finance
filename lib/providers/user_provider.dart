import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/repositories/firestore_repository.dart';
import '../services/auth_service.dart';
import 'auth_provider.dart';

/// Provider des donnees du profil utilisateur depuis Firestore.
final userProfileProvider =
    FutureProvider<Map<String, dynamic>?>((ref) async {
  final user = ref.watch(currentUserProvider);
  if (user == null) return null;
  return FirestoreRepository.getUserProfile();
});

/// Provider du prenom de l'utilisateur.
/// Priorite : Firebase Auth displayName -> Firestore -> defaut
final userFirstNameProvider = Provider<String>((ref) {
  // 1. Essayer Firebase Auth en premier (instantane)
  final displayName = AuthService.currentUser?.displayName ?? '';
  if (displayName.isNotEmpty) {
    return displayName.split(' ').first;
  }

  // 2. Essayer Firestore ensuite
  final profile = ref.watch(userProfileProvider).asData?.value;
  if (profile != null && profile['firstName'] != null) {
    return profile['firstName'] as String;
  }

  return 'Utilisateur';
});

/// Provider du nom complet de l'utilisateur.
final userFullNameProvider = Provider<String>((ref) {
  // 1. Firebase Auth en premier
  final displayName = AuthService.currentUser?.displayName ?? '';
  if (displayName.isNotEmpty) return displayName;

  // 2. Firestore ensuite
  final profile = ref.watch(userProfileProvider).asData?.value;
  if (profile != null) {
    final firstName = profile['firstName'] as String? ?? '';
    final lastName = profile['lastName'] as String? ?? '';
    if (firstName.isNotEmpty) return '$firstName $lastName'.trim();
  }

  return 'Utilisateur';
});

/// Provider de l'email de l'utilisateur.
final userEmailProvider = Provider<String>((ref) {
  return AuthService.currentUser?.email ?? '';
});