import '../models/transaction_model.dart';
import '../models/card_model.dart';
import '../models/user_model.dart';

class MockData {
  MockData._();

  static const UserModel currentUser = UserModel(
    id: 'user_001',
    email: 'robert.martin@email.com',
    firstName: 'Robert',
    lastName: 'Martin',
    totalBalance: 4790.05,
  );

  static final List<TransactionModel> transactions = [
    TransactionModel(
      id: 'txn_001',
      title: 'Electricite',
      subtitle: 'ENEDIS',
      amount: -75.00,
      date: DateTime.now().subtract(const Duration(hours: 3)),
      category: TransactionCategory.energy,
    ),
    TransactionModel(
      id: 'txn_002',
      title: 'Shopping',
      subtitle: 'Zara',
      amount: -135.00,
      date: DateTime.now().subtract(const Duration(hours: 6)),
      category: TransactionCategory.shopping,
    ),
    TransactionModel(
      id: 'txn_003',
      title: 'Virement',
      subtitle: 'BoursoBank',
      amount: 450.00,
      date: DateTime.now().subtract(const Duration(days: 1)),
      category: TransactionCategory.transfer,
    ),
    TransactionModel(
      id: 'txn_004',
      title: 'Courses',
      subtitle: 'Carrefour',
      amount: -62.40,
      date: DateTime.now().subtract(const Duration(days: 1)),
      category: TransactionCategory.food,
    ),
    TransactionModel(
      id: 'txn_005',
      title: 'Salaire',
      subtitle: 'Employeur SA',
      amount: 2800.00,
      date: DateTime.now().subtract(const Duration(days: 2)),
      category: TransactionCategory.salary,
    ),
    TransactionModel(
      id: 'txn_006',
      title: 'Transport',
      subtitle: 'RATP',
      amount: -19.90,
      date: DateTime.now().subtract(const Duration(days: 3)),
      category: TransactionCategory.transport,
    ),
    TransactionModel(
      id: 'txn_007',
      title: 'Cinema',
      subtitle: 'UGC',
      amount: -13.50,
      date: DateTime.now().subtract(const Duration(days: 4)),
      category: TransactionCategory.entertainment,
    ),
    TransactionModel(
      id: 'txn_008',
      title: 'Pharmacie',
      subtitle: 'Pharmacie du Centre',
      amount: -28.60,
      date: DateTime.now().subtract(const Duration(days: 5)),
      category: TransactionCategory.health,
    ),
  ];

  static final List<CardModel> cards = [
    CardModel(
      id: 'card_001',
      bankName: 'Visa',
      cardHolder: 'Robert Martin',
      cardNumber: '4532123456781234',
      expiryMonth: 9,
      expiryYear: 2027,
      cardType: CardType.visa,
      colorStart: 0xFF2C3E50,
      colorEnd: 0xFF4CA1AF,
      balance: 2340.50,
      isDefault: true,
    ),
    CardModel(
      id: 'card_002',
      bankName: 'Caisse d\'Epargne',
      cardHolder: 'Robert Martin',
      cardNumber: '5214567890123456',
      expiryMonth: 3,
      expiryYear: 2026,
      cardType: CardType.mastercard,
      colorStart: 0xFFB8860B,
      colorEnd: 0xFFDAA520,
      balance: 1890.20,
      isDefault: false,
    ),
    CardModel(
      id: 'card_003',
      bankName: 'LCL',
      cardHolder: 'Robert Martin',
      cardNumber: '5412345678901234',
      expiryMonth: 12,
      expiryYear: 2025,
      cardType: CardType.mastercard,
      colorStart: 0xFF1A1A1A,
      colorEnd: 0xFF363636,
      balance: 559.35,
      isDefault: false,
    ),
  ];

  static const Map<TransactionCategory, double> expensesByCategory = {
    TransactionCategory.food: 312.40,
    TransactionCategory.shopping: 245.00,
    TransactionCategory.transport: 89.70,
    TransactionCategory.energy: 75.00,
    TransactionCategory.entertainment: 54.50,
    TransactionCategory.health: 28.60,
  };

  static const List<double> balanceHistory = [
    3200.0,
    3850.0,
    3600.0,
    4100.0,
    4500.0,
    4790.05,
  ];

  static const List<String> monthLabels = [
    'Dec', 'Jan', 'Fev', 'Mar', 'Avr', 'Mai',
  ];
}