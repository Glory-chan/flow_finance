import 'package:flutter/material.dart';

class AppColors {
  AppColors._();

  static const Color primary = Color(0xFF2ECC71);
  static const Color primaryDark = Color(0xFF27AE60);
  static const Color primaryLight = Color(0xFFE8F8F0);

  static const Color background = Color(0xFFFFFFFF);
  static const Color backgroundSecondary = Color(0xFFF8F9FA);
  static const Color backgroundCard = Color(0xFFFFFFFF);

  static const Color textPrimary = Color(0xFF1A1A2E);
  static const Color textSecondary = Color(0xFF6B7280);
  static const Color textHint = Color(0xFFADB5BD);
  static const Color textOnPrimary = Color(0xFFFFFFFF);

  static const Color expense = Color(0xFFE74C3C);
  static const Color expenseLight = Color(0xFFFDECEA);
  static const Color income = Color(0xFF2ECC71);
  static const Color incomeLight = Color(0xFFE8F8F0);

  static const Color border = Color(0xFFE9ECEF);
  static const Color borderLight = Color(0xFFF1F3F4);
  static const Color divider = Color(0xFFF0F0F0);

  static const Color shadow = Color(0x1A000000);
  static const Color navBackground = Color(0xFFFFFFFF);
  static const Color navInactive = Color(0xFFADB5BD);
  static const Color navActive = Color(0xFF2ECC71);

  static const LinearGradient primaryGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [Color(0xFF2ECC71), Color(0xFF27AE60)],
  );

  static const LinearGradient headerGradient = LinearGradient(
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
    colors: [Color(0xFF2ECC71), Color(0xFF1DB954)],
  );
}