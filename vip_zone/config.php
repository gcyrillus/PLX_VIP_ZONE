<?php if(!defined("PLX_ROOT")) exit; 


$nbUser=0;	

// configuation : fait un backup du fichier users.xml
	if (!file_exists(PLX_ROOT.PLX_CONFIG_PATH.'users.xml.bak')) {
		$file = PLX_ROOT.PLX_CONFIG_PATH.'users.xml';
        $newfile = PLX_ROOT.PLX_CONFIG_PATH.'users.xml.bak';

		if (!copy($file, $newfile)) {
			echo "La copie $file du fichier a échoué...\n";
		}
		else {
		$jour = date("d/m/Y");
        $plxPlugin->setParam('set', '<p style="color:tomato;background:#E7F16E;width:max-content;max-width:80%;padding-right:1em;margin:0 0 0.5em;"><i style="color:green"> <b>'.$jour. '</b> '.L_SAVE_FILE_SUCCESSFULLY.' </i> :<br> '.PLX_ROOT.PLX_CONFIG_PATH.'<b>users.xml.bak </b> </p>', 'cdata');
        $plxPlugin->saveParams();
        header('Location: parametres_plugin.php?p='.$plugin);
        exit;
		}
	}
	
  if(!empty($_GET['ploc'])) {
	  
	if(!empty($_POST['csv'])) {	  
		$fileupdate = fopen(PLX_PLUGINS.$plugin."/username.csv", "w")  ;
		fwrite($fileupdate, $_POST['csv']);
		$fileCsv=$_POST['csv'];
		fclose($fileupdate); 	
	}  
	updateFromCsv(); 
  }
  
if(!empty($_POST['privatize'])) {

	
	if($_POST['privatize'] =="catart") {
		$vip = "catart";
	}
	else if ($_POST['privatize'] =="stat") {
		$vip ="static";
	}
	else if ($_POST['privatize'] =="blog") {
		$vip ="blog";
	}
	else if ($_POST['privatize'] =="none") {
		$vip ="none";
	}

        $plxPlugin->setParam('privatize', $vip, 'cdata');
        $plxPlugin->saveParams();
		header('Location: parametres_plugin.php?p='.$plugin);
	exit;
    }	
	
	

if  (@($open = fopen(PLX_PLUGINS.$plugin."/username.csv", "r")) !== FALSE) {
	 $fileCsv = file_get_contents(PLX_PLUGINS.$plugin."/username.csv", true);
}
else {
	$open = fopen(PLX_PLUGINS.$plugin."/username.csv", "w") ;
	$fileCsv="login;name;password;email\n";
	fwrite($open, $fileCsv);
	fclose($open);
}




  ?>
  

  <?php
     $configZone = $plxPlugin->getParam('privatize');
	 if($configZone =="none")   {$none=" selected ";}   else {$none="";}
	 if($configZone =="catart") {$catart=" selected ";} else {$catart="";}
	 if($configZone =="static") {$static=" selected ";} else {$static="";}
	 if($configZone =="blog")   {$blog=" selected ";}   else {$blog="";} 
	?>
	<div  class="grid-center">
	<div>
		<form action="" method="post">
		<fieldset><legend>
			<?php $plxPlugin->lang("L_VIP_ZONE_PRIVATIZED"); ?>
			</legend><div class="gridx2">
			<label><?php $plxPlugin->lang("L_VIP_ZONE_SELECTION"); ?></label>
			<select name="privatize">
				<option value="none" <?php  echo $none; ?>     ><?php $plxPlugin->lang("L_ZONE_NONE2");   ?></option>
				<option value="blog"  <?php  echo $blog; ?>    ><?php $plxPlugin->lang("L_ZONE_BLOG");    ?></option>
				<option value="catart" <?php  echo $catart; ?> ><?php $plxPlugin->lang("L_ZONE_CAT_ART"); ?></option>
				<option value="stat"  <?php  echo $static; ?>  ><?php $plxPlugin->lang("L_ZONE_STATIC");  ?></option>
			</select>
			<br>
			<input value="<?php  $plxPlugin->lang("L_SAVE_VIP_ZONE_SELECTED"); ?>"  type="submit">
				</div>
				</fieldset>
		</form>
	</div>
	<div class="visitorUser">
		<form enctype="multipart/form-data" action="<?php echo PLX_PLUGINS.$plugin.'/upCSV.php'; ?>" method="post"  >
			<fieldset>
				<legend><?php  $plxPlugin->lang("L_BATCH_NEW_VIPS"); ?></legend>
				<div class="gridx2">
					<button type="button" name="blobupload" style="margin-top:auto;background:green;color:ivory;" onclick="dl_CSV();" ><?php $plxPlugin->lang("L_DL_101_CSV_FILE") ?></button>
					<?php if(!empty($_GET['upmsg']) &&  ($_GET['upmsg']=="fail")){ echo '<p class="alert red row1-2col" >'.L_SAVE_FILE_ERROR.' <br> '.L_PLUGINS_REQUIREMENTS.': '.L_FILE_REQUIRED.'  <b>username.csv</b>.</p>'; }  ?>
					<?php if(!empty($_GET['upmsg']) && ($_GET['upmsg']=="sucess")){ echo '<p class="alert green row1-2col">'.L_SAVE_FILE_SUCCESSFULLY.'</p>'; }  ?>
					<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
					<input name="userfile" type="file" accept=".csv"  required ><br>
					<input type="submit" value="<?php $plxPlugin->lang("L_UPLOAD_NEW_CSV_FILE"); ?>" />
				</div>
			</fieldset>
		</form>

	<?php
	echo '<form action="parametres_plugin.php?p='.$plugin.'&ploc=envoyer" method="post" >
		<fieldset><legend> ';
			$plxPlugin->lang("L_SAVE_VIP_PROFILE_FILE_ONLINE");
			echo ':</legend>
		<textarea name="csv"  name="csv" >'.  $fileCsv . '</textarea>
		<br>
		<input value="';
			$plxPlugin->lang("L_UPDATE_NEW_USERS_CSV");
			echo '" type="submit">
			</fieldset>
	</form>

	</div>
	<footer>
		<p>';
			$plxPlugin->lang("L_INFO_CONFIG_PLUGIN");
			echo '</p>
		<ol>
			<li>';
			$plxPlugin->lang("L_USER_FILE");
			echo '<br>'.PLX_ROOT.PLX_CONFIG_PATH.'<b>users.xml</b></li>
			<li>';
			$plxPlugin->lang("L_BACKUP_USER_FILE_FIRST_INSTALL");
			echo '<br>'.$plxPlugin->getParam('set').'</li>
			<li>';
			$plxPlugin->lang("L_BATCH_FILE_NAME_DIRECTORY");
			echo '<br>'.PLX_PLUGINS.$plugin.'/<b>username.csv</b></li>
		</ol>
	</footer>
	</div>';	
