<?php /* Smarty version Smarty-3.1-DEV, created on 2017-11-08 16:13:44
         compiled from "cms_template:Simplex Footer" */ ?>
<?php /*%%SmartyHeaderCode:65455a0381983153d7-11803681%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3de2b634adeec980c78e5c6f7700596ee1d12dcc' => 
    array (
      0 => 'cms_template:Simplex Footer',
      1 => '1510168142',
      2 => 'cms_template',
    ),
  ),
  'nocache_hash' => '65455a0381983153d7-11803681',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'start_year' => 0,
    'current_year' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5a03819832cae5_36095346',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a03819832cae5_36095346')) {function content_5a03819832cae5_36095346($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\lib\\smarty\\plugins\\modifier.date_format.php';
if (!is_callable('smarty_function_cms_version')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.cms_version.php';
?>
<?php if (isset($_smarty_tpl->tpl_vars['start_year'])) {$_smarty_tpl->tpl_vars['start_year'] = clone $_smarty_tpl->tpl_vars['start_year'];
$_smarty_tpl->tpl_vars['start_year']->value = '2004'; $_smarty_tpl->tpl_vars['start_year']->nocache = null; $_smarty_tpl->tpl_vars['start_year']->scope = 0;
} else $_smarty_tpl->tpl_vars['start_year'] = new Smarty_variable('2004', null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['current_year'])) {$_smarty_tpl->tpl_vars['current_year'] = clone $_smarty_tpl->tpl_vars['current_year'];
$_smarty_tpl->tpl_vars['current_year']->value = smarty_modifier_date_format(time(),'%Y'); $_smarty_tpl->tpl_vars['current_year']->nocache = null; $_smarty_tpl->tpl_vars['current_year']->scope = 0;
} else $_smarty_tpl->tpl_vars['current_year'] = new Smarty_variable(smarty_modifier_date_format(time(),'%Y'), null, 0);?>


<ul class='social cf'>
    <li class='twitter'><a title='Twitter' href='http://twitter.com/#!/cmsms'><i class='icon-twitter'></i><span class='visuallyhidden'>Twitter</span></a></li>
    <li class='facebook'><a title='Facebook' href='https://www.facebook.com/cmsmadesimple'><i class='icon-facebook'></i><span class='visuallyhidden'>Facebook</span></a></li>
    <li class='linkedin'><a title='LinkedIn' href='http://www.linkedin.com/groups?gid=1139537'><i class='icon-linkedin'></i><span class='visuallyhidden'>LinkedIn</span></a></li>
    <li class='youtube'><a title='YouTube' href='http://www.youtube.com/user/cmsmadesimple'><i class='icon-youtube'></i><span class='visuallyhidden'>YouTube</span></a></li>
    <li class='google'><a title='Google Plus' href='https://plus.google.com/+cmsmadesimple/posts'><i class='icon-google'></i><span class='visuallyhidden'>Google Plus</span></a></li>
    <li class='pinterest'><a title='Pinterest' href='http://www.pinterest.com/cmsmadesimple/'><i class='icon-pinterest'></i><span class='visuallyhidden'>Pinterest</span></a></li>
</ul>
<p class='copyright-info'>&copy; Copyright <?php echo $_smarty_tpl->tpl_vars['start_year']->value;?>
<?php if ($_smarty_tpl->tpl_vars['start_year']->value!==$_smarty_tpl->tpl_vars['current_year']->value) {?> - <?php echo $_smarty_tpl->tpl_vars['current_year']->value;?>
<?php }?> - CMS Made Simple<br /> This site is powered by <a href='http://www.cmsmadesimple.org'>CMS Made Simple</a> version <?php echo smarty_function_cms_version(array(),$_smarty_tpl);?>
</p><?php }} ?>
