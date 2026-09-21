import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/formatters.dart';
import '../../../data/models/transaction_model.dart';
import '../../../data/repositories/firestore_repository.dart';
import '../../../providers/transactions_provider.dart';
import '../../../providers/cards_provider.dart';
import '../../../providers/user_provider.dart';
import '../widgets/period_tab_bar.dart';
import '../widgets/transaction_item.dart';

class HomeScreen extends ConsumerStatefulWidget {
  const HomeScreen({super.key});

  @override
  ConsumerState<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends ConsumerState<HomeScreen> {
  int _selectedPeriodIndex = 1;
  final _searchController = TextEditingController();
  String _searchQuery = '';
  bool _isSearching = false;

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  /// Filtre les transactions selon la periode et la recherche.
  List<TransactionModel> _filterTransactions(
    List<TransactionModel> transactions,
  ) {
    final now = DateTime.now();
    List<TransactionModel> filtered;

    switch (_selectedPeriodIndex) {
      case 0:
        filtered = transactions
            .where((t) =>
                t.date.year == now.year &&
                t.date.month == now.month &&
                t.date.day == now.day)
            .toList();
        break;
      case 1:
        final weekAgo = now.subtract(const Duration(days: 7));
        filtered =
            transactions.where((t) => t.date.isAfter(weekAgo)).toList();
        break;
      case 2:
        filtered = transactions
            .where((t) =>
                t.date.year == now.year && t.date.month == now.month)
            .toList();
        break;
      case 3:
        filtered =
            transactions.where((t) => t.date.year == now.year).toList();
        break;
      default:
        filtered = transactions;
    }

    // Filtre par recherche
    if (_searchQuery.isNotEmpty) {
      filtered = filtered
          .where((t) =>
              t.title.toLowerCase().contains(_searchQuery.toLowerCase()) ||
              t.subtitle
                  .toLowerCase()
                  .contains(_searchQuery.toLowerCase()) ||
              t.category.label
                  .toLowerCase()
                  .contains(_searchQuery.toLowerCase()))
          .toList();
    }

    return filtered;
  }

  @override
  Widget build(BuildContext context) {
    final firstName = ref.watch(userFirstNameProvider);
    final totalBalance = ref.watch(totalBalanceProvider);
    final transactionsAsync = ref.watch(transactionsProvider);

    return Scaffold(
      backgroundColor: AppColors.background,
      body: CustomScrollView(
        slivers: [
          // En-tete vert
          SliverToBoxAdapter(
            child: _HomeHeader(
              userName: firstName,
              balance: totalBalance,
            ),
          ),

          // Barre de recherche
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.fromLTRB(
                AppSpacing.pageHorizontal,
                AppSpacing.md,
                AppSpacing.pageHorizontal,
                0,
              ),
              child: _SearchBar(
                controller: _searchController,
                isSearching: _isSearching,
                onChanged: (value) {
                  setState(() => _searchQuery = value);
                },
                onSearchToggle: () {
                  setState(() {
                    _isSearching = !_isSearching;
                    if (!_isSearching) {
                      _searchQuery = '';
                      _searchController.clear();
                    }
                  });
                },
              ),
            ),
          ),

          // Onglets de periode (caches pendant la recherche)
          if (!_isSearching)
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

          // Titre transactions
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(
                horizontal: AppSpacing.pageHorizontal,
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    _searchQuery.isNotEmpty
                        ? 'Resultats de recherche'
                        : AppStrings.homeTransactions,
                    style: AppTextStyles.headlineSmall,
                  ),
                  if (_searchQuery.isEmpty)
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

          // Liste des transactions
          transactionsAsync.when(
            loading: () => const SliverToBoxAdapter(
              child: Center(
                child: Padding(
                  padding: EdgeInsets.all(AppSpacing.xl),
                  child: CircularProgressIndicator(
                    color: AppColors.primary,
                  ),
                ),
              ),
            ),
            error: (error, _) => SliverToBoxAdapter(
              child: Center(
                child: Padding(
                  padding: const EdgeInsets.all(AppSpacing.xl),
                  child: Text(
                    'Erreur de chargement',
                    style: AppTextStyles.bodyMedium.copyWith(
                      color: AppColors.expense,
                    ),
                  ),
                ),
              ),
            ),
            data: (transactions) {
              final filtered = _filterTransactions(transactions);
              return filtered.isEmpty
                  ? SliverToBoxAdapter(
                      child: Padding(
                        padding: const EdgeInsets.all(AppSpacing.xl),
                        child: Center(
                          child: Column(
                            children: [
                              Icon(
                                _searchQuery.isNotEmpty
                                    ? Icons.search_off_rounded
                                    : Icons.receipt_long_outlined,
                                size: 48,
                                color: AppColors.textHint,
                              ),
                              const SizedBox(height: AppSpacing.md),
                              Text(
                                _searchQuery.isNotEmpty
                                    ? 'Aucun resultat pour\n"$_searchQuery"'
                                    : 'Aucune transaction\nsur cette periode',
                                style: AppTextStyles.bodyMedium.copyWith(
                                  color: AppColors.textSecondary,
                                ),
                                textAlign: TextAlign.center,
                              ),
                            ],
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
                              transaction: filtered[index],
                            ),
                          );
                        },
                        childCount: filtered.length,
                      ),
                    );
            },
          ),

          const SliverToBoxAdapter(
            child: SizedBox(height: AppSpacing.xl),
          ),
        ],
      ),

