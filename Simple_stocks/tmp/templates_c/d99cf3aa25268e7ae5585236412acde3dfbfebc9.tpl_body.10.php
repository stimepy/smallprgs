<?php /* Smarty version Smarty-3.1-DEV, created on 2017-11-08 16:13:43
         compiled from "tpl_body:10" */ ?>
<?php /*%%SmartyHeaderCode:194865a038197e928d1-28116339%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd99cf3aa25268e7ae5585236412acde3dfbfebc9' => 
    array (
      0 => 'tpl_body:10',
      1 => '1510168142',
      2 => 'tpl_body',
    ),
  ),
  'nocache_hash' => '194865a038197e928d1-28116339',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page_alias' => 0,
    'content_id' => 0,
    'theme_path' => 0,
    'main_title' => 0,
    'main_content' => 0,
    'prev_page' => 0,
    'next_page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5a03819804e3b2_45026830',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a03819804e3b2_45026830')) {function content_5a03819804e3b2_45026830($_smarty_tpl) {?><?php if (!is_callable('smarty_function_anchor')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.anchor.php';
if (!is_callable('smarty_function_root_url')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.root_url.php';
if (!is_callable('smarty_function_sitename')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.sitename.php';
if (!is_callable('smarty_function_global_content')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.global_content.php';
if (!is_callable('smarty_function_cms_jquery')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\plugins\\function.cms_jquery.php';
?><body id='boxed' class='container page-wrapper page-<?php echo $_smarty_tpl->tpl_vars['page_alias']->value;?>
 page-<?php echo $_smarty_tpl->tpl_vars['content_id']->value;?>
'>
        <!-- #wrapper (wrapping content in a box) -->
        <div class='row' id='wrapper'>
            <!-- accessibility links, jump to nav or content -->
            <ul class="visuallyhidden">
                <li><?php echo smarty_function_anchor(array('anchor'=>'nav','title'=>'Skip to navigation','accesskey'=>'n','text'=>'Skip to navigation'),$_smarty_tpl);?>
</li>
                <li><?php echo smarty_function_anchor(array('anchor'=>'main','title'=>'Skip to content','accesskey'=>'s','text'=>'Skip to content'),$_smarty_tpl);?>
</li>
            </ul>
            <!-- accessibility //-->
            <!-- .top (top section of page containing logo, navigation search...) -->
            <header class='top inner-section'>
                <div class='row header'>
                    <!-- .logo (cmsms logo on the left side) -->
                    <div class='logo four-col'>
                        <a href='<?php echo smarty_function_root_url(array(),$_smarty_tpl);?>
' title='<?php echo smarty_function_sitename(array(),$_smarty_tpl);?>
'>
                            <img src='<?php echo $_smarty_tpl->tpl_vars['theme_path']->value;?>
/images/cmsmadesimple-logo.png' width='227' height='59' alt='<?php echo smarty_function_sitename(array(),$_smarty_tpl);?>
' />
                            <span class='palm'></span>
                        </a>
                    </div>
                    <!-- .logo //-->
                    <!-- .main-navigation (main navigation on the right side) -->
                    <nav class='main-navigation eight-col cf noprint' id='nav' role='navigation'>
                        <?php echo Navigator::function_plugin(array('loadprops'=>'0','template'=>'Simplex Main Navigation'),$_smarty_tpl);?>
 
                    </nav>
                    <!-- .main-navigation //-->
                </div>
                <!-- .header-bottom (bottom part of header containing catchphrase and search field) -->
                <div class='row header-bottom'>
                    <section class='phrase cf'>
                        <span class='seven-col phrase-text'>Power for professionals<br class='lt-768' /> Simplicity for End Users</span>
                        <?php ob_start();?><?php echo Search::function_plugin(array('formtemplate'=>'Simplex Search'),$_smarty_tpl);?>
<?php echo preg_replace('!\s+!u', ' ',ob_get_clean())?> 
                    </section>
                </div>
                <!-- .header-bottom //-->
                <!-- .banner (banner area for a slider or teaser image) -->
                <?php echo smarty_function_global_content(array('name'=>'Simplex Slideshow'),$_smarty_tpl);?>

                <!-- .banner //-->
            </header>
            <!-- .top //-->
            <!-- .content-wrapper (wrapping div for content area) -->
            <main role='main' class='content-wrapper inner-section'>
                <div class='row'>
                    <!-- .content-inner (display content first) -->
                    <div class='content-inner eight-col push-four'>
                        <!-- .content-top (breadcrumbs) -->
                        <div class='content-top cf' itemscope itemtype='http://data-vocabulary.org/Breadcrumb'>
                            <?php echo Navigator::function_plugin(array('action'=>'breadcrumbs'),$_smarty_tpl);?>
 
                            <span class='title-border' aria-hidden='true'></span>
                        </div>
                        <!-- .content-top //-->
                        <!-- .content (actual content with title and content tags) -->
                        <article class='content' id='main'>
                            <h1><?php echo $_smarty_tpl->tpl_vars['main_title']->value;?>
 </h1> 
                                <?php echo $_smarty_tpl->tpl_vars['main_content']->value;?>
 
                        </article>
                        <!-- .content //-->
                    </div>
                    <!-- .content-inner //-->
                    <!-- .sidebar (then show sidebar) -->
                    <aside class='sidebar four-col pull-eight'>
                        
                        <?php echo News::function_plugin(array('summarytemplate'=>'Simplex News Summary','number'=>'2','detailtemplate'=>'Simplex News Detail'),$_smarty_tpl);?>

                    </aside>
                    <!-- .sidebar //-->
                    <div class='cf eight-col push-four'>
                        <?php if (!empty($_smarty_tpl->tpl_vars['prev_page']->value)) {?><span class='previous'><?php echo $_smarty_tpl->tpl_vars['prev_page']->value;?>
</span><?php }?>
                        <?php if (!empty($_smarty_tpl->tpl_vars['next_page']->value)) {?><span class='next'><?php echo $_smarty_tpl->tpl_vars['next_page']->value;?>
</span><?php }?>
                    </div>
                </div>
            </main>
            <!-- .content-wrapper //-->
            <!-- .footer (footer area) -->
            <footer class='footer inner-section'>
                <span class='back-top'><a href='<?php echo smarty_function_anchor(array('anchor'=>'main','onlyhref'=>'1'),$_smarty_tpl);?>
' id='scroll-top'><i class='icon-arrow-up' aria-hidden='true'></i></a></span>
                <div class='row'>
                    <section class='eight-col push-four noprint'>
                        <nav class='footer-navigation row'>
                            <?php echo Navigator::function_plugin(array('template'=>'Simplex Footer Navigation','excludeprefix'=>'home','number_of_levels'=>'2','loadprops'=>'0'),$_smarty_tpl);?>

                        </nav>
                    </section> 
                    <section class='four-col pull-eight copyright'>
                        <?php ob_start();?><?php echo smarty_function_global_content(array('name'=>'Simplex Footer'),$_smarty_tpl);?>
<?php echo preg_replace('!\s+!u', ' ',ob_get_clean())?> 
                    </section>
                </div>
            </footer>
        <!-- #wrapper //--> 
        </div>
    <?php echo smarty_function_cms_jquery(array('exclude'=>'ui,nestedSortable,json,migrate','append'=>'uploads/simplex/js/jquery.sequence-min.js,uploads/simplex/js/functions.min.js'),$_smarty_tpl);?>

    </body>
</html><?php }} ?>
