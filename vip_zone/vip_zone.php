<?php
    if(!defined('PLX_ROOT')) {
        die('oups!');
    }

    class vip_zone extends plxPlugin {
        const HOOKS = array(
            'AdminPrepend',
            'AdminTopEndHead',
            'AdminUsersTop',
            'AdminAuthEndHead',
			'ThemeEndHead',
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
		$this->setAdminProfil(PROFIL_ADMIN);
        }

		public function AdminPrepend() {
            echo self::BEGIN_CODE;
?>
         const PROFIL_VISITOR = 5;  
<?php
            echo self::END_CODE;
        }

        public function OnDeactivate() {
            # code à exécuter à la désactivation du plugin, desactive les comptes visiteurs
			
			$deactivateVisitors=simplexml_load_file(PLX_ROOT.PLX_CONFIG_PATH."users.xml") or die("Error: Cannot create object");
			foreach($deactivateVisitors->children() as $users) {
			  if ( $users['profil'] =='5') {
			  $users->attributes()->delete = '1';
			  }
			} 
			$deactivateVisitors->asXml(PLX_ROOT.PLX_CONFIG_PATH."users.xml");
	}

        public function OnActivate() {
            # code à exécuter à l’activation du plugin , réactive compte visiteur s'il y en a 
			$reactivateVisitors=simplexml_load_file(PLX_ROOT.PLX_CONFIG_PATH."users.xml") or die("Error: Cannot create object");
			foreach($reactivateVisitors->children() as $users) {
			  if (( $users['profil'] =='5') and ( $users['delete'] =='1') ) {
			  $users->attributes()->delete = '0';
			  }
			} 
			$reactivateVisitors->asXml(PLX_ROOT.PLX_CONFIG_PATH."users.xml");			
			
			
			
        }	
        public function AdminTopEndHead() {
            echo self::BEGIN_CODE;
?>
            if(isset($_SESSION['profil']) and $_SESSION['profil'] == '5') { header("location: ".$_COOKIE['page']);} 
<?php
            echo self::END_CODE;
        }

        public function AdminUsersTop() {
            echo self::BEGIN_CODE;
?>
            # Tableau des profils
            $aProfils = array(
                PROFIL_ADMIN => L_PROFIL_ADMIN,
                PROFIL_MANAGER => L_PROFIL_MANAGER,
                PROFIL_MODERATOR => L_PROFIL_MODERATOR,
                PROFIL_EDITOR => L_PROFIL_EDITOR,
                PROFIL_WRITER => L_PROFIL_WRITER,
                PROFIL_VISITOR => L_PLUGINS_REQUIREMENTS_NONE  // affiche aucun en fr à partir des fichiers lang de PluXml
            );

<?php
            echo self::END_CODE;
        } 

        public function AdminAuthEndHead() {
            echo self::BEGIN_CODE;
?>
            if(isset($_GET['page'])) {setcookie("page",   $_GET['page']) ;}
<?php
            echo self::END_CODE;
        }
		
 


        public function ThemeEndHead() {	
		            echo self::BEGIN_CODE;
?>
		  # On recupere le mode privatisé
		  $plxMotor = plxMotor::getInstance();
		  $plugin = $plxMotor->plxPlugins->aPlugins['visitor'];
		  $parameter = $plugin->getParam('privatize');
		 
		  #Selon le mode privatisé, on redirige vers la page d'authenfication si par encore logué
		  if($parameter =="catart") {
		        if (!isset($_SESSION['profil']) && ($plxMotor->mode !== 'home' ) && ($plxMotor->mode !== 'static' )) {
					header("Location: /core/admin/auth.php?page=".$_SERVER['REQUEST_URI']);
				}
		}
		  else if ($parameter =="static") {
				if (!isset($_SESSION['profil']) && ($plxMotor->mode === 'static' )) {
					header("Location: /core/admin/auth.php?page=".$_SERVER['REQUEST_URI']);
				}
		}	
		  else if ($parameter =="blog") {
				if (!isset($_SESSION['profil']) && ($plxMotor->mode !== 'static' )) {
					header("Location: /core/admin/auth.php?page=".$_SERVER['REQUEST_URI']);
				}
		}		
		 
		 
		 
		 
<?php
            echo self::END_CODE;
        }


		
		
		
		
		
		

        	
		
		
		
		 
    }