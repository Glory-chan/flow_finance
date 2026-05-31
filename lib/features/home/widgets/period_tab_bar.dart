import 'package:flutter/material.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';
import '../../../core/constants/app_styles.dart';

class PeriodTabBar extends StatelessWidget {
  const PeriodTabBar({
    super.key,
    required this.selectedIndex,
    required this.onChanged,
  });

  final int selectedIndex;
  final ValueChanged<int> onChanged;

  static const List<String> _labels = [
    AppStrings.homePeriodDay,
    AppStrings.homePeriodWeek,
    AppStrings.homePeriodMonth,
    AppStrings.homePeriodYear,
  ];

  @override
  Widget build(BuildContext context) {
    return Container(
      height: 40,
      padding: const EdgeInsets.all(3),
      decoration: BoxDecoration(
        color: AppColors.backgroundSecondary,
        borderRadius: AppRadius.fullRadius,
        border: Border.all(color: AppColors.border),
      ),
      child: Row(
        children: List.generate(
          _labels.length,
          (index) => Expanded(
            child: _PeriodTab(
              label: _labels[index],
              isActive: selectedIndex == index,
              onTap: () => onChanged(index),
            ),
          ),
        ),
      ),
    );
  }
}

class _PeriodTab extends StatelessWidget {
  const _PeriodTab({
    required this.label,
    required this.isActive,
    required this.onTap,
  });

  final String label;
  final bool isActive;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        decoration: BoxDecoration(
          color: isActive ? AppColors.primary : Colors.transparent,
          borderRadius: AppRadius.fullRadius,
        ),
        child: Center(
          child: Text(
            label,
            style: TextStyle(
              fontFamily: 'Poppins',
              fontSize: 12,
              fontWeight:
                  isActive ? FontWeight.w600 : FontWeight.w400,
              color: isActive
                  ? AppColors.textOnPrimary
                  : AppColors.textSecondary,
            ),
          ),
        ),
      ),
    );
  }
}