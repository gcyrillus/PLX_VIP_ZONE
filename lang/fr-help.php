<?php if(!defined('PLX_ROOT')) exit; ?>
<style>
  .gridx1 {
    display: grid;
    justify-content: center;
    gap: 0.5em;
    max-width: 1000px;
    margin: auto;
  }

  .gridx2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    max-width: 900px;
    margin: auto;
    gap: 1em;
    text-align: center;
    background: linear-gradient(black, gray) 50% 50% / 2px 100% no-repeat;
    border: solid 2px gray;
    align-items: center;
  }

  .gridx2 img {
    order: 2;
    grid-row: 3;
    margin: auto;
    max-width: 100%;
    border: solid green;
  }

  .gridx2 strong {
    display: block;
    transform: translatex(50%);
    background: tomato;
  }

  .gridx1 h3 {
    min-width: 70%;
    width: max-content;
    max-width: 95%;
    border-bottom: solid 1px #258FD6;
    line-height: 0.7em;
  }
</style>
<section class="gridx1">
  <h2>Aide</h2>
  <p>Le plugin "Espace Privée" permet de rendre tout ou partie de votre site accessible uniquement à des utilisateurs bénificiant d'un accés V.I.P..</p>

  <p>A l'activation, un nouveau profil apparait pour vos utilisateurs. Vous pouvez ajouter un utilisateur qui aura accès aux zones privatisés sans avoir de droits ni pouvoir accéder à la partie administration de votre site.</p>
  <p>La seule page de l'administration qui lui sera accessible sera la page authentification.</p>

  <h3>Prérequis</h3>
  <p><strong>Comme tout les plugins, dés l'activation il vous faut configurer votre plugin de façon à ce qu'il fonctionne entièrement.</strong></p>
  <p>Au premier accès à la page de configuration, le plugin va automatiquement valider sa configuration en faisant un backup de votre fichier utilisateurs <small>(user.xml => user.xml.bak)</small> et crèer un fichier CSV <small>(username.csv dans le dossier du plugin)</small> avec ses entêtes, celui-ci vous permettra d'ajouter par lot des utilisateur VIP.<small>Voir plus bas pour le détail</small>.<br> Vous pouvez dés lors configurer vos zones privatisées ou créer des pages statiques individuelles privatisées. </p>
  <p>&Agrave; la désacitvation du plugin, le plugin va désactivé, touts les utilisateurs VIP et les pages statiques individuelles VIP sans les effacer ni toucher aux fichiers <code>users.xml</code> et <code>users.xml.bak</code>.</p>
  <p>&Agrave; l'activation ou réactivation du plugin, le plugin va activé, si il y a, tous les utilisateurs VIP et pages statiques individuelles VIP qu'il trouvera.</p>
  
  <h3>Configuration</h3>
  <p>Le plugin vous permet de choisir entre plusieurs configuration d'espaces V.I.P., Vous pouvez opter pour rendre privé:</p>
  <ol>
    <li> l'espace <b>Blog</b>, si votre page d’accueil est une page statique </b>.</li>
    <li> les pages <b>Catégories et Articles</b>.</li>
    <li> les pages <b>Statiques</b>. </li>
    <li>ou désactiver tout les espaces privée en sélectionnant <b>Aucune</b> <small>(valeur par défaut à l'activation du plugin)</small>.</li>
    <li>Une ou plusieurs <b>page statique spécifique</b> que vous pourrez éditer et activer dans l'administration à l'onglet <a href="/core/admin/statiques.php">Pages Statiques</a>.</li>
  </ol>.</p>

  <h3>&Eacute;dition d'une pages statique privatisée.</h3>
  <p>La création d'une page statique privée la relie au groupe de pages VIP, ou elles se trouveront toutes. Dans le thème par défaut de PluXml, cela génere un sous menu déroulant au menu principal intitulé VIP.</p>
  <p>&Agrave; la création , ces pages sont inactives et contienne le script nécessitant l'authentification pour qu'un visiteur puisse y accéder.Dans la page d'édition <a href="/core/admin/statiques.php">Pages Statiques</a>, ne touchez pas à cette portion:
  <code style="font-size:0.7em;display:block;margin:0.5em auto;width:max-content;max-width:100%;">&lt;?php if (!isset($_SESSION['profil']) ) {$_SESSION['pageRequest'] = $_SERVER['REQUEST_URI'] ; header("Location: /core/admin/");} ?></code>. Vous pouvez editer tout ce qui se trouve sous cette ligne et y placer vos scripts et contenus.</p>
  
  <h3>Traitement par lot de nouveaux V.I.P.</h3>
  <p>Le traitement par lot s'effectue sur la structure d'un fichier au format CSV, ce type de fichier peut-être ouvert et éditer avec de nombreux éditeurs de textes ou de feuille de calcul ou éditer en direct dans le plugin. Les 2 programmes plus connus du grand publics sont Excel(MS) et OpenOffice calc.</p>
  
<h4>Vous pouvez:</h4>
<ol>
  <li><p>Télécharger le fichier du plugin et l’éditer dans votre programme favori en ajoutant vos utilisateurs ligne par ligne,<br> puis le renvoyer sur le serveur pour que le plugin le traite. </p>
    <p>le point virgule <big><code>;</code></big> est utilisé comme seul séparateur de champ. <small>veillé à configurer votre éditeur en excluant les espaces ou les virgules</small> </p>
  </li>
  <li>
    Ajouter vos utilisateurs dans l’éditeur du plugin en respectant la syntaxe de l'entête avec un enregistrement par ligne.
  </li>
</ol>
    <aside>
      <h4>La syntaxe utilisée</h4>
      <ol>
        <li>La première ligne est l'entête de votre tableau d'enregistrement</li> 
        <li>1 seul enregistrement par ligne en respectant le tableau d'entête.</li>        
      </ol>
    </aside>
  </div>
  <h3>résumé et info à propos de l'édition <i>'Ajout utilisateurs'</i></h3>
  <ol>
    <li>Conservez les entêtes de la première ligne,<code>Login; Name; Password; Email; Infos</code> cette ligne n'est pas enregistrée comme Utilisateur mais affiche les entêtes de votre tableau dans un éditeur ou vous rappelle la syntaxe à respecter dans un éditeur texte ou en ligne . </li>
    <li>Il y a cinq champs séparés par un point virgule, ces champs vont créer les utilisateurs avec leur adresse mail et mot de passe respectif .<b>Les quatres premier champs sont obligatoire</b>, Le champ Infos est optionnel et peut-être omis.</li>
    <li> Le mot de passe sera crypté à l'enregistrement, si il est perdu par l'utilisateur et selon la configuration native de votre PluXml, son adresse mail <small>si valide</small> pourra lui servir à en créer un nouveau.</li>
    <li>L'enregistrement vérifie , la présence des 4 premiers champs obligatoires, les doublons et la validité de l’e-mail. Le plugin ne finalisera pas un traitement en lot si une ligne est incomplète ou comporte une erreur de syntaxe, un message vous indiquera l'erreur et le numéro de ligne où se trouve cette erreur pour la corrigé dans l'éditeur en ligne.</li>
    <li>Lorsque vous désactiver le Plugin, tous vos utilisateurs V.I.P., n'ayant plus d’accès privé réservé, voient leur compte désactivé afin de ne plus apparaitre dans la partie 'Comptes Utilisateurs' de l'administration. Si vous avez des pages statiques privées, elles seront aussi désactivées.</li>
    <li>Lorsque vous activer le Plugin, tous les comptes V.I.P. déjà enregistrés seront réactivés ainsi que les pages statiques privées si il y en a.</li>
    <li>Pour effacer ou modifier un Utilisateur, cela se passe par la page '<a href="/core/admin/parametres_users.php">Comptes Utilisateurs</a>' de l'administration.</li>
  </ol>

<h3>Ajout d'utilisateur VIP </h3>
      <p>Pour l'ajout d'un petit nombre d'utilisateur, la page '<a href="/core/admin/parametres_users.php">Comptes Utilisateurs</a>' de l'administration convient parfaitement en selectionnant le profil VIP pour le nouveau compte.</p>

<h3>Repositionnement du plugin.</h3>
<p>A chaque activation d'un plugin dans l'administration de PluXml, celui-ci est placé en fin de liste des plugins . Ceux-ci sont chargés les uns après les autres. En configurant votre plugin en mode "pages Statiques", il peut y avoir une interaction avec d'autres plugin utilisant le mode  des pages statiques pour s'afficher, il seront alors aussi privatisés. Pour éviter cela, vous pouvez replacer le plugin en première position pour qu'il soit charger en premier.</p>

<h3>Informations du plugin</h3>
<p>En fin de page vous retrouverez quelques informations à propos du plugin, nombre d'utilisateur , nombre de pages statiques mais aussi  la <code>class</code> ajoutée a <code>body</code> vous permettant d'appliquer des styles spécifiques si le visiteur est connecté comme VIP ou non.</p>

<h3>Déconnexion d'un utilisateur VIP</h3>
<p>Un bouton de déconnexion apparait dans le menu principal lorsqu'un utilisateur avec le profil VIP est connecté. En cliquant dessus, le visiteur est déconnecter et renvoyé sur la page d'acceuil</p>
<p>La durée de session de la connexion est de 2 heures, c'est celle utilisée par PluXml</p>
</section>