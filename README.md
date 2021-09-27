# PLX_VIP_ZONE
Plugin pour PluXml , ajoute la gestion d'espace privé ,blog,categories ét articles ou pages statiques aux choix, inspiré du précedent squelette: https://github.com/gcyrillus/plx-log-visitor

**Le plugin "Espace Privée" permet de rendre tout ou partie de votre site accessible uniquement à des utilisateurs bénificiant d'un accés V.I.P..**

A l'activation, un nouveau profil apparait pour vos utilisateurs. Vous pouvez ajouter un utilisateur qui aura accés aux zones privatisés sans avoir de droits pour accéder à la partie administration de votre site.

La seule page de l'administration qui lui sera accéssible sera la page d'authenfication.

Comme tout les plugins, dés l'activation il vous faut configurer votre plugin de façon à ce qu'il fonctionne entierement.

Le plugin à la premiere configuration va faire un backup de votre fichier utilisateurs (user.xml => user.xml.back) et crèer un fichier CSV (username.csv dans le dossier du plugin) avec ses entêtes, celui-ci vous permettra d'ajouter par lot des utilisateur VIP.Voir plus bas pour le detail
Configuration

Le plugin vous permet de choisir entre plusieurs configuration d'espaces V.I.P., Vous pouvez opter pour rendre privé:

   * l'espace Blog, si votre page d'acceuil est une page statique sinon c'est votre page d'acceuil! ;) ,
   * les pages Catégories et Articles ,
   * les pages Statiques
   * ou désactiver tout les espaces privée en selectionnant Aucune (valeur par défaut à l'activation du plugin)

## Traitement par lot de nouveaux V.I.P.

Le traitement par lot s'effectue sur la structure d'un fichier au format CSV, ce type de fichier peut-être ouvert et editer avec de nombreux editeurs de texte ou de feuille de calcul. Les 2 programmes plus connus du grand publics sont Excel(MS) et OpenOffice calc.

Vous pouvez, soit télecharger le fichier du plugin et l'editer dans votre programme favori en ajoutant vos utilisateurs **ligne par ligne** en respectant **les colonnes**   ou directement dans l'éditeur du plugin en respectant la syntaxe.

## résumé et info à propos de l'édition 'Ajout utilisateurs'

   1.  Conservez les entêtes de la premiere ligne,Login;Name;Password;Email cette ligne n'est pas enregistrée comme Utilisateur mais vous rapelle la syntaxe à respecter.
   2.  Il y a quatre champs séparés par un point virgule, ces champs vont créer les utilisateurs avec leur adresse mail et mot de passe respectif .
   3.  Le mot de passe sera crypté à l'enregistrement, si il est perdu par l'utilisateur et selon la configuration native de votre PluXml, son adresse mail si valide pourra lui servir à en créer un nouveau.
   4.  L'enregistrement ne gére pas les doublons, mais ne finalisera pas un traitement en lot si une ligne est incompléte ou comporte une erreur de syntaxe
   5.  Lorsque vous désactiver le Plugin, tous vos utilisateurs V.I.P., n'ayant plus d'accés privé réservé, voient leur compte désactivé afin de ne plus apparaitre dans la partie 'Comptes Utilisateurs' de l'administration.
   6.  Lorsque vous activer le Plugin, tous les comptes V.I.P. déjà enregistrés seront réactiver
   7.  Pour effacer ou modifier un Utilisateur, cela se passe par la page 'Comptes Utilisateurs' de l'administration.
