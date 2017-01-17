<?php
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (tedkulp@users.sf.net)
# Visit our homepage at: http://www.cmsmadesimple.org
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# BUT withOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
#$Id: class.content.inc.php 6905 2011-02-20 22:23:40Z calguy1000 $

/**
 * @package CMS
 */

/**
 * Helper class to deal with parsing or fetching content blocks.
 *
 * @author              Robert Campbell <calguy1000@cmsmadesimple.org>
 * @since		1.11
 * @ignore
 * @internal
 * @package		CMS
 */
final class CMS_Content_Block
{
    private static $_priority;
    private static $_contentBlocks;
    private function __construct() {}

    private static function content_return($result, &$params, &$smarty)
    {
        // this is a smarty tempalte, not the global smarty instance
        if ( empty($params['assign']) ) {
            echo $result;
        }
        else {
            $smarty->assign(trim($params['assign']), $result);
            return;
        }
    }

    public static function get_content_blocks()
    {
        return self::$_contentBlocks;
    }

    public static function reset()
    {
        self::$_priority = 100;
        self::$_contentBlocks = null;
    }

    public static function smarty_compiler_contentblock($params,$smarty)
    {
        // todo: should be in page_template_parser
        // {content} tag encountered.
        $rec = array('type'=>'text','id'=>'','name'=>'','noedit'=>false, 'usewysiwyg'=>'true','oneline'=>'false','default'=>'','label'=>'',
                     'size'=>'50','tab'=>'','maxlength'=>'255','required'=>0,'placeholder'=>'','priority'=>'','cssname'=>'','adminonly'=>0);
        foreach( $params as $key => $value ) {
            $value = trim($value,'"\'');
            if( $key == 'type' ) continue;
            if( $key == 'block' ) $key = 'name';
            if( $key == 'wysiwyg' ) $key = 'usewysiwyg';
            if( isset($rec[$key]) ) $rec[$key] = $value;
        }

        if( !$rec['name'] ) {
            $rec['name'] = 'content_en';
            $rec['id'] = 'content_en';
        }
        if( !$rec['id'] ) $rec['id'] = str_replace(' ','_',$rec['name']);
        if( !$rec['priority'] ) {
            if( !self::$_priority ) self::$_priority = 100;
            $rec['priority'] = self::$_priority++;
        }

        // check for duplicate.
        if( isset(self::$_contentBlocks[$rec['name']]) ) throw new CmsEditContentException('Duplicate content block: '.$rec['name']);

        if( !is_array(self::$_contentBlocks) ) self::$_contentBlocks = array();
        self::$_contentBlocks[$rec['name']] = $rec;
    }

    public static function smarty_compiler_imageblock($params,$smarty)
    {
        // todo: should be in page_template_parser
        // {content_image} tag encountered.
        if( !isset($params['block']) || empty($params['block']) ) {
            throw new CmsEditContentException('{content_image} tag requires block parameter');
        }

        $rec = array('type'=>'image','id'=>'','name'=>'','label'=>'','upload'=>true,'dir'=>'','default'=>'','tab'=>'',
                     'priority'=>'','exclude'=>'','sort'=>0);
        foreach( $params as $key => $value ) {
            if( $key == 'type' ) continue;
            if( $key == 'block' ) $key = 'name';
            if( isset($rec[$key]) ) $rec[$key] = trim($value,"'\"");
        }

        if( !$rec['name'] ) {
            $n = count(self::$_contentBlocks)+1;
            $rec['id'] = $rec['name'] = 'image_'+$n;
        }
        if( !$rec['id'] ) $rec['id'] = str_replace(' ','_',$rec['name']);
        if( !$rec['priority'] ) {
            if( !self::$_priority ) self::$_priority = 100;
            $rec['priority'] = self::$_priority++;
        }

        // check for duplicate.
        if( isset(self::$_contentBlocks[$rec['name']]) ) {
            throw new CmsEditContentException('Duplicate content block: '.$rec['name']);
        }

        if( !is_array(self::$_contentBlocks) ) self::$_contentBlocks = array();
        self::$_contentBlocks[$rec['name']] = $rec;
    }

    public static function smarty_compiler_moduleblock($params,$smarty)
    {
        // todo: should be in page_template_parser
        // {content_module} tag encountered.
        if( !isset($params['block']) || empty($params['block']) ) {
            throw new CmsEditContentException('{content_module} tag requires block parameter');
        }

        $rec = array('type'=>'module','id'=>'','name'=>'','module'=>'','label'=>'', 'blocktype'=>'','tab'=>'','priority'=>'');
        $parms = array();
        foreach( $params as $key => $value ) {
            if( $key == 'block' ) $key = 'name';

            $value = trim(trim($value,'"\''));
            if( isset($rec[$key]) ) {
                $rec[$key] = $value;
            }
            else {
                $parms[$key] = $value;
            }
        }

        if( !$rec['name'] ) {
            $n = count(self::$_contentBlocks)+1;
            $rec['id'] = $rec['name'] = 'module_'+$n;
        }
        if( !$rec['id'] ) $rec['id'] = str_replace(' ','_',$rec['name']);
        $rec['params'] = $parms;
        if( $rec['module'] == '' ) {
            throw new CmsEditContentException('Missing module param for content_module tag');
        }
        if( !$rec['priority'] ) {
            if( !self::$_priority ) self::$_priority = 100;
            $rec['priority'] = self::$_priority++;
        }

        // check for duplicate.
        if( isset(self::$_contentBlocks[$rec['name']]) ) {
            throw new CmsEditContentException('Duplicate content block: '.$rec['name']);
        }

        if( !is_array(self::$_contentBlocks) ) self::$_contentBlocks = array();
        self::$_contentBlocks[$rec['name']] = $rec;
    }

