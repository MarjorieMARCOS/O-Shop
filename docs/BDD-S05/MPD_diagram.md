// Use DBML to define your database structure
// Docs: https://dbml.dbdiagram.io/docs
// https://dbdiagram.io/d
// Symboles relations : https://community.dbdiagram.io/uploads/default/original/2X/6/6d6d9a6313330ff2ee9d94360fd70bfd8b81b076.png
// Shared diagram : https://dbdiagram.io/d/65c5d380ac844320aeca024f

Table brand {
  id INT [primary key]
  name VARCHAR(64)
  footer_order TINYINT(1) 
  created_at TIMESTAMP
  updated_at TIMESTAMP
}

Table type {
  id INT [primary key]
  name VARCHAR(64)
  footer_order TINYINT(1) 
  created_at TIMESTAMP
  updated_at TIMESTAMP
}

Table product {
  id INT [primary key]
  name VARCHAR(64)
  description TEXT
  picture VARCHAR(128)
  price DECIMAL(10,2)
  rate TINYINT(1)
  status TINYINT(1)
  created_at TIMESTAMP
  updated_at TIMESTAMP
  brand_id INT [null]
  category_id INT [null]
  type_id INT [null]
}

Table category {
  id INT [primary key]
  name VARCHAR(64)
  subtitle VARCHAR(128)
  home_order TINYINT(1)
  created_at TIMESTAMP
  updated_at TIMESTAMP
}

Ref: product.brand_id > brand.id

Ref: product.type_id > type.id

Ref: product.category_id > category.id
