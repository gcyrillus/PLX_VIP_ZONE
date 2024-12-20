<?php
const PLX_ROOT = '../../';
const PLX_CORE = PLX_ROOT .'core/';
const SESSION_LIFETIME = 7200;


include PLX_ROOT.'config.php';
include PLX_CORE.'lib/config.php';


# On verifie que PluXml est installé
if(!file_exists(path('XMLFILE_PARAMETERS'))) {
	header('Location: '.PLX_ROOT.'install.php');
	exit;
}

# On démarre la session
session_start();
setcookie(session_name(),session_id(),time()+SESSION_LIFETIME, "/", $_SERVER['SERVER_NAME'], isset($_SERVER["HTTPS"]), true);

# Test sur l'identification
	if (!isset($_SESSION['profil'])){
		header('Location:'.PLX_CORE.'admin/auth.php?p='.htmlentities($_SERVER['REQUEST_URI']));
		exit;
	}


# maintenant on bouge ce fichier  seulement si c'est un fichier username.csv
if($_FILES['userfile']['name'] =='username.csv') {
	$uploaddir = PLX_ROOT.'plugins/vip_zone/';
	$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
	// renvoi vers la page de config du plugin pour traitement des enregistrements
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {		
		header("location:".PLX_CORE."admin/parametres_plugin.php?p=vip_zone&ploc=envoyer"); 
		exit;
	} 
}
else {  
	// renvoi vers la page du plugin sans traitement
	header("location:".PLX_CORE."admin/parametres_plugin.php?p=vip_zone&upmsg=fail"); 
	exit;
}

// note: fonction eventuellement à passer dans la class du plugin.
?>