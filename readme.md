# Test technique GAC

## Versions utilisées

- **Apache** : 2.4.33
- **PHP** : 7.2.3 
  + utilisation des `short_open_tag`
  + utilisation de l'extension `pdo_mysql`
- **MySQL** : 5.7.9

## Librairies

- **Bootstrap** : 5.0.1
- **Gac** : librairie spécifique au projet, utilisant quelques fichiers issus d'un framework PHP personnel

Le schéma de la base de données est dans le fichier `database.sql`.

## Utilisation

Pour utiliser le projet, il suffit de le placer dans un répertoire accessible par le serveur `apache`. Aucune réécriture d'URL n'a été mise en place pour éviter toute configuration.

L'application comporte 3 pages :

- Accueil : page d'accueil qui explique comment utiliser l'application
- Import : page qui vide la table contenant les données puis importe les données utiles & valides
- Requêtes : page qui affiche les résultats des 3 requêtes