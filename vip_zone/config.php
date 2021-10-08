<?php if(!defined("PLX_ROOT")) exit; 
	$plxAdmin = plxAdmin::getInstance();
	$plxMotor = plxMotor::getInstance();
			
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
		//maj du fichier username.csv		
	if(!empty($_GET['ploc'])) {	  
	if(!empty($_POST['csv'])) {	 

		$fileupdate = fopen(PLX_PLUGINS.$plugin."/username.csv", "w")  ;
		fwrite($fileupdate, $_POST['csv']);
		$fileCsv=$_POST['csv'];
		fclose($fileupdate); 	
	}  
	updateFromCsv(); 
  }
 
	if (!empty($_GET['sendIt'])) {
		$plxPlugin->resetPluginsToTop();
		header('Location: parametres_plugin.php?p='.$plugin);			 
	}	
	//maj configuration zone privatisée  
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

//ajout d'une page static privatisé dans le groupe V.I.P.	
if (!empty($_POST['newVipStatic'])) {
	addVipStatic();
}	

// creation du fichier username.csv si absent
if (file_exists(PLX_PLUGINS.$plugin."/username.csv")) {
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
		#verif compatibilité mode PLuXMl et mode privatisé
    	$configZone = $plxPlugin->getParam('privatize');
		#message d'alert , devrait ne jamais s'afficher . Situation en principe gerer en amont sur le formulaire en désactivant les config incompatibles.
		$messageZone='';

		#selectionne le parametre active
			if($configZone =="none")   {$none=" selected "  ;}   					else {$none=""  ;}
			if($configZone =="catart") {$catart=" selected ";}   					else {$catart="";}
			if($configZone =="blog")   {$blog=" selected "  ; $static="disabled ";} else {$blog=""  ;} 
			if($configZone =="static") {$static=" selected "; $blog="disabled";   } else {$static="";}
			if(empty($plxMotor->aConf['homestatic'])) {$blog="disabled"; }
			
        if (isset($plxMotor->aStats[$plxMotor->aConf['homestatic']]) and $configZone =="static") { 
			$static="disabled ";
			$messageZone = "L_UNSET_PRIVATE_ZONE_STATIC_TO_NONE";
			$plxPlugin->setParam('privatize', 'none', 'cdata');
			$plxPlugin->saveParams(); 
		}		
		else if ((empty($plxMotor->aConf['homestatic'] ) and $configZone =="blog")) {
			$blog="disabled"; 
			$messageZone = "L_UNSET_PRIVATE_ZONE_BLOG_TO_NONE";
			$plxPlugin->setParam('privatize', 'none', 'cdata');
			$plxPlugin->saveParams();			
			
		}
	?>
		
		
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" media="screen"/>

	<div  class="grid-center x2">
		<div class="vip-edit-zone">
			<form action="" method="post">
				<fieldset><legend>
					<?php $plxPlugin->lang("L_VIP_ZONE_PRIVATIZED"); ?>
					</legend>
					<div class="gridx2">
						<label><?php $plxPlugin->lang("L_VIP_ZONE_SELECTION"); ?></label>
						<select name="privatize">
							<option value="none" <?php  echo $none; ?>     ><?php $plxPlugin->lang("L_ZONE_NONE2");   ?></option>
							<option value="blog"  <?php  echo $blog; ?>    ><?php $plxPlugin->lang("L_ZONE_BLOG");    ?></option>
							<option value="catart" <?php  echo $catart; ?> ><?php $plxPlugin->lang("L_ZONE_CAT_ART"); ?></option>
							<option value="stat"  <?php  echo $static; ?>  ><?php $plxPlugin->lang("L_ZONE_STATIC");  ?></option>
						</select>
						<p class="alert red"><?php echo $plxPlugin->lang($messageZone); ?></p>
						<input value="<?php  $plxPlugin->lang("L_SAVE_VIP_ZONE_SELECTED"); ?>"  type="submit">
					</div>
				</fieldset>
			</form>
			<form action=" " method="post" autocomplete="off">
				<fieldset><legend><?php echo  $plxPlugin->lang("L_NEW_STATIC_VIP").' <br> '. L_CONFIG_VIEW_HOMESTATIC_ACTIVE  ?></legend>				
					<div class="gridx2"> 
						<div class="gridx2">
							<input type="text"  placeholder="<?php echo L_STATICS_TITLE ; ?>"  name="newVipStatic" >
							<input type="submit" name="update" value="<?php echo L_STATICS_UPDATE ?>" />
						</div>
					</div>
				</fieldset>
				<script>
				let ipt = document.querySelector("[name='newVipStatic']");

				ipt.addEventListener("keyup", function () {
					checkValue(ipt.value, statName_arr);
				});

				//verifie si le nom de page existe
				const statName_arr = [<?php  $noms = $plxMotor->aStats; foreach($noms as $nb => $nom){ echo "'".$nom['name']."',";} echo "'".time(); ?>'];
					function checkValue(value, arr) {
							for (let i = 0; i < arr.length; i++) {
								let name = arr[i];
								if (name == value) {
									ipt.value= prompt('<?php echo L_ERR_STATIC_ALREADY_EXISTS ; ?> : ' + ipt.value +' \n\n<?php echo L_MEDIAS_NEW_NAME .':' ;  ?>');
									checkValue(ipt.value, arr);// juste au cas c'est pas clair
									break;
								}
							}
						}
			</script>
				<aside><?php 
				if (!empty($_POST['newVipStatic'])) {
					echo '<p class="alert orange">'.$plxPlugin->getLang("L_DO_UPDATE_NEW_VIP_STAT").' <a href="'. PLX_ROOT.'core/admin/statiques.php" style="color:#258fd6;">'. L_STATICS_PAGE_TITLE .'</a></p>';
				} else {
				echo '<small><a href="'. PLX_ROOT.'core/admin/statiques.php" style="color:#258fd6;">'. L_STATICS_PAGE_TITLE .'</a></small>';
				}		?>
				</aside>
			</form>
		</div>
		<div class="visitorUser">
			<form enctype="multipart/form-data" action="<?php echo PLX_PLUGINS.$plugin.'/upCSV.php'; ?>" method="post"  >
				<fieldset>
					<legend><?php  $plxPlugin->lang("L_BATCH_NEW_VIPS"); ?></legend>
					<div class="gridx2">
						<button type="button" name="blobupload"  onclick="dl_CSV();" > <?php $plxPlugin->lang("L_DL_101_CSV_FILE") ?></button>
						<?php if(!empty($_GET['upmsg']) &&  ($_GET['upmsg']=="fail")){ echo '<p class="alert red row1-2col" >'.L_SAVE_FILE_ERROR.' <br> '.L_PLUGINS_REQUIREMENTS.': '.L_FILE_REQUIRED.'  <b>username.csv</b>.</p>'; }  ?>
						<?php if(!empty($_GET['upmsg']) && ($_GET['upmsg']=="sucess")){ echo '<p class="alert green row1-2col">'.L_SAVE_FILE_SUCCESSFULLY.'</p>'; }  ?>
						<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
						<input name="userfile" type="file" accept=".csv"  required ><br>
						<button type="submit"> <?php $plxPlugin->lang("L_UPLOAD_NEW_CSV_FILE"); ?></button>
					</div>
				</fieldset>
			</form>
		</div>				
<?php

	echo '	<div class="visitorUser">
		<form action="parametres_plugin.php?p='.$plugin.'&ploc=envoyer" method="post" >
		<fieldset><legend> ';
			$plxPlugin->lang("L_SAVE_VIP_PROFILE_FILE_ONLINE");
			echo ':</legend>
		<textarea name="csv"  id="csv" >'.  $fileCsv . '</textarea>
		<br>
		<input value="';
			$plxPlugin->lang("L_UPDATE_NEW_USERS_CSV");
			echo '" type="submit">
			</fieldset>
		</form>
	</div>';


	# on regarde si le plugin est en premiere position, sinon on affiche le formulaire de debogage.
	$plugPos =  (array)$plxMotor->plxPlugins->aPlugins;
	foreach($plugPos as $key => $value) {
		$firstKey = $key;
		break;// on ne veut que la premiere.
	}

if ($firstKey !=='vip_zone') {echo '
	<div class="resetVipFirst grid-center x2 span2col ">
		<form action="parametres_plugin.php?p='.$plugin.'&sendIt=first" method="post" class="span2col">
			<fieldset>
				<legend>'.$plxPlugin->getLang("L_LOAD_VIP_POSITION").'</legend>
				<p class="alert orange span2col">'.$plxPlugin->getLang("L_LOAD_VIP_WARNING").'</p>
				<label  class="gridx2">'.$plxPlugin->getLang("L_LOAD_VIP_FIRST").'<input type="submit" name="resetToTop" value="'.L_PLUGINS_APPLY_BUTTON.'" ></label>
			</fieldset>
		</form>
	</div>';
}	# fin formulaire débogage

	# infos configurations du plugins
	

			$nbVIP = '0';					
			$nbactVIP = '0';		
			$nbStatVIP = '0';		
			foreach($plxAdmin->aUsers as $users) {
				if ($users['profil']=='5') {$nbVIP++;}
				if (($users['profil']=='5') && ($users['active']=='1')) {$nbactVIP++;}
			}			
			foreach($plxAdmin->aStats as $statpage) {
				if ($statpage['group']=='V.I.P.') {$nbStatVIP++;}
			}
	
echo '
	<footer class="span2col">	
		<table>
			<caption>'.$plxPlugin->getLang("L_INFO_CONFIG_PLUGIN").'</caption>
			<tbody>
				<tr>
					<th>'.$plxPlugin->getLang("L_NBR_REGISTERED_VIP").'</th>
					<td>'.$nbVIP.'</td>
				</tr>
				<tr>
					<th>'.$plxPlugin->getLang("L_NBR_REGISTERED_ACTIVE_VIP").'</th>
					<td>'.$nbVIP.'</td>
				</tr>
				<tr>
					<th>'.$plxPlugin->getLang("L_CLASS_FOR_BODY").'</th>
					<td>  <code>vip-mode-'.$plxPlugin->getParam('privatize').'</code></td>
				</tr>
				<tr>
					<th>'.$plxPlugin->getLang("L_NBR_STATIC_VIP").'</th>
					<td>'.$nbStatVIP.'</td>
				</tr>
				<tr>
					<th>'.$plxPlugin->getLang("L_USER_FILE").'</th>
					<td>'.PLX_ROOT.PLX_CONFIG_PATH.'<b>users.xml</b></td>
				</tr>
				<tr>
					<th>'.$plxPlugin->getLang("L_BACKUP_USER_FILE_FIRST_INSTALL").'</th>
					<td>'.$plxPlugin->getParam('set').'</td>
				</tr>
				<tr>
					<th>'.$plxPlugin->getLang("L_BATCH_FILE_NAME_DIRECTORY").'</th>
					<td>'.PLX_PLUGINS.$plugin.'/<b>username.csv</b></td>
				</tr>
			</tbody>
		</table>
	</footer>
	';	
?></div><!-- zone de repli code -->
<?php
class SimpleXMLExtended extends SimpleXMLElement {
// from https://web.archive.org/web/20110223233311/http://coffeerings.posterous.com/php-simplexml-and-cdata	
  public function addCData($cdata_text) {
    $node = dom_import_simplexml($this); 
    $no   = $node->ownerDocument; 
    $node->appendChild($no->createCDATASection($cdata_text)); 
  } 
}

function updateFromCsv() {
	$plxMotor = plxMotor::getInstance();
	// on verfie que nos fichiers sont accessibles
	if ((file_exists(PLX_ROOT.PLX_CONFIG_PATH.'users.xml')) && (($open = fopen(PLX_PLUGINS.'/vip_zone/username.csv', 'r')) !== FALSE) && (!isset($_GET['upmsg']))) {
		
		// on commence avec le fichier csv  
		while (($data = fgetcsv($open, 1000, ";")) !== FALSE)     {        
		  $array[] = $data; 
		}  
		fclose($open);

		// on recupere le fichier XML
		$xml = file_get_contents(PLX_ROOT.PLX_CONFIG_PATH.'users.xml', true);

		// on charge le fichier xml
		$doc = new SimpleXMLExtended($xml); 
		
		// on compte ses enregistrements
		$kids = $doc->children();
		$nbUser = count($kids);
		$nbRecords =0;

		 // on boucle sur les lignes du fichiers CSV pour récuperer les données et les ajouter aux données existantes 
		foreach($array as $i => $line){ 
				if($i >0) { // on passe la premiere ligne ou sont  stockées les entêtes de colonnes.
					$nbRecords++; // on compte les enregistrements qui sont ajouter.
					$nbUser++;
					//test si données extraites
					if((!isset($line[0])) or (!isset($line[1])) or (!isset($line[2])) or (!isset($line[3]))) {
					$nbRecords++;
					echo	'<p class="alert red "> '.$plxMotor->plxPlugins->aPlugins['vip_zone']->getLang('L_ERROR_BATCH_RECORDS').': <b> '.$nbRecords  .'</b></p>';
					 
					$fileError ='1';

					} 
					else {
					
			 		// on alimente les données visiteur V.I.P..
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

					// grain de sel
					$salt = plxUtils::charAleatoire(10);

					//cryptage du mot de passe
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
				
		if (!isset($fileError)) {
			//On refait l'indentation du fichier  parceque c'est plus joli
			$xmlDoc = new DOMDocument ();
			$xmlDoc->preserveWhiteSpace = false;
			$xmlDoc->formatOutput = true;
			$xmlDoc->loadXML ( $doc->asXML() );
			// on sauvegarde le fichier xml mis à jour.
			$xmlDoc->save(PLX_ROOT.PLX_CONFIG_PATH.'users.xml');
			echo '<p class="alert green ">'.L_SAVE_FILE_SUCCESSFULLY.' - '.L_CONFIG_USERS_NEW.' <b> '.$nbRecords  .'</b></p>';
			
			// on vide le fichier si tout s'est bien déroulé pour le prochain enregistrement.
			$open = fopen(PLX_PLUGINS."/vip_zone/username.csv", "w") ;
			$fileCsv="login;name;password;email\n";
			fwrite($open, $fileCsv);
			fclose($open);
		}

	}
	else {
    exit(L_SAVE_FILE_ERROR.' user.xml / username.csv.');
	}
}

function addVipStatic(){
	    $plxMotor = plxMotor::getInstance();
$newVipStatic = $_POST["newVipStatic"]	;	
		// on recupere le fichier XML
		
	#on verifie que l'on a accés au fichier de config
	if (file_exists(PLX_ROOT.PLX_CONFIG_PATH.'statiques.xml')) {
			$xml = file_get_contents(PLX_ROOT.PLX_CONFIG_PATH.'statiques.xml', true) ;

		// on charge le fichier xml
		$doc = new SimpleXMLExtended($xml); 
		
		// on recherche le dernier numero et on l'incremente de 1 pour numeroter notre nouvelle page statique.
			$searchMax = new SimpleXMLElement($xml);
			$numbers= array();
			foreach($searchMax->statique as $a => $b) {
			   $numbers[]= (string)$b['number'];
			}
			sort($numbers);
			$max=  array_pop($numbers);
			$max= $max + 1 ;

		// on alimente les données visiteur V.I.P..
					$element = $doc->addChild('statique'); 
					
					
					$element->addAttribute('number', str_pad($max, 3,'0', STR_PAD_LEFT)  );
					$element->addAttribute('active', '0' );
					$element->addAttribute('menu', 'oui' );
					$element->addAttribute('url',  $_POST["newVipStatic"]  );	   
					
					$element->addAttribute('template', 'static.php' );
					
					$group = $element->addChild('group'); 
					$group->addCData('V.I.P.'); 


					$name = $element->addChild('name'); 
					$name->addCData($_POST['newVipStatic']); 

					$meta_description = $element->addChild('meta_description'); 
					$meta_description->addCData(''); 
	
					
					$title_htmltag = $element->addChild('title_htmltag'); 
					$title_htmltag->addCData(''); 
					

					$date_creation = $element->addChild('date_creation'); 
					$date_creation->addCData(date('YmdHi')); 

					$date_update = $element->addChild('date_update'); 
					$date_update->addCData(date('YmdHi')); 

					$element = $element.'\n';	
					
					
					$content="<?php if (!isset(\$_SESSION['profil']) ) {\$_SESSION['pageRequest'] = \$_SERVER['REQUEST_URI'] ; header(\"Location: /core/admin/\");} ?>\n\n ".$plxMotor->plxPlugins->aPlugins['vip_zone']->getLang("L_NOT_FIRST_LINE");
					
					if (!file_exists(PLX_ROOT.'data/statiques/'.$newVipStatic.'.php')) {
					/*if  (@($open = fopen(PLX_ROOT.'data/statiques/'.$newVipStatic.'.php', "r")) !== FALSE) {
						//okay pas de doublon ... en principe
					}
					else {*/
						$open = fopen(PLX_ROOT.'data/statiques/'.str_pad($nbUser, 3,'0', STR_PAD_LEFT).'.'.$_POST["newVipStatic"].'.php', 'w') ;
						fwrite($open, $content);
						fclose($open);
					}
					}			   

				
		if (!isset($fileError)) {
			$xmlDoc = new DOMDocument ();
			$xmlDoc->preserveWhiteSpace = false;
			$xmlDoc->formatOutput = true;
			$xmlDoc->loadXML ( $doc->asXML() );
			// on sauvegarde le fichier xml mis à jour.
			$xmlDoc->save(PLX_ROOT.PLX_CONFIG_PATH.'statiques.xml');
			echo '<p class="alert green ">'.L_SAVE_FILE_SUCCESSFULLY.'</p>';
		}	

		else {
			exit(L_SAVE_FILE_ERROR.' statiques.xml .');
			}
}	

?>
<!-- code mirror -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/codemirror.min.css" integrity="sha512-6sALqOPMrNSc+1p5xOhPwGIzs6kIlST+9oGWlI4Wwcbj1saaX9J3uzO3Vub016dmHV7hM+bMi/rfXLiF5DNIZg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/codemirror.min.js" integrity="sha512-Mq3vhmcyngWQdBzrOf0SA5p9O3WePmAFfsewXSy5v3BzreKxO4WNzIYa9MyWTNBWTjERTNrU5dBnqbEKIl/4dA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/meta.min.js" integrity="sha512-/2x+sfL5ERHfoViXm/UncFBzaD54f2bkjEui6w2IheEUafG2gcHJv3gQ4VDIqNr+LuApQwpnsjjrEMXI43nPzg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- add codemirror to textarea editing CSV file -->
<script>

var editor = CodeMirror.fromTextArea(document.getElementById('csv'), {
    lineNumbers: true,
    mode: 'text/plain',
    matchBrackets: true,
});
</script>
<!-- upload default username.csv file -->
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