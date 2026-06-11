import 'package:flutter/material.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/formatters.dart';
import '../../../data/mock/mock_data.dart';
import '../../../data/models/transaction_model.dart';
import '../widgets/period_tab_bar.dart';
import '../widgets/transaction_item.dart';
import '../../../services/auth_service.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _selectedPeriodIndex = 1;

  List<TransactionModel> get _filteredTransactions {
    final now = DateTime.now();
    final transactions = MockData.transactions;
    switch (_selectedPeriodIndex) {
      case 0:
        return transactions
            .where((t) =>
                t.date.year == now.year &&
                t.date.month == now.month &&
                t.date.day == now.day)
            .toList();
      case 1:
        final weekAgo = now.subtract(const Duration(days: 7));
        return transactions.where((t) => t.date.isAfter(weekAgo)).toList();
      case 2:
        return transactions
            .where((t) =>
                t.date.year == now.year && t.date.month == now.month)
            .toList();
      case 3:
        return transactions.where((t) => t.date.year == now.year).toList();
      default:
        return transactions;
    }
  }

  @override
  Widget build(BuildContext context) {
    final userName = AuthService.currentUserFirstName;
    final transactions = _filteredTransactions;

    return Scaffold(
      backgroundColor: AppColors.background,
      body: CustomScrollView(
        slivers: [
          SliverToBoxAdapter(
            child: _HomeHeader(
              userName: userName,
              balance: 4790.05,
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(
                horizontal: AppSpacing.pageHorizontal,
                vertical: AppSpacing.md,
              ),
              child: PeriodTabBar(
                selectedIndex: _selectedPeriodIndex,
                onChanged: (index) {
                  setState(() => _selectedPeriodIndex = index);
                },
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(
                horizontal: AppSpacing.pageHorizontal,
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    AppStrings.homeTransactions,
                    style: AppTextStyles.headlineSmall,
                  ),
                  TextButton(
                    onPressed: () {},
                    child: Text(
                      AppStrings.homeSeeAll,
                      style: AppTextStyles.bodySmall.copyWith(
                        color: AppColors.primary,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
          transactions.isEmpty
              ? SliverToBoxAdapter(
                  child: Padding(
                    padding: const EdgeInsets.all(AppSpacing.xl),
                    child: Center(
                      child: Text(
                        'Aucune transaction sur cette periode',
                        style: AppTextStyles.bodyMedium.copyWith(
                          color: AppColors.textSecondary,
                        ),
                      ),
                    ),
                  ),
                )
              : SliverList(
                  delegate: SliverChildBuilderDelegate(
                    (context, index) {
                      return Padding(
                        padding: const EdgeInsets.symmetric(
                          horizontal: AppSpacing.pageHorizontal,
                        ),
                        child: TransactionItem(
                          transaction: transactions[index],
                        ),
                      );
                    },
                    childCount: transactions.length,
                  ),
                ),
          const SliverToBoxAdapter(
            child: SizedBox(height: AppSpacing.xl),
          ),
        ],
      ),
    );
  }
}

class _HomeHeader extends StatelessWidget {
  const _HomeHeader({
    required this.userName,
    required this.balance,
  });

  final String userName;
  final double balance;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      decoration: const BoxDecoration(
        gradient: AppColors.headerGradient,
        borderRadius: BorderRadius.only(
          bottomLeft: Radius.circular(28),
          bottomRight: Radius.circular(28),
        ),
      ),
      child: SafeArea(
        bottom: false,
        child: Padding(
          padding: const EdgeInsets.fromLTRB(
            AppSpacing.pageHorizontal,
            AppSpacing.lg,
            AppSpacing.pageHorizontal,
            AppSpacing.xl,
          ),
          child: Column(
            children: [
              Text(
                '${AppStrings.homeGreeting} $userName',
                style: AppTextStyles.headlineLarge.copyWith(
                  color: AppColors.textOnPrimary,
                  fontWeight: FontWeight.w700,
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: AppSpacing.sm),
              Text(
                AppStrings.appTagline,
                style: AppTextStyles.bodySmall.copyWith(
                  color: AppColors.textOnPrimary.withOpacity(0.8),
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: AppSpacing.xl),
              Text(
                AppStrings.homeBalance,
                style: AppTextStyles.bodySmall.copyWith(
                  color: AppColors.textOnPrimary.withOpacity(0.8),
                ),
              ),
              const SizedBox(height: AppSpacing.xs),
              Text(
                AppFormatters.formatCurrency(balance),
                style: AppTextStyles.balanceDisplay.copyWith(
                  color: AppColors.textOnPrimary,
                ),
              ),
              const SizedBox(height: AppSpacing.md),
            ],
          ),
        ),
      ),
    );
  }
}