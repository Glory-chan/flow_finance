import 'package:equatable/equatable.dart';

class UserModel extends Equatable {
  const UserModel({
    required this.id,
    required this.email,
    required this.firstName,
    required this.lastName,
    this.avatarUrl,
    this.totalBalance = 0.0,
  });

  final String id;
  final String email;
  final String firstName;
  final String lastName;
  final String? avatarUrl;
  final double totalBalance;

  String get fullName => '$firstName $lastName';
  String get displayName => firstName;

  @override
  List<Object?> get props =>
      [id, email, firstName, lastName, avatarUrl, totalBalance];
}