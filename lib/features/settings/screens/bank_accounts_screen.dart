import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/formatters.dart';
import '../../../data/repositories/firestore_repository.dart';
import '../../../providers/cards_provider.dart';
import '../../../data/models/card_model.dart';

/// Ecran de gestion des comptes bancaires.
/// Affiche la liste des cartes avec leurs soldes
/// et permet d'en ajouter ou supprimer.
class BankAccountsScreen extends ConsumerWidget {
  const BankAccountsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final cardsAsync = ref.watch(cardsProvider);
    final totalBalance = ref.watch(totalBalanceProvider);

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Comptes bancaires'),
        leading: IconButton(
          onPressed: () => context.pop(),
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
        ),
      ),
      body: cardsAsync.when(
        loading: () => const Center(
          child: CircularProgressIndicator(color: AppColors.primary),
        ),
        error: (error, _) => Center(
          child: Text(
            'Erreur de chargement',
            style: AppTextStyles.bodyMedium.copyWith(
              color: AppColors.expense,
            ),
          ),
        ),
        data: (cards) => SafeArea(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(AppSpacing.pageHorizontal),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: AppSpacing.md),

                // Carte de resume du solde total
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(AppSpacing.lg),
                  decoration: BoxDecoration(
                    gradient: AppColors.primaryGradient,
                    borderRadius: AppRadius.lgRadius,
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Solde total consolide',
                        style: AppTextStyles.bodySmall.copyWith(
                          color: Colors.white.withOpacity(0.8),
                        ),
                      ),
                      const SizedBox(height: AppSpacing.xs),
                      Text(
                        AppFormatters.formatCurrency(totalBalance),
                        style: AppTextStyles.balanceDisplay.copyWith(
                          color: Colors.white,
                        ),
                      ),
                      const SizedBox(height: AppSpacing.xs),
                      Text(
                        '${cards.length} compte${cards.length > 1 ? 's' : ''} enregistre${cards.length > 1 ? 's' : ''}',
                        style: AppTextStyles.bodySmall.copyWith(
                          color: Colors.white.withOpacity(0.7),
                        ),
                      ),
                    ],
                  ),
                ),

                const SizedBox(height: AppSpacing.xl),

                // Liste des comptes
                if (cards.isEmpty)
                  Center(
                    child: Padding(
                      padding: const EdgeInsets.all(AppSpacing.xl),
                      child: Column(
                        children: [
                          const Icon(
                            Icons.account_balance_outlined,
                            size: 64,
                            color: AppColors.textHint,
                          ),
                          const SizedBox(height: AppSpacing.md),
                          Text(
                            'Aucun compte enregistre',
                            style: AppTextStyles.headlineSmall.copyWith(
                              color: AppColors.textSecondary,
                            ),
                          ),
                          const SizedBox(height: AppSpacing.sm),
                          Text(
                            'Ajoutez vos cartes depuis\nla page "Mes cartes"',
                            style: AppTextStyles.bodySmall,
                            textAlign: TextAlign.center,
                          ),
                        ],
                      ),
                    ),
                  )
                else ...[
                  Text(
                    'MES COMPTES',
                    style: AppTextStyles.labelSmall.copyWith(
                      color: AppColors.textSecondary,
                      letterSpacing: 1,
                    ),
                  ),
                  const SizedBox(height: AppSpacing.sm),
                  Container(
                    decoration: BoxDecoration(
                      color: AppColors.backgroundCard,
                      borderRadius: AppRadius.lgRadius,
                      border: Border.all(color: AppColors.border),
                    ),
                    child: Column(
                      children: cards.asMap().entries.map((entry) {
                        final index = entry.key;
                        final card = entry.value;
                        final isLast = index == cards.length - 1;
                        return Column(
                          children: [
                            _BankAccountItem(
                              card: card,
                              onDelete: () async {
                                await _confirmDelete(
                                  context,
                                  card,
                                );
                              },
                            ),
                            if (!isLast)
                              const Padding(
                                padding: EdgeInsets.only(
                                  left: AppSpacing.pageHorizontal,
                                ),
                                child: Divider(height: 1),
                              ),
                          ],
                        );
                      }).toList(),
                    ),
                  ),
                ],

                const SizedBox(height: AppSpacing.xxl),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Future<void> _confirmDelete(
    BuildContext context,
    CardModel card,
  ) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(
          borderRadius: AppRadius.lgRadius,
        ),
        title: Text(
          'Supprimer le compte',
          style: AppTextStyles.headlineSmall,
        ),
        content: Text(
          'Voulez-vous supprimer le compte ${card.bankName} ?',
          style: AppTextStyles.bodyMedium,
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(false),
            child: Text(
              'Annuler',
              style: AppTextStyles.labelLarge.copyWith(
                color: AppColors.textSecondary,
              ),
            ),
          ),
          ElevatedButton(
            onPressed: () => Navigator.of(ctx).pop(true),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.expense,
              foregroundColor: Colors.white,
              elevation: 0,
              shape: const RoundedRectangleBorder(
                borderRadius: AppRadius.mdRadius,
              ),
            ),
            child: const Text('Supprimer'),
          ),
        ],
      ),
    );

    if (confirmed == true && context.mounted) {
      await FirestoreRepository.deleteCard(card.id);
    }
  }
}

/// Un item de compte bancaire dans la liste.
class _BankAccountItem extends StatelessWidget {
  const _BankAccountItem({
    required this.card,
    required this.onDelete,
  });

  final CardModel card;
  final VoidCallback onDelete;

  @override
  Widget build(BuildContext context) {
    return ListTile(
      contentPadding: const EdgeInsets.symmetric(
        horizontal: AppSpacing.md,
        vertical: AppSpacing.sm,
      ),
      leading: Container(
        width: 44,
        height: 44,
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: [Color(card.colorStart), Color(card.colorEnd)],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          borderRadius: AppRadius.mdRadius,
        ),
        child: const Icon(
          Icons.credit_card_rounded,
          color: Colors.white,
          size: 22,
        ),
      ),
      title: Text(
        card.bankName,
        style: AppTextStyles.bodyMedium.copyWith(
          fontWeight: FontWeight.w600,
        ),
      ),
      subtitle: Text(
        '${card.cardType.label} •••• ${card.lastFourDigits}',
        style: AppTextStyles.bodySmall,
      ),
      trailing: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(
                AppFormatters.formatCurrency(card.balance),
                style: AppTextStyles.labelLarge.copyWith(
                  color: AppColors.primary,
                ),
              ),
              Text(
                'Expire ${card.formattedExpiry}',
                style: AppTextStyles.labelSmall,
              ),
            ],
          ),
          const SizedBox(width: AppSpacing.sm),
          IconButton(
            onPressed: onDelete,
            icon: const Icon(
              Icons.delete_outline_rounded,
              color: AppColors.expense,
              size: 20,
            ),
          ),
        ],
      ),
    );
  }
}