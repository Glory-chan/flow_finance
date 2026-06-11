import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_styles.dart';

/// Ecran des parametres generaux de l'application.
class GeneralScreen extends StatefulWidget {
  const GeneralScreen({super.key});

  @override
  State<GeneralScreen> createState() => _GeneralScreenState();
}

class _GeneralScreenState extends State<GeneralScreen> {
  String _selectedCurrency = 'EUR';
  String _selectedLanguage = 'Francais';
  bool _biometricAuth = false;
  bool _darkMode = false;

  final List<String> _currencies = ['EUR', 'USD', 'GBP', 'CHF', 'CAD'];
  final List<String> _languages = ['Francais', 'English', 'Espanol'];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('General'),
        leading: IconButton(
          onPressed: () => context.pop(),
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(AppSpacing.pageHorizontal),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: AppSpacing.md),

              // Section affichage
              _SectionTitle(title: 'AFFICHAGE'),
              const SizedBox(height: AppSpacing.sm),
              Container(
                decoration: BoxDecoration(
                  color: AppColors.backgroundCard,
                  borderRadius: AppRadius.lgRadius,
                  border: Border.all(color: AppColors.border),
                ),
                child: Column(
                  children: [
                    // Mode sombre
                    _ToggleItem(
                      icon: Icons.dark_mode_outlined,
                      title: 'Mode sombre',
                      subtitle: 'Activer le theme sombre',
                      value: _darkMode,
                      onChanged: (val) => setState(() => _darkMode = val),
                    ),
                    const Padding(
                      padding: EdgeInsets.only(left: AppSpacing.pageHorizontal),
                      child: Divider(height: 1),
                    ),
                    // Langue
                    _DropdownItem(
                      icon: Icons.language_outlined,
                      title: 'Langue',
                      value: _selectedLanguage,
                      items: _languages,
                      onChanged: (val) {
                        if (val != null) {
                          setState(() => _selectedLanguage = val);
                        }
                      },
                    ),
                  ],
                ),
              ),

              const SizedBox(height: AppSpacing.lg),

              // Section finances
              _SectionTitle(title: 'FINANCES'),
              const SizedBox(height: AppSpacing.sm),
              Container(
                decoration: BoxDecoration(
                  color: AppColors.backgroundCard,
                  borderRadius: AppRadius.lgRadius,
                  border: Border.all(color: AppColors.border),
                ),
                child: _DropdownItem(
                  icon: Icons.euro_outlined,
                  title: 'Devise',
                  value: _selectedCurrency,
                  items: _currencies,
                  onChanged: (val) {
                    if (val != null) {
                      setState(() => _selectedCurrency = val);
                    }
                  },
                ),
              ),

              const SizedBox(height: AppSpacing.lg),

              // Section securite
              _SectionTitle(title: 'SECURITE'),
              const SizedBox(height: AppSpacing.sm),
              Container(
                decoration: BoxDecoration(
                  color: AppColors.backgroundCard,
                  borderRadius: AppRadius.lgRadius,
                  border: Border.all(color: AppColors.border),
                ),
                child: _ToggleItem(
                  icon: Icons.fingerprint_rounded,
                  title: 'Authentification biometrique',
                  subtitle: 'Utiliser l\'empreinte digitale ou Face ID',
                  value: _biometricAuth,
                  onChanged: (val) => setState(() => _biometricAuth = val),
                ),
              ),

              const SizedBox(height: AppSpacing.lg),

              // Section a propos
              _SectionTitle(title: 'A PROPOS'),
              const SizedBox(height: AppSpacing.sm),
              Container(
                decoration: BoxDecoration(
                  color: AppColors.backgroundCard,
                  borderRadius: AppRadius.lgRadius,
                  border: Border.all(color: AppColors.border),
                ),
                child: Column(
                  children: [
                    _InfoItem(
                      icon: Icons.info_outline_rounded,
                      title: 'Version',
                      value: '1.0.0',
                    ),
                    const Padding(
                      padding: EdgeInsets.only(left: AppSpacing.pageHorizontal),
                      child: Divider(height: 1),
                    ),
                    _InfoItem(
                      icon: Icons.description_outlined,
                      title: 'Conditions d\'utilisation',
                      value: '',
                      onTap: () {},
                    ),
                    const Padding(
                      padding: EdgeInsets.only(left: AppSpacing.pageHorizontal),
                      child: Divider(height: 1),
                    ),
                    _InfoItem(
                      icon: Icons.privacy_tip_outlined,
                      title: 'Politique de confidentialite',
                      value: '',
                      onTap: () {},
                    ),
                  ],
                ),
              ),

              const SizedBox(height: AppSpacing.xxl),
            ],
          ),
        ),
      ),
    );
  }
}

class _SectionTitle extends StatelessWidget {
  const _SectionTitle({required this.title});

  final String title;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(left: AppSpacing.xs),
      child: Text(
        title,
        style: AppTextStyles.labelSmall.copyWith(
          color: AppColors.textSecondary,
          letterSpacing: 1,
        ),
      ),
    );
  }
}

class _ToggleItem extends StatelessWidget {
  const _ToggleItem({
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.value,
    required this.onChanged,
  });

  final IconData icon;
  final String title;
  final String subtitle;
  final bool value;
  final ValueChanged<bool> onChanged;

  @override
  Widget build(BuildContext context) {
    return ListTile(
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
      title: Text(title, style: AppTextStyles.bodyMedium),
      subtitle: Text(subtitle, style: AppTextStyles.bodySmall),
      trailing: Switch(
        value: value,
        onChanged: onChanged,
        activeColor: AppColors.primary,
      ),
    );
  }
}

class _DropdownItem extends StatelessWidget {
  const _DropdownItem({
    required this.icon,
    required this.title,
    required this.value,
    required this.items,
    required this.onChanged,
  });

  final IconData icon;
  final String title;
  final String value;
  final List<String> items;
  final ValueChanged<String?> onChanged;

  @override
  Widget build(BuildContext context) {
    return ListTile(
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
      title: Text(title, style: AppTextStyles.bodyMedium),
      trailing: DropdownButton<String>(
        value: value,
        underline: const SizedBox.shrink(),
        style: AppTextStyles.bodySmall.copyWith(
          color: AppColors.textPrimary,
        ),
        items: items.map((item) {
          return DropdownMenuItem(
            value: item,
            child: Text(item),
          );
        }).toList(),
        onChanged: onChanged,
      ),
    );
  }
}

class _InfoItem extends StatelessWidget {
  const _InfoItem({
    required this.icon,
    required this.title,
    required this.value,
    this.onTap,
  });

  final IconData icon;
  final String title;
  final String value;
  final VoidCallback? onTap;

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
      title: Text(title, style: AppTextStyles.bodyMedium),
      trailing: value.isNotEmpty
          ? Text(value, style: AppTextStyles.bodySmall)
          : const Icon(
              Icons.chevron_right_rounded,
              color: AppColors.textSecondary,
              size: 20,
            ),
    );
  }
}