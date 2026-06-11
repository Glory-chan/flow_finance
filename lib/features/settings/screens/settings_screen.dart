import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/router/app_router.dart';
import '../../../services/auth_service.dart';
import 'profile_screen.dart';
import 'security_screen.dart';
import 'notifications_screen.dart';
import 'general_screen.dart';
import 'export_screen.dart';

class SettingsScreen extends StatelessWidget {
  const SettingsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text(
          AppStrings.settingsTitle,
          style: AppTextStyles.headlineMedium,
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              Padding(
                padding: const EdgeInsets.all(AppSpacing.pageHorizontal),
                child: _UserProfileCard(
                  fullName: AuthService.currentUserFullName,
                  email: AuthService.currentUserEmail,
                ),
              ),
              _SettingsSection(
                title: AppStrings.settingsAccount,
                items: [
                  _SettingsItem(
                    icon: Icons.person_outline_rounded,
                    label: AppStrings.settingsProfile,
                    onTap: () => context.push('/profile'),
                  ),
                  _SettingsItem(
                    icon: Icons.lock_outline_rounded,
                    label: AppStrings.settingsSecurity,
                    onTap: () => context.push('/security'),
                  ),
                  _SettingsItem(
                    icon: Icons.notifications_outlined,
                    label: AppStrings.settingsNotifications,
                    onTap: () => context.push('/notifications'),
                    trailing: _NotificationBadge(),
                  ),
                  _SettingsItem(
                    icon: Icons.account_balance_outlined,
                    label: AppStrings.settingsBankAccounts,
                    onTap: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text('Comptes bancaires a venir...'),
                        ),
                      );
                    },
                  ),
                ],
              ),
              _SettingsSection(
                title: AppStrings.settingsPreferences,
                items: [
                  _SettingsItem(
                    icon: Icons.tune_rounded,
                    label: AppStrings.settingsGeneral,
                    onTap: () => context.push('/general'),
                  ),
                  _SettingsItem(
                    icon: Icons.credit_card_outlined,
                    label: AppStrings.settingsBilling,
                    onTap: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text('Facturation a venir...'),
                        ),
                      );
                    },
                  ),
                ],
              ),
              _SettingsSection(
                title: AppStrings.settingsData,
                items: [
                  _SettingsItem(
                    icon: Icons.download_outlined,
                    label: AppStrings.settingsExport,
                    onTap: () => context.push('/export'),
                  ),
                ],
              ),
              Padding(
                padding: const EdgeInsets.symmetric(
                  horizontal: AppSpacing.pageHorizontal,
                  vertical: AppSpacing.sm,
                ),
                child: _LogoutButton(),
              ),
              Padding(
                padding: const EdgeInsets.symmetric(
                  horizontal: AppSpacing.pageHorizontal,
                ),
                child: _DangerZone(),
              ),
              const SizedBox(height: AppSpacing.xxl),
            ],
          ),
        ),
      ),
    );
  }
}

class _UserProfileCard extends StatelessWidget {
  const _UserProfileCard({
    required this.fullName,
    required this.email,
  });

  final String fullName;
  final String email;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(AppSpacing.lg),
      decoration: const BoxDecoration(
        gradient: AppColors.primaryGradient,
        borderRadius: AppRadius.lgRadius,
      ),
      child: Row(
        children: [
          Container(
            width: 56,
            height: 56,
            decoration: BoxDecoration(
              color: Colors.white.withOpacity(0.2),
              shape: BoxShape.circle,
            ),
            child: Center(
              child: Text(
                fullName
                    .split(' ')
                    .map((w) => w.isNotEmpty ? w[0] : '')
                    .take(2)
                    .join(),
                style: AppTextStyles.headlineMedium.copyWith(
                  color: Colors.white,
                ),
              ),
            ),
          ),
          const SizedBox(width: AppSpacing.md),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  fullName,
                  style: AppTextStyles.headlineSmall.copyWith(
                    color: Colors.white,
                  ),
                ),
                Text(
                  email,
                  style: AppTextStyles.bodySmall.copyWith(
                    color: Colors.white.withOpacity(0.8),
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
          IconButton(
            onPressed: () => context.push('/profile'),
            icon: const Icon(
              Icons.edit_outlined,
              color: Colors.white,
              size: 20,
            ),
          ),
        ],
      ),
    );
  }
}

class _SettingsSection extends StatelessWidget {
  const _SettingsSection({
    required this.title,
    required this.items,
  });

