import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:equatable/equatable.dart';

enum CardType { visa, mastercard, amex }

extension CardTypeExtension on CardType {
  String get label {
    switch (this) {
      case CardType.visa:
        return 'Visa';
      case CardType.mastercard:
        return 'Mastercard';
      case CardType.amex:
        return 'American Express';
    }
  }
}

class CardModel extends Equatable {
  const CardModel({
    required this.id,
    required this.bankName,
    required this.cardHolder,
    required this.cardNumber,
    required this.expiryMonth,
    required this.expiryYear,
    required this.cardType,
    required this.colorStart,
    required this.colorEnd,
    this.balance = 0.0,
    this.isDefault = false,
  });

  final String id;
  final String bankName;
  final String cardHolder;
  final String cardNumber;
  final int expiryMonth;
  final int expiryYear;
  final CardType cardType;
  final int colorStart;
  final int colorEnd;
  final double balance;
  final bool isDefault;

  String get lastFourDigits => cardNumber.length >= 4
      ? cardNumber.substring(cardNumber.length - 4)
      : cardNumber;

  String get formattedExpiry {
    final month = expiryMonth.toString().padLeft(2, '0');
    final year = expiryYear.toString().substring(2);
    return '$month/$year';
  }

  String get maskedNumber =>
      '\u2022\u2022\u2022\u2022  \u2022\u2022\u2022\u2022  \u2022\u2022\u2022\u2022  $lastFourDigits';

  @override
  List<Object?> get props => [
        id, bankName, cardHolder, cardNumber,
        expiryMonth, expiryYear, cardType,
        colorStart, colorEnd, balance, isDefault,
      ];

  /// Convertit pour envoi vers Firestore.
  Map<String, dynamic> toFirestore() {
    return {
      'bankName': bankName,
      'cardHolder': cardHolder,
      'cardNumber': cardNumber,
      'expiryMonth': expiryMonth,
      'expiryYear': expiryYear,
      'cardType': cardType.name,
      'colorStart': colorStart,
      'colorEnd': colorEnd,
      'balance': balance,
      'isDefault': isDefault,
      'createdAt': FieldValue.serverTimestamp(),
    };
  }

  /// Cree depuis un document Firestore.
  factory CardModel.fromFirestore(Map<String, dynamic> data) {
    return CardModel(
      id: data['id'] as String? ?? '',
      bankName: data['bankName'] as String? ?? '',
      cardHolder: data['cardHolder'] as String? ?? '',
      cardNumber: data['cardNumber'] as String? ?? '',
      expiryMonth: data['expiryMonth'] as int? ?? 1,
      expiryYear: data['expiryYear'] as int? ?? 2025,
      cardType: CardType.values.firstWhere(
        (e) => e.name == data['cardType'],
        orElse: () => CardType.visa,
      ),
      colorStart: data['colorStart'] as int? ?? 0xFF2C3E50,
      colorEnd: data['colorEnd'] as int? ?? 0xFF4CA1AF,
      balance: (data['balance'] as num?)?.toDouble() ?? 0.0,
      isDefault: data['isDefault'] as bool? ?? false,
    );
  }
}