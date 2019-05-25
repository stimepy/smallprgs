<?php /* Smarty version Smarty-3.1-DEV, created on 2017-11-08 16:13:43
         compiled from "tpl_top:10" */ ?>
<?php /*%%SmartyHeaderCode:106595a038197dcf386-34592522%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1d2564fb4117d48c4a4788fdc05bac4cc5af55a3' => 
    array (
      0 => 'tpl_top:10',
      1 => '1510168142',
      2 => 'tpl_top',
    ),
  ),
  'nocache_hash' => '106595a038197dcf386-34592522',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'nls' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5a038197e50235_30461255',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a038197e50235_30461255')) {function content_5a038197e50235_30461255($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cms_lang_info')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.cms_lang_info.php';
if (!is_callable('smarty_function_uploads_url')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.uploads_url.php';
if (!is_callable('smarty_function_title')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.title.php';
if (!is_callable('smarty_function_cms_selflink')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.cms_selflink.php';
if (!is_callable('smarty_cms_function_share_data')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.share_data.php';
?><?php echo CMS_Content_Block::smarty_fetch_pagedata(array(),$_smarty_tpl);?>
<?php echo smarty_function_cms_lang_info(array('assign'=>'nls'),$_smarty_tpl);?>
<?php ob_start();?><?php echo smarty_function_uploads_url(array(),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php if (isset($_smarty_tpl->tpl_vars['theme_path'])) {$_smarty_tpl->tpl_vars['theme_path'] = clone $_smarty_tpl->tpl_vars['theme_path'];
$_smarty_tpl->tpl_vars['theme_path']->value = $_tmp1."/simplex"; $_smarty_tpl->tpl_vars['theme_path']->nocache = null; $_smarty_tpl->tpl_vars['theme_path']->scope = 0;
} else $_smarty_tpl->tpl_vars['theme_path'] = new Smarty_variable($_tmp1."/simplex", null, 0);?><?php echo smarty_function_title(array('assign'=>'main_title'),$_smarty_tpl);?>
<?php CMS_Content_Block::smarty_internal_fetch_contentblock(array('assign'=>'main_content'),$_smarty_tpl); ?><?php echo smarty_function_cms_selflink(array('dir'=>'previous','assign'=>'prev_page'),$_smarty_tpl);?>
<?php echo smarty_function_cms_selflink(array('dir'=>'next','assign'=>'next_page'),$_smarty_tpl);?>
<?php echo smarty_cms_function_share_data(array('scope'=>'global','vars'=>'nls,theme_path,main_title,main_content,prev_page,next_page'),$_smarty_tpl);?>
<!doctype html>
<!--[if IE 8]>         <html lang='<?php echo $_smarty_tpl->tpl_vars['nls']->value->htmlarea();?>
' dir='<?php echo $_smarty_tpl->tpl_vars['nls']->value->direction();?>
' class='lt-ie9'> <![endif]-->
<!--[if gt IE 8]><!--> <html lang='<?php echo $_smarty_tpl->tpl_vars['nls']->value->htmlarea();?>
' dir='<?php echo $_smarty_tpl->tpl_vars['nls']->value->direction();?>
'> <!--<![endif]--><?php }} ?>
