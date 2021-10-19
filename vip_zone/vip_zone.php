<?php
/*###################################################################################
# Plugin PluXml (Cette version est incompatible avec les versions 6 et + de PluXml)
# Auteur : Gcyrillus aka Gc-Nomade
# premiere version : Sept. 2021
# Version :  1.08  / Oct. 2021
# Dépot github : https://github.com/gcyrillus/PLX_VIP_ZONE
# Options de configurations de Zone privatisée dans votre PluXml.
# Aide Forum : https://forum.pluxml.org/discussion/7056/plugin-vip-zone-options-de-privatisation-de-votre-pluxml#latest
###################################################################################*/
    if(!defined('PLX_ROOT')) {
        die('oups!');
    }

    class vip_zone extends plxPlugin {
        const HOOKS = array(
            'AdminPrepend',
			'AdminAuthPrepend',
			'AdminAuthTop',
            'AdminTopEndHead',
            'AdminUsersTop',
			'ThemeEndHead',
			'ThemeEndBody',
			'plxShowStaticListEnd',
			'plxMotorParseArticle',
			'plxMotorParseCommentaire',

        );
        const BEGIN_CODE = '<?php' . PHP_EOL;
        const END_CODE = PHP_EOL . '?>';

        public function __construct($default_lang) {
        # appel du constructeur de la classe plxPlugin (obligatoire)
        parent::__construct($default_lang);
		

		# Ajoute des hooks
		foreach(self::HOOKS as $hook) {
			$this->addHook($hook, $hook);
		}
			
		# droits pour accéder à la page config.php et admin.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);
		$this->setAdminProfil( PROFIL_ADMIN);
        }
		
	
		#code à exécuter à la désactivation du plugin
        public function OnDeactivate() {		
			#desactive les comptes visiteurs	
				$deactivateVisitors=simplexml_load_file(PLX_ROOT.PLX_CONFIG_PATH."users.xml") or die("Error: Cannot create object");
				foreach($deactivateVisitors->children() as $users) {
					if ( $users['profil'] =='5') {
					$users->attributes()->delete = '1';
					}
				} 
				$deactivateVisitors->asXml(PLX_ROOT.PLX_CONFIG_PATH."users.xml");
								
			#desactive les pages static VIP
				$deactivateVipStatics=simplexml_load_file(PLX_ROOT.PLX_CONFIG_PATH."statiques.xml") or die("Error: Cannot create object");
				foreach($deactivateVipStatics->children() as $vipStats) {
					if ( (string) $vipStats->group =='V.I.P.') {	
						$vipStats->Attributes()->active='0';
					}
				} 		
				$deactivateVipStatics->asXml(PLX_ROOT.PLX_CONFIG_PATH."statiques.xml");
		}
		
        #code à exécuter à l’activation du plugin 
        public function OnActivate() { 
			#réactive compte visiteur s'il y en a
				$reactivateVisitors=simplexml_load_file(PLX_ROOT.PLX_CONFIG_PATH."users.xml") or die("Error: Cannot create object");
				foreach($reactivateVisitors->children() as $users) {
					if (( $users['profil'] =='5') and ( $users['delete'] =='1') ) {
					$users->attributes()->delete = '0';
					}
				} 
				$reactivateVisitors->asXml(PLX_ROOT.PLX_CONFIG_PATH."users.xml");	
				
			#reactive pages static VIP
				$reactivateVipStatics=simplexml_load_file(PLX_ROOT.PLX_CONFIG_PATH."statiques.xml") or die("Error: Cannot create object");
				foreach($reactivateVipStatics->children() as $vipStats) {
					if ( (string) $vipStats->group =='V.I.P.') {	
						$vipStats->Attributes()->active='1';
					}
				} 
				$reactivateVipStatics->asXml(PLX_ROOT.PLX_CONFIG_PATH."statiques.xml");		
		}

#HOOKS		
		
		#ajout constante profil VIP
		public function AdminPrepend() {
			echo self::BEGIN_CODE;
?>
         const PROFIL_VISITOR = 5; 					
<?php
            echo self::END_CODE;
        }

		#renvoi VIP à la page d'acceuil à la deconnexion
		public function  AdminAuthPrepend() {
			echo self::BEGIN_CODE;
?>
		if (!empty($_GET['d']) and $_GET['d'] == 1) {

				if(!empty($_SESSION['pageRequest'])) {
					$_SESSION = array();
					session_destroy();
					header('Location:'.PLX_ROOT);
				}
				else {	
					$_SESSION = array();
					session_destroy();
					header('Location: auth.php');
				}
			exit;
		}    
<?php
            echo self::END_CODE;
        }
		
        #personalise le titre du formulaire de connexion	
        public function AdminAuthTop() {	
	    echo self::BEGIN_CODE;
?>
		# pour accés fichier lang du plugin	
		$plxMotor = plxMotor::getInstance();
		$plugin = $plxMotor->plxPlugins->aPlugins['vip_zone']; 
		
		$newValue = $plugin->getLang("L_LOGIN_PAGE_VIP");

		#pour accés fichier lang de l'admin 
		$plxAdmin = plxAdmin::getInstance();
		$lang = $plxAdmin->aConf['default_lang'];
				# Chargement du fichier de lang du theme
				$langfile = PLX_CORE.'lang/'.$lang.'/admin.php';
				if (is_file($langfile)) {
					include $langfile;
					$lang = $LANG; 
				}		 
		$oldValue = $lang["L_LOGIN_PAGE"];
		echo '
		<script>
		window.addEventListener("load", (event) => {
			let h1s = document.querySelectorAll("form h1");
			[...h1s].forEach((h1) => {
			  if (h1.textContent =="'. $oldValue.'") { h1.textContent ="'.$plxAdmin->aConf['title'].'\n\n'.$newValue.'";h1.style.whiteSpace="pre"; }
			});
		});
		</script>';	 
<?php
            echo self::END_CODE;
        }


	    #On renvoi le VIP vers la page privée demandée aprés authentification
        public function AdminTopEndHead() {
            echo self::BEGIN_CODE;
?>				 
		      if(isset($_SESSION['profil']) and $_SESSION['profil'] == '5')  { header("location: ".$_SESSION['pageRequest']);} 	
<?php
            echo self::END_CODE;
        }
		#On ajoute un profil utilisateur
        public function AdminUsersTop() {
	    echo self::BEGIN_CODE;
?>
		$plxMotor = plxMotor::getInstance();
		$plugin = $plxMotor->plxPlugins->aPlugins['vip_zone'];
		$VIP_Profil = $plugin->getLang('L_PROFIL_VISITOR');				
				# Tableau des profils
				$aProfils = array(
					PROFIL_ADMIN => L_PROFIL_ADMIN,
					PROFIL_MANAGER => L_PROFIL_MANAGER,
					PROFIL_MODERATOR => L_PROFIL_MODERATOR,
					PROFIL_EDITOR => L_PROFIL_EDITOR,
					PROFIL_WRITER => L_PROFIL_WRITER,
					PROFIL_VISITOR => $VIP_Profil   
				);
<?php
            echo self::END_CODE;
        } 
		

		#Avant l'affichage de la page, on verifie si il y a besoin d'authenfication en comparant la configuration du plugin et le type de page demandée
        public function ThemeEndHead() {	
	    echo self::BEGIN_CODE;
?>
		  # On recupere le mode privatisé
		  $plxMotor = plxMotor::getInstance();
		  $plugin = $plxMotor->plxPlugins->aPlugins['vip_zone'];
		  $parameter = $plugin->getParam('privatize');
	
		  #Selon le mode privatisé, on redirige vers la page d'authenfication si par encore logué
		  if($parameter =="catart") {
		        if (!isset($_SESSION['profil']) && (($plxMotor->mode === 'categorie' ) || ($plxMotor->mode === 'article' ))) {
					$_SESSION['pageRequest']= $_SERVER['REQUEST_URI'];
					header("Location: /core/admin/");
					exit;
				}
		}
		  else if ($parameter =="static") {
				if (!isset($_SESSION['profil']) && ($plxMotor->mode === 'static' )) {
					$_SESSION['pageRequest']= $_SERVER['REQUEST_URI'];
					header("Location: /core/admin/");
					exit;
				}
		}	
		  else if ($parameter =="blog") {
				if (!isset($_SESSION['profil']) && (($plxMotor->mode === 'home' ))) {
					$_SESSION['pageRequest']=$_SERVER['REQUEST_URI'];
					header("Location: /core/admin/");
					exit;
				}
		}
<?php
	    echo self::END_CODE;
        }

		#Ajout class a body selon config du plugin
        public function ThemeEndBody() {	
	    echo self::BEGIN_CODE;
?> 	
			if ((!isset($_SESSION['profil']))) {	 
				echo'
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" media="screen"/>
				<script>		
						let zoneMode = document.querySelector("body#top");
						zoneMode.classList.add("vip-mode-'.$parameter.'");	
				</script>';	
			}	
<?php
	    echo self::END_CODE;
        }

        #Ajout d'un bouton de deconnexion dans le menu principal si le visiteur est authentifier .	
        public function plxShowStaticListEnd() {	
	    echo self::BEGIN_CODE;
?>
		$plxMotor = plxMotor::getInstance();
		$plugin = $plxMotor->plxPlugins->aPlugins['vip_zone'];		
			 if ((isset($_SESSION['profil'])) && ($_SESSION['profil']=='5') ) { array_push($menus, '<li class="static menu noactive"  id="vip_logout"><a href="'.PLX_ROOT.'core/admin/auth.php?d=1"  title="'.$plugin->getLang("L_VIP_LOGOUT_TITLE").'">'.$plugin->getLang("L_VIP_LOGOUT").'</a></li>');}

			 if (!isset($_SESSION['profil'])) {
				$menus = array(); 
			 $home = ((empty($this->plxMotor->get) or preg_match('/^page[0-9]*/', $this->plxMotor->get)) and basename($_SERVER['SCRIPT_NAME']) == "index.php");
			# Si on a la variable extra, on affiche un lien vers la page d'accueil (avec $extra comme nom)
			if ($extra != '') {
				$stat = str_replace('#static_id', 'static-home', $format);
				$stat = str_replace('#static_class', 'static menu', $stat);
				$stat = str_replace('#static_url', $this->plxMotor->urlRewrite(), $stat);
				$stat = str_replace('#static_name', plxUtils::strCheck($extra), $stat);
				$stat = str_replace('#static_status', ($home == true ? "active" : "noactive"), $stat);
				$menus[][] = $stat;
			}
			$group_active = "";
			if ($this->plxMotor->aStats) {
				foreach ($this->plxMotor->aStats as $k => $v) {
					if ($v['active'] == 1 and $v['menu'] == 'oui') { # La page  est bien active et dispo ds le menu
						$stat = str_replace('#static_id', 'static-' . intval($k), $format);
						$stat = str_replace('#static_class', 'static menu vip-zone', $stat);
						if ($v['url'][0] == '?') # url interne commençant par ?
							$stat = str_replace('#static_url', $this->plxMotor->urlRewrite($v['url']), $stat);
						elseif (plxUtils::checkSite($v['url'], false)) # url externe en http ou autre
							$stat = str_replace('#static_url', $v['url'], $stat);
						else # url page statique
							$stat = str_replace('#static_url', $this->plxMotor->urlRewrite('?static' . intval($k) . '/' . $v['url']), $stat);
						$stat = str_replace('#static_name', plxUtils::strCheck($v['name']), $stat);
						$stat = str_replace('#static_status', ($this->staticId() == intval($k) ? 'active' : 'noactive'), $stat);
						if ($v['group'] == '')
							$menus[][] = $stat;
						else
							$menus[$v['group']][] = $stat;
						if ($group_active == "" and $home === false and $this->staticId() == intval($k) and $v['group'] != '')
							$group_active = $v['group'];
					}
				}
			}
			if ($menublog) {
				if ($this->plxMotor->aConf['homestatic'] != '' and isset($this->plxMotor->aStats[$this->plxMotor->aConf['homestatic']])) {
					if ($this->plxMotor->aStats[$this->plxMotor->aConf['homestatic']]['active']) {
						$menu = str_replace('#static_id', 'static-blog', $format);
						if ($this->plxMotor->get and preg_match('/(blog|categorie|archives|tag|article)/', $_SERVER['QUERY_STRING'] . $this->plxMotor->mode)) {
							$menu = str_replace('#static_status', 'active', $menu);
						} else {
							$menu = str_replace('#static_status', 'noactive', $menu);
						}
						$menu = str_replace('#static_url', $this->plxMotor->urlRewrite('?blog'), $menu);
						$menu = str_replace('#static_name', L_PAGEBLOG_TITLE, $menu);
						$menu = str_replace('#static_class', 'static menu', $menu);
						array_splice($menus, (intval($menublog) - 1), 0, array($menu));
					}
				}
			}			
		}
 
<?php
            echo self::END_CODE;
        }	
		
        #cache le contenu des article si en zone VIP non connecté.	
        public function plxMotorParseArticle() {	
	    echo self::BEGIN_CODE;
?>
		if (!isset($_SESSION['profil'])) {
			# pour accéder au plugin	
			$plxMotor = plxMotor::getInstance();
			$plugin = $plxMotor->plxPlugins->aPlugins['vip_zone']; 
			$parameter = $plugin->getParam('privatize');
			
			$artHidden = $plugin->getLang("L_HIDDEN_ARTICLE");
			if($parameter =='blog') {$artHidden = $plugin->getLang("L_HIDDEN_ARTICLE_BLOG");}

			if(($parameter =='catart') or ($parameter =='blog') ){
				$art = array(
					'filename'		=> $filename,
					# Recuperation des valeurs de nos champs XML
					'title'				=> plxUtils::getValue($values[$iTags['title'][0]]['value']),
					'allow_com'			=> plxUtils::getValue($values[$iTags['allow_com'][0]]['value'], 0),
					'template'			=> plxUtils::getValue($values[$iTags['template'][0]]['value'], 'article.php'),
					'chapo'				=> $artHidden,
					'content'			=> '',
					'tags'				=> plxUtils::getValue($values[ $iTags['tags'][0] ]['value']),
					'meta_description'	=> plxUtils::getValue($values[$meta_description]['value']),
					'meta_keywords'		=> plxUtils::getValue($values[$meta_keywords]['value']),
					'title_htmltag'		=> plxUtils::getValue($values[$iTags['title_htmltag'][0]]['value']),
					'thumbnail'			=> '',
					'thumbnail_title'	=> '',
					'thumbnail_alt'		=> '',
					'numero'			=> $tmp['artId'],
					'author'			=> $tmp['usrId'],
					'categorie'			=> $tmp['catId'],
					'url'				=> $tmp['artUrl'],
					'date'				=> $tmp['artDate'],
					'nb_com'			=> $this->getNbCommentaires('#^' . $tmp['artId'] . '.\d{10}.\d+.xml$#'),
					'date_creation'		=> plxUtils::getValue($values[$iTags['date_creation'][0]]['value'], $tmp['artDate']),
					'date_update'		=> plxUtils::getValue($values[$iTags['date_update'][0]]['value'], $tmp['artDate']),
				);
			}
		}	 
<?php
            echo self::END_CODE;
        }		
		
        #cache les commentaires si en zone VIP 
        public function plxMotorParseCommentaire() {	
	    echo self::BEGIN_CODE;
?>
				# pour accéder au plugin	
				$plxMotor = plxMotor::getInstance();
				$plugin = $plxMotor->plxPlugins->aPlugins['vip_zone']; 
				$parameter = $plugin->getParam('privatize');
				
			if(($parameter =='catart') or ($parameter =='blog') ){ 
				$com['content'] = $plugin->getLang("L_HIDDEN_COMMENT");
			}
<?php
            echo self::END_CODE;
        }	
		
	# pas hook 
       # repositionne le plugin en premiere position // utilise le formatage XML produit par PluXml
	   /* aprés maj du fichier plugins.xml, les tags 
	   
	      <plugin name="non du plugin" scope=""></plugin>     sont réecris en
		  <plugin name="non du plugin" scope=""/>             conformement à la syntaxe XML 
		  sans provoquer de dysfonctionement de lecture dans PluXml .
	   */
        public function resetPluginsToTop() {	
			$xmlplug = file_get_contents(PLX_ROOT.PLX_CONFIG_PATH.'plugins.xml', true);
			$topDoc ='<document>';
			$topPlug= '	<plugin name="vip_zone" scope=""></plugin>';//recherche sur syntaxe produite par PluXml
			$newxmlplug = str_replace($topPlug, '', $xmlplug);
			$res = str_replace($topDoc, $topDoc.' '.$topPlug, $newxmlplug);		
			$doc = simplexml_load_string($res);
			$endres = new DOMDocument ();
			$endres->preserveWhiteSpace = false;
			$endres->formatOutput = true;
			$endres->loadXML ( $doc->asXML() );
			$endres->save(PLX_ROOT.PLX_CONFIG_PATH.'plugins.xml');
        }	

	#end class
    }
?> 