/*
 <!-- ';
			$plxPlugin->lang("");
			echo ' -->
*/	

class SimpleXMLExtended extends SimpleXMLElement {
// from https://web.archive.org/web/20110223233311/http://coffeerings.posterous.com/php-simplexml-and-cdata	
  public function addCData($cdata_text) {
    $node = dom_import_simplexml($this); 
    $no   = $node->ownerDocument; 
    $node->appendChild($no->createCDATASection($cdata_text)); 
  } 
}
function updateFromCsv() {

// on verfie que nos fichiers sont accessibles
if ((file_exists(PLX_ROOT.PLX_CONFIG_PATH.'users.xml')) && (($open = fopen(PLX_PLUGINS."/vip_zone/username.csv", "r")) !== FALSE) ) {
	




	// on commence avec le fichier csv  
    while (($data = fgetcsv($open, 1000, ";")) !== FALSE)     {        
      $array[] = $data; 
    }  
    fclose($open);

	// on recupere le fichier XML
    $xml = file_get_contents(PLX_ROOT.PLX_CONFIG_PATH.'users.xml', true);

    // on charge le fichier xml
	$doc = new SimpleXMLExtended($xml); 
    // on compte les enregistrements
	$kids = $doc->children();
	$nbUser = count($kids);


  // on boucle sur les lignes du fichiers CSV pour récuperer les données et les ajouter aux données existantes 

foreach($array as $i => $line){ 


		if($i >0) { // on passe la premiere ligne ou sont  stockées les entêtes de colonnes.
				  $nbUser++;

			//foreach($line  as $key => $value){
		 	if((!isset($line[0])) or (!isset($line[1])) or (!isset($line[2])) or (!isset($line[3]))) {
		    echo	'<p class="alert red "> Syntax error to fix on user record line (missing field): <b> '.$nbUser  .'</b></p>';
			$nbUser--;
	        } else {
			
	 
			$element = $doc->addChild('user'); 
			$element->addAttribute('number', str_pad($nbUser, 3,'0', STR_PAD_LEFT)  );
			$element->addAttribute('active', '1' );
			$element->addAttribute('profil', '5' );
			$element->addAttribute('delete', '0' );	 
			
			$login = $element->addChild('login'); 
			$login->addCData($line[0]); 

			$name = $element->addChild('name'); 
			$name->addCData($line[1]); 

			$infos = $element->addChild('infos'); 
			$infos->addCData(''); 

	//$salt='';
	$salt = plxUtils::charAleatoire(10);
	
	//$pwd=$line[2];
	$pwd=sha1($salt.md5($line[2]));			
			
			$password = $element->addChild('password'); 
			$password->addCData($pwd); 
			

			$salted = $element->addChild('salt'); 
			$salted->addCData($salt); 

			$email = $element->addChild('email'); 
			$email->addCData($line[3]); 

			$lang = $element->addChild('lang'); 
			$lang->addCData('fr'); 

			$password_token = $element->addChild('password_token'); 
			$password_token->addCData(''); 

			$password_token_expiry = $element->addChild('password_token_expiry'); 
			$password_token_expiry->addCData(''); 
			}

	   
	   }  
	 }
			//On refait l'indentation du fichier  parceque c'est plus joli
			$xmlDoc = new DOMDocument ();
			$xmlDoc->preserveWhiteSpace = false;
			$xmlDoc->formatOutput = true;
			$xmlDoc->loadXML ( $doc->asXML() );
			// on sauvegarde le fichier xml mis à jour.
			$xmlDoc->save(PLX_ROOT.PLX_CONFIG_PATH.'users.xml');
			$nbUser = $nbUser -1 ;
			echo '<p class="alert green ">'.L_SAVE_FILE_SUCCESSFULLY.' - '.L_CONFIG_USERS_NEW.' <b> '.$nbUser  .'</b></p>';

}
	 else {
    exit(L_SAVE_FILE_ERROR.' user.xml / username.csv.');
}
}

?>

				 <script>
				 function dl_CSV() {
					let link = document.createElement("a");
					link.download = "username.csv";
					let blob = new Blob(["Login;Name;Passsword;Email\n"], {type: "text/csv"});
					link.href = URL.createObjectURL(blob);
					link.target="_blank"
					link.click();
					URL.revokeObjectURL(link.href);
					}
				</script>