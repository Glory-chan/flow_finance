import 'dart:async';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/router/app_router.dart';
import '../../../services/auth_service.dart';
import '../../../shared/widgets/app_button.dart';

/// Ecran de verification d'email apres inscription.
/// L'utilisateur doit cliquer sur le lien recu par email
/// puis appuyer sur "J'ai verifie mon email" pour continuer.
class OtpScreen extends StatefulWidget {
  const OtpScreen({super.key, required this.email});

  final String email;

  @override
  State<OtpScreen> createState() => _OtpScreenState();
}

class _OtpScreenState extends State<OtpScreen> {
  bool _isChecking = false;
  bool _isResending = false;
  int _resendSeconds = 60;
  Timer? _resendTimer;

  @override
  void initState() {
    super.initState();
    _startResendTimer();
  }

  @override
  void dispose() {
    _resendTimer?.cancel();
    super.dispose();
  }

  /// Demarre le compte a rebours pour le renvoi de l'email.
  void _startResendTimer() {
    _resendSeconds = 60;
    _resendTimer?.cancel();
    _resendTimer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (!mounted) {
        timer.cancel();
        return;
      }
      setState(() {
        if (_resendSeconds > 0) {
          _resendSeconds--;
        } else {
          timer.cancel();
        }
      });
    });
  }

  /// Verifie si l'email a ete confirme dans Firebase.
  Future<void> _handleCheckVerification() async {
    setState(() => _isChecking = true);

    final isVerified = await AuthService.checkEmailVerified();

    if (!mounted) return;
    setState(() => _isChecking = false);

    if (isVerified) {
      // Email confirme -> aller vers l'accueil
      context.go(AppRoutes.home);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text(
            'Email pas encore verifie. Verifiez votre boite mail.',
          ),
          backgroundColor: AppColors.expense,
        ),
      );
    }
  }

  /// Renvoie l'email de verification.
  Future<void> _handleResend() async {
    if (_resendSeconds > 0) return;

    setState(() => _isResending = true);

    final error = await AuthService.resendVerificationEmail();

    if (!mounted) return;
    setState(() => _isResending = false);

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
        content: Text('Email de verification renvoye !'),
        backgroundColor: AppColors.primary,
      ),
    );

    _startResendTimer();
  }

  /// Masque partiellement l'email pour l'affichage.
  String _maskEmail(String email) {
    final parts = email.split('@');
    if (parts.length != 2) return email;
    final local = parts[0];
    final masked = local.length > 3
        ? '${local.substring(0, 3)}***'
        : '***';
    return '$masked@${parts[1]}';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        leading: IconButton(
          onPressed: () => context.pop(),
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
        ),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(
            horizontal: AppSpacing.pageHorizontal,
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const SizedBox(height: AppSpacing.xl),

              // Icone email
              Container(
                width: 80,
                height: 80,
                decoration: const BoxDecoration(
                  color: AppColors.primaryLight,
                  shape: BoxShape.circle,
                ),
                child: const Icon(
                  Icons.mark_email_read_outlined,
                  color: AppColors.primary,
                  size: 40,
                ),
              ),

              const SizedBox(height: AppSpacing.lg),

              // Titre
              Text(
                'Verifiez votre email',
                style: AppTextStyles.headlineLarge,
                textAlign: TextAlign.center,
              ),

              const SizedBox(height: AppSpacing.sm),

              // Description
              Text(
                'Un email de verification a ete envoye a',
                style: AppTextStyles.bodyMedium.copyWith(
                  color: AppColors.textSecondary,
                ),
                textAlign: TextAlign.center,
              ),

              const SizedBox(height: AppSpacing.xs),

              // Email masque
              Text(
                _maskEmail(widget.email),
                style: AppTextStyles.bodyMedium.copyWith(
                  color: AppColors.primary,
                  fontWeight: FontWeight.w600,
                ),
                textAlign: TextAlign.center,
              ),

              const SizedBox(height: AppSpacing.sm),

              Text(
                'Cliquez sur le lien dans l\'email\npuis revenez ici pour continuer.',
                style: AppTextStyles.bodySmall.copyWith(
                  color: AppColors.textSecondary,
                ),
                textAlign: TextAlign.center,
              ),

              const SizedBox(height: AppSpacing.xxl),

              // Etapes visuelles
              _VerificationSteps(),

              const SizedBox(height: AppSpacing.xxl),

              // Bouton principal
              PrimaryButton(
                label: 'J\'ai verifie mon email',
                onPressed: _handleCheckVerification,
                isLoading: _isChecking,
              ),

              const SizedBox(height: AppSpacing.lg),

              // Lien de renvoi avec compte a rebours
              GestureDetector(
                onTap: _resendSeconds == 0 ? _handleResend : null,
                child: _isResending
                    ? const SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: AppColors.primary,
                        ),
                      )
                    : RichText(
                        text: TextSpan(
                          style: AppTextStyles.bodySmall,
                          children: [
                            TextSpan(
                              text: _resendSeconds > 0
                                  ? 'Renvoyer dans $_resendSeconds s'
                                  : 'Renvoyer l\'email',
                              style: TextStyle(
                                color: _resendSeconds == 0
                                    ? AppColors.primary
                                    : AppColors.textSecondary,
                                fontWeight: _resendSeconds == 0
                                    ? FontWeight.w600
                                    : FontWeight.w400,
                              ),
                            ),
                          ],
                        ),
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

/// Widget affichant les 3 etapes de verification visuellement.
class _VerificationSteps extends StatelessWidget {
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
        children: [
          _StepItem(
            number: '1',
            text: 'Ouvrez votre boite mail',
            icon: Icons.inbox_outlined,
          ),
          const SizedBox(height: AppSpacing.md),
          _StepItem(
            number: '2',
            text: 'Cliquez sur le lien de verification',
            icon: Icons.link_rounded,
          ),
          const SizedBox(height: AppSpacing.md),
          _StepItem(
            number: '3',
            text: 'Revenez ici et appuyez sur le bouton',
            icon: Icons.check_circle_outline_rounded,
          ),
        ],
      ),
    );
  }
}

/// Une etape individuelle dans le widget des etapes.
class _StepItem extends StatelessWidget {
  const _StepItem({
    required this.number,
    required this.text,
    required this.icon,
  });

  final String number;
  final String text;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          width: 32,
          height: 32,
          decoration: const BoxDecoration(
            color: AppColors.primaryLight,
            shape: BoxShape.circle,
          ),
          child: Center(
            child: Text(
              number,
              style: AppTextStyles.labelLarge.copyWith(
                color: AppColors.primary,
                fontWeight: FontWeight.w700,
              ),
            ),
          ),
        ),
        const SizedBox(width: AppSpacing.md),
        Icon(icon, color: AppColors.primary, size: 20),
        const SizedBox(width: AppSpacing.sm),
        Expanded(
          child: Text(text, style: AppTextStyles.bodySmall),
        ),
      ],
    );
  }
}