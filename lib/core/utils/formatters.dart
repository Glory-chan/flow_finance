import 'package:intl/intl.dart';

class AppFormatters {
  AppFormatters._();

  static String formatCurrency(double amount) {
    final formatter = NumberFormat.currency(
      locale: 'fr_FR',
      symbol: '\u20ac',
      decimalDigits: 2,
    );
    return formatter.format(amount);
  }

  static String formatTransactionAmount(double amount) {
    final absAmount = amount.abs();
    final formatted = NumberFormat.currency(
      locale: 'fr_FR',
      symbol: '\u20ac',
      decimalDigits: 2,
    ).format(absAmount);
    return amount >= 0 ? '+$formatted' : '-$formatted';
  }

  static String formatDateShort(DateTime date) {
    return DateFormat('d MMM', 'fr_FR').format(date);
  }

  static String formatTransactionDate(DateTime date) {
    final now = DateTime.now();
    final today = DateTime(now.year, now.month, now.day);
    final yesterday = today.subtract(const Duration(days: 1));
    final transactionDay = DateTime(date.year, date.month, date.day);
    if (transactionDay == today) return 'Aujourd\'hui';
    if (transactionDay == yesterday) return 'Hier';
    return formatDateShort(date);
  }

  static String formatPercentage(double value) {
    return NumberFormat.percentPattern('fr_FR').format(value);
  }

  static String getMonthAbbr(int month) {
    final date = DateTime(2024, month);
    return DateFormat('MMM', 'fr_FR').format(date);
  }
}