<?php
/**
 * @version � 1.5.2 June 9, 2011
 * @author � �RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license � http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

require_once('rtcore.class.php');
define("UPDATE_SLUG", 'missioncontrol-admin-template');
define("UPDATE_NAME", 'MissionControl Admin Template');
define("UPDATE_URL", 'http://updates.rockettheme.com/joomla/templates/missioncontrol-updates.json');
define("CURRENT_VERSION", '1.5.2');

class MissionControl extends RTCore {
    
	private static $instance;

	var $core;
    
    public static function getInstance(){
    	
    	if (!self::$instance)
        {
            self::$instance = new MissionControl();
        }

        return self::$instance;
    }

	public function __construct() {
		
		parent::__construct();
        
		$this->updateSlug = UPDATE_SLUG;
        $this->updateUrl = UPDATE_URL;


        $this->params->set('update_name',UPDATE_NAME);
        $this->params->set('update_slug',UPDATE_SLUG);
        $this->params->set('current_version',CURRENT_VERSION);
	}

    public function addCustomHeaders() {

        $inline_css = '';
        if ($inline_css) $this->addInlineStyle($inline_css);
    }
	
	public function displayLoginForm() {
		require_once($this->templatePath.DS.'html'.DS.'login.php');
	}
	
	public function displayMenu() {

        $custom_menu = $this->templatePath.DS.'html'.DS.'mod_menu'.DS.'rtmenuhelper.class.php';

        if (file_exists($custom_menu))
            require_once($custom_menu);
        else
	   	    require_once('rtmenuhelper.class.php' );

        $hide	= JRequest::getInt('hidemainmenu');

        $menuhelper = new RTMenuHelper();

        if ( $hide ) {
            $menuhelper->buildDisabledMenu();
        } else {
            $menuhelper->buildMenu();
        }
	}
	
	public function displayBodyTags() 
	{
		global $option;
		$params = $this->params;
		echo "transitions-". $params->get('enableTransitions') ." ";
        echo "headers-".$params->get('enableFancyHeaders') . " ";
        echo "extendmenu-".$params->get('extendmenu'). " ";
        echo "width-".$params->get('templateWidth') . " ";
		echo $this->document->direction ." ";
		$task = JRequest::getString('task');
		echo ("option-".str_replace("_","-",$option)." task-".str_replace("_","-",$task));
	}
	
	
	public function displaySubMenu() 
	{
		echo '<jdoc:include type="modules" name="submenu" style="rounded" id="submenu-box" />';	
	}
	
	public function displayDashText() {
		
		$params = $this->params;
		$default = 'You can put anything you want here to provide some information to your Administrators. <a href="index.php?option=com_templates&task=edit&cid[]=rt_missioncontrol_j15&client=1">Edit This Text Now...</a>';
		$dashtext = $params->get('dashboard',$default);
		echo '<p class="mc-dashtext">'.$dashtext.'</p>';
	}

    public function displayLogo() {

        $dest = 'images/missioncontrol-logo.png';

        if (file_exists($dest)) {
            $logo_url = $dest;
            $size = getimagesize($dest);
        } else {
            $logo_url = $this->templateUrl."/images/logo.png";
            $size = getimagesize($this->templatePath.DS.'images'.DS.'logo.png');
        }
        $logo_url .= '?'.intval(microtime(true));

        echo '<img src="'.$logo_url.'" alt="logo" class="mc-logo" '.$size[3] .' />';
    }


	
	
	public function displayLoginStatus() {
	
		$browserLang = null;
		$output = array();
		$cancel = array();
	
		jimport('joomla.language.helper');
		$languages = array();
		$languages = JLanguageHelper::createLanguageList($browserLang );
		array_unshift( $languages, JHTML::_('select.option',  '', JText::_( 'Language: Default' ) ) );
		$langs = JHTML::_('select.genericlist',   $languages, 'lang', ' class="inputbox"', 'value', 'text', $browserLang );

		$output[] = $this->_addListItem('<a href="'.$this->baseUrl.'">'.JText::_('CANCEL').'</a>','action');
		$output[] = $this->_addListItem($langs,'dropdown');
		
		echo $this->_listify($output);
	
	}
	
	public function displayTitle() {
		global $mainframe, $option;
		
		if ($option == "com_cpanel")
			$title = JText::_('Site Dashboard');
		else
			$title = $mainframe->get('JComponentTitle');

		if (!empty($title)) {
			echo '<h1>'.strip_tags($title).'</h1>';
		} else {
			$document = JFactory::getDocument();
			$buffer = $document->getBuffer();
			if(isset($buffer['modules']['title'])) echo '<h1>'.strip_tags($buffer['modules']['title']).'</h1>';
		}
	}
	
	public function displayHelpButton() {

		echo $this->help->render('help');
	}
	
	public function displayToolbar() {
		
		echo $this->toolbar_output;
	
	}
	
	public function displayStatus() {
		
		$task 		= JRequest::getString('task');
		
		$user		= $this->user;
        $viewer = get_viewer();
		$db			=& JFactory::getDBO();
		$output 	= array();
		
		$canConfig	= $viewer->superadmin();
		$disabled = ($task == 'edit' || $task == 'editA' || JRequest::getInt('hidemainmenu'));
		$disabled_class = $disabled ? 'disabled' : 'active';
		
		// add logout button
		if ($disabled )
			 $output[] = $this->_addListItem("<span class=\"logout\"><a>".JText::_('Logout')."</a></span>","inactive");
		else
			$output[] = $this->_addListItem("<span class=\"logout\"><a href=\"index.php?option=com_login&amp;task=logout\">".JText::_('Logout')."</a></span>","action");

		// Print the preview button
		if ($this->params->get('enableViewSite')) {
			$output[] = '<span class="preview"><a href="'.JURI::root().'" target="_blank">'.JText::_('VIEW_SITE').'</a></span>';
		}
		
		echo $this->_listify($output, $disabled_class);
	}
}
