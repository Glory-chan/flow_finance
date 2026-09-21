import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:fl_chart/fl_chart.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/formatters.dart';
import '../../../data/models/transaction_model.dart';
import '../../../providers/transactions_provider.dart';
import '../../../providers/cards_provider.dart';

class AnalyticsScreen extends ConsumerWidget {
  const AnalyticsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final transactionsAsync = ref.watch(transactionsProvider);
    final totalIncome = ref.watch(totalIncomeProvider);
    final totalExpenses = ref.watch(totalExpensesProvider);
    final totalBalance = ref.watch(totalBalanceProvider);

    final savingsRate = totalIncome > 0
        ? (totalIncome - totalExpenses) / totalIncome
        : 0.0;

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text(
          AppStrings.analyticsTitle,
          style: AppTextStyles.headlineMedium,
        ),
      ),
      body: transactionsAsync.when(
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
        data: (transactions) {
          // Calcul des depenses par categorie
          final expensesByCategory = <TransactionCategory, double>{};
          for (final t in transactions.where((t) => t.isExpense)) {
            expensesByCategory[t.category] =
                (expensesByCategory[t.category] ?? 0) + t.amount.abs();
          }

          return SafeArea(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(
                horizontal: AppSpacing.pageHorizontal,
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const SizedBox(height: AppSpacing.md),

                  // Cartes de resume
                  _SummaryCards(
                    totalIncome: totalIncome,
                    totalExpenses: totalExpenses,
                    savingsRate: savingsRate,
                  ),

                  const SizedBox(height: AppSpacing.xl),

                  // Solde total
                  _BalanceCard(balance: totalBalance),

                  const SizedBox(height: AppSpacing.xl),

                  // Graphique depenses par categorie
                  if (expensesByCategory.isNotEmpty) ...[
                    Text(
                      'Depenses par categorie',
                      style: AppTextStyles.headlineSmall,
                    ),
                    const SizedBox(height: AppSpacing.md),
                    _ExpensesBarChart(
                      expensesByCategory: expensesByCategory,
                    ),
                    const SizedBox(height: AppSpacing.xl),
                    _CategoryLegend(
                      expensesByCategory: expensesByCategory,
                    ),
                  ] else ...[
                    _EmptyAnalytics(),
                  ],

                  const SizedBox(height: AppSpacing.xl),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

class _SummaryCards extends StatelessWidget {
  const _SummaryCards({
    required this.totalIncome,
    required this.totalExpenses,
    required this.savingsRate,
  });

  final double totalIncome;
  final double totalExpenses;
  final double savingsRate;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
          child: _StatCard(
            label: AppStrings.analyticsIncome,
            value: AppFormatters.formatCurrency(totalIncome),
            color: AppColors.income,
            icon: Icons.arrow_upward_rounded,
          ),
        ),
        const SizedBox(width: AppSpacing.sm),
        Expanded(
          child: _StatCard(
            label: AppStrings.analyticsExpenses,
            value: AppFormatters.formatCurrency(totalExpenses),
            color: AppColors.expense,
            icon: Icons.arrow_downward_rounded,
          ),
        ),
        const SizedBox(width: AppSpacing.sm),
        Expanded(
          child: _StatCard(
            label: AppStrings.analyticsSavingsRate,
            value: AppFormatters.formatPercentage(savingsRate),
            color: AppColors.primary,
            icon: Icons.savings_outlined,
          ),
        ),
      ],
    );
  }
}

class _StatCard extends StatelessWidget {
  const _StatCard({
    required this.label,
    required this.value,
    required this.color,
    required this.icon,
  });

  final String label;
  final String value;
  final Color color;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(AppSpacing.sm),
      decoration: BoxDecoration(
        color: color.withOpacity(0.08),
        borderRadius: AppRadius.mdRadius,
        border: Border.all(color: color.withOpacity(0.2)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color, size: 18),
          const SizedBox(height: AppSpacing.xs),
          Text(
            value,
            style: AppTextStyles.labelLarge.copyWith(
              color: color,
              fontSize: 13,
            ),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
          Text(
            label,
            style: AppTextStyles.labelSmall,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
        ],
      ),
    );
  }
}

class _BalanceCard extends StatelessWidget {
  const _BalanceCard({required this.balance});

  final double balance;

