<?php /* Smarty version Smarty-3.1-DEV, created on 2017-01-05 18:31:53
         compiled from "cms_template:Breadcrumbs" */ ?>
<?php /*%%SmartyHeaderCode:7157586ee5798d74a2-71055865%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1c7570b5104d51c91b55f34321a8460292f9fa68' => 
    array (
      0 => 'cms_template:Breadcrumbs',
      1 => '1483662546',
      2 => 'cms_template',
    ),
  ),
  'nocache_hash' => '7157586ee5798d74a2-71055865',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'starttext' => 0,
    'nodelist' => 0,
    'node' => 0,
    'spanclass' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_586ee5799062b1_92139837',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_586ee5799062b1_92139837')) {function content_586ee5799062b1_92139837($_smarty_tpl) {?>
<div class="breadcrumb"><?php if (isset($_smarty_tpl->tpl_vars['starttext']->value)) {?><?php echo $_smarty_tpl->tpl_vars['starttext']->value;?>
:&nbsp;<?php }?><?php  $_smarty_tpl->tpl_vars['node'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['node']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['nodelist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['node']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['node']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['node']->key => $_smarty_tpl->tpl_vars['node']->value) {
$_smarty_tpl->tpl_vars['node']->_loop = true;
 $_smarty_tpl->tpl_vars['node']->iteration++;
 $_smarty_tpl->tpl_vars['node']->last = $_smarty_tpl->tpl_vars['node']->iteration === $_smarty_tpl->tpl_vars['node']->total;
?><?php if (isset($_smarty_tpl->tpl_vars['spanclass'])) {$_smarty_tpl->tpl_vars['spanclass'] = clone $_smarty_tpl->tpl_vars['spanclass'];
$_smarty_tpl->tpl_vars['spanclass']->value = 'breadcrumb'; $_smarty_tpl->tpl_vars['spanclass']->nocache = null; $_smarty_tpl->tpl_vars['spanclass']->scope = 0;
} else $_smarty_tpl->tpl_vars['spanclass'] = new Smarty_variable('breadcrumb', null, 0);?><?php if ($_smarty_tpl->tpl_vars['node']->value->current) {?><?php if (isset($_smarty_tpl->tpl_vars['spanclass'])) {$_smarty_tpl->tpl_vars['spanclass'] = clone $_smarty_tpl->tpl_vars['spanclass'];
$_smarty_tpl->tpl_vars['spanclass']->value = ($_smarty_tpl->tpl_vars['spanclass']->value).(' current'); $_smarty_tpl->tpl_vars['spanclass']->nocache = null; $_smarty_tpl->tpl_vars['spanclass']->scope = 0;
} else $_smarty_tpl->tpl_vars['spanclass'] = new Smarty_variable(($_smarty_tpl->tpl_vars['spanclass']->value).(' current'), null, 0);?><?php }?><span class="<?php echo $_smarty_tpl->tpl_vars['spanclass']->value;?>
"><?php if ($_smarty_tpl->tpl_vars['node']->last) {?><?php echo $_smarty_tpl->tpl_vars['node']->value->menutext;?>
<?php } elseif ($_smarty_tpl->tpl_vars['node']->value->type=='sectionheader') {?><?php echo $_smarty_tpl->tpl_vars['node']->value->menutext;?>
&nbsp;<?php } else { ?><a href="<?php echo $_smarty_tpl->tpl_vars['node']->value->url;?>
" title="<?php echo $_smarty_tpl->tpl_vars['node']->value->menutext;?>
"><?php echo $_smarty_tpl->tpl_vars['node']->value->menutext;?>
</a><?php }?></span><?php if (!$_smarty_tpl->tpl_vars['node']->last) {?>&raquo;&nbsp;<?php }?><?php } ?></div><?php }} ?>