    public static function smarty_compile_fecontentblock($params,$template)
    {
        // todo: should be in page_template_parser
        $ptext = 'array(';
        $tmp = array();
        foreach($params as $k => $v) {
            $tmp[] .= '\''.$k.'\'=>'.$v;
        }
        $ptext .= implode(',',$tmp);
        $ptext .= ')';
        return '<?php CMS_Content_Block::smarty_internal_fetch_contentblock('.$ptext.',$_smarty_tpl); ?>';
    }

    /**
     * @since 1.11
     * @author calguy1000
     * @internal
     * @ignore
     */
    public static function smarty_internal_fetch_contentblock($params,&$smarty)
    {
        $gCms = CmsApp::get_instance();

        $contentobj = CmsApp::get_instance()->get_content_object();
        if (is_object($contentobj)) {
            if( !$contentobj->IsPermitted() ) throw new CmsError403Exception();
            $id = '';
            $modulename = '';
            $action = '';
            $inline = false;
            $assign = (isset($params['assign'])) ? trim($params['assign']) : null;
            if (isset($_REQUEST['mact'])) {
                $ary = explode(',', cms_htmlentities($_REQUEST['mact']), 4);
                $modulename = (isset($ary[0])?$ary[0]:'');
                $id = (isset($ary[1])?$ary[1]:'');
                $action = (isset($ary[2])?$ary[2]:'');
                $inline = (isset($ary[3]) && $ary[3] == 1?true:false);
            }

            //Only consider doing module processing if
            //a. There is no block parameter
            //b. then
            //   1. $id is cntnt01
            //   2. or inline is false

            if (!isset($params['block']) &&
                ($id == 'cntnt01' || $id == '_preview_' || ($id != '' && $inline == false))) {
                // todo, would be neat here if we could get a list of only frontend modules.
                $modops = ModuleOperations::get_instance();
                $installedmodules = $modops->GetInstalledModules();
                if( count($installedmodules) ) {
                    // case insensitive module match.
                    foreach( $installedmodules  as $key ) {
                        if( !strcasecmp($modulename,$key) ) $modulenae = $key;
                    }

                    if (!isset($modulename) || empty($modulename) ) {
                        // no module specified.
                        @trigger_error("Module $modulename requested but is not installed");
                        throw new \CmsError404Exception("Module $modulename requested, but is not installed");
                        //return self::content_return('', $params, $smarty);
                    }

                    $modobj = $modops->get_module_instance($modulename);
                    if( !$modobj ) {
                        // module not found... couldn't even autoload it.
                        @trigger_error('Attempt to access module '.$modulename.' which could not be found (is it properly installed and configured?');
                        throw new \CmsError404Exception('Attempt to access module '.$modulename.' which could not be found (is it properly installed and configured?');
                        //return self::content_return('', $params, $smarty);
                    }

                    if ($modobj->IsPluginModule() ) {
                        @ob_start();
                        $parms = $params;
                        unset($parms['block']);
                        unset($parms['label']);
                        unset($parms['wysiwyg']);
                        unset($parms['oneline']);
                        unset($parms['default']);
                        unset($parms['size']);
                        unset($parms['tab']);
                        unset($parms['required']);
                        unset($parms['priority']);
                        unset($parms['placeholder']);
                        unset($parms['assign']);
                        unset($parms['adminonly']);
                        $parms = array_merge($parms, $modops->GetModuleParameters($id));
                        $returnid = '';
                        if (isset($parms['returnid'])) {
                            $returnid = $parms['returnid'];
                        }
                        else {
                            $returnid = $contentobj->Id();
                        }

                        $oldcache = $smarty->caching;
                        $smarty->caching = false;
                        $result = $modobj->DoActionBase($action, $id, $parms, $returnid,$smarty);
                        $smarty->caching = $oldcache;

                        if ($result !== FALSE) echo $result;
                        $modresult = @ob_get_contents();
                        @ob_end_clean();

                        return self::content_return($modresult, $params, $smarty);
                    }
                    else {
                        @trigger_error('Attempt to access module '.$key.' which could not be found (is it properly installed and configured?');
                        return self::content_return("<!-- Not a tag module -->\n", $params, $smarty);
                    }
                }
            }
            else {
                $block = (isset($params['block']))?$params['block']:'content_en';
                $result = '';

                $oldvalue = $smarty->caching;
                $smarty->caching = false;
                if( isset($_SESSION['__cms_preview__']) && $contentobj->Id() == __CMS_PREVIEW_PAGE__ ) {
                    // note: content precompile/postcompile events will not be triggererd in preview.
                    $val = $contentobj->Show($block);
                    $result = $smarty->fetch('eval:'.$val);
                }
                else {
                    $result = $smarty->fetch(str_replace(' ', '_', 'content:' . $block), '|'.$block, $contentobj->Id().$block);
                }
                $smarty->caching = $oldvalue;

                return self::content_return($result, $params, $smarty);
            }
        }
        return self::content_return('', $params, $smarty);
    }

