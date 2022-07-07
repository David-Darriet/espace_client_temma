# espace_client_temma
Projet de la semaine d'innovation. Création d'un espace client pour l'entreprise Temma, notre premier client et bêta testeur.

Projet du 27 Juin au 8 Juillet.

## Cloner le projet

`git clone https://github.com/Aelhya/espace_client_temma.git`

## Outils et versions

- PHP 8.1 
- Composer 2.1.9
- Symfony 6

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
`php bin/console cache:clear --end=prod --no-debug
`
`composer dump-env prod
`
`symfony serve -d
`

## importer une base



## Répartition du travail 


### **Kim-Anh**

**Tâches Autres**
  - Réalisation de la maquette
  - Lean Canvas / Impact mapping
  - Charte graphique
  - Configuration du VPS

**Tâches front**
 - Page de connexino
 - Page d'ajout d'un client
 - Page liste des clients (dashboard côté admin)
 - Flash message / alerte quand il y a un ajout / modif, etc...

**Tâches back**
- Ajout d'un utilisation
- Envoi mail avec identifiants
- Envoi mail réinitialisation du mdp
- Envoi mail  dès l'ajout d'un fichier
- Vérification mot de passe

### Florian
**Tâches autres**
- Réalisation du schéma de la base de données
- Recherche upload / download fichier
- Repérage de bugs

**Tâches front**
- Menu

**Tâches back**
- Upload d'un fichier
- Download d'un fichier

### Adélaïde :

**Tâches autres**
- Réalisation du site de présentation du projet
- Réalisation de données de tests (fixture)
- Story Mapping
- Correction des bugs

**Tâches front**
- Page qui liste les dossiers (admin et user)
- Pages qui liste les fichiers (admin et user)
- Action sur le dashboard de l'admin

**Tâches back**
- Création  du projet + création de la base de données
- Gestion des routes
- Processus de connexion
- Liste des utilisateurs (pour l'admin)
- Vérification mot de passe
- Page client back (show, edit, delete)
- Suppression fichier
- Import des clients via un csv
- Vérification des formats des fichiers
