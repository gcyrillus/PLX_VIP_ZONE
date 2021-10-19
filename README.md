# PLX_VIP_ZONE <sup><sub>intitulé *Espace Privé* </sub></sup>
Plugin pour PluXml , ajoute la gestion d'espace privé ,blog,catégories ét articles ou pages statiques aux choix, inspiré du précédent squelette: https://github.com/gcyrillus/plx-log-visitor

**Le plugin "Espace Privée" permet de rendre tout ou partie de votre site accessible uniquement à des utilisateurs bénéficiant d'un accès V.I.P..**

A l'activation, un nouveau profil apparait pour vos utilisateurs. Vous pouvez ajouter un utilisateur qui aura accès aux zones privatisés sans avoir de droits pour accéder à la partie administration de votre site.

La seule page de l'administration qui lui sera accessible sera la page authentification.

Comme tout les plugins, dés l'activation il vous faut configurer votre plugin de façon à ce qu'il fonctionne entièrement.

Le plugin à la première configuration va faire un backup de votre fichier utilisateurs (`user.xml` => `user.xml.back`) et créer un fichier CSV (username.csv dans le dossier du plugin) avec ses entêtes, celui-ci vous permettra d'ajouter par lot des utilisateur VIP. *Voir plus bas pour le détail* 

## Configuration

