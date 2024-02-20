# Contribuer aux supports

Tout d'abord, merci de ta collaboration :pray:

Comme il est le seul avec une vue d'ensemble du programme, le **référent du programme** est le seule à avoir les droits pour `push` sur les branches `master` de tous les dépôts de cette orga.

En conséquence, il faut suivre une procédure simple mais stricte pour contribuer aux supports des programmes de formation.

## Etapes

- cloner le repo (sans déc !?)
- créer une branche locale
- coder la feature ou le fix dans cette branche
- `push` la branche sur le repo
- créer une Pull Request et y noter
  - la description de la PR
  - le texte "Issue : Fixes #NumeroIssue" afin de lieré automatiquement l'issue et la PR (ainsi, au merge de la PR, l'issue sera closed :boom:)
- ne pas sélectionner de reviewers, assignees ou autre, ce sera le taf du référent :wink:

## Coding Style

### PHP

Suivre les recommandations PHP-FIG :

- [PSR-12](https://www.php-fig.org/psr/psr-12/#example)
- extended from [PSR-2](https://www.php-fig.org/psr/psr-2/#11-example)
- extended from [PSR-1](https://www.php-fig.org/psr/psr-1/)

### JavaScript

Cette page [standardjs](https://standardjs.com/rules.html) semble assez bien résumer le _Coding Style_ (validé par la Team SocleJS). Sauf qu'il manque un truc important : les **semicolons** `;` à la fin de chaque ligne !

### SQL

Cette page https://www.sqlstyle.guide/ est correcte et validée par Jean.  
Sauf pour la partie "_Always include the AS keyword_". Non, alias quand nécessaire (aggrégats par exemple).
