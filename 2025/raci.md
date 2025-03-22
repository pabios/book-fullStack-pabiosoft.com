# Alors, c’est quoi une RACI ? 🎉

##  RACI expliquée ✨

Salut guys ! Imagine qu’on doit organiser une méga fête à la maison avec les copains. Y’a des chips à acheter, de la musique à choisir, des ballons à gonfler… mais si tout le monde fait tout (ou pire, si personne ne fait rien), c’est le chaos total, non ? Eh ben, une RACI, c’est comme la feuille magique ✨ qui dit : "Toi, tu fais ça, et toi, tu surveilles que ça roule." Simple, mais génial !

RACI, ça veut dire **Responsable, Accountable, Consulted, Informed**. En français, on pourrait dire : "Celui qui bosse, celui qui valide, ceux qu’on demande, et ceux qu’on prévient." Allez, je t’explique avec notre fête :

- **Responsable (R)** : C’est celui qui met les mains dans le cambouis. Par exemple, toi, tu vas au magasin acheter les chips 🥔. T’es le héros qui agit !
- **Accountable (A)** : C’est le chef, celui qui dit "OK, c’est bon" ou "Non, t’as oublié le sel !" Genre, moi, je vérifie que t’as pris les bonnes chips et pas juste des bonbons (parce que je te connais 😜).
- **Consulted (C)** : Eux, on leur demande leur avis avant de bouger. Comme Mamie, qu’on appelle pour savoir si elle préfère du rap ou du rock à la fête – elle donne son conseil, mais elle achète rien.
- **Informed (I)** : Ceux-là, on les tient juste au courant. Papa et Maman, par exemple, on leur dit "Hey, la fête est prête, venez à 18h !" Ils font rien, mais ils savent.

En gros, une RACI, c’est une grille où tu mets les noms des gens en face de chaque tâche, avec un R, A, C ou I. Comme ça, pas de "Mais j’croyais que c’était toi !" et tout le monde sait qui fait quoi. À la fin, la fête est nickel, et toi, t’as même le temps de danser 💃 !

## RACI en action 💻

Bon, imaginons qu’on code une appli Angular pour gérer notre budget – un truc utile pour savoir si on peut s’acheter une nouvelle console ou pas ! On a deux composants : un pour entrer les dépenses ("Dépenses") et un pour voir le total ("Résumé"). Tout ça sur un seul repo GitHub. Sans RACI, ça risque de partir en vrille : toi, tu codes "Dépenses", moi aussi, et on finit avec deux versions qui plantent. Non merci !

Avec une RACI, on met tout au clair dans une petite grille :

| Tâche                        | Toi   | Moi   |
|------------------------------|-------|-------|
| Coder le composant Dépenses  | R     | A     |
| Coder le composant Résumé    | A     | R     |
| Tester les deux composants   | R     | C     |
| Pousser sur une branche      | R     | R     |
| Merger sur la branche main   | I     | R     |



- **Coder le composant Dépenses** : Toi, tu codes (R), moi, je check que ça marche bien (A). Pas de doublon, pas de prise de tête.
- **Coder le composant Résumé** : Moi, je m’y colle (R), et toi, tu valides si le total est clair (A).
- **Tester les deux composants** : Toi, tu testes tout (R), et moi, je donne mon avis si y’a un bug qui m’échappe (C).
- **Pousser sur une branche** : Toi (R) et moi (R), on peut tous les deux pousser nos changements sur des branches séparées, tranquille 🚀.
- **Merger sur la branche main** : Moi, je m’occupe du merge sur `main` (R) vu que c’est la prod, et toi, t’es prévenu quand c’est fait (I).

L’avantage ? On bosse chacun sur notre truc sans se gêner, et on peut tous les deux pousser nos commits sans attendre. Mais comme y’a qu’un seul boss pour merger sur `main`, on évite de casser la prod avec un code bancal. À la fin, l’appli est clean, on voit direct qu’on a dépensé trop en pizzas 🍕, et on peut économiser pour cette console. On tente ?

<meta name="title" content="C’est quoi une RACI ? Guide fun pour organiser tes projets 🎉">
<meta name="description" content="Découvre la RACI, une méthode simple pour organiser tes projets comme une fête ou une appli Angular ! Responsable, Accountable, Consulted, Informed : on t’explique tout avec des exemples fun.">