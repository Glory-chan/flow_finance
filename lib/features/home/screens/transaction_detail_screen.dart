import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/formatters.dart';
import '../../../data/models/transaction_model.dart';
import '../../../data/repositories/firestore_repository.dart';

/// Ecran de detail d'une transaction.
/// Affiche toutes les informations et permet la suppression.
class TransactionDetailScreen extends StatelessWidget {
  const TransactionDetailScreen({
    super.key,
    required this.transaction,
  });

  final TransactionModel transaction;

  IconData get _categoryIcon {
    switch (transaction.category) {
      case TransactionCategory.food:
        return Icons.restaurant_outlined;
      case TransactionCategory.transport:
        return Icons.directions_car_outlined;
      case TransactionCategory.shopping:
        return Icons.shopping_bag_outlined;
      case TransactionCategory.energy:
        return Icons.bolt_outlined;
      case TransactionCategory.entertainment:
        return Icons.movie_outlined;
      case TransactionCategory.health:
        return Icons.local_hospital_outlined;
      case TransactionCategory.salary:
        return Icons.account_balance_outlined;
      case TransactionCategory.transfer:
        return Icons.swap_horiz_rounded;
      case TransactionCategory.other:
        return Icons.category_outlined;
    }
  }

  @override
  Widget build(BuildContext context) {
    final isIncome = transaction.isIncome;
    final color = isIncome ? AppColors.income : AppColors.expense;

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Detail de la transaction'),
        leading: IconButton(
          onPressed: () => context.pop(),
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
        ),
        actions: [
          IconButton(
            onPressed: () => _confirmDelete(context),
            icon: const Icon(
              Icons.delete_outline_rounded,
              color: AppColors.expense,
            ),
          ),
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(AppSpacing.pageHorizontal),
          child: Column(
            children: [
              const SizedBox(height: AppSpacing.lg),

              // Icone et montant
              Container(
                width: 80,
                height: 80,
                decoration: BoxDecoration(
                  color: color.withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: Icon(_categoryIcon, color: color, size: 40),
              ),

              const SizedBox(height: AppSpacing.lg),

              // Montant
              Text(
                AppFormatters.formatTransactionAmount(transaction.amount),
                style: TextStyle(
                  fontFamily: 'Poppins',
                  fontSize: 40,
                  fontWeight: FontWeight.w700,
                  color: color,
                  letterSpacing: -1,
                ),
              ),

              const SizedBox(height: AppSpacing.xs),

              // Titre
              Text(
                transaction.title,
                style: AppTextStyles.headlineMedium,
              ),

              const SizedBox(height: AppSpacing.xs),

              // Sous-titre
              Text(
                transaction.subtitle,
                style: AppTextStyles.bodyMedium.copyWith(
                  color: AppColors.textSecondary,
                ),
              ),

              const SizedBox(height: AppSpacing.xl),

              // Carte de details
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(AppSpacing.lg),
                decoration: BoxDecoration(
                  color: AppColors.backgroundSecondary,
                  borderRadius: AppRadius.lgRadius,
                  border: Border.all(color: AppColors.border),
                ),
                child: Column(
                  children: [
                    _DetailRow(
                      label: 'Type',
                      value: isIncome ? 'Revenu' : 'Depense',
                      valueColor: color,
                    ),
                    const Divider(color: AppColors.border, height: 24),
                    _DetailRow(
                      label: 'Categorie',
                      value: transaction.category.label,
                    ),
                    const Divider(color: AppColors.border, height: 24),
                    _DetailRow(
                      label: 'Date',
                      value: AppFormatters.formatDateLong(transaction.date),
                    ),
                    const Divider(color: AppColors.border, height: 24),
                    _DetailRow(
                      label: 'Heure',
                      value:
                          '${transaction.date.hour.toString().padLeft(2, '0')}:${transaction.date.minute.toString().padLeft(2, '0')}',
                    ),
                    const Divider(color: AppColors.border, height: 24),
                    _DetailRow(
                      label: 'Reference',
                      value: transaction.id.substring(0, 8).toUpperCase(),
                      valueColor: AppColors.textSecondary,
                    ),
                  ],
                ),
              ),

              const SizedBox(height: AppSpacing.xl),

              // Bouton supprimer
              SizedBox(
                width: double.infinity,
                child: OutlinedButton.icon(
                  onPressed: () => _confirmDelete(context),
                  icon: const Icon(Icons.delete_outline_rounded, size: 18),
                  label: const Text('Supprimer la transaction'),
                  style: OutlinedButton.styleFrom(
                    foregroundColor: AppColors.expense,
                    side: const BorderSide(color: AppColors.expense),
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: const RoundedRectangleBorder(
                      borderRadius: AppRadius.mdRadius,
                    ),
                  ),
                ),
              ),

              const SizedBox(height: AppSpacing.xxl),
            ],
          ),
        ),
      ),
    );
  }

  void _confirmDelete(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(
          borderRadius: AppRadius.lgRadius,
        ),
        title: Text(
          'Supprimer la transaction',
          style: AppTextStyles.headlineSmall,
        ),
        content: Text(
          'Voulez-vous vraiment supprimer cette transaction ?',
          style: AppTextStyles.bodyMedium.copyWith(
            color: AppColors.textSecondary,
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(),
            child: Text(
              'Annuler',
              style: AppTextStyles.labelLarge.copyWith(
                color: AppColors.textSecondary,
              ),
            ),
          ),
          ElevatedButton(
            onPressed: () async {
              Navigator.of(ctx).pop();
              await FirestoreRepository.deleteTransaction(
                transaction.id,
              );
              if (!context.mounted) return;
              context.pop();
            },
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
  }
}

/// Ligne de detail avec label a gauche et valeur a droite.
class _DetailRow extends StatelessWidget {
  const _DetailRow({
    required this.label,
    required this.value,
    this.valueColor,
  });

  final String label;
  final String value;
  final Color? valueColor;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: AppTextStyles.bodySmall,
        ),
        Text(
          value,
          style: AppTextStyles.labelLarge.copyWith(
            color: valueColor ?? AppColors.textPrimary,
          ),
        ),
      ],
    );
  }
}