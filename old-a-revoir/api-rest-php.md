```Article en cours de relecture```

# Créer une API REST en PHP : Les B.A.-B.A. 
### Prérequis

*Avoir déjà mis en place une page HTML qui dialogue avec un script PHP.*

Si tu as déjà osé créer une page HTML et la faire interagir avec PHP, prouve-le en réalisant cet exemple simple 💆:

**index.html**

```html
<!-- index.html -->

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Interaction HTML & PHP</title>
</head>
<body>
  <form action="process.php" method="post">
    <label for="username">Votre nom :</label>
    <input type="text" name="username" id="username" placeholder="Entrez votre nom">
    <button type="submit">Envoyer</button>
  </form>
</body>
</html>
```
**process.php**

```php
/*
  process.php
*/

    <?php
        if (isset($_POST['username'])) {
            $username = htmlspecialchars($_POST['username']);
            echo "Bonjour, " . $username . " !";
        } else {
            echo "Aucun nom reçu.";
        }
    ?>
```

### Contexte le web 2.0:
✨ Cet article a été écrit en 2020, mais nous le conservons pour souligner l’importance de maîtriser la création d’une API avec le langage (ex. PHP) **avant** de basculer sur des frameworks plus complexes tels que Symfony. 🚀


Alors, pourquoi parler du **web 2.0** ? 💆

Comme tu as déjà réussi à soumettre un formulaire en PHP, tu sais qu’à chaque fois que tu valides le formulaire, ta page se réactualise.

Et lorsque tu likes une vidéo sur YouTube ou que tu y postes un commentaire, cela ne remet pas la vidéo à zéro ; la page ne se réactualise pas, même si les données ont bien été envoyées. Le web 2.0 est généralement conçu via des **frameworks JS récents** comme **Angular** ou **React**, pour ne citer qu’eux.


## Api REST ✈ ✈
Essayons de faire plus simple ⛹ !

Le mot **API**, de par son sigle, désigne tout simplement une **Interface d’Application Programmable**. En résumé, c’est un outil qui permet une communication facile entre le **front** (Angular, Vue, etc.) et le **back** (PHP, Node…). Par exemple :