Le plugin vous permet de choisir entre plusieurs configuration d'espaces V.I.P., Vous pouvez opter pour rendre privé:

   * l'espace Blog, si votre page d’accueil est une page statique sinon c'est votre page d’accueil ! ;) ,
   * les pages Catégories et Articles ,
   * les pages Statiques
   * ou désactiver tout les espaces privée en sélectionnant Aucune (valeur par défaut à l'activation du plugin)
   * Il y a aussi la possibilité de créer uniquement des pages statiques privée dans un group dédié.

## Traitement par lot de nouveaux utilisateurs V.I.P.

Le traitement par lot s'effectue sur la structure d'un fichier au format CSV, ce type de fichier peut-être ouvert et éditer avec de nombreux éditeurs de texte ou de feuille de calcul. Les 2 programmes plus connus du grand publics sont Excel(MS) et OpenOffice calc. Le caractéres de séparation des champs utilisé est le point virgule `;`.

Vous pouvez:
   1.  Télécharger le fichier ***username.csv*** et l’éditer dans votre programme favori en ajoutant vos utilisateurs **ligne par ligne** et en respectant **les colonnes** et son format (caractheres de champ `;`), puis en le renvoyant sur le serveur. <up>Un lien de téléchargement est affiché dans la page de configuration.</sup>
   2.  Renvoyer sur le serveur le fichier modifié , il sera traité automatiquement puis vidé.
   3.  Directement dans la page *configuration*  du plugin dans l'editeur affiché en respectant la syntaxe des entêtes en première ligne ,  **ligne par ligne**  en séparant chaque champs (au nombre de quatre) d'une double virgule  <kbd>**;**</kbd> en faisant la saisie au clavier. <sup><sub>(l'editeur embarque https://github.com/codemirror pour rendre l'édition en ligne plus confortable)</sup></sup>

  **Note** . <del>Le plugin ne vérifie pas les doublons ni la validité des adresses mails</del> En cas de doublon, un message s'affiche indiquant le champ et la ligne à corrigé.

## résumé et info à propos de l'édition 'Ajout utilisateurs'

   1.  Conservez les entêtes de la première ligne du fichier CSV:  `Login;Name;Password;Email` . Cette ligne n'est pas enregistrée comme Utilisateur mais vous rappelle la syntaxe à respecter.
   2.  Il y a quatre champs séparés par un point virgule, ces champs vont créer les utilisateurs avec leur adresse mail et mot de passe respectif .
   3.  Le mot de passe sera crypté à l'enregistrement, si il est perdu par l'utilisateur et selon la configuration native de votre PluXml, son adresse mail si valide pourra lui servir à en créer un nouveau.
   4.  L'enregistrement ne gère pas les doublons, mais ne finalisera pas un traitement en lot si une ligne est incomplète ou comporte une erreur de syntaxe, *le n° de lignes est alors indiqué* .
   5.  Lorsque vous désactiver le Plugin, tous vos utilisateurs V.I.P., n'ayant plus d’accès privé réservé, voient leur compte désactivé afin de ne plus apparaitre dans la partie 'Comptes Utilisateurs' de l'administration.
   6.  Lorsque vous activer le Plugin, tous les comptes V.I.P. déjà enregistrés seront réactivés.
   7.  Pour effacer ou modifier un Utilisateur, cela se passe par la page 'Comptes Utilisateurs' de l'administration.
   8.  Un lien de déconnexion est ajouté au menu principal lorsque vous êtes authentifié, gestion des langues possibles: dispo: **fr** et **en** .
   9.  Le titre du formulaire de connexion est personnalisé.
   10.  Un VIP qui se déconnecte est renvoyé sur la page d'accueil
   11.  Le plugin vérifie la compatibilité de la configuration de son mode privée avec la configuration de PluXml. *(blog ou pas blog ? )*
   12.  Ajout d’icônes devant les contenus privatisés si non connecté. (fontawesome 5).
   13.  Cache le contenu des articles et des commentaires si non connecté.
   14.  Ajout de page statique privatisée dans un groupe (VIP). <br>Pour utiliser ces pages , il faut les éditer dans la page d'administration "pages statiques" et les activer pour les finaliser et les afficher sur le site.
   15.  A la désactivation du plugin , les pages statiques privées du groupe <b>VIP</b>  sont aussi désactivées.
   16.  A l'activation du plugin, si des pages statiques privatisées dans le groupe <b>VIP</b> existent, elles sont réactivées.
   17. Ajout du champs infos dans la gestion via le fichier CSV (ou edition nouvelle utilisateur dans la page configuration).
   18. Verification des doublons sur les champs login, name et email.
   19. option de repositionement du plugin en premiere position au chargements des plugins d'un simple clique.

___

### Note à l'installation et sa position dans la page d'administration des plugins. 
Selon l'ordre du plugin dans la page `http://votrePluXml.com/core/admin/parametres_plugins.php`  si vous utilisez un autre plugin , comme ***MySearch: moteur de recherche***, qui s'ajoute en lien dans le menu,ce type de plugin sera considéré comme une page statique et sera privatisé si l'option de configuration est calé sur ***Pages Statiques*** .
 Pour garder les pages de ce type de plugin en accès libre, il faut que le plugin `vip_zone , intitulé *'Espace Privée'* soit placé devant. A défaut, tous les plugins placés derrière et se greffant au menu seront considérés comme des pages statiques privatisables et accéssible seulement aprés connexion.

Pour replacer le plugin en amont de vos autre plugins, un glisser/déposer suffit ou bien indiquez le numéro de position **-1** pour le remettre en tête de liste,. En fin, sauvegardez la modification de la liste des plugins. **Vous pouvez repassé le plugin en premiere position dans la page de configuration/administration du plugin.


___
Un fichier `username.csv` de 509 utilisateurs fictifs est inclus dans le dossier `vip_zone/test-CSV_file` du plugin pour vos tests. 

Ce fichier comportent des erreurs qu'un message vous notifiera, il vous suffit de vous rendre au(x) numéro(s) de ligne(s) indiquée(s) et de faire la correction dans l'éditeur texte du plugin.

Aprés vos tests avec ce fichier fictif, vous pouvez effacer le fichier `users.xml` et renommer le fichier `users.xml.back` en `users.xml` pour retrouver votre liste d'utilisateurs d'origine.
___

Un post est ouvert sur le forum de PluXml à propos de ce plugin : https://forum.pluxml.org/discussion/7056/plugin-vip-zone-options-de-privatisation-de-votre-pluxml
