import '../constants/app_strings.dart';

class AppValidators {
  AppValidators._();

  static String? validateEmail(String? value) {
    if (value == null || value.trim().isEmpty) {
      return AppStrings.errorEmailRequired;
    }
    final emailRegex = RegExp(r'^[^@\s]+@[^@\s]+\.[^@\s]+$');
    if (!emailRegex.hasMatch(value.trim())) {
      return AppStrings.errorEmailInvalid;
    }
    return null;
  }

  static String? validatePassword(String? value) {
    if (value == null || value.isEmpty) {
      return AppStrings.errorPasswordRequired;
    }
    if (value.length < 8) {
      return AppStrings.errorPasswordTooShort;
    }
    return null;
  }

  static String? validateConfirmPassword(String? value, String password) {
    if (value == null || value.isEmpty) {
      return AppStrings.errorPasswordRequired;
    }
    if (value != password) {
      return AppStrings.errorPasswordMismatch;
    }
    return null;
  }
}