- Le front demande à afficher une liste de clients et le back lui retourne ceci :  
  [https://domain.com/customers](https://domain.com/customers)
- Le front demande les informations de l’utilisateur **4**, alors le back répond ceci :  
  [https://domain.com/user/4](https://domain.com/user/4)
- Le front veut sauvegarder des commandes dans une base de données, alors le back lui propose ceci :  
  [https://domain.com/orders](https://domain.com/orders)

Je crois qu’on a compris 🦳

Question ? et si on veut supprimer cet utilisateur 4 ?

Le back aussi peut lui offrir ceci https://domaine.com/user/4

Me diriez-vous que la récupération (https://domaine.com/user/4) et la suppression (https://domaine.com/user/4) sont identiques.

Je suis d'accord, mais c'est là où va intervenir notre développeur back pour nous expliquer pourquoi il a choisi une méthode REST pour la conception de cette API sans nous fournir la documentation de suis-la.

Vous avez donc compris qu'une API REST nécessite donc une vraie documentation.

Requete - Reponse
Si on parle ici du web 2.0 c'est qu'on a deja compris les b-a-ba classique du fonctionnement du web. On va s'attarder un peu sur ces requêtes http émissent par le client (navigateur).

>> Si on est natif de php on sais deja comment emmetre des requettes GET et POST pour recuperer ou envoyer des donnees au serveur. on y est deja habituer que ce bout de code nous devient toute logique.

```php
 <?php 
     // on elimine toutes les balises  et on  parse la saisie en entier   
     $id =  htmlspecialchars(intval($_GET['id']));
     $title = htmlspecialchars($_POST['title'] 
 ?>
```
Ils sont si connu, car jusqu'à présent le HTML classique ne sais faire que ça.
```html
  <form  method="get" action="posts" />   
  <form method="post" action="edit"/>
```
 Mais saviez-vous qu'on peut également emmètre des requêtes PUT ou DELETE

```typescript
//exemple avec Angular
 const body = { title: 'Angular PUT Request Example' };
 this.http.put<any>('https://domain.com/posts/1', body)
        .subscribe(data => this.postId = data.id); 
```
 Oui, mais bien sure faudrait récupérer les données cote back d'une autre manière
```php
 <?php 
 	 if($_SERVER['REQUEST_METHOD'] == 'PUT') {
    echo "une requete put \n";
    parse_str(file_get_contents("php://input"),$post_vars);
    echo $post_vars['orders']." est le nom\n";
    echo "je recupere une quantite ".$post_vars['quantity']."  \n\n";
} 
 	?>
```

# Vif du sujet

Assez parlé, passons aux choses sérieuses : concevons une API qui récupère une liste de posts.

Nous allons adopter un design pattern des plus simples.

Pour commencer, créons un dossier que nous nommerons **back** et suivons cette arborescence :  
- À la racine, le dossier **back** qui contiendra un fichier **index.php**  
- À l'intérieur, un dossier **src** dans lequel nous créerons trois sous-dossiers : **Controller**, **Entity** et **Repository**


> back
> ===> src
> ========>Controller
> ========>Entity
> ========>Repository
> ===>index.php

Allez, c'est simple ! Le point d'entrée de notre application est le fichier **index.php**, qui joue le rôle de routeur principal.

Lorsqu'un utilisateur sollicite une route spécifique, le routeur se charge d'interroger le contrôleur concerné. Ce dernier, à son tour, fait appel au repository pour traiter les données en s'appuyant sur l'entité correspondante. 👴 (T'inquiète, ça va venir !)


### Les Modèles 🗃️

Parler de backend nécessite aujourd'hui une connexion à une base de données.

Ici, nous allons utiliser MySQL pour créer une base de données appelée **labe** et une table nommée **post**.

> ```Entity Manager.php```

Dans le dossier **Entity**, créez un fichier nommé **Manager.php** et collez-y ce code classique pour se connecter à une BDD avec PDO.


```php
<?php
//manager.php
namespace Pabiosoft\Entity;

class Manager
{
    private $db;

    protected function dbConnect()
    {
        try{
            $this->db = new \PDO('mysql:host=localhost;dbname=labe;charset=utf8', 'root', 'pass');
            return $this->db;
        }catch(\Exception $e){
            echo ' impossible de se connecter';
        }
        return $this->db;
    }
}
```
> ``` Entity Post.php ```

On va créer dans ce même dossier ( entity) un fichier Post.php qui va être une abstraction de notre table post avec les mêmes caractéristiques
```php
<?php
//Post.php
namespace Pabiosoft\Entity;

class Post
{
    private ?int $id = null;
    private ?string $title = null;
    private ?string $description = null;
    //Si vous ne pouvez pas typer vos variables,
    //retirer les différents types ou passer à php8

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
```
>Le Repository PostRepository.php 🗄️

Dans le dossier Repository créer un fichier nommer PostRepository qui va contenir toute nos différentes requêtes lier à la table post.

Et y ajouter deux methods (une pour récupérer les posts et une pour insérer des posts
```php
<?php
//PostRepository.php
namespace Pabiosoft\Repository;

use Pabiosoft\Entity\Manager;
use Pabiosoft\Entity\Post;

class PostRepository extends Manager
{
    public function getPosts()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT id, title, description  FROM post ORDER BY id DESC ');
        return $req;
    }
    
    public function insert(Post $post): void
    {
        $sql = '
            INSERT INTO `post` (`title`, `description`)
            VALUES (:title, :description)
        ';
        $q = $this->dbConnect()->prepare($sql);
        $q->bindValue(':title', $post->getTitle(), \PDO::PARAM_STR);
        $q->bindValue(':description', $post->getDescription(), \PDO::PARAM_STR);
        $q->execute();
    }

}
```
### Les Contrôleurs ⚙️

Le contrôler c'est un peu comme la boite à vitesse d'une voiture qui attende les missions du chauffeur puis commissionne un comportement au moteur.

Dans notre cas, le moteur va être l'entité Post et le chauffeur va être le fameux index.php qu'on verra tout à l'heure

> ``` le Contrôler PostController.php```

Dans le dossier Contrôler, on crée un fichier nommé PostController.php

```php
<?php
//PostController.php
namespace Pabiosoft\Controller;

use \Pabiosoft\Entity\Post;
use \Pabiosoft\Repository\PostRepository;


class PostController{

    /**
     * @return void liste des posts format json
     */
    public function posts()
    {
        header("Access-Control-Allow-Headers: Authorization, Content-Type");
        header("Access-Control-Allow-Origin : *");
        header('Content-Type: application/json' );

        $postManager = new PostRepository();
        $posts = $postManager->getPosts();
        $response = [];
        foreach($posts as $all ){
            $response[] = $all;
        }

        $final = [];
        $i = 0;
        while ($i != count($response)){
            $final[] = array(
                "id"=> $response[$i]["id"],
                "title"=> $response[$i]["title"],
                "description"=> $response[$i]["description"],
            );
            $i++;
        }

        echo json_encode($final, JSON_PRETTY_PRINT);
    }

     /**
     * @return void ajout d'un post
     */
   public function ajout():void{
    header("Access-Control-Allow-Headers: Authorization, Content-Type");
    header("Access-Control-Allow-Origin : *");
    header('Content-Type: application/json' );

    $post = new Post();
    if (!empty($_POST['insert'])){
        $title = htmlspecialchars($_POST['title']);
        $desc = htmlspecialchars($_POST['description']);
        
        if(!empty($title) AND !empty($desc) ) {
            $post->setTitle($title);
            $post->setDescription($desc);
            $postRepo = new PostRepository();
            $postRepo->insert($post);
            echo json_encode('bien inserer', JSON_PRETTY_PRINT);
        }else{
            echo json_encode('oups donnees doivent exister et contenir des valeurs ', JSON_PRETTY_PRINT);
        }
    }
}
```
### Le Routeur index.php 🔀

On sait déjà que l'index.php est le point d'entrée de notre API, c'est donc là que tout se passe.

Il est primordial de partir sur de bonnes bases solides, car un routeur mal conçu peut entraîner, disons, 99,99 % de failles de sécurité.

La solution la plus simple serait de concevoir ce routeur en utilisant les actions demandées par l'utilisateur afin d'interroger le bon contrôleur, comme ceci :


```php
<?php
if($_GET['action'] == 'posts') : posts() ? echo '404 par exemple '; 
 // avec posts() la methode post de PostController.php 
 // pour le tester n'oublier pas d'importer le controller
 ?>
```
Dans ce cas de figure, si l'utilisateur essayait d'accéder à ceci  

```bash
http://localhost:8000/?action=posts
```
cela fonctionnait parfaitement, car cette action appelait la méthode posts() du contrôleur PostController qui, à son tour, récupérait les bonnes données du modèle (Post et PostRepository).

Mais bon, nous, on aimerait que notre API dispose de belles URL sans utiliser de fichier .htaccess (que je vous déconseille d'ailleurs).

Alors notre but, c'est d'avoir ceci
```bash
http://localhost:8000/posts
```
qui nous retourne du JSON (l'objet qu'on a créé dans la méthode posts du contrôleur PostController).

Et pour ce faire, nous allons utiliser un bundle très populaire appelé Fast Routeur.

🪔 Qui parle d'utiliser un bundle implique Composer

Si tu ne l'as jamais utilisé, c'est le moment, car il est si simple

commence par installer, composer dans ta machine puis ouvre un terminal dans ton projet
```bash
composer require nikic/fast-route
```
Cela va créer deux fichiers à la racine composer.json et composer.lock + un dossier vendor (qu'on ne touchera pas)

Dans le fichier composer.json ajouter de la ligne 5 à la ligne 9 pour la gestion de nos Namespace

```json
{
    "require": {
        "nikic/fast-route": "^1.3"  
    },
    "autoload": {
        "psr-4": {
          "Pabiosoft\\": "src/"
        }
      }
}
```
### Les namespaces 📦

C'est simplement un espace de noms. Si vous observez bien vos différentes classes récemment créées, elles possèdent toutes un namespace en haut du fichier.

Pour l'entité post, on avais ceci

```php
<?php
namespace Pabiosoft\Entity;
```
Pour le controller PostController on avais ceci
```php
<?php
namespace Pabiosoft\Controller;
```
Pour le Repository  PostRepository on avais ceci
```php
<?php
namespace Pabiosoft\Repository;
```
Ce que nous faisons, c'est que, chaque fois que nous créons une classe, nous la plaçons dans un sac 🎒.

Ainsi, si un jour nous voulons accéder à cette classe, il nous suffit d'ouvrir le bon sac. Par exemple, dans le contrôleur, nous avons :


```php
<?php
use \Pabiosoft\Entity\Post;
use \Pabiosoft\Repository\PostRepository;

```
Et dans notre composer.json, dès la ligne 7, grosso modo, on spécifie à l'autoloader de Composer quelle est notre namespace de base et où la trouver.

Elle s'appelle **Pabiosoft** et se trouve dans le dossier **src**.

👽 Allez, on fera un autre article pour démystifier Composer, mais pour notre API, nous avons obtenu ce que nous voulions.
Nous sommes enfin prêts à construire ce fameux routeur (index.php).
Vous pouvez jeter un œil sur le dépôt de nikic pour comprendre plus en détail son bundle : [github/nikic/FastRoute](https://github.com/nikic/FastRoute)


Je vous offre ce code vanilla, dont l'explication se trouve plus bas (🏇 prenez 3 minutes pour l'examiner, cela vous sera très utile — il fonctionne, mais pourrait être refactorisé).



```php
<?php

require 'vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/posts', '\Pabiosoft\Controller\PostController::posts');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

if($routeInfo[0] == FastRoute\Dispatcher::FOUND){
    // je verifie si mon parametre est une chaine de caratere
    if(is_string($routeInfo[1])){
        // si dans la chaine recue on trouve les ::
        if(strpos($routeInfo[1],'::') !== false){
            // on coupe par ::
            $route = explode('::',$routeInfo[1]);
            $method = [new $route[0],$route[1]];
        }else{
            // diretement la chaine
            $method = $routeInfo[1];
        }
        //var_dump($routeInfo[2]);
        call_user_func_array($method,$routeInfo[2]);
    }
}
elseif($routeInfo[0] == FastRoute\Dispatcher::NOT_FOUND){
    header("HTTP/1.0 404 Not Found");
    if(method_exists('\Pabiosoft\Controller\HomeController','error404')) {
        echo call_user_func([new \Pabiosoft\Controller\HomeController, 'error404']);
    } else {
        echo '<h1>404 Not Found</h1>';
        exit();
    }
}
elseif($routeInfo[0] == FastRoute\Dispatcher::METHOD_NOT_ALLOWED){
    header("HTTP/1.0 405 Method Not Allowed");
    if(method_exists('\Pabiosoft\Controller\HomeController','error405')) {
        echo call_user_func([new \Pabiosoft\Controller\HomeController, 'error405']);
    } else {
        echo '<h1>405 Method Not Allowed</h1>';
        exit();
    }
}
```
Alors la directrice, c'est la méthode simpleDispather qui prend en callback les differentes route de notre API .

```php
<?php
require 'vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/posts', '\Pabiosoft\Controller\PostController::posts');
});
```
Ici, on a une seule route nommé posts accessible en GET et qui appelle la méthode posts() du PostController

Facile non

Donc si tu veux ajouter une route en Post par exemple pour insérer des data avec notre méthode ajout() du PostController

Il faudra juste ajouter une nouvelle route comme ceci
```php
$r->addRoute(
          ['GET', 'POST'],
          '/add',
          '\Pabiosoft\Controller\PostController::ajout'
          );
```
Avec ce routeur, pour indiquer qu'une action est en Post, il faudra lui donnée un tableau ['GET','POST']

Donc ici, on a ajouté une route http://localhost:8000/add qui va envoyer des données au serveur en appelant notre méthode ajout du PostController

Je te donne un Bonus qu'on ne couvre pas ici

Supposons que tu veux avoir une route comme http://localhost:8000/post/2 qui récupère le post qui a comme id 2

Tu auras à y ajouter une route
```php
<?php
require 'vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/posts', '\Pabiosoft\Controller\PostController::posts');
    $r->addRoute(['GET', 'POST'], '/add', '\Pabiosoft\Controller\ApiController::addPostApi');
    $r->addRoute('GET', '/post/{id:\d+}', '\Pabiosoft\Controller\ApiController::post');
});
```
Bien evidement il faudra créer la méthode post() dans PostController qui retourne le post qui a comme id 2. (🏇 je te fais confiance)

### Gestion des erreurs ⚠️

Comme tu as bien pu le constater dans le routeur actuel ``index.php``, j'appelle deux méthodes :

- À la ligne 44, une `error404` qui se trouve dans le HomeController.
- À la ligne 53, une `error405` qui se trouve également dans le HomeController.

Oui, c'est bien ça.

Nous allons créer un fichier appelé **HomeController** dans le dossier **Contrôleur** (n'oublie pas de le mettre dans le bon sac, c'est-à-dire dans le namespace `Pabiosoft\Controller`).


À l'intérieur créé les deux méthodes. Et ```php echo 'pages not found' ; ``` dans la méthode error404() et ```php echo 'page not allowed ';  ```dans la methode error405()

```php
<?php
namespace Pabiosoft\Controller;

class HomeController{

      /*********************************************************
     *                E R R O R
     **************************************/
    public function  error404(){
        echo ' pages non disponible ';
        die();
    }

    public function  error405(){
        echo "une erreur s'y est produite";
        die();
    }
}
```
Alors maintenant, on va tester tout ça

Commence par insérer des données dans ta base de donnée
Puis on start le server 
```bash 
php -S localhost:8000 
```
Puis dans le navigateur accède  http://localhost:8000/post

👋 bingo si tu vois ce beau tableau d'objet Json, prend une pause-café
```json
[
    {
        "id": 4,
        "title": "Buildozer",
        "description": "convertit ton code python et kivy en  .apk"
    },
    {
        "id": 3,
        "title": "Automotive os",
        "description": "un Os qui a de l'avenir"
    },
    {
        "id": 2,
        "title": "PCB",
        "description": "circuit imprimer"
    },
    {
        "id": 1,
        "title": "Raspberry Pi 4",
        "description": "8 Go Ram wifi \/ blutooth"
    },
]
```

## Conclusion 🚀

Bravo, tu as suivi toutes les étapes et créé une API REST en PHP ! 🎉

Il est maintenant temps de passer à la pratique et de tester ton petit projet en local. N’hésite pas à explorer de nouvelles routes, à améliorer la gestion des erreurs ou à refactoriser le code pour le rendre encore plus performant. 🔧

La prochaine étape ? Intégrer ton API avec un front dynamique et continuer à expérimenter pour maîtriser l’ensemble du processus. Amuse-toi ! 🤓

> **Note :** Mon clavier QWERTY et moi avons sans doute laissé quelques fautes d’orthographe, cet article est donc encore en cours de relecture.  🤝


À bientôt pour de nouvelles aventures de développement ! 👋


### Meta Title :
Créer une API REST en PHP : Les B.A.-B.A. – Tutoriel Complet
### Meta Description :
Découvrez pas à pas comment concevoir une API REST en PHP : de la configuration du routeur à la gestion des requêtes et des erreurs, en passant par la connexion à MySQL. Un guide pratique pour renforcer vos bases avant de passer à des frameworks plus avancés.
___
## Auteur : 
> Ismaila Baldé
 [Connect with me on Reddit](https://www.reddit.com/user/pabios_af/) 
___

