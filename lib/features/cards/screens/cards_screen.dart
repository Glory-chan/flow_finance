import 'package:flutter/material.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/formatters.dart';
import '../../../data/mock/mock_data.dart';
import '../../../data/models/card_model.dart';

class CardsScreen extends StatefulWidget {
  const CardsScreen({super.key});

  @override
  State<CardsScreen> createState() => _CardsScreenState();
}

class _CardsScreenState extends State<CardsScreen> {
  int _selectedCardIndex = 0;

  @override
  Widget build(BuildContext context) {
    final cards = MockData.cards;

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text(AppStrings.cardsTitle, style: AppTextStyles.headlineMedium),
      ),
      body: SafeArea(
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
                selectedIndex: _selectedCardIndex,
                onCardTapped: (index) {
                  setState(() => _selectedCardIndex = index);
                },
              ),
              const SizedBox(height: AppSpacing.xl),
              if (cards.isNotEmpty)
                _CardDetails(card: cards[_selectedCardIndex]),
              const SizedBox(height: AppSpacing.xl),
              _AddCardButton(
                onPressed: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Ajout de carte a venir...')),
                  );
                },
              ),
              const SizedBox(height: AppSpacing.xl),
            ],
          ),
        ),
      ),
    );
  }
}

class _CardStack extends StatelessWidget {
  const _CardStack({
    required this.cards,
    required this.selectedIndex,
    required this.onCardTapped,
  });

  final List<CardModel> cards;
  final int selectedIndex;
  final ValueChanged<int> onCardTapped;

  @override
  Widget build(BuildContext context) {
    const cardHeight = 200.0;
    const stackOffset = 24.0;
    final totalHeight = cardHeight + (stackOffset * (cards.length - 1));

    return SizedBox(
      height: totalHeight,
      child: Stack(
        children: cards.asMap().entries.map((entry) {
          final index = entry.key;
          final card = entry.value;
          final topOffset = index * stackOffset;
          return Positioned(
            top: topOffset,
            left: 0,
            right: 0,
            child: GestureDetector(
              onTap: () => onCardTapped(index),
              child: _BankCard(
                card: card,
                isSelected: index == selectedIndex,
              ),
            ),
          );
        }).toList(),
      ),
    );
  }
}

class _BankCard extends StatelessWidget {
  const _BankCard({
    required this.card,
    required this.isSelected,
  });

  final CardModel card;
  final bool isSelected;

  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 200),
      height: 200,
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
                .withOpacity(isSelected ? 0.4 : 0.2),
            blurRadius: isSelected ? 20 : 10,
            offset: const Offset(0, 8),
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
                      style: AppTextStyles.labelLarge.copyWith(
                        color: Colors.white,
                      ),
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
                      style: AppTextStyles.labelLarge.copyWith(
                        color: Colors.white,
                      ),
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

class _CardDetails extends StatelessWidget {
  const _CardDetails({required this.card});

  final CardModel card;

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
                icon: Icons.send_rounded,
                label: 'Virement',
                onTap: () {},
              ),
              const SizedBox(width: AppSpacing.md),
              _QuickAction(
                icon: Icons.receipt_long_outlined,
                label: 'Releve',
                onTap: () {},
              ),
              const SizedBox(width: AppSpacing.md),
              _QuickAction(
                icon: Icons.block_rounded,
                label: 'Bloquer',
                onTap: () {},
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _QuickAction extends StatelessWidget {
  const _QuickAction({
    required this.icon,
    required this.label,
    required this.onTap,
  });

  final IconData icon;
  final String label;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: AppSpacing.sm),
          decoration: BoxDecoration(
            color: AppColors.primaryLight,
            borderRadius: AppRadius.mdRadius,
          ),
          child: Column(
            children: [
              Icon(icon, color: AppColors.primary, size: 22),
              const SizedBox(height: 4),
              Text(
                label,
                style: AppTextStyles.labelSmall.copyWith(
                  color: AppColors.primary,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

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
          border: Border.all(
            color: AppColors.primary.withOpacity(0.3),
          ),
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
              child: const Icon(
                Icons.add_rounded,
                color: Colors.white,
                size: 18,
              ),
            ),
            const SizedBox(width: AppSpacing.sm),
            Text(
              AppStrings.cardsAddCard,
              style: AppTextStyles.labelLarge.copyWith(
                color: AppColors.primary,
              ),
            ),
          ],
        ),
      ),
    );
  }
}