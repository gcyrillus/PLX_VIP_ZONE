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
  <h2>Help</h2>
  <p>The "Espace Privée (Private Space)" plugin makes it possible to make all or part of your site accessible only to users benefiting from V.I.P. access.</p>

  <p>At activation, a new profile appears for your users. You can add a user who will have access to the privatized areas without having rights or being able to access the administration part of your site.</p>
  <p>The only page of the administration that will be accessible to him will be the authentication page.</p>

  <h3>Prerequisite</h3>
  <p><strong>Like all plugins, as soon as you activate you need to configure your plugin so that it works fully.</strong></p>
  <p>At the first access to the configuration page, the plugin will automatically validate its configuration by making a backup of your users file<small>(user.xml => user.xml.bak)</small> and create a CSV file<small>(username.csv in the plugin folder)</small> with its headers, it will allow you to add VIP users in batches. <small>See below for details</small>. <br>You can then configure your privatized areas or create individual privatized static pages. </p>
  <p>On de-actication of the plugin, the plugin will disable, all VIP users and individual static VIP pages without erasing them or touching the files<code>users.xml</code> &  <code>users.xml.bak</code>.</p>
  <p>On activation or reactivation of the plugin, the plugin will activate, if there are, all VIP users and individual VIP static pages that it will find.</p>
  
  <h3>Configuration</h3>
  <p>The plugin allows you to choose between several configuration of V.I.P. spaces, You can opt to make private:</p>
  <ol>
    <li> the <b>Blog</b> space, if your home page is a static page</b>.</li>
    <li> the <b>Categories and Articles</b> pages.</li>
    <li> <b>Static</b> pages.</li>
    <li>or disable all private spaces by selecting <b>None</b> <small> (default value when activating the plugin)</small>.</li>
    <li>One or more <b>specific static page</b> that you can edit and enable in administration on the <a href="/core/admin/statiques.php">Statics Pages</a> tab.</li>
  </ol>.</p>

  <h3>edition of a privatized static page.</h3>
  <p>Creating a private static page links it to the VIP group of pages, where they will all be found. In pluXml's default theme, this generates a drop-down sub-menu to the main menu labeled VIP.</p>
  <p>&Agrave;  At creation, these pages are inactive and contain the script that requires authentication for a visitor to access. In the edit page <a href="/core/admin/statiques.php">Sal</a> Pages, do not touch this portion:
  <code style="font-size:0.7em;display:block;margin:0.5em auto;width:max-content;max-width:100%;">&lt;?php if (!isset($_SESSION['profil']) ) {$_SESSION['pageRequest'] = $_SERVER['REQUEST_URI'] ; header("Location: /core/admin/");} ?></code>. You can edit everything below this line and place your scripts and content there.</p>
  
  <h3>Batch processing of new V.I.P.</h3>
  <p>Batch processing is done on the structure of a file in CSV format, this type of file can be opened and edited with many text or spreadsheet editors or edit live in the plugin. The 2 most well-known programs for the general public are Excel(MS) and OpenOffice calc.</p>
  
<h4>You can:</h4>
<ol>
  <li><p>Download the plugin file and edit it in your favorite program by adding your users line by line, <br> then send it back to the server for the plugin to process.</p>
    <p>the semicolon <big><code>;</code></big> is used as the only field separator. <small> make sure to configure your editor excluding spaces or commas</small> </p>
  </li>
  <li>
    Add your users in the plugin editor respecting the syntax of the header with one record per line.
  </li>
</ol>
    <aside>
      <h4>The syntax used</h4>
      <ol>
        <li>The first row is the header of your record board</li> 
        <li>Only 1 record per row respecting the header table.</li>        
      </ol>
    </aside>
  </div>
  <h3>summary and info about editing <i>'Users'</i></h3>
  <ol>
    <li>Keep the headers of the first line, <code>Login; Name; Password; Email; Infos</code> this line is not saved as a User but displays the headers of your table in a spreadsheet editor or reminds you of the syntax to follow in a text or online editor. </li>
    <li>There are five fields separated by a semicolon, these fields will create users with their respective email address and password. <b>The first four fields are required</b>, The Info field is optional and may be omitted.</li>
    <li> The password will be encrypted at registration, if it is lost by the user and according to the native configuration of your PluXml, his email address <small>if valid</small> can be used to create a new one.</li>
    <li>The registration checks, the presence of the first 4 mandatory fields, duplicates and the validity of the email. The plugin will not finalize a batch process if a line is incomplete or has a syntax error, a message will tell you the error and the line number where this error appears so it can be corrected in the online editor.</li>
    <li>When you deactivate the Plugin, all your V.I.P. users, no longer having reserved private access, have their account deactivated in order to no longer appear in the 'User Accounts' part of the administration. If you have private static pages, they will also be disabled.</li>
    <li>When you activate the Plugin, all V.I.P. accounts already registered will be reactivated as well as private static pages if there are any.</li>
    <li>To delete or modify a User, this happens through the page '<a href="/core/admin/parametres_users.php">User Accounts</a>' of the administration.</li>
  </ol>

<h3>Adding VIP user</h3>
      <p>For the addition of a small number of users, the page '<a href="/core/admin/parametres_users.php">User Accounts</a>' of the administration is perfectly suitable by selecting the VIP profile for the new account.</p>

<h3>Repositioning of the plugin.</h3>
<p>Each time a plugin is activated in the PluXml administration, it is placed at the end of the list of plugins. These are loaded one after the other. By configuring your plugin in "Static pages" mode, there may be an interaction with other plugins using the static pages mode to display, they will then also be privatized. To avoid this, you can put the plugin back in the first position so that it is loaded first.</p>

<h3>Plugin information</h3>
<p>At the end of the page you will find some information about the plugin, number of users, number of static pages but also the <code>class</code> added to <code>body</code> allowing you to apply specific styles if the visitor is connected as VIP or not.</p>

<h3>Logging out of a VIP user</h3>
<p>A logout button appears in the main menu when a user with the VIP profile is logged in. By clicking on it, the visitor is disconnected and returned to the home page.</p>
<p>The session duration of the connection is 2 hours, this is the value set by PluXml</p>
</section>