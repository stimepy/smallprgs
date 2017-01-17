<?php
#CMS - CMS Made Simple
#(c)2004-2013 by Ted Kulp (wishy@users.sf.net)
#Visit our homepage at: http://www.cmsmadesimple.org
#
#This program is free software; you can redistribute it and/or modify
#it under the terms of the GNU General Public License as published by
#the Free Software Foundation; either version 2 of the License, or
#(at your option) any later version.
#
#This program is distributed in the hope that it will be useful,
#but WITHOUT ANY WARRANTY; without even the implied warranty of
#MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#GNU General Public License for more details.
#You should have received a copy of the GNU General Public License
#along with this program; if not, write to the Free Software
#Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
#$Id$

/**
 * This file is included in every page.  It does all setup functions including
 * importing additional functions/classes, setting up sessions and nls, and
 * construction of various important variables like $gCms.
 *
 * This function cannot be included by third party applications to create access to CMSMS API's.  It is intended for
 * and supported for use in CMSMS applications only.
 *
 * @package CMS
 */

/**
 * Special vairables that may be set before this file is included which will influence its behavior.
 *
 * DONT_LOAD_DB = Indicates that the database should not be initialized and any database related functions should not be called
 * DONT_LOAD_SMARTY = Indicates that smarty should not be initialized, and no smarty related variables assigned.
 * CMS_INSTALL_PAGE - Indicates that the file was included from the CMSMS Installation/Upgrade process
 * CMS_PHAR_INSTALLER - Indicates that the file was included from the CMSMS PHAR based installer (note: CMS_INSTALL_PAGE will also be set).
 * CMS_ADMIN_PAGE - Indicates that the file was included from an admin side request.
 * CMS_LOGIN_PAGE - Indicates that the file was included from the admin login form.
 * LOAD_ALL_MODULES - Indicates that all available modules should be loaded (deprecated)
 */

$dirname = __DIR__;

define('CMS_DEFAULT_VERSIONCHECK_URL','http://www.cmsmadesimple.org/latest_version.php');
define('CMS_SECURE_PARAM_NAME','_sk_');
define('CMS_USER_KEY','_userkey_');

global $CMS_INSTALL_PAGE,$CMS_ADMIN_PAGE,$CMS_LOGIN_PAGE,$DONT_LOAD_DB,$DONT_LOAD_SMARTY;

// minimum stuff to get started (autoloader needs the cmsms() and the config stuff.
if( !defined('CONFIG_FILE_LOCATION') ) define('CONFIG_FILE_LOCATION',dirname(__DIR__).'/config.php');

// sanitize $_SERVER and $_GET
$_SERVER = filter_var_array($_SERVER, FILTER_SANITIZE_STRING);
$_GET = filter_var_array($_GET, FILTER_SANITIZE_STRING);

// include some stuff
require_once($dirname.DIRECTORY_SEPARATOR.'compat.functions.php');
require_once($dirname.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'class.CmsException.php');
require_once($dirname.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'class.cms_config.php');
require_once($dirname.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'class.CmsApp.php');
require_once($dirname.DIRECTORY_SEPARATOR.'autoloader.php');
require_once($dirname.DIRECTORY_SEPARATOR.'misc.functions.php');
require_once($dirname.DIRECTORY_SEPARATOR.'module.functions.php');
require_once($dirname.DIRECTORY_SEPARATOR.'version.php');
debug_buffer('done loading required files');

if( cms_to_bool(ini_get('register_globals')) ) {
    echo 'FATAL ERROR: For security reasons register_globals must not be enabled for any CMSMS install.  Please adjust your PHP configuration settings to disable this feature.';
    die();
}

if( isset($CMS_ADMIN_PAGE) ) setup_session();

#Grab the current configuration
$_app = CmsApp::get_instance(); // for use in this file only.
$config = $_app->GetConfig();

if( isset($CMS_ADMIN_PAGE) ) {
    function cms_admin_sendheaders($content_type = 'text/html',$charset = '') {
        // Language shizzle
        if( !$charset ) $charset = get_encoding();
        header("Content-Type: $content_type; charset=$charset");
    }

    // ugly hack, this shouldn't be necessary here.
    if( !isset($_SESSION[CMS_USER_KEY]) ) {
        if( cms_cookies::exists(CMS_SECURE_PARAM_NAME) ) {
            $_SESSION[CMS_USER_KEY] = cms_cookies::get(CMS_SECURE_PARAM_NAME);
        }
    }
}

