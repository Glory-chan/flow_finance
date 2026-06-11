import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/formatters.dart';
import '../../../data/mock/mock_data.dart';
import '../../../shared/widgets/app_button.dart';

/// Ecran d'export des donnees financieres.
class ExportScreen extends StatefulWidget {
  const ExportScreen({super.key});

  @override
  State<ExportScreen> createState() => _ExportScreenState();
}

class _ExportScreenState extends State<ExportScreen> {
  String _selectedFormat = 'CSV';
  String _selectedPeriod = 'Ce mois';
  bool _isExporting = false;

  final List<String> _formats = ['CSV', 'PDF', 'Excel'];
  final List<String> _periods = [
    'Ce mois',
    'Les 3 derniers mois',
    'Les 6 derniers mois',
    'Cette annee',
    'Tout',
  ];

  Future<void> _handleExport() async {
    setState(() => _isExporting = true);
    await Future.delayed(const Duration(seconds: 2));
    if (!mounted) return;
    setState(() => _isExporting = false);
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          'Export $_selectedFormat pour "$_selectedPeriod" effectue !',
        ),
        backgroundColor: AppColors.primary,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Exporter mes donnees'),
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

              // Resume des donnees disponibles
              Container(
                padding: const EdgeInsets.all(AppSpacing.lg),
                decoration: BoxDecoration(
                  gradient: AppColors.primaryGradient,
                  borderRadius: AppRadius.lgRadius,
                ),
                child: Row(
                  children: [
                    const Icon(
                      Icons.bar_chart_rounded,
                      color: Colors.white,
                      size: 40,
                    ),
                    const SizedBox(width: AppSpacing.md),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          '${MockData.transactions.length} transactions',
                          style: AppTextStyles.headlineSmall.copyWith(
                            color: Colors.white,
                          ),
                        ),
                        Text(
                          'disponibles pour l\'export',
                          style: AppTextStyles.bodySmall.copyWith(
                            color: Colors.white.withOpacity(0.8),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),

              const SizedBox(height: AppSpacing.xl),

              // Selection du format
              Text(
                'FORMAT D\'EXPORT',
                style: AppTextStyles.labelSmall.copyWith(
                  color: AppColors.textSecondary,
                  letterSpacing: 1,
                ),
              ),
              const SizedBox(height: AppSpacing.sm),
              Row(
                children: _formats.map((format) {
                  final isSelected = _selectedFormat == format;
                  return Expanded(
                    child: GestureDetector(
                      onTap: () => setState(() => _selectedFormat = format),
                      child: Container(
                        margin: EdgeInsets.only(
                          right: format != _formats.last ? AppSpacing.sm : 0,
                        ),
                        padding: const EdgeInsets.symmetric(
                          vertical: AppSpacing.md,
                        ),
                        decoration: BoxDecoration(
                          color: isSelected
                              ? AppColors.primary
                              : AppColors.backgroundCard,
                          borderRadius: AppRadius.mdRadius,
                          border: Border.all(
                            color: isSelected
                                ? AppColors.primary
                                : AppColors.border,
                          ),
                        ),
                        child: Column(
                          children: [
                            Icon(
                              _formatIcon(format),
                              color: isSelected
                                  ? Colors.white
                                  : AppColors.textSecondary,
                              size: 24,
                            ),
                            const SizedBox(height: AppSpacing.xs),
                            Text(
                              format,
                              style: AppTextStyles.labelLarge.copyWith(
                                color: isSelected
                                    ? Colors.white
                                    : AppColors.textPrimary,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  );
                }).toList(),
              ),

              const SizedBox(height: AppSpacing.xl),

              // Selection de la periode
              Text(
                'PERIODE',
                style: AppTextStyles.labelSmall.copyWith(
                  color: AppColors.textSecondary,
                  letterSpacing: 1,
                ),
              ),
              const SizedBox(height: AppSpacing.sm),
              Container(
                decoration: BoxDecoration(
                  color: AppColors.backgroundCard,
                  borderRadius: AppRadius.lgRadius,
                  border: Border.all(color: AppColors.border),
                ),
                child: Column(
                  children: _periods.asMap().entries.map((entry) {
                    final index = entry.key;
                    final period = entry.value;
                    final isSelected = _selectedPeriod == period;
                    final isLast = index == _periods.length - 1;

                    return Column(
                      children: [
                        ListTile(
                          onTap: () =>
                              setState(() => _selectedPeriod = period),
                          contentPadding: const EdgeInsets.symmetric(
                            horizontal: AppSpacing.md,
                            vertical: AppSpacing.xs,
                          ),
                          title: Text(
                            period,
                            style: AppTextStyles.bodyMedium.copyWith(
                              color: isSelected
                                  ? AppColors.primary
                                  : AppColors.textPrimary,
                              fontWeight: isSelected
                                  ? FontWeight.w600
                                  : FontWeight.w400,
                            ),
                          ),
                          trailing: isSelected
                              ? const Icon(
                                  Icons.check_circle_rounded,
                                  color: AppColors.primary,
                                  size: 20,
                                )
                              : null,
                        ),
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

              const SizedBox(height: AppSpacing.xl),

              // Bouton export
              PrimaryButton(
                label: 'Exporter en $_selectedFormat',
                onPressed: _handleExport,
                isLoading: _isExporting,
                icon: const Icon(
                  Icons.download_rounded,
                  color: Colors.white,
                  size: 20,
                ),
              ),

              const SizedBox(height: AppSpacing.xxl),
            ],
          ),
        ),
      ),
    );
  }

  IconData _formatIcon(String format) {
    switch (format) {
      case 'CSV':
        return Icons.table_chart_outlined;
      case 'PDF':
        return Icons.picture_as_pdf_outlined;
      case 'Excel':
        return Icons.grid_on_outlined;
      default:
        return Icons.file_download_outlined;
    }
  }
}