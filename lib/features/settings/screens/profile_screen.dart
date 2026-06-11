import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_styles.dart';
import '../../../core/utils/validators.dart';
import '../../../services/auth_service.dart';
import '../../../shared/widgets/app_button.dart';
import '../../../shared/widgets/app_text_field.dart';

/// Ecran de modification du profil utilisateur.
class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _firstNameController;
  late final TextEditingController _lastNameController;
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    // Pre-remplir avec les infos actuelles
    final fullName = AuthService.currentUserFullName;
    final parts = fullName.split(' ');
    _firstNameController = TextEditingController(
      text: parts.isNotEmpty ? parts.first : '',
    );
    _lastNameController = TextEditingController(
      text: parts.length > 1 ? parts.sublist(1).join(' ') : '',
    );
  }

  @override
  void dispose() {
    _firstNameController.dispose();
    _lastNameController.dispose();
    super.dispose();
  }

  Future<void> _handleSave() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _isLoading = true);

    final fullName =
        '${_firstNameController.text.trim()} ${_lastNameController.text.trim()}';
    final error = await AuthService.updateDisplayName(fullName);

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
        content: Text('Profil mis a jour avec succes !'),
        backgroundColor: AppColors.primary,
      ),
    );

    context.pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Mon profil'),
        leading: IconButton(
          onPressed: () => context.pop(),
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(AppSpacing.pageHorizontal),
          child: Form(
            key: _formKey,
            child: Column(
              children: [
                const SizedBox(height: AppSpacing.lg),

                // Avatar avec initiales
                Container(
                  width: 80,
                  height: 80,
                  decoration: const BoxDecoration(
                    gradient: AppColors.primaryGradient,
                    shape: BoxShape.circle,
                  ),
                  child: Center(
                    child: Text(
                      AuthService.currentUserFullName
                          .split(' ')
                          .map((w) => w.isNotEmpty ? w[0] : '')
                          .take(2)
                          .join(),
                      style: AppTextStyles.headlineLarge.copyWith(
                        color: Colors.white,
                      ),
                    ),
                  ),
                ),

                const SizedBox(height: AppSpacing.xs),

                Text(
                  AuthService.currentUserEmail,
                  style: AppTextStyles.bodySmall,
                ),

                const SizedBox(height: AppSpacing.xl),

                AppTextField(
                  controller: _firstNameController,
                  label: 'Prenom',
                  hint: 'Jean',
                  prefixIcon: Icons.person_outline,
                  textInputAction: TextInputAction.next,
                  validator: (value) =>
                      AppValidators.validateRequired(value, 'Le prenom'),
                ),

                const SizedBox(height: AppSpacing.md),

                AppTextField(
                  controller: _lastNameController,
                  label: 'Nom',
                  hint: 'Dupont',
                  prefixIcon: Icons.person_outline,
                  textInputAction: TextInputAction.done,
                  validator: (value) =>
                      AppValidators.validateRequired(value, 'Le nom'),
                  onSubmitted: (_) => _handleSave(),
                ),

                const SizedBox(height: AppSpacing.xl),

                PrimaryButton(
                  label: 'Enregistrer les modifications',
                  onPressed: _handleSave,
                  isLoading: _isLoading,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}