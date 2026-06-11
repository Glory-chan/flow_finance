import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_styles.dart';

/// Ecran de gestion des notifications.
class NotificationsScreen extends StatefulWidget {
  const NotificationsScreen({super.key});

  @override
  State<NotificationsScreen> createState() => _NotificationsScreenState();
}

class _NotificationsScreenState extends State<NotificationsScreen> {
  // Etats des toggles de notification
  bool _transactionAlerts = true;
  bool _weeklyReport = true;
  bool _monthlyReport = false;
  bool _securityAlerts = true;
  bool _promotions = false;
  bool _budgetAlerts = true;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Notifications'),
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

              // Section transactions
              _NotificationSection(
                title: 'TRANSACTIONS',
                items: [
                  _NotificationItem(
                    icon: Icons.swap_horiz_rounded,
                    title: 'Alertes de transaction',
                    subtitle: 'Notifie a chaque nouvelle transaction',
                    value: _transactionAlerts,
                    onChanged: (val) =>
                        setState(() => _transactionAlerts = val),
                  ),
                  _NotificationItem(
                    icon: Icons.account_balance_wallet_outlined,
                    title: 'Alertes budget',
                    subtitle: 'Notifie quand vous depassez votre budget',
                    value: _budgetAlerts,
                    onChanged: (val) =>
                        setState(() => _budgetAlerts = val),
                  ),
                ],
              ),

              const SizedBox(height: AppSpacing.lg),

              // Section rapports
              _NotificationSection(
                title: 'RAPPORTS',
                items: [
                  _NotificationItem(
                    icon: Icons.bar_chart_rounded,
                    title: 'Rapport hebdomadaire',
                    subtitle: 'Resume de vos depenses chaque semaine',
                    value: _weeklyReport,
                    onChanged: (val) =>
                        setState(() => _weeklyReport = val),
                  ),
                  _NotificationItem(
                    icon: Icons.calendar_month_outlined,
                    title: 'Rapport mensuel',
                    subtitle: 'Bilan complet de votre mois',
                    value: _monthlyReport,
                    onChanged: (val) =>
                        setState(() => _monthlyReport = val),
                  ),
                ],
              ),

              const SizedBox(height: AppSpacing.lg),

              // Section securite
              _NotificationSection(
                title: 'SECURITE',
                items: [
                  _NotificationItem(
                    icon: Icons.security_outlined,
                    title: 'Alertes de securite',
                    subtitle: 'Connexions suspectes et activites inhabituelles',
                    value: _securityAlerts,
                    onChanged: (val) =>
                        setState(() => _securityAlerts = val),
                  ),
                ],
              ),

              const SizedBox(height: AppSpacing.lg),

              // Section autres
              _NotificationSection(
                title: 'AUTRES',
                items: [
                  _NotificationItem(
                    icon: Icons.campaign_outlined,
                    title: 'Promotions et offres',
                    subtitle: 'Nouvelles fonctionnalites et offres speciales',
                    value: _promotions,
                    onChanged: (val) =>
                        setState(() => _promotions = val),
                  ),
                ],
              ),

              const SizedBox(height: AppSpacing.xxl),
            ],
          ),
        ),
      ),
    );
  }
}

/// Section de notifications avec titre et liste d'items.
class _NotificationSection extends StatelessWidget {
  const _NotificationSection({
    required this.title,
    required this.items,
  });

  final String title;
  final List<_NotificationItem> items;

  @override
  Widget build(BuildContext context) {
    return Column(
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
    );
  }
}

/// Un item de notification avec toggle switch.
class _NotificationItem extends StatelessWidget {
  const _NotificationItem({
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