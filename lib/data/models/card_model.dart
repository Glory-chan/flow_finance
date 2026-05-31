import 'package:equatable/equatable.dart';

enum CardType { visa, mastercard, amex }

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

  String get lastFourDigits => cardNumber.substring(cardNumber.length - 4);

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
}