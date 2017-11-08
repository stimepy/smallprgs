<?php /* Smarty version Smarty-3.1-DEV, created on 2017-11-08 16:13:44
         compiled from "cms_template:Simplex Footer Navigation" */ ?>
<?php /*%%SmartyHeaderCode:224565a0381982983a6-60425540%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b9a70c7aee91cfc7805c871c3f7b053f71bd72fe' => 
    array (
      0 => 'cms_template:Simplex Footer Navigation',
      1 => '1510168147',
      2 => 'cms_template',
    ),
  ),
  'nocache_hash' => '224565a0381982983a6-60425540',
  'function' => 
  array (
    'do_footer_class' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
    'Simplex_footer_menu' => 
    array (
      'parameter' => 
      array (
        'depth' => '1',
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'classes' => 0,
    'main_id' => 0,
    'ul_class' => 0,
    'data' => 0,
    'node' => 0,
    'list_class' => 0,
    'depth' => 0,
    'href_class' => 0,
    'nodes' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5a0381983059d2_98562520',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a0381983059d2_98562520')) {function content_5a0381983059d2_98562520($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['main_id'])) {$_smarty_tpl->tpl_vars['main_id'] = clone $_smarty_tpl->tpl_vars['main_id'];
$_smarty_tpl->tpl_vars['main_id']->value = ' id=\'footer-menu\''; $_smarty_tpl->tpl_vars['main_id']->nocache = null; $_smarty_tpl->tpl_vars['main_id']->scope = 0;
} else $_smarty_tpl->tpl_vars['main_id'] = new Smarty_variable(' id=\'footer-menu\'', null, 0);?><?php if (!function_exists('smarty_template_function_do_footer_class')) {
    function smarty_template_function_do_footer_class($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['do_footer_class']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php if (count($_smarty_tpl->tpl_vars['classes']->value)>0) {?> class='<?php echo implode(' ',$_smarty_tpl->tpl_vars['classes']->value);?>
'<?php }?><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!function_exists('smarty_template_function_Simplex_footer_menu')) {
    function smarty_template_function_Simplex_footer_menu($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['Simplex_footer_menu']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><ul<?php echo $_smarty_tpl->tpl_vars['main_id']->value;?>
<?php if (isset($_smarty_tpl->tpl_vars['ul_class']->value)&&$_smarty_tpl->tpl_vars['ul_class']->value!='') {?> class="<?php echo $_smarty_tpl->tpl_vars['ul_class']->value;?>
"<?php }?>><?php if (isset($_smarty_tpl->tpl_vars['main_id'])) {$_smarty_tpl->tpl_vars['main_id'] = clone $_smarty_tpl->tpl_vars['main_id'];
$_smarty_tpl->tpl_vars['main_id']->value = ''; $_smarty_tpl->tpl_vars['main_id']->nocache = null; $_smarty_tpl->tpl_vars['main_id']->scope = 0;
} else $_smarty_tpl->tpl_vars['main_id'] = new Smarty_variable('', null, 0);?><?php if (isset($_smarty_tpl->tpl_vars['ul_class'])) {$_smarty_tpl->tpl_vars['ul_class'] = clone $_smarty_tpl->tpl_vars['ul_class'];
$_smarty_tpl->tpl_vars['ul_class']->value = ''; $_smarty_tpl->tpl_vars['ul_class']->nocache = null; $_smarty_tpl->tpl_vars['ul_class']->scope = 0;
} else $_smarty_tpl->tpl_vars['ul_class'] = new Smarty_variable('', null, 0);?><?php  $_smarty_tpl->tpl_vars['node'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['node']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['node']->key => $_smarty_tpl->tpl_vars['node']->value) {
$_smarty_tpl->tpl_vars['node']->_loop = true;
?><?php if (isset($_smarty_tpl->tpl_vars['list_class'])) {$_smarty_tpl->tpl_vars['list_class'] = clone $_smarty_tpl->tpl_vars['list_class'];
$_smarty_tpl->tpl_vars['list_class']->value = array(); $_smarty_tpl->tpl_vars['list_class']->nocache = null; $_smarty_tpl->tpl_vars['list_class']->scope = 0;
} else $_smarty_tpl->tpl_vars['list_class'] = new Smarty_variable(array(), null, 0);?><?php if (isset($_smarty_tpl->tpl_vars['href_class'])) {$_smarty_tpl->tpl_vars['href_class'] = clone $_smarty_tpl->tpl_vars['href_class'];
$_smarty_tpl->tpl_vars['href_class']->value = array(); $_smarty_tpl->tpl_vars['href_class']->nocache = null; $_smarty_tpl->tpl_vars['href_class']->scope = 0;
} else $_smarty_tpl->tpl_vars['href_class'] = new Smarty_variable(array(), null, 0);?><?php if ($_smarty_tpl->tpl_vars['node']->value->current||$_smarty_tpl->tpl_vars['node']->value->parent) {?><?php $_smarty_tpl->createLocalArrayVariable('list_class', null, 0);
$_smarty_tpl->tpl_vars['list_class']->value[] = 'current';?><?php $_smarty_tpl->createLocalArrayVariable('href_class', null, 0);
$_smarty_tpl->tpl_vars['href_class']->value[] = 'current';?><?php }?><?php if ($_smarty_tpl->tpl_vars['node']->value->children_exist) {?><?php $_smarty_tpl->createLocalArrayVariable('list_class', null, 0);
$_smarty_tpl->tpl_vars['list_class']->value[] = 'parent';?><?php }?><?php if ($_smarty_tpl->tpl_vars['node']->value->type=='sectionheader') {?><?php $_smarty_tpl->createLocalArrayVariable('list_class', null, 0);
$_smarty_tpl->tpl_vars['list_class']->value[] = 'sectionheader';?><li<?php smarty_template_function_do_footer_class($_smarty_tpl,array('classes'=>$_smarty_tpl->tpl_vars['list_class']->value));?>
><span><?php echo $_smarty_tpl->tpl_vars['node']->value->menutext;?>
</span><?php if (isset($_smarty_tpl->tpl_vars['node']->value->children)) {?><?php smarty_template_function_Simplex_footer_menu($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['node']->value->children,'depth'=>$_smarty_tpl->tpl_vars['depth']->value+1));?>
<?php }?></li><?php } elseif ($_smarty_tpl->tpl_vars['node']->value->type=='separator') {?><?php $_smarty_tpl->createLocalArrayVariable('list_class', null, 0);
$_smarty_tpl->tpl_vars['list_class']->value[] = 'separator';?><li<?php smarty_template_function_do_footer_class($_smarty_tpl,array('classes'=>$_smarty_tpl->tpl_vars['list_class']->value));?>
'><hr class='separator'/></li><?php } else { ?><li<?php smarty_template_function_do_footer_class($_smarty_tpl,array('classes'=>$_smarty_tpl->tpl_vars['list_class']->value));?>
><a<?php smarty_template_function_do_footer_class($_smarty_tpl,array('classes'=>$_smarty_tpl->tpl_vars['href_class']->value));?>
 href='<?php echo $_smarty_tpl->tpl_vars['node']->value->url;?>
'<?php if ($_smarty_tpl->tpl_vars['node']->value->target!='') {?> target='<?php echo $_smarty_tpl->tpl_vars['node']->value->target;?>
'<?php }?>><?php echo $_smarty_tpl->tpl_vars['node']->value->menutext;?>
</a><?php if (isset($_smarty_tpl->tpl_vars['node']->value->children)) {?><?php smarty_template_function_Simplex_footer_menu($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['node']->value->children,'depth'=>$_smarty_tpl->tpl_vars['depth']->value+1));?>
<?php }?></li><?php }?><?php } ?></ul><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (isset($_smarty_tpl->tpl_vars['nodes']->value)) {?><?php smarty_template_function_Simplex_footer_menu($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['nodes']->value,'depth'=>'0','ul_class'=>'cf'));?>
<?php }?><?php }} ?>
