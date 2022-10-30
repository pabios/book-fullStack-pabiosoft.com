```Article en cours de relecture```

## Le Web 2.0 ✈ ✈
### Prérequis
``` Avoir une fois créer un script php```
### Contexte :
Alors pourquoi parlez du web 2.0? 💆

Comme tu as une fois réussi à soumettre un formulaire en php, tu sais qu'à chaque submit du formulaire ta page se réactualise.

Et lorsque tu like une vidéo sur youtube oubien que tu y poste un commentaire cela ne remet pas la vidéo à zéro donc la page se reactualise pas meme si les donnees on ete bien envoyer. Le web 2.0 est généralement conçu via des frameworks  JS récent comme Angular ou React pour ne citer que ceux-là.

Api Rest
Essayons de faire plus simple ⛹ !

Le mot Api de par son sigle désigne tout simplement une Interface d'Application Programmable . En résumer c'est un outil qui permet une communication facile entre le front ( Angular, Vue .. ) et le back (php, node) . Par exemple :

* le front demande à afficher une liste des clients et le back lui offre ceci (https://domain.com/custemers)

* le front demande les informations de l'utilisateur 4 alors le back répond ceci (https://domaine.com/user/4)

* le front veut sauvegarder les commandes dans une base de données alors le back lui offre ceci (https://domaine.com/orders)

 je crois qu'on a compris 🦳

Question ? et si on veut supprimer cet utilisateur 4 ?

Le back aussi peut lui offrir ceci https://domaine.com/user/4

Me diriez-vous que la récupération (https://domaine.com/user/4) et la suppression (https://domaine.com/user/4) sont identiques.

Je suis d'accord, mais c'est là où va intervenir notre développeur back pour nous expliquer pourquoi il a choisi une méthode REST pour la conception de cette API sans nous fournir la documentation de suis-la.

Vous avez donc compris qu'une API REST nécessite donc une vraie documentation.

Requete - Reponse
Si on parle ici du web 2.0 c'est qu'on a deja compris les b.a-ba classique du fonctionnement du web. On va s'attarder un peu sur ces requêtes http émissent par le client (navigateur).

>> Si on est natif de php on sais deja comment emmetre des requettes GET et POST pour recuperer ou envoyer des donnees au serveur. on y est deja habituer que ce bout de code nous devient toute logique.

```php
 <?php 
     // on elimine toutes les balises  et on  parse la saisie en entier   
     $id =  htmlspecialchars(intval($_GET['id']));
     $title = htmlspecialchars($_POST['title'] 
 ?>
```
Ils ont si connu, car jusqu'à présent le HTML classique ne sais faire que ça.
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
Allez assez parler, commençons à concevoir une api qui récupère une liste de posts.

On va utiliser un design pattern toute simple

On commence par créer un dossier vide qu'on va appeler back et suivre cette arborescence
un dossier back puis un dossier src et un fichier index.php a la racine . Et dans le dossier src on cree 3 dossiers (Controller - Entity -Repository )

> back
> ===> src
> ========>Controller
> ========>Entity
> ========>Repository
> ===>index.php

 Allez le principe est simple ! le point d'entrer est notre index.php qui va être notre routeur principal

Si un utilisateur demande une route spécifique, alors le routeur interroge le Controller spécifier; et ce Controller fais appel au Repository qui va faire le traitement sur les données en utilisant l'Entity .👴. ( tkt ça va venir ).

### Les Models
Parlez de backend nécessite forcément de nos jours d'une connexion à une base de donnée

Ici, on va utiliser Mysql et y créer une base de donnée appeler labe et une table nommée post
> ```Entity Manager.php ```

Dans le dossier Entity, on crée un fichier qu'on va appeler Manager.php et y coller se code classique pour se connecter à une bdd avec Pdo
```php
<?php
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
>Le Repository PostRepository.php

Dans le dossier Repository créer un fichier nommer PostRepository qui va contenir toute nos différentes requêtes lier à la table post.

Et y ajouter deux methods (une pour récupérer les posts et une pour insérer des posts
```php
<?php
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
> ``` Les Contrôleurs```

Le contrôler c'est un peu comme la boite à vitesse d'une voiture qui attende les missions du chauffeur puis commissionne un comportement au moteur.

Dans notre cas, le moteur va être l'entité Post et le chauffeur va être le fameux index.php qu'on verra tout à l'heure

> ``` le Contrôler PostController.php```

Dans le dossier Contrôler, on crée un fichier nommé PostController.php

```php
<?php
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
}
```
> ``` Le Routeur index.php ```

On sait déjà que l'index.php c'est le point d'entrer de notre api ; donc c'est là où tout va se passer

Alors. Il vaut mieux partir sur de bonne base solide, car un routeur mal fait implique et disons 99,99% de failles de sécurité

La chose la plus simple serait de concevoir ce routeur en utilisant les actions demandé par l'utilisateur afin d'interroger le bon contrôleur comme ceci
```php
<?php
if($_GET['action'] == 'posts') : posts() ? echo '404 par exemple '; 
 // avec posts() la methode post de PostController.php 
 // pour le tester n'oublier pas d'importer le controller
 ?>
```
Dans ce cas de figure, si l'utilisateur essayait d'y accéder à ceci
```bash
http://localhost:8000/?action=posts
```
Cela allait bien marcher, car cette action appelle la méthode posts() du contrôleur PostController qui a son tour récupère les bonnes données du Model (Post et PostRepository )

Mais bon, nous, on aimerait que notre API ait du beau URL sans utiliser de fichier .htaccess (que je vous déconseille d'ailleurs)

Alors notre but, c'est d'avoir ceci
```bash
http://localhost:8000/posts
```
Qui nous retourne du Json ( l'objet qu'on a créé dans la méthode posts du contrôler PostController )

Et pour ce faire, on va utiliser un bundle très populaire appeler Fast Routeur

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
> ``` Les namespaces ```

c'est juste un espace de nom, quand vous observez bien vos différents classe récemment créer

Il possédait toutes un namespace en haut du fichier

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
Ce qu'on fait, c'est qu'à chaque fois qu'on crée une classe, on la met à l'intérieur d'un sac

Donc si un jour, on veut accéder à cette classe, on ouvre juste le bon sac à chaque fois, par exemple dans le Contrôler, on a
```php
<?php
use \Pabiosoft\Entity\Post;
use \Pabiosoft\Repository\PostRepository;

```
Et dans notre composer.json tout a l'heur à la ligne 7 grosso modo on specifie a l'autolodeur de composer quelle est notre namespace de base et ou le trouver

il s'appelle Pabiosoft et il se trouve dans le dossier src

👽 Allez, on fera un autre article pour démystifier, composer, mais pour notre api, on a eu ce qu'on voulait
On est enfin prêt pour construire ce fameux routeur (index.php)
Vous pouvez faire un tour sur le dépôt de nikic pour comprendre de plus en détails, son bundle  [github/nikic/fasRoute](https://github.com/nikic/FastRoute)

Je vous offre ce code, on va l'explication plus bas (🏇 prend 3 minutes pour essayer de le comprendre cela aide beaucoup)
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

Gestion des erreurs
Comme tu as bien lu le routeur actuel, tu as remarqué que j'appelle deux methodes

À la ligne 44, une erro404 qui se trouve dans un HomeController

À la ligne 53, une erro405 qui se trouve également dans le HomeController

Oui c'est ça

On Va créer un Fichier appeler HomeController dans le dossier Contrôler (n'oublie pas de le mètre dans le bon sac => namespace Pabiosoft/Controller )

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
___
## Auteur : 
> Ismaila Baldé
 [Connect with me on Reddit](https://www.reddit.com/user/pabios_af/) 
___

