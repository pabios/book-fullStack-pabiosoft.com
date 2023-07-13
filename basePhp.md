<h2 style="color:blue">Php Inscription Utilisateur</h2>

### Niveau débutant 002
## Table des matières

- [Introduction](#introduction)
- [Petit exercice ](#exo)
- [Base de donné ](#bdd)
- [Résumer et Factorisation du code](#refactor)
- [Résultat](#result)
- [Auteur](#author)

#### {#introduction}
<h2 style="color:blue"> Introduction</h2>  
L'une des notions primordiales dans la conquête des sites dynamique et la notion de récupération et de la sauvegarde de donner. 
:closed_lock_with_key: il y a une seule règle qui s'applique 
:lock: Ne jamais faire confiance à l'utilisateur

#### Bien Débuter {#debutant}
Maintenant qu'on a réussi a bien aligné nos inputs on aimerait inscrire cet utilisateur.
Donc sauvegarder son email et son mot de passe dans une base donnée afin qu'on puisse l'identifier s'il revient visiter notre plateforme.
<form>
  <label for="email">Email :</label><br>
  <input type="email" id="email" name="email"><br>
  <label for="password">Mot de passe :</label><br>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" value="Se connecter">
</form>

pour le moment notre formulaire ressemble à ceci
```HTML
<form>
  <label for="email">Email :</label><br>
  <input type="email" id="email" name="email"><br>
  <label for="password">Mot de passe :</label><br>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" value="Se connecter">
</form>
```
Ainsi juste un formulaire qui ne fait rien.
Et pour dire a ce formulaire d'envoyer ces données à un serveur, il faut lui ajouter deux attributs 
```HTML
<form method="post" action="script.php">
  ...
</form>
```
 1. L'attribut *method=""* indique comment les données vont être transmise à notre serveur.
 Elle peuvent prendre deux valeur.
 * GET : pour faire transiter les donner via l'URL.
 exemple: ``` https: //nomDuSite.com/script.php?email=user@gmail.com?mdp=monPassword ```
Vous avez compris qu'avec cette method les donner transmissent seront visibles et modifiables par l'utilisateur. 

* POST: pour transmettre les données dans le corps de la requête HTTP. Avec cette méthode les données ne seront visibles sur l'adresse web,  de ce fait elle est plus sécurisé, mais pas infaillible. 

En général, il est recommandé d'utiliser la méthode POST pour les requêtes qui modifient les données sur le serveur, car elle est plus sécurisée que la méthode GET. La méthode GET peut être utilisée pour les requêtes qui ne modifient pas les données, mais il est important de noter que les données envoyées dans l'URL sont visibles dans l'adresse web, ce qui peut poser des problèmes de confidentialité.

2. Et l'attribut *action=""* indique quel fichier ou route va faire le traitement sur les donner, envoyer.

#### Passons au code
 Les attributs name des inputs vont nous permettre de récupérer la saisie   
```html 
  <input type="email" id="email" name="email"><br>
  <input type="password" id="password" name="password"><br><br>
 ```
Dans le fichier ``script.php` écrivons tout simplement   
 ```php 
 if(!empty($_POST)){
    var_dump($_POST)  
 }else{
  echo 'Aucune donnee transmise';
  die();
 }
 ```
 Pour afficher les données reçues du formulaire de façons détaillées. 

Maintenant une fois le formulaire envoyé, s'il contient des données, elles seront afficher 
 ```php
$_POST = array(
  "email" => "amad@gmail.com",
  "password" => "amad$115"
);
```


Et si le formulaire est vide, ce message apparaitra
 `` 
Aucune donnée envoyé
``
####  {#exo}
<h2 style="color:blue"> Petit exercice</h2>  
Écrire un Programme qui va hacher le mot de passe et qui vérifie si l'email est bien valide. 
* Je vous propose une solution 
```php
if(!empty($_POST['email']) && !empty($_POST['password'])){
  $email =  $_POST['email'];
  $password = htmlspecialchars($_POST['password']);



  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
  // l'adresse email est valide 
   // alors on hash le mot de passe
  $hash = hash('SHA512',$password);

    // on appelle une methode pour enregistrer ces donner dans une base de donnee
    signUp($email,$hash)

  } else {
    // l'adresse email est invalide
    die();
  }

}

```

* la fonction htmlspecialchars()
 pour échapper les caractères spéciaux 
 
*  la fonction hash()
 Permet de générer un hachage d'une chaîne de caractères en utilisant un algorithme de hachage
* SHA512 est un Algorithme  de hashage unidirectionnel 

ici notre variable hash récupère le mot de passe envoyé c'est-à-dire (amad$115) et génère ce hash unique 
```text
1d8bd7ecc3029b70433e1ee604b836917d7bb832bb7e4df72591c69fd6bf1742b964c02efc75124579668a993e8f265b253c4d2388c6b504b2fe7052b2c21b27
```

- Réalisation de la fonction signUp($email,$hash)

#### {#bdd}
<h2 style="color:blue"> Création de la base de donner</h2> 
Si ce n'est pas encore fait, suivez ce lien et sa doc[https://dev.mysql.com/downloads/] pour installer notre SGBDR Mysql en local selon votre système d'exploitation.
Ou lisez cet article qui vous expliquera comment procéder https://openclassrooms.com/fr/courses/6971126-implementez-vos-bases-de-donnees-relationnelles-avec-sql/7152681-installez-le-sgbd-mysql (n'hésitez pas d'y passer un peu de temps)
 😉 Vous pouvez aussi utiliser MAMP, WAMP OU XAMPP 
Une fois Mysql installer, ouvrez un terminal

```terminal
➜ mysql --version
mysql  Ver 8.0.30 for macos11.6 on arm64 (Homebrew)
```
Cela indique que mysql est bien installer et donne sa version.
Puis connectez vous 

```terminal
➜  mysql -u pabios -p
   Enter password:
```
Une fois connecter.
```sql
➜  CREATE DATABASE IF NOT EXISTS `pita`;

-- Sélection de la base de données
USE pita;

-- Création de la table companies
  
  CREATE TABLE `user` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `email`  VARCHAR(50),
  `password` VARCHAR(255),
);
```
La   fonction signUp:
```php
   function signUp($email, $hash)
    {
        $sql = '
            INSERT INTO `user` (`email`, `password`)
            VALUES (:email, :password)  
        ';
        $db = dbConnect();
        $q = dbConnect()->prepare($sql);

        $q->bindValue(':email', $email, \PDO::PARAM_STR);
        $q->bindValue(':password', $hash, \PDO::PARAM_STR);

        $q->execute();
    } 
```
- Réalisation de la fonction dbConnect()
cette fonction permet d'établir  une connexion à notre base de donner
```php
function dbConnect()
{
    try{
        $db = new \PDO('mysql:host=localhost;dbname=pita;charset=utf8', 'root', 'root');
        echo 'succes';
        return $db;
    }catch(Exception $e){
        echo ' impossible de se connecter';
        die();
    }
}

```

Cette fonction dbConnect  tente de se connecter à une base de données MySQL  en utilisant la classe PDO (PHP Data Objects). La fonction prend en compte les paramètres suivants :

* host : l'adresse de l'hôte où se trouve la base de données (dans cet exemple, "localhost")
* dbname : ``pita`` le nom de la base de données à laquelle on veut se connecter
* charset : le jeu de caractères à utiliser (dans cet exemple, "utf8")
* root : le nom d'utilisateur pour se connecter à la base de données
* root : le mot de passe pour se connecter à la base de données

Si la connexion est établie avec succès, la fonction affiche le message "succes" et retourne l'objet PDO qui représente la connexion à la base de données. Si une erreur se produit, la fonction affiche un message d'erreur et termine l'exécution du script en utilisant la fonction die.

#### {#refactor}
<h2 style="color:blue"> Résumer et Factorisation du code</h2> 
 
* Arborescence

mon_dossier
├── index.php
└── script.php


-- index.html
```HTML
<form method="post" action="script.php">
  <label for="email">Email :</label><br>
  <input type="email" id="email" name="email"><br>
  <label for="password">Mot de passe :</label><br>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" value="Se connecter">
</form>
```
-- script.php
```php

// connection a la base de donner
function dbConnect()
{
    try{
        $db = new \PDO('mysql:host=localhost;dbname=pita;charset=utf8', 'root', 'root');
        return $db;
    }catch(Exception $e){
        echo ' impossible de se connecter';
        die();
    }
}

// sauvegarde des donnees dans la base de donnee 
function signUp($email, $hash)
    {
        $sql = '
            INSERT INTO `user` (`email`, `password`)
            VALUES (:email, :password)  
        ';
        $db = dbConnect();
        $q = dbConnect()->prepare($sql);

        $q->bindValue(':email', $email, \PDO::PARAM_STR);
        $q->bindValue(':password', $hash, \PDO::PARAM_STR);

        $q->execute();
    } 
  
// traitement du formulaire
function home(){
    if(!empty($_POST['email']) && !empty($_POST['password'])){
    $email =  $_POST['email'];
    $password = htmlspecialchars($_POST['password']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $hash = hash('SHA512',$password);
      signUp($email,$hash)
    } else {
      // l'adresse email est invalide
      die();
    }

  }
}

home();
```
#### {#result}
<h2 style="color:blue"> Résultat</h2>  

```sql
  SELECT id,email,password FROM user;
```

| id | email             | password       |
|----|-------------------|----------------|
| 1  | amad@gmail.com | 1d8bd7ecc3029b70433e1ee604b836917d7bb832bb7e4df72591c69fd6bf1742b964c02efc75124579668a993e8f265b253c4d2388c6b504b2fe7052b2c21b27     |




#### {#author}
<h2 style="color:blue"> Auteur</h2>  


| Prénom     | Nom        | Contact Discord|     
|------------|------------|----------------| 
| Ismaila    | Baldé      |[discord](https://discord.gg/TuPCmYD8Ex)       |    
