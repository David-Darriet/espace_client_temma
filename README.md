# espace_client_temma
Projet de la semaine d'innovation. Création d'un espace client pour l'entreprise Temma, notre premier client et bêta testeur.
Projet du 27 Juin au 8 Juillet.

Ce projet a été réalisé par 3 étudiants en moins de 15 jours, des bugs sont succeptibles d'être présents.

## Cloner le projet

`git clone https://github.com/Aelhya/espace_client_temma.git`

## Outils et versions

- PHP 8.1 
- Composer 2.1.9
- Symfony 6
- mysql (par exemple, vous être libre d'utiliser un autre type de base de données)

## Configuration
Allez dans le dossier `espace_client` en utilisant : `cd espace_client`

Dupliquez le `.env.example` et le renommer en `.env`.

### Serveur mail

Dans le `.env` :

**/!\ Pour un compte gmail**

Veuillez remplacer à la ligne suivant, l'email, le mot de passe et le port (en local : localhost)
`MAILER_DSN=gmail://EMAIL:PASSWORD@PORT`

Veuillez renseigner le nom de l'émetteur du mail :
`NAME_EMAIL="<NOM DE L EMETTEUR>"`

Veuillez renseigner l'adresse mail de l'émetteur :
`EMAIL="<ADRESSE EMAIL>"`

Puis dans un terminal faire  la commande : `composer dump-env dev`

### Base de données 
Dans le `.env` :

Dans ce nouveau fichier, dans le bloc pour indiquer la base de données, décommenter la base que vous souhaitez utiliser et renseigner le nom de la base, le nom de l'utilisateur et son mot de passe comme indiquer si dessous par exemple.

Pour une base mysql : 
`DATABASE_URL="mysql://127.0.0.1:3306/<DATABASE>?charset=utf8mb4&serverVersion=5.7&user=<USERNAME>&password=<PASSWORD>"
`

Si vous avez besoin de créer un base de données mysql, dans un terminal faire :

`mysql -u root -p`

puis :

```sql
CREATE DATABASE <nom de la base de données>;
```

### Initialiser la base

Dans un terminal, faire :

`php bin/console doctrine:migrations:migrate`

### Fixtures (dev uniquement)
Pour ajouter des données dans la base (ex: les dossiers).

Dans un terminal, faire la commande :

`php bin/console doctrine:fixtures:load`

### Autre

Si besoin de supprimer la base :

`php bin/console doctrine:schema:drop --full-database --force
`

## Mise en production

`composer install --no-dev --optimize-autoloader
`

`php bin/console cache:clear --env=prod --no-debug
`

`composer dump-env prod
`

`symfony serve -d
`

## Importer une base

A faire dans un terminal, dans le dossier `espace_client` :

`mysql -u <nom de l'utilisateur de la base> <nom de la base> < <nom_du_fichier_sql>`

/!\ **Remarque** :  

Un fichier d'exemple sql (`database_v0.sql`) est présent dans le dossier `espace_client/database`.
Nous vous conseillons de l'utiliser afin de faire un premier import de la base. 
Ce script contient les dossiers de base ainsi que l'ajout du super administrateur.

### Modification du super administrateur :

Il est **impératif** de changer la ligne suivante :

```sql
INSERT INTO `user` (`id`, `email`, `password`, `firstname`, `lastname`, `is_verified`, `enterprise`, `civility`, `login`, `roles`, `is_admin`) VALUES
	(2, 'admin@admin.fr', '$2y$13$xvHx3CSTtR0A14a0M.lApe49DT9CMS.0tnwERpDN9TL9kQGohMlz2', 'Admin', 'Admin', 1, 'Temma', 'Monsieur', 'adminLP', '["ROLE_ADMIN"]', 1);
 ```
 
 Les champs a adapté sont : l'adresse email, le mot de passe, le prénom, le nom, le nom de l'entreprise, la civilité et le login. Ces champs sont marqués entre chevrons (< >)dans la requête ci-dessous.
 
 ```sql
INSERT INTO `user` (`id`, `email`, `password`, `firstname`, `lastname`, `is_verified`, `enterprise`, `civility`, `login`, `roles`, `is_admin`) VALUES
	(2, '<EMAIL ADMIN>', '<MOT DE PASSE PROVISOIRE>', '<PRENOM>', '<NOM>', 1, '<NOM DE L ENTREPRISE>', '<CIVILITÉ>', '<LOGIN>', '["ROLE_ADMIN"]', 1);
 ```
 
 Une fois la commande d'import des données réalisées, nous conseillons à l'administrateur de faire "Mot de passe oublié ?" sur la page de connexion afin de pouvoir changer son mot de passe.
 
 ### Facultatif -  Modification des dossiers
 
Dans le fichier d'exemple sql (`database_v0.sql`) présent dans le dossier `espace_client/database`, figure l'ajout des dossiers dans la base de données :

Code présent : 
```sql
INSERT INTO `category` (`id`, `label`, `icon`) VALUES
	(1, 'Factures', 'fa-file-invoice-dollar'),
	(2, 'Devis', 'fa-file-lines'),
	(3, 'Maintenance', 'fa-screwdriver-wrench'),
	(4, 'Données machines', 'fa-database'),
	(5, 'Echanges', 'fa-headset');
  ```

Avant de faire un import, vous pouvez adapter ce code en changeant par exemple le nom (label) ainsi que les icônes (icon). Vous pouvez aussi ajouter des dossiers en rajoutant des lignes.

## Modification des données de la base de données

Quand l'import a été réalisé et si vous souhaitez modifier les différentes catégories possibles (dossiers), vous pouvez réaliser les commandes suivantes dans un terminal.

`mysql -u root -p`

```sql
USE <Nom de la base de données>;
```

Pour modifier le nom d’une catégorie :  
```sql 
UPDATE category SET label = 'Test' WHERE label = 'Factures';
```

Pour modifier le nom et l’icône d’une catégorie :  
```sql
UPDATE category SET label = 'Test', icon = 'fa-comment' WHERE label = 'Factures';
```
Pour ajouter une catégorie :
```sql
INSERT INTO `category` (`label`, 'icon') VALUES ('<Nom du nouveau dossier>', '<nom de l'icone>');
```
Pour supprimer une catégorie :
```sql
DELETE FROM category where label = '<Nom d’un dossier existant>';
```

## Icônes

Les icônes proviennent du site https://fontawesome.com/icons 

## Documentation client

Une documentation client existe, veuillez nous contacter pour l'obtenir.
