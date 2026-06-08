import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../features/auth/screens/welcome_screen.dart';
import '../../features/auth/screens/login_screen.dart';
import '../../features/auth/screens/register_screen.dart';
import '../../features/auth/screens/otp_screen.dart';
import '../../features/home/screens/home_screen.dart';
import '../../features/cards/screens/cards_screen.dart';
import '../../features/analytics/screens/analytics_screen.dart';
import '../../features/settings/screens/settings_screen.dart';
import '../../shared/widgets/main_scaffold.dart';
import '../../services/auth_service.dart';

class AppRoutes {
  AppRoutes._();

  static const String welcome = '/';
  static const String login = '/login';
  static const String register = '/register';
  static const String otp = '/otp';
  static const String home = '/home';
  static const String cards = '/cards';
  static const String analytics = '/analytics';
  static const String settings = '/settings';
}

final GoRouter appRouter = GoRouter(
  initialLocation: AppRoutes.welcome,
  redirect: (context, state) {
    final isLoggedIn = AuthService.currentUser != null;
    final uri = state.uri.toString();
    final isAuthRoute = uri == AppRoutes.welcome ||
        uri == AppRoutes.login ||
        uri == AppRoutes.register ||
        uri.startsWith(AppRoutes.otp);

    // Si non connecte et sur une route protegee -> welcome
    if (!isLoggedIn && !isAuthRoute) return AppRoutes.welcome;

    // Si connecte et sur une route auth -> home
    if (isLoggedIn && isAuthRoute) return AppRoutes.home;

    return null;
  },
  routes: [
    GoRoute(
      path: AppRoutes.welcome,
      builder: (context, state) => const WelcomeScreen(),
    ),
    GoRoute(
      path: AppRoutes.login,
      builder: (context, state) => const LoginScreen(),
    ),
    GoRoute(
      path: AppRoutes.register,
      builder: (context, state) => const RegisterScreen(),
    ),
    GoRoute(
      path: AppRoutes.otp,
      builder: (context, state) {
        final email = state.uri.queryParameters['email'] ?? '';
        return OtpScreen(email: email);
      },
    ),
    ShellRoute(
      builder: (context, state, child) => MainScaffold(child: child),
      routes: [
        GoRoute(
          path: AppRoutes.home,
          builder: (context, state) => const HomeScreen(),
        ),
        GoRoute(
          path: AppRoutes.analytics,
          builder: (context, state) => const AnalyticsScreen(),
        ),
        GoRoute(
          path: AppRoutes.cards,
          builder: (context, state) => const CardsScreen(),
        ),
        GoRoute(
          path: AppRoutes.settings,
          builder: (context, state) => const SettingsScreen(),
        ),
      ],
    ),
  ],
  errorBuilder: (context, state) => Scaffold(
    body: Center(
      child: Text('Page introuvable : ${state.uri}'),
    ),
  ),
);