// new for 2.0 ... this creates a mechanism whereby items can be cached automatically, and fetched (or calculated) via the use of a callback
// if the cache is too old, or the cached value has been cleared or not yet been saved.
$obj = new \CMSMS\internal\global_cachable('schema_version',
                                           function(){
                                               $db = \CmsApp::get_instance()->GetDb();
                                               $query = 'SELECT version FROM '.CmsApp::get_instance()->GetDbPrefix().'version';
                                               return $db->GetOne($query);
                                  });
\CMSMS\internal\global_cache::add_cachable($obj);
$obj = new \CMSMS\internal\global_cachable('latest_content_modification',
                                           function(){
                                               $db = \CmsApp::get_instance()->GetDb();
                                               $query = 'SELECT modified_date FROM '.CmsApp::get_instance()->GetDbPrefix().'content ORDER BY modified_date DESC';
                                               $tmp = $db->GetOne($query);
                                               return $db->UnixTimeStamp($tmp);
                                           });
\CMSMS\internal\global_cache::add_cachable($obj);
$obj = new \CMSMS\internal\global_cachable('default_content',
                                           function(){
                                               $db = \CmsApp::get_instance()->GetDb();
                                               $query = 'SELECT content_id FROM '.CmsApp::get_instance()->GetDbPrefix().'content WHERE default_content = 1';
                                               $tmp = $db->GetOne($query);
                                               return $tmp;
                                           });
\CMSMS\internal\global_cache::add_cachable($obj);
cms_siteprefs::setup();

#Set the timezone
if( $config['timezone'] != '' ) @date_default_timezone_set(trim($config['timezone']));

#Attempt to override the php memory limit
if( isset($config['php_memory_limit']) && !empty($config['php_memory_limit'])  ) ini_set('memory_limit',trim($config['php_memory_limit']));

if ($config["debug"] == true) {
    @ini_set('display_errors',1);
    @error_reporting(E_ALL);
}

debug_buffer('loading page functions');
require_once($dirname.DIRECTORY_SEPARATOR.'page.functions.php');

debug_buffer('loading content functions');
require_once($dirname.DIRECTORY_SEPARATOR.'content.functions.php');

debug_buffer('loading translation functions');
require_once($dirname.DIRECTORY_SEPARATOR.'translation.functions.php');

debug_buffer('loading php4 entity decode functions');
require_once($dirname.DIRECTORY_SEPARATOR.'html_entity_decode_php4.php');

debug_buffer('done loading files');

#Load them into the usual variables.  This'll go away a little later on.
if (!isset($DONT_LOAD_DB)) {
  debug_buffer('Initialize Database');
  $_app->GetDb();
  debug_buffer('Done Initializing Database');

  // Set a umask
  $global_umask = cms_siteprefs::get('global_umask','');
  if( $global_umask != '' ) umask( octdec($global_umask) );
}

#Fix for IIS (and others) to make sure REQUEST_URI is filled in
if (!isset($_SERVER['REQUEST_URI'])) {
  $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
  if(isset($_SERVER['QUERY_STRING'])) $_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
}

#Load all installed module code
if (! isset($CMS_INSTALL_PAGE)) {
  debug_buffer('','Loading Modules');
  $modops = ModuleOperations::get_instance();
  $modops->LoadModules(isset($LOAD_ALL_MODULES), !isset($CMS_ADMIN_PAGE));
  debug_buffer('', 'End of Loading Modules');
}

#Setup language stuff.... will auto-detect languages (Launch only to admin at this point)
if(isset($CMS_ADMIN_PAGE)) CmsNlsOperations::set_language();

if( !isset($DONT_LOAD_SMARTY) ) {
  debug_buffer('Initialize Smarty');
  $smarty = $_app->GetSmarty();
  debug_buffer('Done Initialiing Smarty');
  if( defined('CMS_DEBUG') && CMS_DEBUG ) {
    $smarty->debugging = true;
    $smarty->error_reporting = 'E_ALL';
  }
  $smarty->assignGlobal('sitename', cms_siteprefs::get('sitename', 'CMSMS Site'));
}


#Do auto task stuff.
if (!isset($CMS_INSTALL_PAGE) && !isset($CMS_LOGIN_PAGE)) CmsRegularTaskHandler::handle_tasks();

?>