  @override
  Widget build(BuildContext context) {
    return Container(
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
            'Solde total',
            style: AppTextStyles.bodySmall.copyWith(
              color: Colors.white.withOpacity(0.8),
            ),
          ),
          const SizedBox(height: AppSpacing.xs),
          Text(
            AppFormatters.formatCurrency(balance),
            style: AppTextStyles.balanceDisplay.copyWith(
              color: Colors.white,
            ),
          ),
        ],
      ),
    );
  }
}

class _ExpensesBarChart extends StatelessWidget {
  const _ExpensesBarChart({required this.expensesByCategory});

  final Map<TransactionCategory, double> expensesByCategory;

  @override
  Widget build(BuildContext context) {
    final entries = expensesByCategory.entries.toList();
    final maxValue = expensesByCategory.values
        .reduce((a, b) => a > b ? a : b);

    return Container(
      height: 200,
      padding: const EdgeInsets.all(AppSpacing.md),
      decoration: BoxDecoration(
        color: AppColors.backgroundCard,
        borderRadius: AppRadius.lgRadius,
        border: Border.all(color: AppColors.border),
      ),
      child: BarChart(
        BarChartData(
          maxY: maxValue * 1.2,
          gridData: FlGridData(
            show: true,
            drawVerticalLine: false,
            getDrawingHorizontalLine: (value) => const FlLine(
              color: AppColors.border,
              strokeWidth: 1,
            ),
          ),
          titlesData: FlTitlesData(
            leftTitles: const AxisTitles(
              sideTitles: SideTitles(showTitles: false),
            ),
            rightTitles: const AxisTitles(
              sideTitles: SideTitles(showTitles: false),
            ),
            topTitles: const AxisTitles(
              sideTitles: SideTitles(showTitles: false),
            ),
            bottomTitles: AxisTitles(
              sideTitles: SideTitles(
                showTitles: true,
                getTitlesWidget: (value, meta) {
                  final index = value.toInt();
                  if (index < 0 || index >= entries.length) {
                    return const SizedBox.shrink();
                  }
                  return Text(
                    entries[index].key.label.substring(0, 3),
                    style: AppTextStyles.labelSmall,
                  );
                },
              ),
            ),
          ),
          borderData: FlBorderData(show: false),
          barGroups: entries.asMap().entries.map((entry) {
            return BarChartGroupData(
              x: entry.key,
              barRods: [
                BarChartRodData(
                  toY: entry.value.value,
                  color: AppColors.primary,
                  width: 20,
                  borderRadius: const BorderRadius.vertical(
                    top: Radius.circular(6),
                  ),
                ),
              ],
            );
          }).toList(),
        ),
      ),
    );
  }
}

class _CategoryLegend extends StatelessWidget {
  const _CategoryLegend({required this.expensesByCategory});

  final Map<TransactionCategory, double> expensesByCategory;

  @override
  Widget build(BuildContext context) {
    final total = expensesByCategory.values
        .fold(0.0, (sum, v) => sum + v);

    return Column(
      children: expensesByCategory.entries.map((entry) {
        final percentage = entry.value / total;
        return Padding(
          padding: const EdgeInsets.only(bottom: AppSpacing.sm),
          child: Row(
            children: [
              Container(
                width: 12,
                height: 12,
                decoration: const BoxDecoration(
                  color: AppColors.primary,
                  shape: BoxShape.circle,
                ),
              ),
              const SizedBox(width: AppSpacing.sm),
              Expanded(
                child: Text(
                  entry.key.label,
                  style: AppTextStyles.bodySmall,
                ),
              ),
              Text(
                AppFormatters.formatCurrency(entry.value),
                style: AppTextStyles.labelMedium.copyWith(
                  color: AppColors.textPrimary,
                ),
              ),
              const SizedBox(width: AppSpacing.sm),
              Text(
                AppFormatters.formatPercentage(percentage),
                style: AppTextStyles.labelSmall,
              ),
            ],
          ),
        );
      }).toList(),
    );
  }
}

class _EmptyAnalytics extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(AppSpacing.xl),
        child: Column(
          children: [
            Icon(
              Icons.bar_chart_outlined,
              size: 64,
              color: AppColors.textHint,
            ),
            const SizedBox(height: AppSpacing.md),
            Text(
              'Pas encore de donnees',
              style: AppTextStyles.headlineSmall.copyWith(
                color: AppColors.textSecondary,
              ),
            ),
            const SizedBox(height: AppSpacing.sm),
            Text(
              'Ajoutez des transactions pour voir\nvos analyses financieres',
              style: AppTextStyles.bodySmall,
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
    );
  }
}