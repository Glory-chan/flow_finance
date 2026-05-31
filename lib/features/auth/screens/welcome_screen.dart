import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/router/app_router.dart';

class WelcomeScreen extends StatelessWidget {
  const WelcomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(
            horizontal: AppSpacing.pageHorizontal,
          ),
          child: Column(
            children: [
              Expanded(
                flex: 3,
                child: Center(child: _FlowFinanceLogo()),
              ),
              Expanded(
                flex: 1,
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: OutlinedButton(
                            onPressed: () => context.push(AppRoutes.register),
                            style: OutlinedButton.styleFrom(
                              foregroundColor: AppColors.primary,
                              side: const BorderSide(
                                color: AppColors.primary,
                                width: 1.5,
                              ),
                              minimumSize: const Size(double.infinity, 48),
                              shape: const RoundedRectangleBorder(
                                borderRadius: AppRadius.mdRadius,
                              ),
                            ),
                            child: Text(
                              AppStrings.welcomeSignUp,
                              style: AppTextStyles.buttonText.copyWith(
                                color: AppColors.primary,
                              ),
                            ),
                          ),
                        ),
                        const SizedBox(width: AppSpacing.md),
                        Expanded(
                          child: ElevatedButton(
                            onPressed: () => context.push(AppRoutes.login),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppColors.primary,
                              foregroundColor: AppColors.textOnPrimary,
                              elevation: 0,
                              minimumSize: const Size(double.infinity, 48),
                              shape: const RoundedRectangleBorder(
                                borderRadius: AppRadius.mdRadius,
                              ),
                            ),
                            child: Text(
                              AppStrings.welcomeSignIn,
                              style: AppTextStyles.buttonText,
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: AppSpacing.xl),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _FlowFinanceLogo extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Row(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            const Text(
              'FL',
              style: TextStyle(
                fontFamily: 'Poppins',
                fontSize: 42,
                fontWeight: FontWeight.w700,
                color: AppColors.primary,
                letterSpacing: -1,
              ),
            ),
            const SizedBox(width: 4),
            Container(
              width: 44,
              height: 44,
              decoration: BoxDecoration(
                color: AppColors.primaryLight,
                borderRadius: AppRadius.smRadius,
              ),
              child: const Icon(
                Icons.account_balance_outlined,
                color: AppColors.primary,
                size: 26,
              ),
            ),
            const SizedBox(width: 4),
            const Text(
              'WFINANCE',
              style: TextStyle(
                fontFamily: 'Poppins',
                fontSize: 28,
                fontWeight: FontWeight.w700,
                color: AppColors.textPrimary,
                letterSpacing: -0.5,
              ),
            ),
          ],
        ),
        const SizedBox(height: AppSpacing.md),
        Text(
          AppStrings.appTagline,
          style: AppTextStyles.bodyMedium.copyWith(
            color: AppColors.textSecondary,
          ),
          textAlign: TextAlign.center,
        ),
      ],
    );
  }
}