      // Bouton ajout de transaction
      floatingActionButton: FloatingActionButton(
        onPressed: () => _showAddTransactionSheet(context),
        backgroundColor: AppColors.primary,
        child: const Icon(Icons.add_rounded, color: Colors.white),
      ),
    );
  }

  void _showAddTransactionSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => const _AddTransactionSheet(),
    );
  }
}

// ---------------------------------------------------------------------------
// En-tete vert
// ---------------------------------------------------------------------------

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

// ---------------------------------------------------------------------------
// Barre de recherche
// ---------------------------------------------------------------------------

class _SearchBar extends StatelessWidget {
  const _SearchBar({
    required this.controller,
    required this.isSearching,
    required this.onChanged,
    required this.onSearchToggle,
  });

  final TextEditingController controller;
  final bool isSearching;
  final ValueChanged<String> onChanged;
  final VoidCallback onSearchToggle;

  @override
  Widget build(BuildContext context) {
    return AnimatedCrossFade(
      duration: const Duration(milliseconds: 300),
      crossFadeState: isSearching
          ? CrossFadeState.showSecond
          : CrossFadeState.showFirst,

      // Bouton loupe (etat normal)
      firstChild: Row(
        mainAxisAlignment: MainAxisAlignment.end,
        children: [
          GestureDetector(
            onTap: onSearchToggle,
            child: Container(
              width: 40,
              height: 40,
              decoration: BoxDecoration(
                color: AppColors.backgroundSecondary,
                borderRadius: AppRadius.mdRadius,
                border: Border.all(color: AppColors.border),
              ),
              child: const Icon(
                Icons.search_rounded,
                color: AppColors.textSecondary,
                size: 20,
              ),
            ),
          ),
        ],
      ),

      // Champ de recherche (etat recherche)
      secondChild: Row(
        children: [
          Expanded(
            child: TextField(
              controller: controller,
              autofocus: true,
              onChanged: onChanged,
              style: AppTextStyles.bodyMedium,
              decoration: InputDecoration(
                hintText: 'Rechercher une transaction...',
                prefixIcon: const Icon(
                  Icons.search_rounded,
                  color: AppColors.textSecondary,
                  size: 20,
                ),
                filled: true,
                fillColor: AppColors.backgroundSecondary,
                contentPadding: const EdgeInsets.symmetric(
                  horizontal: AppSpacing.md,
                  vertical: AppSpacing.sm,
                ),
                border: OutlineInputBorder(
                  borderRadius: AppRadius.mdRadius,
                  borderSide: const BorderSide(color: AppColors.border),
                ),
                enabledBorder: OutlineInputBorder(
                  borderRadius: AppRadius.mdRadius,
                  borderSide: const BorderSide(color: AppColors.border),
                ),
                focusedBorder: OutlineInputBorder(
                  borderRadius: AppRadius.mdRadius,
                  borderSide: const BorderSide(
                    color: AppColors.primary,
                    width: 1.5,
                  ),
                ),
              ),
            ),
          ),
          const SizedBox(width: AppSpacing.sm),
          GestureDetector(
            onTap: onSearchToggle,
            child: Text(
              'Annuler',
              style: AppTextStyles.bodySmall.copyWith(
                color: AppColors.primary,
                fontWeight: FontWeight.w500,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// Bottom sheet ajout de transaction
// ---------------------------------------------------------------------------

class _AddTransactionSheet extends ConsumerStatefulWidget {
  const _AddTransactionSheet();

  @override
  ConsumerState<_AddTransactionSheet> createState() =>
      _AddTransactionSheetState();
}

class _AddTransactionSheetState
    extends ConsumerState<_AddTransactionSheet> {
  final _titleController = TextEditingController();
  final _subtitleController = TextEditingController();
  final _amountController = TextEditingController();
  TransactionCategory _selectedCategory = TransactionCategory.other;
  bool _isExpense = true;
  bool _isLoading = false;

  @override
  void dispose() {
    _titleController.dispose();
    _subtitleController.dispose();
    _amountController.dispose();
    super.dispose();
  }

  Future<void> _handleSave() async {
    if (_titleController.text.isEmpty ||
        _amountController.text.isEmpty) return;

    setState(() => _isLoading = true);

    final amount =
        double.tryParse(_amountController.text.replaceAll(',', '.')) ??
            0.0;

    final transaction = TransactionModel(
      id: '',
      title: _titleController.text.trim(),
      subtitle: _subtitleController.text.trim(),
      amount: _isExpense ? -amount.abs() : amount.abs(),
      date: DateTime.now(),
      category: _selectedCategory,
    );

    await FirestoreRepository.addTransaction(transaction);

    if (!mounted) return;
    setState(() => _isLoading = false);
    Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: AppColors.background,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      padding: EdgeInsets.only(
        left: AppSpacing.pageHorizontal,
        right: AppSpacing.pageHorizontal,
        top: AppSpacing.lg,
        bottom:
            MediaQuery.of(context).viewInsets.bottom + AppSpacing.lg,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Center(
            child: Container(
              width: 40,
              height: 4,
              decoration: BoxDecoration(
                color: AppColors.border,
                borderRadius: AppRadius.fullRadius,
              ),
            ),
          ),
          const SizedBox(height: AppSpacing.lg),

          Text(
            'Nouvelle transaction',
            style: AppTextStyles.headlineMedium,
          ),

          const SizedBox(height: AppSpacing.lg),

          // Toggle Depense / Revenu
          Row(
            children: [
              Expanded(
                child: GestureDetector(
                  onTap: () => setState(() => _isExpense = true),
                  child: Container(
                    padding: const EdgeInsets.symmetric(
                      vertical: AppSpacing.sm,
                    ),
                    decoration: BoxDecoration(
                      color: _isExpense
                          ? AppColors.expense
                          : AppColors.backgroundSecondary,
                      borderRadius: AppRadius.mdRadius,
                    ),
                    child: Center(
                      child: Text(
                        'Depense',
                        style: AppTextStyles.labelLarge.copyWith(
                          color: _isExpense
                              ? Colors.white
                              : AppColors.textSecondary,
                        ),
                      ),
                    ),
                  ),
                ),
              ),
              const SizedBox(width: AppSpacing.sm),
              Expanded(
                child: GestureDetector(
                  onTap: () => setState(() => _isExpense = false),
                  child: Container(
                    padding: const EdgeInsets.symmetric(
                      vertical: AppSpacing.sm,
                    ),
                    decoration: BoxDecoration(
                      color: !_isExpense
                          ? AppColors.income
                          : AppColors.backgroundSecondary,
                      borderRadius: AppRadius.mdRadius,
                    ),
                    child: Center(
                      child: Text(
                        'Revenu',
                        style: AppTextStyles.labelLarge.copyWith(
                          color: !_isExpense
                              ? Colors.white
                              : AppColors.textSecondary,
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ],
          ),

          const SizedBox(height: AppSpacing.md),

          TextField(
            controller: _titleController,
            decoration: _inputDecoration('Titre', 'Ex: Courses, Salaire...'),
          ),
          const SizedBox(height: AppSpacing.sm),

          TextField(
            controller: _subtitleController,
            decoration:
                _inputDecoration('Marchand / Source', 'Ex: Carrefour...'),
          ),
          const SizedBox(height: AppSpacing.sm),

          TextField(
            controller: _amountController,
            keyboardType: const TextInputType.numberWithOptions(
              decimal: true,
            ),
            decoration: _inputDecoration('Montant (€)', '0.00'),
          ),
          const SizedBox(height: AppSpacing.sm),

          DropdownButtonFormField<TransactionCategory>(
            value: _selectedCategory,
            decoration: _inputDecoration('Categorie', ''),
            items: TransactionCategory.values.map((cat) {
              return DropdownMenuItem(
                value: cat,
                child: Text(cat.label),
              );
            }).toList(),
            onChanged: (val) {
              if (val != null) setState(() => _selectedCategory = val);
            },
          ),

          const SizedBox(height: AppSpacing.lg),

          SizedBox(
            width: double.infinity,
            height: 54,
            child: ElevatedButton(
              onPressed: _isLoading ? null : _handleSave,
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                foregroundColor: Colors.white,
                elevation: 0,
                shape: const RoundedRectangleBorder(
                  borderRadius: AppRadius.mdRadius,
                ),
              ),
              child: _isLoading
                  ? const SizedBox(
                      width: 22,
                      height: 22,
                      child: CircularProgressIndicator(
                        strokeWidth: 2.5,
                        color: Colors.white,
                      ),
                    )
                  : const Text(
                      'Enregistrer',
                      style: AppTextStyles.buttonText,
                    ),
            ),
          ),
        ],
      ),
    );
  }

  InputDecoration _inputDecoration(String label, String hint) {
    return InputDecoration(
      labelText: label,
      hintText: hint,
      filled: true,
      fillColor: AppColors.backgroundSecondary,
      contentPadding: const EdgeInsets.symmetric(
        horizontal: AppSpacing.md,
        vertical: AppSpacing.md,
      ),
      border: OutlineInputBorder(
        borderRadius: AppRadius.mdRadius,
        borderSide: const BorderSide(color: AppColors.border),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: AppRadius.mdRadius,
        borderSide: const BorderSide(color: AppColors.border),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: AppRadius.mdRadius,
        borderSide: const BorderSide(
          color: AppColors.primary,
          width: 1.5,
        ),
      ),
    );
  }
}