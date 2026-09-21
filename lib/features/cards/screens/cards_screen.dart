import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/formatters.dart';
import '../../../data/models/card_model.dart';
import '../../../data/repositories/firestore_repository.dart';
import '../../../providers/cards_provider.dart';
import '../../../services/auth_service.dart';

class CardsScreen extends ConsumerWidget {
  const CardsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final cardsAsync = ref.watch(cardsProvider);
    final selectedIndex = ref.watch(selectedCardIndexProvider);

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text(
          AppStrings.cardsTitle,
          style: AppTextStyles.headlineMedium,
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
        data: (cards) => cards.isEmpty
            ? _EmptyCards(
                onAddCard: () => _showAddCardSheet(context, ref),
              )
            : SafeArea(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(
                    horizontal: AppSpacing.pageHorizontal,
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const SizedBox(height: AppSpacing.md),
                      _CardStack(
                        cards: cards,
                        selectedIndex: selectedIndex,
                        onCardTapped: (index) {
                          ref
                              .read(selectedCardIndexProvider.notifier)
                              .state = index;
                        },
                      ),
                      const SizedBox(height: AppSpacing.md),

                      // Indicateur de points
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: List.generate(
                          cards.length,
                          (index) => AnimatedContainer(
                            duration: const Duration(milliseconds: 300),
                            margin:
                                const EdgeInsets.symmetric(horizontal: 3),
                            width: index == selectedIndex ? 20 : 8,
                            height: 8,
                            decoration: BoxDecoration(
                              color: index == selectedIndex
                                  ? AppColors.primary
                                  : AppColors.border,
                              borderRadius: AppRadius.fullRadius,
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: AppSpacing.xl),
                      _CardDetails(
                        card: cards[
                            selectedIndex.clamp(0, cards.length - 1)],
                        onDelete: (cardId) async {
                          await FirestoreRepository.deleteCard(cardId);
                          ref
                              .read(selectedCardIndexProvider.notifier)
                              .state = 0;
                        },
                      ),
                      const SizedBox(height: AppSpacing.xl),
                      _AddCardButton(
                        onPressed: () => _showAddCardSheet(context, ref),
                      ),
                      const SizedBox(height: AppSpacing.xl),
                    ],
                  ),
                ),
              ),
      ),
    );
  }

  void _showAddCardSheet(BuildContext context, WidgetRef ref) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => const _AddCardSheet(),
    );
  }
}

// ---------------------------------------------------------------------------
// Ecran vide quand aucune carte
// ---------------------------------------------------------------------------

class _EmptyCards extends StatelessWidget {
  const _EmptyCards({required this.onAddCard});

