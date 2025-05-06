# Comment sortir du code legacy : un exemple pas à pas

---

## Salut !

T’as déjà ouvert un projet où **tout est dans le même fichier** ? Un bon vieux mix de HTML, PHP, CSS, JS... le tout en freestyle façon spaghetti ? Bienvenue dans le monde du **code legacy** 

Pas de panique ! On va voir **ensemble** comment on a transformé un vieux code bien fatigué en une app moderne, fun, avec du style, des confettis et même un toast pour te dire quand tu fais n'importe quoi 😅

---

##  1. C’est quoi le problème avec le code legacy ?

Un code legacy, c’est un peu comme un grenier plein de trucs utiles... mais aussi de vieilles chaussettes :

- Écrit à la va-vite 
- Mélangé dans tous les sens 
- Pas documenté 🙈
- Et si tu changes un truc... tout peut casser 

### Exemple typique :
```php
<h1>But !</h1>
<?php if ($_POST['joueur'] == '1') {
    echo "But pour joueur 1";
} ?>
```

➡️ On mélange tout : l'affichage, la logique, et même le désespoir 😅

---

##  2. Première étape – Le PHP gère juste les données

On commence à ranger la chambre. Le PHP, son rôle, c’est **gérer les données**, pas de faire le bazar dans l’interface.

```php
<?php
$buts = ['joueur1' => 0, 'joueur2' => 0];
?>
```

➡️ Simple, propre. Le PHP reste derrière, et le front prend le relais 

---

##  3. Deuxième étape – On invite Tailwind CSS à la fête 💅

Tu veux que ça soit beau sans te taper 400 lignes de CSS ? Tailwind, c’est ton pote. Tu l’appelles comme ça :

```html
<script src="https://cdn.tailwindcss.com"></script>
```

Et après ? Tu te fais plaisir :

```html
<div class="bg-white p-4 rounded-xl shadow-lg text-center">
  <h2 class="text-lg font-bold">Mbappé</h2>
  <span class="text-gray-500">Attaquant</span>
</div>
```

➡️ Un seul `<div>` stylé comme jamais. Pas de fichier CSS, pas de prise de tête.

---

##  4. Troisième étape – Le JS devient le cerveau 

On rajoute un peu de logique propre pour gérer les clics, les scores, les animations... et les confettis 

```js
let joueurSelectionne = null;
let buts = { joueur1: 0, joueur2: 0 };
```

Puis on réagit aux clics :

```js
document.getElementById('boutonBut').addEventListener('click', () => {
  if (joueurSelectionne) {
    // on marque un but + confetti
  } else {
    // on affiche le toast
  }
});
```

➡️ Le JS contrôle tout, et c’est lui le chef maintenant.

---

##  Bonus UX – Le toast (oui, comme les pop-tarts)

Imagine tu cliques sur "Marquer un but" sans avoir choisi un joueur... Bah rien ne se passe. Et ça, c’est **nul**.

Alors on affiche un **toast** sympa :

```html
<div id="toast" class="hidden absolute bottom-10 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg">
  Veuillez sélectionner un joueur avant de marquer un but !
</div>
```

Et côté JS :

```js
const toast = document.getElementById('toast');
toast.classList.remove('hidden');
setTimeout(() => toast.classList.add('hidden'), 3000);
```

➡️ Pas de popup moche. Juste un petit rappel mignon mais ferme 😎

---

##  Résultat final : admire le chef-d’œuvre

Tu peux copier tout ça dans un `index.php`, lancer un petit serveur avec `php -S localhost:8000` et… **tester le kiff** 

<details>
<summary>💡 Clique ici pour voir le code complet</summary>

