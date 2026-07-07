class CategoryModel {
  final int id;
  final String name;
  final String? icon;

  CategoryModel({required this.id, required this.name, this.icon});

  factory CategoryModel.fromJson(Map<String, dynamic> json) => CategoryModel(
    id:   json['id'],
    name: json['name'],
    icon: json['icon'],
  );
}