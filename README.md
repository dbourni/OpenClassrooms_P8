# P8 - Améliorez une application existante de ToDo & Co
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/7d81efdb1205454ea45ec23337db9398)](https://www.codacy.com/manual/dbourni/OpenClassrooms_P8?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dbourni/OpenClassrooms_P8&amp;utm_campaign=Badge_Grade)

## Installation

*   Clonez ou téléchargez le repository GitHub :
```system
git clone https://github.com/dbourni/Openclassrooms_P8.git
```
*   Configurez vos variables d'environnement telles que la connexion à la base de données dans le fichier .env

*   Installez les dépendances avec Composer :
```system
composer install
```

*   Créez la base de données :
```system
php bin/console doctrine:database:create
```

*   Créez la structure de la base de données :
```system
php bin/console doctrine:schema:create
```

*   Créez les fixtures vous permettant de tester :
```system
php bin/console doctrine:fixtures:load
```

## Tests

*   Lancer les tests :
```system
php bin/phpunit
```

*   Génération du rapport de couverture de code :
```system
php bin/phpunit --coverage-html web/test-coverage
```