import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/validators.dart';
import '../../../services/auth_service.dart';
import '../../../shared/widgets/app_button.dart';
import '../../../shared/widgets/app_text_field.dart';

/// Ecran de securite : changement de mot de passe et reinitialisation.
class SecurityScreen extends StatefulWidget {
  const SecurityScreen({super.key});

  @override
  State<SecurityScreen> createState() => _SecurityScreenState();
}

class _SecurityScreenState extends State<SecurityScreen> {
  final _formKey = GlobalKey<FormState>();
  final _newPasswordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  bool _isLoading = false;
  bool _isResetLoading = false;

  @override
  void dispose() {
    _newPasswordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  Future<void> _handleChangePassword() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _isLoading = true);

    final error = await AuthService.updatePassword(
      _newPasswordController.text,
    );

    if (!mounted) return;
    setState(() => _isLoading = false);

    if (error != null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(error), backgroundColor: AppColors.expense),
      );
      return;
    }

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Mot de passe mis a jour avec succes !'),
        backgroundColor: AppColors.primary,
      ),
    );

    _newPasswordController.clear();
    _confirmPasswordController.clear();
  }

  Future<void> _handleResetPassword() async {
    setState(() => _isResetLoading = true);

    final error = await AuthService.sendPasswordResetEmail(
      AuthService.currentUserEmail,
    );

    if (!mounted) return;
    setState(() => _isResetLoading = false);

    if (error != null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(error), backgroundColor: AppColors.expense),
      );
      return;
    }

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Email de reinitialisation envoye !'),
        backgroundColor: AppColors.primary,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Securite'),
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

              // Section changer mot de passe
              Text(
                'CHANGER LE MOT DE PASSE',
                style: AppTextStyles.labelSmall.copyWith(
                  color: AppColors.textSecondary,
                  letterSpacing: 1,
                ),
              ),

              const SizedBox(height: AppSpacing.md),

              Container(
                padding: const EdgeInsets.all(AppSpacing.lg),
                decoration: BoxDecoration(
                  color: AppColors.backgroundCard,
                  borderRadius: AppRadius.lgRadius,
                  border: Border.all(color: AppColors.border),
                ),
                child: Form(
                  key: _formKey,
                  child: Column(
                    children: [
                      AppTextField(
                        controller: _newPasswordController,
                        label: 'Nouveau mot de passe',
                        hint: 'Minimum 8 caracteres',
                        prefixIcon: Icons.lock_outlined,
                        isPassword: true,
                        textInputAction: TextInputAction.next,
                        validator: AppValidators.validatePassword,
                      ),
                      const SizedBox(height: AppSpacing.md),
                      AppTextField(
                        controller: _confirmPasswordController,
                        label: 'Confirmer le mot de passe',
                        hint: 'Repetez le mot de passe',
                        prefixIcon: Icons.lock_outlined,
                        isPassword: true,
                        textInputAction: TextInputAction.done,
                        validator: (value) =>
                            AppValidators.validateConfirmPassword(
                          value,
                          _newPasswordController.text,
                        ),
                        onSubmitted: (_) => _handleChangePassword(),
                      ),
                      const SizedBox(height: AppSpacing.lg),
                      PrimaryButton(
                        label: 'Mettre a jour',
                        onPressed: _handleChangePassword,
                        isLoading: _isLoading,
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: AppSpacing.xl),

              // Section reinitialisation par email
              Text(
                'REINITIALISATION',
                style: AppTextStyles.labelSmall.copyWith(
                  color: AppColors.textSecondary,
                  letterSpacing: 1,
                ),
              ),

              const SizedBox(height: AppSpacing.md),

              Container(
                padding: const EdgeInsets.all(AppSpacing.lg),
                decoration: BoxDecoration(
                  color: AppColors.backgroundCard,
                  borderRadius: AppRadius.lgRadius,
                  border: Border.all(color: AppColors.border),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Recevoir un email de reinitialisation',
                      style: AppTextStyles.bodyMedium,
                    ),
                    const SizedBox(height: AppSpacing.xs),
                    Text(
                      'Un lien sera envoye a ${AuthService.currentUserEmail}',
                      style: AppTextStyles.bodySmall,
                    ),
                    const SizedBox(height: AppSpacing.lg),
                    PrimaryButton(
                      label: 'Envoyer le lien',
                      onPressed: _handleResetPassword,
                      isLoading: _isResetLoading,
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