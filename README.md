
# NetPrime +

NetPrime + est une future plateforme de VOD (Video On Demand) qui permettra de visionner des films en streaming. Elle se veut innovante et simple d'utilisation.
Elle s'axe autour d'un champ de recherche hyper puissant qui permettra à nos clients de trouver facilement les films qu'ils souhaitent visionner.

Le projet est fait en MVC.

# Stacks utilisées  

PHP, MYSQL, CSS avec Bootsrap et HTML.

## Installation

1. Installer les dépendances avec `composer install`.
2. Importer la base de données `docs/database.sql`.
3. Créer le fichier `app/config.ini` à partir du fichier `app/config.ini.dist` et le compléter avec les informations de connexion à la base de données.
4. Installer `https://packagist.org/packages/benoclock/alto-dispatcher`.


## Models

Création des models afin de récupérer les données de la base de données.

- `Product` : (extends de `CoreModel`) qui permettra de récupérer les informations d'un produit.
- `brand` : (extends de `CoreModel`) qui permettra de récupérer les informations d'une marque.
- `Category` : (extends de `CoreModel`) qui permettra de récupérer les informations d'une catégorie.
- `Tag` : (extends de `CoreModel`) qui permettra de récupérer les informations d'un tag.
- `Type` : (extends de `CoreModel`) qui permettra de récupérer les informations d'un type.
- `AppUser` : (extends de `CoreModel`) qui permettra de récupérer les informations d'un utilisateur du site.
- `CoreModel` : qui est le parent des autres models.

## Controlleurs

Création des controllers : 

- `CoreController` = qui permet d'afficher les tpl avec la méthode `show`, gère aussi les ACL, les permissions avec les token, les messages d'alerte et de validation.
- `CrudController` = (extends de `CoreController`) contient les méthodes abstraites `list`, `add`, `update` et `delete`.
- `MainController` = (extends de `CoreController`) qui permettra d'afficher la page home page.
- `ErrorController` = (extends de `CoreController`) qui permet d'afficher les pages d'error.
- `ProductController` = (extends de `CrudController`) qui permet d'afficher les routes des produits.
- `BrandController` = (extends de `CrudController`) qui permet d'afficher les routes des marques.
- `TypeController` = (extends de `CrudController`) qui permet d'afficher les routes des types.
- `TagController` = (extends de `CrudController`) qui permet d'afficher les routes des tags.
- `CategoryController` = (extends de `CrudController`) qui permet d'afficher les routes des categorie.