  final VoidCallback onAddCard;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Icon(
            Icons.credit_card_off_outlined,
            size: 64,
            color: AppColors.textHint,
          ),
          const SizedBox(height: AppSpacing.md),
          Text(
            'Aucune carte enregistree',
            style: AppTextStyles.headlineSmall.copyWith(
              color: AppColors.textSecondary,
            ),
          ),
          const SizedBox(height: AppSpacing.sm),
          Text(
            'Ajoutez votre premiere carte bancaire',
            style: AppTextStyles.bodySmall,
          ),
          const SizedBox(height: AppSpacing.xl),
          ElevatedButton.icon(
            onPressed: onAddCard,
            icon: const Icon(Icons.add_rounded),
            label: const Text('Ajouter une carte'),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary,
              foregroundColor: Colors.white,
              elevation: 0,
              padding: const EdgeInsets.symmetric(
                horizontal: AppSpacing.xl,
                vertical: AppSpacing.md,
              ),
              shape: const RoundedRectangleBorder(
                borderRadius: AppRadius.mdRadius,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// Carousel de cartes
// ---------------------------------------------------------------------------

class _CardStack extends StatefulWidget {
  const _CardStack({
    required this.cards,
    required this.selectedIndex,
    required this.onCardTapped,
  });

  final List<CardModel> cards;
  final int selectedIndex;
  final ValueChanged<int> onCardTapped;

  @override
  State<_CardStack> createState() => _CardStackState();
}

class _CardStackState extends State<_CardStack> {
  late final PageController _pageController;

  @override
  void initState() {
    super.initState();
    _pageController = PageController(
      initialPage: widget.selectedIndex,
      viewportFraction: 0.82,
    );
  }

  @override
  void didUpdateWidget(_CardStack oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.selectedIndex != widget.selectedIndex) {
      _pageController.animateToPage(
        widget.selectedIndex,
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeInOut,
      );
    }
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 220,
      child: PageView.builder(
        controller: _pageController,
        itemCount: widget.cards.length,
        onPageChanged: widget.onCardTapped,
        itemBuilder: (context, index) {
          final isSelected = index == widget.selectedIndex;
          return AnimatedScale(
            scale: isSelected ? 1.0 : 0.88,
            duration: const Duration(milliseconds: 300),
            curve: Curves.easeInOut,
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 6),
              child: GestureDetector(
                onTap: () => widget.onCardTapped(index),
                child: _BankCard(
                  card: widget.cards[index],
                  isSelected: isSelected,
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// Visuel d'une carte bancaire
// ---------------------------------------------------------------------------

class _BankCard extends StatelessWidget {
  const _BankCard({required this.card, required this.isSelected});

  final CardModel card;
  final bool isSelected;

  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 300),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [Color(card.colorStart), Color(card.colorEnd)],
        ),
        borderRadius: AppRadius.xlRadius,
        boxShadow: [
          BoxShadow(
            color: Color(card.colorStart)
                .withOpacity(isSelected ? 0.5 : 0.15),
            blurRadius: isSelected ? 24 : 8,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Padding(
        padding: const EdgeInsets.all(AppSpacing.lg),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  card.bankName,
                  style: AppTextStyles.headlineSmall.copyWith(
                    color: Colors.white,
                    fontWeight: FontWeight.w700,
                  ),
                ),
                _CardNetworkLogo(cardType: card.cardType),
              ],
            ),
            const Spacer(),
            Text(
              card.maskedNumber,
              style: AppTextStyles.bodyMedium.copyWith(
                color: Colors.white.withOpacity(0.9),
                letterSpacing: 2,
                fontWeight: FontWeight.w500,
              ),
            ),
            const SizedBox(height: AppSpacing.md),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'TITULAIRE',
                      style: AppTextStyles.labelSmall.copyWith(
                        color: Colors.white.withOpacity(0.6),
                      ),
                    ),
                    Text(
                      card.cardHolder.toUpperCase(),
                      style: AppTextStyles.labelLarge
                          .copyWith(color: Colors.white),
                    ),
                  ],
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Text(
                      'EXPIRE',
                      style: AppTextStyles.labelSmall.copyWith(
                        color: Colors.white.withOpacity(0.6),
                      ),
                    ),
                    Text(
                      card.formattedExpiry,
                      style: AppTextStyles.labelLarge
                          .copyWith(color: Colors.white),
                    ),
                  ],
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// Logo reseau (Visa / Mastercard / Amex)
// ---------------------------------------------------------------------------

class _CardNetworkLogo extends StatelessWidget {
  const _CardNetworkLogo({required this.cardType});

  final CardType cardType;

  @override
  Widget build(BuildContext context) {
    switch (cardType) {
      case CardType.visa:
        return const Text(
          'VISA',
          style: TextStyle(
            fontFamily: 'Poppins',
            fontSize: 20,
            fontWeight: FontWeight.w800,
            color: Colors.white,
            fontStyle: FontStyle.italic,
          ),
        );
      case CardType.mastercard:
        return SizedBox(
          width: 44,
          height: 28,
          child: Stack(
            children: [
              Positioned(
                left: 0,
                child: Container(
                  width: 28,
                  height: 28,
                  decoration: BoxDecoration(
                    color: const Color(0xFFEB001B).withOpacity(0.9),
                    shape: BoxShape.circle,
                  ),
                ),
              ),
              Positioned(
                left: 16,
                child: Container(
                  width: 28,
                  height: 28,
                  decoration: BoxDecoration(
                    color: const Color(0xFFF79E1B).withOpacity(0.9),
                    shape: BoxShape.circle,
                  ),
                ),
              ),
            ],
          ),
        );
      case CardType.amex:
        return const Text(
          'AMEX',
          style: TextStyle(
            fontFamily: 'Poppins',
            fontSize: 16,
            fontWeight: FontWeight.w700,
            color: Colors.white,
          ),
        );
    }
  }
}

// ---------------------------------------------------------------------------
// Panneau de details de la carte selectionnee
// ---------------------------------------------------------------------------

class _CardDetails extends StatelessWidget {
  const _CardDetails({
    required this.card,
    required this.onDelete,
  });

  final CardModel card;
  final Function(String) onDelete;

  void _showEditBalanceSheet(BuildContext context) {
    final balanceController = TextEditingController(
      text: card.balance.toStringAsFixed(2),
    );

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => Container(
        decoration: const BoxDecoration(
          color: AppColors.background,
          borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
        ),
        padding: EdgeInsets.only(
          left: AppSpacing.pageHorizontal,
          right: AppSpacing.pageHorizontal,
          top: AppSpacing.lg,
          bottom: MediaQuery.of(ctx).viewInsets.bottom + AppSpacing.lg,
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
            Text('Modifier le solde', style: AppTextStyles.headlineMedium),
            const SizedBox(height: AppSpacing.xs),
            Text(card.bankName, style: AppTextStyles.bodySmall),
            const SizedBox(height: AppSpacing.lg),
            TextField(
              controller: balanceController,
              keyboardType: const TextInputType.numberWithOptions(
                decimal: true,
              ),
              autofocus: true,
              decoration: InputDecoration(
                labelText: 'Nouveau solde (€)',
                hintText: '0.00',
                filled: true,
                fillColor: AppColors.backgroundSecondary,
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
            const SizedBox(height: AppSpacing.lg),
            SizedBox(
              width: double.infinity,
              height: 54,
              child: ElevatedButton(
                onPressed: () async {
                  final newBalance = double.tryParse(
                    balanceController.text.replaceAll(',', '.'),
                  );
                  if (newBalance == null) return;
                  await FirestoreRepository.updateCardBalance(
                    card.id,
                    newBalance,
                  );
                  if (!ctx.mounted) return;
                  Navigator.of(ctx).pop();
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('Solde mis a jour avec succes !'),
                      backgroundColor: AppColors.primary,
                    ),
                  );
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  elevation: 0,
                  shape: const RoundedRectangleBorder(
                    borderRadius: AppRadius.mdRadius,
                  ),
                ),
                child: const Text(
                  'Enregistrer',
                  style: AppTextStyles.buttonText,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showCardDetails(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (ctx) => Container(
        decoration: const BoxDecoration(
          color: AppColors.background,
          borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
        ),
        padding: const EdgeInsets.all(AppSpacing.pageHorizontal),
        child: Column(
          mainAxisSize: MainAxisSize.min,
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
            Text('Details de la carte',
                style: AppTextStyles.headlineMedium),
            const SizedBox(height: AppSpacing.lg),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(AppSpacing.md),
              decoration: BoxDecoration(
                color: AppColors.backgroundSecondary,
                borderRadius: AppRadius.lgRadius,
                border: Border.all(color: AppColors.border),
              ),
              child: Column(
                children: [
                  _InfoRow(label: 'Banque', value: card.bankName),
                  const Divider(color: AppColors.border, height: 20),
                  _InfoRow(label: 'Titulaire', value: card.cardHolder),
                  const Divider(color: AppColors.border, height: 20),
                  _InfoRow(label: 'Numero', value: card.maskedNumber),
                  const Divider(color: AppColors.border, height: 20),
                  _InfoRow(
                      label: 'Expiration', value: card.formattedExpiry),
                  const Divider(color: AppColors.border, height: 20),
                  _InfoRow(label: 'Type', value: card.cardType.label),
                ],
              ),
            ),
            const SizedBox(height: AppSpacing.xxl),
          ],
        ),
      ),
    );
  }

  void _confirmDelete(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: AppRadius.lgRadius),
        title: Text(
          'Supprimer la carte',
          style: AppTextStyles.headlineSmall,
        ),
        content: Text(
          'Voulez-vous vraiment supprimer la carte ${card.bankName} ?',
          style: AppTextStyles.bodyMedium,
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(),
            child: Text(
              'Annuler',
              style: AppTextStyles.labelLarge
                  .copyWith(color: AppColors.textSecondary),
            ),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.of(ctx).pop();
              onDelete(card.id);
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

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(AppSpacing.lg),
      decoration: BoxDecoration(
        color: AppColors.backgroundSecondary,
        borderRadius: AppRadius.lgRadius,
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('Solde disponible', style: AppTextStyles.bodySmall),
          const SizedBox(height: AppSpacing.xs),
          Text(
            AppFormatters.formatCurrency(card.balance),
            style: AppTextStyles.displayLarge,
          ),
          const SizedBox(height: AppSpacing.md),
          const Divider(color: AppColors.border),
          const SizedBox(height: AppSpacing.md),
          Row(
            children: [
              _QuickAction(
                icon: Icons.edit_outlined,
                label: 'Modifier',
                onTap: () => _showEditBalanceSheet(context),
              ),
              const SizedBox(width: AppSpacing.md),
              _QuickAction(
                icon: Icons.info_outline_rounded,
                label: 'Details',
                onTap: () => _showCardDetails(context),
              ),
              const SizedBox(width: AppSpacing.md),
              _QuickAction(
                icon: Icons.delete_outline_rounded,
                label: 'Supprimer',
                color: AppColors.expense,
                onTap: () => _confirmDelete(context),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// Bouton d'action rapide
// ---------------------------------------------------------------------------

class _QuickAction extends StatelessWidget {
  const _QuickAction({
    required this.icon,
    required this.label,
    required this.onTap,
    this.color,
  });

  final IconData icon;
  final String label;
  final VoidCallback onTap;
  final Color? color;

  @override
  Widget build(BuildContext context) {
    final effectiveColor = color ?? AppColors.primary;
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: AppSpacing.sm),
          decoration: BoxDecoration(
            color: effectiveColor.withOpacity(0.1),
            borderRadius: AppRadius.mdRadius,
          ),
          child: Column(
            children: [
              Icon(icon, color: effectiveColor, size: 22),
              const SizedBox(height: 4),
              Text(
                label,
                style:
                    AppTextStyles.labelSmall.copyWith(color: effectiveColor),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// Bouton ajouter une carte
// ---------------------------------------------------------------------------

class _AddCardButton extends StatelessWidget {
  const _AddCardButton({required this.onPressed});

  final VoidCallback onPressed;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onPressed,
      child: Container(
        width: double.infinity,
        height: 56,
        decoration: BoxDecoration(
          color: AppColors.primaryLight,
          borderRadius: AppRadius.lgRadius,
          border: Border.all(color: AppColors.primary.withOpacity(0.3)),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: 28,
              height: 28,
              decoration: const BoxDecoration(
                color: AppColors.primary,
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.add_rounded,
                  color: Colors.white, size: 18),
            ),
            const SizedBox(width: AppSpacing.sm),
            Text(
              AppStrings.cardsAddCard,
              style:
                  AppTextStyles.labelLarge.copyWith(color: AppColors.primary),
            ),
          ],
        ),
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// Bottom sheet ajout de carte
// ---------------------------------------------------------------------------

class _AddCardSheet extends ConsumerStatefulWidget {
  const _AddCardSheet();

  @override
  ConsumerState<_AddCardSheet> createState() => _AddCardSheetState();
}

class _AddCardSheetState extends ConsumerState<_AddCardSheet> {
  final _bankNameController = TextEditingController();
  final _cardNumberController = TextEditingController();
  final _balanceController = TextEditingController();
  int _expiryMonth = 1;
  int _expiryYear = 2026;
  CardType _selectedType = CardType.visa;
  int _selectedColorStart = 0xFF2C3E50;
  int _selectedColorEnd = 0xFF4CA1AF;
  bool _isLoading = false;

  final List<Map<String, dynamic>> _colorOptions = [
    {'start': 0xFF2C3E50, 'end': 0xFF4CA1AF, 'label': 'Bleu'},
    {'start': 0xFFB8860B, 'end': 0xFFDAA520, 'label': 'Or'},
    {'start': 0xFF1A1A1A, 'end': 0xFF363636, 'label': 'Noir'},
    {'start': 0xFF1DB954, 'end': 0xFF27AE60, 'label': 'Vert'},
    {'start': 0xFF8E44AD, 'end': 0xFF9B59B6, 'label': 'Violet'},
  ];

  @override
  void dispose() {
    _bankNameController.dispose();
    _cardNumberController.dispose();
    _balanceController.dispose();
    super.dispose();
  }

  Future<void> _handleSave() async {
    if (_bankNameController.text.isEmpty ||
        _cardNumberController.text.isEmpty) return;

    setState(() => _isLoading = true);

    final fullName = AuthService.currentUserFullName;
    final balance =
        double.tryParse(_balanceController.text.replaceAll(',', '.')) ??
            0.0;

    final card = CardModel(
      id: '',
      bankName: _bankNameController.text.trim(),
      cardHolder: fullName,
      cardNumber: _cardNumberController.text.replaceAll(' ', ''),
      expiryMonth: _expiryMonth,
      expiryYear: _expiryYear,
      cardType: _selectedType,
      colorStart: _selectedColorStart,
      colorEnd: _selectedColorEnd,
      balance: balance,
    );

    await FirestoreRepository.addCard(card);

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
        bottom: MediaQuery.of(context).viewInsets.bottom + AppSpacing.lg,
      ),
      child: SingleChildScrollView(
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
            Text('Nouvelle carte', style: AppTextStyles.headlineMedium),
            const SizedBox(height: AppSpacing.lg),

            TextField(
              controller: _bankNameController,
              decoration:
                  _inputDecoration('Nom de la banque', 'Ex: BNP Paribas'),
            ),
            const SizedBox(height: AppSpacing.sm),

            TextField(
              controller: _cardNumberController,
              keyboardType: TextInputType.number,
              maxLength: 16,
              decoration: _inputDecoration(
                  'Numero de carte (16 chiffres)', '1234567890123456'),
            ),

            TextField(
              controller: _balanceController,
              keyboardType:
                  const TextInputType.numberWithOptions(decimal: true),
              decoration: _inputDecoration('Solde actuel (€)', '0.00'),
            ),
            const SizedBox(height: AppSpacing.sm),

            DropdownButtonFormField<CardType>(
              value: _selectedType,
              decoration: _inputDecoration('Type de carte', ''),
              items: CardType.values.map((type) {
                return DropdownMenuItem(
                  value: type,
                  child: Text(type.label),
                );
              }).toList(),
              onChanged: (val) {
                if (val != null) setState(() => _selectedType = val);
              },
            ),
            const SizedBox(height: AppSpacing.sm),

            Row(
              children: [
                Expanded(
                  child: DropdownButtonFormField<int>(
                    value: _expiryMonth,
                    decoration: _inputDecoration('Mois', ''),
                    items: List.generate(12, (i) => i + 1).map((m) {
                      return DropdownMenuItem(
                        value: m,
                        child: Text(m.toString().padLeft(2, '0')),
                      );
                    }).toList(),
                    onChanged: (val) {
                      if (val != null) setState(() => _expiryMonth = val);
                    },
                  ),
                ),
                const SizedBox(width: AppSpacing.sm),
                Expanded(
                  child: DropdownButtonFormField<int>(
                    value: _expiryYear,
                    decoration: _inputDecoration('Annee', ''),
                    items: List.generate(10, (i) => 2025 + i).map((y) {
                      return DropdownMenuItem(
                        value: y,
                        child: Text(y.toString()),
                      );
                    }).toList(),
                    onChanged: (val) {
                      if (val != null) setState(() => _expiryYear = val);
                    },
                  ),
                ),
              ],
            ),
            const SizedBox(height: AppSpacing.md),

            Text('Couleur de la carte', style: AppTextStyles.labelLarge),
            const SizedBox(height: AppSpacing.sm),
            Row(
              children: _colorOptions.map((option) {
                final isSelected = _selectedColorStart == option['start'];
                return Expanded(
                  child: GestureDetector(
                    onTap: () => setState(() {
                      _selectedColorStart = option['start'] as int;
                      _selectedColorEnd = option['end'] as int;
                    }),
                    child: Container(
                      margin: const EdgeInsets.only(right: 6),
                      height: 36,
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          colors: [
                            Color(option['start'] as int),
                            Color(option['end'] as int),
                          ],
                        ),
                        borderRadius: AppRadius.smRadius,
                        border: isSelected
                            ? Border.all(
                                color: AppColors.primary, width: 2)
                            : null,
                      ),
                    ),
                  ),
                );
              }).toList(),
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
                        'Ajouter la carte',
                        style: AppTextStyles.buttonText,
                      ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  InputDecoration _inputDecoration(String label, String hint) {
    return InputDecoration(
      labelText: label,
      hintText: hint,
      filled: true,
      fillColor: AppColors.backgroundSecondary,
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
        borderSide:
            const BorderSide(color: AppColors.primary, width: 1.5),
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// Widget utilitaire : ligne label / valeur
// ---------------------------------------------------------------------------

class _InfoRow extends StatelessWidget {
  const _InfoRow({required this.label, required this.value});

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(label, style: AppTextStyles.bodySmall),
        Text(
          value,
          style: AppTextStyles.labelLarge,
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
      ],
    );
  }
}