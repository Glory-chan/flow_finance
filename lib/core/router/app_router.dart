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
import '../../features/settings/screens/profile_screen.dart';
import '../../features/settings/screens/security_screen.dart';
import '../../features/settings/screens/notifications_screen.dart';
import '../../features/settings/screens/general_screen.dart';
import '../../features/settings/screens/export_screen.dart';
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
  static const String profile = '/profile';
  static const String security = '/security';
  static const String notifications = '/notifications';
  static const String general = '/general';
  static const String export = '/export';
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

    if (!isLoggedIn && !isAuthRoute) return AppRoutes.welcome;
    if (isLoggedIn && isAuthRoute) return AppRoutes.home;

    return null;
  },
  routes: [
    // Routes d'authentification
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

    // Routes settings sans BottomNav
    GoRoute(
      path: AppRoutes.profile,
      builder: (context, state) => const ProfileScreen(),
    ),
    GoRoute(
      path: AppRoutes.security,
      builder: (context, state) => const SecurityScreen(),
    ),
    GoRoute(
      path: AppRoutes.notifications,
      builder: (context, state) => const NotificationsScreen(),
    ),
    GoRoute(
      path: AppRoutes.general,
      builder: (context, state) => const GeneralScreen(),
    ),
    GoRoute(
      path: AppRoutes.export,
      builder: (context, state) => const ExportScreen(),
    ),

    // Shell route avec BottomNav
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