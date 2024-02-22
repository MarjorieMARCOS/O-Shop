# Routes

## Sprint 2 : Backoffice

| URL | HTTP Method | Controller | Method | Title | Content | Comment |
|--|--|--|--|--|--|--|
| `/` | `GET` | `MainController` | `home` | Backoffice oShop | Backoffice dashboard | - |
| `/category/list` | `GET`| `CategoryController` | `list` | Liste des catégories | Categories list | - |
| `/category/add` | `GET`| `CategoryController` | `add` | Ajouter une catégorie | Le formulaire pour ajouter une catégorie | - |
| `/category/add` | `POST`| `CategoryController` | `create` | Enregistre une catégorie | Enregistrer dans la BDD une nouvelle catégorie | - |
| `/category/[i:id]/update` | `GET`| `CategoryController` | `update` | Éditer une catégorie | Form to update a category | [i:id] is the category to update |
| `/category/[i:id]/delete` | `GET`| `CategoryController` | `delete` | Supprimer une catégorie | Category delete | [i:id] is the category to delete |
| `/brand/list` | `GET`| `BrandController` | `list` | Liste des marques | Categories list | - |
| `/brand/add` | `GET`| `BrandController` | `add` | Ajouter une marque | Form to add a brand | - |
| `/brand/[i:id]/update` | `GET`| `BrandController` | `update` | Éditer une marque | Form to update a brand | [i:id] is the brand to update |
| `/brand/[i:id]/delete` | `GET`| `BrandController` | `delete` | Supprimer une marque | Brand delete | [i:id] is the brand to delete |
| `/product/list` | `GET`| `ProductController` | `list` | Liste des produits | Categories list | - |
| `/product/add` | `GET`| `ProductController` | `add` | Ajouter un produit | Form to add a product | - |
| `/product/[i:id]/update` | `GET`| `ProductController` | `update` | Éditer un produit | Form to update a product | [i:id] is the product to update |
| `/product/[i:id]/delete` | `GET`| `ProductController` | `delete` | Supprimer un produit | Product delete | [i:id] is the product to delete |
| `/type/list` | `GET`| `TypeController` | `list` | Liste des types | Types list | - |
| `/type/add` | `GET`| `TypeController` | `add` | Ajouter un type | Form to add a type | - |
| `/type/[i:id]/update` | `GET`| `TypeController` | `update` | Éditer un type | Form to update a type | [i:id] is the type to update |
| `/type/[i:id]/delete` | `GET`| `TypeController` | `delete` | Supprimer un type | Type delete | [i:id] is the type to delete |
| `/user/list` | `GET`| `UserController` | `list` | Liste des utilisateurs | Users list | - |
| `/user/add` | `GET`| `UserController` | `add` | Ajouter un utilisateur | Form to add a user | - |
| `/user/[i:id]/update` | `GET`| `UserController` | `update` | Éditer un utilisateur | Form to update a user | [i:id] is the user to update |
| `/user/[i:id]/delete` | `GET`| `UserController` | `delete` | Supprimer un utilisateur | User delete | [i:id] is the user to delete |