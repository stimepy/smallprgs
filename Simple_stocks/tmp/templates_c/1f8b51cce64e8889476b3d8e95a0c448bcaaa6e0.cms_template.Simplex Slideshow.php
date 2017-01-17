<?php /* Smarty version Smarty-3.1-DEV, created on 2017-01-05 18:31:53
         compiled from "cms_template:Simplex Slideshow" */ ?>
<?php /*%%SmartyHeaderCode:5420586ee579856608-26762567%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1f8b51cce64e8889476b3d8e95a0c448bcaaa6e0' => 
    array (
      0 => 'cms_template:Simplex Slideshow',
      1 => '1483662539',
      2 => 'cms_template',
    ),
  ),
  'nocache_hash' => '5420586ee579856608-26762567',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'slides' => 0,
    'slide' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_586ee5798a8699_73260466',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_586ee5798a8699_73260466')) {function content_586ee5798a8699_73260466($_smarty_tpl) {?><?php if (!is_callable('smarty_function_uploads_url')) include 'C:\\xampp\\htdocs\\Simple_stocks\\plugins\\function.uploads_url.php';
if (!is_callable('smarty_modifier_cms_escape')) include 'C:\\xampp\\htdocs\\Simple_stocks\\plugins\\modifier.cms_escape.php';
?><?php if (isset($_smarty_tpl->tpl_vars['slides'])) {$_smarty_tpl->tpl_vars['slides'] = clone $_smarty_tpl->tpl_vars['slides'];
$_smarty_tpl->tpl_vars['slides']->value = array(); $_smarty_tpl->tpl_vars['slides']->nocache = null; $_smarty_tpl->tpl_vars['slides']->scope = 0;
} else $_smarty_tpl->tpl_vars['slides'] = new Smarty_variable(array(), null, 0);?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[0]['heading'] = 'Power for professionals';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[0]['subheading'] = 'Simplicity for end Users';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[0]['image'] = 'palm-logo.png';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[1]['heading'] = 'Faster &amp; Easier';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[1]['subheading'] = 'Website management';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[1]['image'] = 'mate-zimple.png';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[2]['heading'] = 'Flexible &amp; Powerful';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[2]['subheading'] = 'Manage your Website anywhere and anytime';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[2]['image'] = 'mobile-devices-scene.png';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[3]['heading'] = 'Secure &amp; Robust';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[3]['subheading'] = 'Take control of your application';?><?php $_smarty_tpl->createLocalArrayVariable('slides', null, 0);
$_smarty_tpl->tpl_vars['slides']->value[3]['image'] = 'browser-scene.png';?><section class='banner row noprint' id='sx-slides' role='banner'><ul class="sequence-canvas"><?php  $_smarty_tpl->tpl_vars['slide'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slide']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['slides']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['slide']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['slide']->key => $_smarty_tpl->tpl_vars['slide']->value) {
$_smarty_tpl->tpl_vars['slide']->_loop = true;
 $_smarty_tpl->tpl_vars['slide']->index++;
 $_smarty_tpl->tpl_vars['slide']->first = $_smarty_tpl->tpl_vars['slide']->index === 0;
?><li<?php if ($_smarty_tpl->tpl_vars['slide']->first) {?> class='animate-in'<?php }?>><?php if (!empty($_smarty_tpl->tpl_vars['slide']->value['heading'])) {?><h2 class='title'><?php echo $_smarty_tpl->tpl_vars['slide']->value['heading'];?>
</h2><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['slide']->value['subheading'])) {?><h3 class='subtitle'><?php echo $_smarty_tpl->tpl_vars['slide']->value['subheading'];?>
</h3><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['slide']->value['image'])) {?><img class='image' src='<?php echo smarty_function_uploads_url(array(),$_smarty_tpl);?>
/simplex/teaser/<?php echo $_smarty_tpl->tpl_vars['slide']->value['image'];?>
' alt='<?php echo smarty_modifier_cms_escape($_smarty_tpl->tpl_vars['slide']->value['heading'],'htmlall');?>
' /><?php }?></li><?php } ?></ul></section><?php }} ?>
