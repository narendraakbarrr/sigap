class CategoryModel {
  final int id;
  final String name;
  final String? icon;

  CategoryModel({required this.id, required this.name, this.icon});

  factory CategoryModel.fromJson(Map<String, dynamic> json) => CategoryModel(
    id:   json['id'] is int ? json['id'] : int.parse(json['id'].toString()),
    name: (json['name'] ?? '').toString(),
    icon: json['icon']?.toString(),
  );
}