```php
<?php
$buts = ['joueur1' => 0, 'joueur2' => 0];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Joueurs de Foot - Passe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen relative">

  <div class="flex flex-col items-center gap-10">
    <div class="flex items-center gap-16">
      <!-- Joueur 1 -->
      <div id="joueur1" class="bg-white shadow-lg rounded-xl w-48 p-4 flex flex-col items-center cursor-pointer">
        <img src="./image/rag.png" alt="Joueur 1" class="rounded-full mb-3">
        <h2 class="font-bold text-lg">Mbappé</h2>
        <span class="text-gray-500">Attaquant</span>
        <span id="but-joueur1" class="mt-2 font-semibold">Buts: <?php echo $buts['joueur1']; ?></span>
      </div>

      <!-- Flèche -->
      <div class="text-5xl text-green-500">&rarr;</div>

      <!-- Joueur 2 -->
      <div id="joueur2" class="bg-white shadow-lg rounded-xl w-48 p-4 flex flex-col items-center cursor-pointer">
        <img src="./image/rag.png" alt="Joueur 2" class="rounded-full mb-3">
        <h2 class="font-bold text-lg">Haaland</h2>
        <span class="text-gray-500">Buteur</span>
        <span id="but-joueur2" class="mt-2 font-semibold">Buts: <?php echo $buts['joueur2']; ?></span>
      </div>
    </div>

    <!-- Bouton -->
    <button id="boutonBut" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition-colors">
      ⚽ Marquer un but !
    </button>
  </div>

  <!-- Toast -->
  <div id="toast" class="hidden absolute bottom-10 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg">
    Veuillez sélectionner un joueur avant de marquer un but !
  </div>

  <script>
    let joueurSelectionne = null;
    let buts = { joueur1: 0, joueur2: 0 };

    document.getElementById('joueur1').addEventListener('click', () => {
      joueurSelectionne = 'joueur1';
      highlightSelection('joueur1');
    });

    document.getElementById('joueur2').addEventListener('click', () => {
      joueurSelectionne = 'joueur2';
      highlightSelection('joueur2');
    });

    document.getElementById('boutonBut').addEventListener('click', () => {
      if (joueurSelectionne) {
        buts[joueurSelectionne]++;
        document.getElementById('but-' + joueurSelectionne).textContent = 'Buts: ' + buts[joueurSelectionne];

        const el = document.getElementById(joueurSelectionne);
        const rect = el.getBoundingClientRect();
        confetti({
          particleCount: 100,
          spread: 70,
          origin: {
            x: (rect.left + rect.width / 2) / window.innerWidth,
            y: (rect.top + rect.height / 2) / window.innerHeight
          }
        });
      } else {
        const toast = document.getElementById('toast');
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
      }
    });

    function highlightSelection(id) {
      ['joueur1', 'joueur2'].forEach(j => {
        document.getElementById(j).classList.remove('ring-4', 'ring-green-500');
      });
      document.getElementById(id).classList.add('ring-4', 'ring-green-500');
    }
  </script>
</body>
</html>
```

</details>

---

##  Pour tester en local

1. Crée le fichier `index.php`  
2. Mets une image `rag.png` dans un dossier `/image/`  
3. Lance un serveur PHP :
```bash
php -S localhost:8000
```
4. Ouvre [http://localhost:8000](http://localhost:8000)

---

## 5. Pistes d'amélioration - Séparer pour mieux régner

Maintenant qu'on a un code propre, on peut aller encore plus loin ! Imagine que ton app grandisse... tu vas pas tout garder dans un seul fichier comme un barbare, non ? 😅

### Séparation des fichiers - Chacun son job

Comme pour une coloc, chaque techno doit avoir sa chambre :

```
mon-projet/
├── index.php      👈 Juste la structure HTML + inclusion
├── css/
│   └── style.css  👈 Tout le style
├── js/
│   └── app.js     👈 Toute la logique JS
└── includes/
    └── data.php   👈 Les données et fonctions PHP
```

## Structure de dossiers - Comme un pro

Si tu veux vraiment faire les choses bien, voici une structure plus complète :

```
mon-projet/
├── public/           👈 Accessible par le navigateur
│   ├── index.php     👈 Point d'entrée
│   ├── assets/       👈 Ressources statiques
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
├── src/              👈 Code source (pas accessible directement)
│   ├── config/       👈 Configuration
│   ├── includes/     👈 Fonctions PHP
│   └── templates/    👈 Morceaux de HTML réutilisables
└── vendor/           👈 Bibliothèques externes (si tu utilises Composer)
```
| Bravo, tu viens de créer ton framework php ! 😊

## Conclusion

On est passé de :
-  Un vieux code qui pique les yeux
- À Une petite app fun, claire et stylée

Et surtout...
> **On a sorti ce code du legacy comme un boss.**

Tu veux pousser encore plus loin ? Symfony ? Angular ? Une API REST ? Dis-moi, j’ai les doigts qui démangent 😎


---
## les meta

## Meta Title
Comment Sortir du Code Legacy : Guide Pas à Pas pour Moderniser ton Application

## Meta Description
Découvre comment transformer un code legacy spaghetti en une application moderne et structurée. De la séparation des responsabilités à l'organisation des dossiers, ce guide te montre comment moderniser ton code avec des exemples concrets et une approche fun.