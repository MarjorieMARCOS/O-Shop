# Dictionnaire de données

## Produits (`product`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, AUTO_INCREMENT, UNSIGNED|L'identifiant de notre produit|
|name|VARCHAR(64)|NOT NULL|Le nom du produit|
|description|TEXT|NULL|Description du produit|
|picture|VARCHAR(128)|NULL|Photo du produit|
|price|DECIMAL(10,2)|NOT NULL, DEFAULT 0|Prix du produit|
|rate|TINYINT(1)|NOT NULL, DEFAULT 0|Moyenne des notes sur le produit (de 1 à 5)|
|status|tinyint(1)|NOT NULL DEFAULT 0|status du produit (1=dispo, 2=pas dispo)|
|created_at|TIMESTAMP|NOT NULL DEFAULT CURRENT_TIMESTAMP|date de création du produit|
|updated_at|TIMESTAMP|NULL|La date de la dernière mise à jour du produit|
|brand|entity|NULL|La marque (autre entité) du produit|
|category|entity|NULL|La catégorie (autre entité) du produit|
|type|entity|NULL|Le type (autre entité) du produit|

## Catégories (`category`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de la catégorie|
|name|VARCHAR(64)|NOT NULL|Le nom de la catégorie|
|subtitle|VARCHAR(64)|NULL|Le sous-titre/slogan de la catégorie|
|picture|VARCHAR(128)|NULL|L'URL de l'image de la catégorie (utilisée en home, par exemple)|
|home_order|TINYINT(1)|NOT NULL, DEFAULT 0|L'ordre d'affichage de la catégorie sur la home (0=pas affichée en home)|
|created_at|TIMESTAMP|DEFAULT CURRENT_TIMESTAMP|La date de création de la catégorie|
|updated_at|TIMESTAMP|NULL|La date de la dernière mise à jour de la catégorie|

## Marque (`brand`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de la marque|
|name|VARCHAR(64)|NOT NULL|Le nom de la marque|
|footer_order|TINYINT(1)|NOT NULL, DEFAULT 0|L'ordre d'affichage de la marque dans le footer (0=pas affichée dans le footer)|
|created_at|TIMESTAMP|DEFAULT CURRENT_TIMESTAMP|La date de création de la marque|
|updated_at|TIMESTAMP|NULL|La date de la dernière mise à jour de la marque|

## Type de produit (`type`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant du type|
|name|VARCHAR(64)|NOT NULL|Le nom du type|
|footer_order|TINYINT(1)|NOT NULL, DEFAULT 0|L'ordre d'affichage du type dans le footer (0=pas affichée dans le footer)|
|created_at|TIMESTAMP|DEFAULT CURRENT_TIMESTAMP|La date de création du type|
|updated_at|TIMESTAMP|NULL|La date de la dernière mise à jour du type|
