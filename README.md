# Symfony
## Auteur : Christelle Souka
### Installation / Configuration 

#### Serveur Web Local
Pour lancer le serveur web de test il est possible d'utiliser le script :
``Composer start``

#### Style de codage
Le code peut être contrôlé avec `composer test:cs` et reformaté automatiquement avec `composer fix:cs`.

#### Lancer les tests 
Le script `composer test:codeception` permet d'initialiser la base de données avant de lancer les tests.


Pour tester la mise en forme et lancer les tests : `composer test`.

#### Base de données
La commande `composer db` permet de :
- Détruire la base de données
- Créer la base de données
- Appliquer des migrations successives sans questions interactives 
- Gérèrer des données factices sans questions interactives"