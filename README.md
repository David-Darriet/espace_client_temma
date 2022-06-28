# espace_client_temma
Projet de la semaine d'innovation. Création d'un espace client pour l'entreprise Temma.

Projet du 27 Juin au 8 Juillet.

## Configuration

### Serveur mail

Dans le `.env` :

**/!\ Pour un compte gmail**

Veuillez remplacer à la ligne suivant, l'email, le mot de passe et le port (en local : localhost)
`MAILER_DSN=gmail://EMAIL:PASSWORD@PORT`

### Base de données 
Dupliquer le `.env.example` et le renommer en `.env`.

Dans ce nouveau fichier, dans le bloc pour indiquer la base de données, décommenter la base que vous souhaitez utiliser et renseigner le nom de la base, le nom de l'utilisateur et son mot de passe comme indiquer si dessous par exemple.

Pour une base mysql : 
`DATABASE_URL="mysql://127.0.0.1:3306/<DATABASE>?charset=utf8mb4&serverVersion=5.7&user=<USERNAME>&password=<PASSWORD>"
`

### Initialiser la base

Dans un terminal, faire :

`php bin/console doctrine:migrations:migrate`


### Fixtures

Dans un terminal, faire la commande :

`php bin/console doctrine:fixtures:load`