    public static function smarty_fetch_pagedata($params,&$smarty)
    {
        $gCms = CmsApp::get_instance();

        $contentobj = CmsApp::get_instance()->get_content_object();
        if( !is_object($contentobj) || $contentobj->Id() <= 0 ) return self::content_return('', $params, $smarty);

        $result = $smarty->fetch('content:pagedata','',$contentobj->Id());
        if( isset($params['assign']) ){
            $smarty->assign(trim($params['assign']),$result);
            return;
        }
        return $result;
    }

    public static function smarty_fetch_imageblock($params,&$smarty)
    {
        $gCms = CmsApp::get_instance();
        $config = $gCms->GetConfig();

        $contentobj = $gCms->get_content_object();
        if( !is_object($contentobj) || $contentobj->Id() <= 0 ) return self::content_return('', $params, $smarty);

        $adddir = get_site_preference('contentimage_path');
        if( isset($params['dir']) && $params['dir'] != '' ) $adddir = $params['dir'];
        $dir = cms_join_path($config['uploads_path'],$adddir);
        $basename = basename($config['uploads_path']);

        $result = '';
        if( isset($params['block']) ) {
            $oldvalue = $smarty->caching;
            $smarty->caching = false;
            $result = $smarty->fetch(str_replace(' ', '_', 'content:' . $params['block']), '|'.$params['block'], $contentobj->Id().$params['block']);
            $smarty->caching = $oldvalue;
        }
        $img = $result;

        $out = null;
        if( startswith(realpath($dir),realpath($basename)) ) {
            if( ($img == -1 || empty($img)) && isset($params['default']) && $params['default'] ) {
                $img = $params['default'];
            }

            if( $img != -1 && !empty($img) ) {
                // create the absolute url.
                if( startswith($img,$basename) ) {
                    // old style url.
                    if( !startswith($img,'http') ) $img = str_replace('//','/',$img);
                    $img = substr($img,strlen($basename.'/'));
                    $img = $config['uploads_url'] . '/'.$img;
                }
                else {
                    $img = $config['uploads_url'] . '/'.$adddir.'/'.$img;
                }

                $alt = '';
                $width = '';
                $height = '';
                $urlonly = false;
                $xid = '';
                $class = '';
                if( isset($params['class']) ) $class = $params['class'];
                if( isset($params['id']) ) $xid = $params['id'];
                if( isset($params['alt']) ) $alt = $params['alt'];
                if( isset($params['width']) ) $width = $params['width'];
                if( isset($params['height']) ) $height = $params['height'];
                if( isset($params['urlonly']) ) $urlonly = true;
                if( !isset($params['alt']) ) $alt = $img;

                $out = '';
                if( $urlonly ) {
                    $out = $img;
                }
                else {
                    $out = '<img src="'.$img.'" ';
                    if( !empty($class) ) $out .= 'class="'.$class.'" ';
                    if( !empty($xid) ) $out .= 'id="'.$xid.'" ';
                    if( !empty($width) ) $out .= 'width="'.$width.'" ';
                    if( !empty($height) ) $out .= 'height="'.$height.'" ';
                    if( !empty($alt) ) $out .= 'alt="'.$alt.'" ';
                    $out .= '/>';
                }
            }
        }
        if( isset($params['assign']) ){
            $smarty->assign(trim($params['assign']),$out);
            return;
        }
        return $out;
    }

    public static function smarty_fetch_moduleblock($params,&$smarty)
    {
        $result = '';
        $key = '';

        if( !isset($params['block']) ) return;
        $block = $params['block'];

        $gCms = CmsApp::get_instance();
        $content_obj = $gCms->get_content_object();
        if( is_object($content_obj) ) {
            $result = $content_obj->GetPropertyValue($block);
            if( $result == -1 ) $result = '';
            $module = isset($params['module']) ? trim($params['module']) : null;
            if( $module ) {
                $mod = \cms_utils::get_module($module);
                if( is_object($mod) ) {
                    $result = $mod->RenderContentBlockField($block,$result,$params,$content_obj);
                }
            }
        }

        if( isset($params['assign']) ) {
            $smarty->assign($params['assign'],$result);
            return;
        }

        return $result;
    }

} // end of class.

?>
