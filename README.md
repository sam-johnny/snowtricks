# SnowTricks V2

Afin d'approfondir mes connaissances, j'ai décidé de reprendre mon projet et d'améliorer mon code.

## Nouveauté

* Mettre les envoie de mail en tâche asynchrone grâce Messenger de symfony
* Implémentation Voter de symfony
* Implémentation Event subscriber au lieu de Event listener de doctrine
* Implémentation des Handler (intance de formulaire depuis le contrôleur)
* Implémentation des DataTransformer
* Implémentation des tests fonctionnels (en cours)

Création d'un site communautaire de partage de figures de snowboard via le framework Symfony.

## Environnement utilisé durant le développement
* Symfony 6.0.6
* Composer 2.1.12
* Bootstrap 5.1.3
* WampServer 3.2.3

## Installation
1. Clonez ou téléchargez le repository GitHub dans le dossier voulu :
```
    git clone https://github.com/sam-johnny/snowtricks.git
```
2. Configurez vos variables d'environnement tel que la connexion à la base de données ou votre serveur SMTP ou adresse mail dans le fichier `.env.local` qui devra être crée à la racine du projet en réalisant une copie du fichier `.env`.

3. Téléchargez et installez les dépendances back-end du projet avec [Composer](https://getcomposer.org/download/) :
```
    composer install
```
4. Créez la base de données si elle n'existe pas déjà, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :
```
    php bin/console doctrine:database:create
```
5. Créez les différentes tables de la base de données en appliquant les migrations :
```
    php bin/console doctrine:migrations:migrate
```
6. Félications le projet est installé correctement, vous pouvez désormais commencer à l'utiliser à votre guise !