  final String title;
  final List<_SettingsItem> items;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(
        AppSpacing.pageHorizontal,
        AppSpacing.md,
        AppSpacing.pageHorizontal,
        0,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.only(
              left: AppSpacing.xs,
              bottom: AppSpacing.sm,
            ),
            child: Text(
              title,
              style: AppTextStyles.labelSmall.copyWith(
                color: AppColors.textSecondary,
                letterSpacing: 1,
              ),
            ),
          ),
          Container(
            decoration: BoxDecoration(
              color: AppColors.backgroundCard,
              borderRadius: AppRadius.lgRadius,
              border: Border.all(color: AppColors.border),
            ),
            child: Column(
              children: items.asMap().entries.map((entry) {
                final index = entry.key;
                final item = entry.value;
                final isLast = index == items.length - 1;
                return Column(
                  children: [
                    item,
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
      ),
    );
  }
}

class _SettingsItem extends StatelessWidget {
  const _SettingsItem({
    required this.icon,
    required this.label,
    required this.onTap,
    this.trailing,
  });

  final IconData icon;
  final String label;
  final VoidCallback onTap;
  final Widget? trailing;

  @override
  Widget build(BuildContext context) {
    return ListTile(
      onTap: onTap,
      contentPadding: const EdgeInsets.symmetric(
        horizontal: AppSpacing.md,
        vertical: AppSpacing.xs,
      ),
      leading: Container(
        width: 38,
        height: 38,
        decoration: BoxDecoration(
          color: AppColors.primaryLight,
          borderRadius: AppRadius.smRadius,
        ),
        child: Icon(icon, color: AppColors.primary, size: 20),
      ),
      title: Text(label, style: AppTextStyles.bodyMedium),
      trailing: trailing ??
          const Icon(
            Icons.chevron_right_rounded,
            color: AppColors.textSecondary,
            size: 20,
          ),
    );
  }
}

class _NotificationBadge extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
      decoration: const BoxDecoration(
        color: AppColors.expense,
        borderRadius: AppRadius.fullRadius,
      ),
      child: const Text(
        '3',
        style: TextStyle(
          color: Colors.white,
          fontSize: 11,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}

class _LogoutButton extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      child: OutlinedButton.icon(
        onPressed: () async {
          await AuthService.signOut();
          if (!context.mounted) return;
          context.go(AppRoutes.welcome);
        },
        icon: const Icon(Icons.logout_rounded, size: 18),
        label: const Text(AppStrings.settingsLogout),
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.expense,
          side: const BorderSide(color: AppColors.expense),
          padding: const EdgeInsets.symmetric(vertical: 14),
          shape: const RoundedRectangleBorder(
            borderRadius: AppRadius.mdRadius,
          ),
        ),
      ),
    );
  }
}

class _DangerZone extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(
            left: AppSpacing.xs,
            top: AppSpacing.md,
            bottom: AppSpacing.sm,
          ),
          child: Text(
            AppStrings.settingsDanger,
            style: AppTextStyles.labelSmall.copyWith(
              color: AppColors.expense,
              letterSpacing: 1,
            ),
          ),
        ),
        Container(
          decoration: BoxDecoration(
            color: AppColors.expenseLight,
            borderRadius: AppRadius.lgRadius,
            border: Border.all(
              color: AppColors.expense.withOpacity(0.3),
            ),
          ),
          child: ListTile(
            onTap: () => _showDeleteConfirmation(context),
            contentPadding: const EdgeInsets.symmetric(
              horizontal: AppSpacing.md,
              vertical: AppSpacing.xs,
            ),
            leading: Container(
              width: 38,
              height: 38,
              decoration: BoxDecoration(
                color: AppColors.expense.withOpacity(0.15),
                borderRadius: AppRadius.smRadius,
              ),
              child: const Icon(
                Icons.delete_outline_rounded,
                color: AppColors.expense,
                size: 20,
              ),
            ),
            title: Text(
              AppStrings.settingsDeleteAccount,
              style: AppTextStyles.bodyMedium.copyWith(
                color: AppColors.expense,
              ),
            ),
            trailing: const Icon(
              Icons.chevron_right_rounded,
              color: AppColors.expense,
              size: 20,
            ),
          ),
        ),
      ],
    );
  }

  void _showDeleteConfirmation(BuildContext context) {
    showDialog(
      context: context,
      builder: (dialogContext) => AlertDialog(
        shape: RoundedRectangleBorder(
          borderRadius: AppRadius.lgRadius,
        ),
        title: Text(
          'Supprimer le compte',
          style: AppTextStyles.headlineSmall,
        ),
        content: Text(
          'Cette action est irreversible. Toutes vos donnees seront supprimees definitivement.',
          style: AppTextStyles.bodyMedium.copyWith(
            color: AppColors.textSecondary,
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(dialogContext).pop(),
            child: Text(
              'Annuler',
              style: AppTextStyles.labelLarge.copyWith(
                color: AppColors.textSecondary,
              ),
            ),
          ),
          ElevatedButton(
            onPressed: () async {
              Navigator.of(dialogContext).pop();
              await _handleDeleteAccount(context);
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

  Future<void> _handleDeleteAccount(BuildContext context) async {
    final error = await AuthService.deleteAccount();
    if (!context.mounted) return;
    if (error != null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(error),
          backgroundColor: AppColors.expense,
        ),
      );
      return;
    }
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Compte supprime avec succes.'),
        backgroundColor: AppColors.primary,
      ),
    );
    context.go(AppRoutes.welcome);
  }
}