import 'package:equatable/equatable.dart';

enum TransactionCategory {
  food,
  transport,
  shopping,
  energy,
  entertainment,
  health,
  salary,
  transfer,
  other,
}

extension TransactionCategoryExtension on TransactionCategory {
  String get label {
    switch (this) {
      case TransactionCategory.food:
        return 'Alimentation';
      case TransactionCategory.transport:
        return 'Transport';
      case TransactionCategory.shopping:
        return 'Shopping';
      case TransactionCategory.energy:
        return 'Energie';
      case TransactionCategory.entertainment:
        return 'Loisirs';
      case TransactionCategory.health:
        return 'Sante';
      case TransactionCategory.salary:
        return 'Salaire';
      case TransactionCategory.transfer:
        return 'Virement';
      case TransactionCategory.other:
        return 'Autre';
    }
  }
}

class TransactionModel extends Equatable {
  const TransactionModel({
    required this.id,
    required this.title,
    required this.subtitle,
    required this.amount,
    required this.date,
    required this.category,
  });

  final String id;
  final String title;
  final String subtitle;
  final double amount;
  final DateTime date;
  final TransactionCategory category;

  bool get isExpense => amount < 0;
  bool get isIncome => amount > 0;

  @override
  List<Object?> get props => [id, title, subtitle, amount, date, category];
}