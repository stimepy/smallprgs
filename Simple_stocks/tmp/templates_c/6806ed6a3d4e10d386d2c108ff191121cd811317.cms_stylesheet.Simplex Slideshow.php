<?php /* Smarty version Smarty-3.1-DEV, created on 2017-01-05 18:31:53
         compiled from "cms_stylesheet:Simplex Slideshow" */ ?>
<?php /*%%SmartyHeaderCode:21677586ee579d05aa1-45236128%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6806ed6a3d4e10d386d2c108ff191121cd811317' => 
    array (
      0 => 'cms_stylesheet:Simplex Slideshow',
      1 => '1483662539',
      2 => 'cms_stylesheet',
    ),
  ),
  'nocache_hash' => '21677586ee579d05aa1-45236128',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'orange' => 0,
    'dark_grey' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_586ee579d11626_42267116',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_586ee579d11626_42267116')) {function content_586ee579d11626_42267116($_smarty_tpl) {?>/* cmsms stylesheet: Simplex Slideshow modified: 01/05/17 18:28:59 */
.banner {background: #fefefe;background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZlZmVmZSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQ3JSIgc3RvcC1jb2xvcj0iI2YxZjFmMSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNlOWU5ZTkiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);background: -moz-linear-gradient(top,  #fefefe 0%, #f1f1f1 47%, #e9e9e9 100%);background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fefefe), color-stop(47%,#f1f1f1), color-stop(100%,#e9e9e9));background: -webkit-linear-gradient(top,  #fefefe 0%,#f1f1f1 47%,#e9e9e9 100%);background: -o-linear-gradient(top,  #fefefe 0%,#f1f1f1 47%,#e9e9e9 100%);background: -ms-linear-gradient(top,  #fefefe 0%,#f1f1f1 47%,#e9e9e9 100%);background: linear-gradient(to bottom,  #fefefe 0%,#f1f1f1 47%,#e9e9e9 100%);}.lt-ie9 .banner {filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fefefe', endColorstr='#e9e9e9',GradientType=0 );}#sx-slides {position: relative;overflow: hidden;width: 100%;margin: 0 auto;position: relative;height: 380px;}#sx-slides > .sequence-canvas {height: 100%;width: 100%;margin: 0;padding: 0;list-style: none;}#sx-slides > .sequence-canvas > li {position: absolute;width: 100%;height: 100%;z-index: 1;top: -50%;}#sx-slides > .sequence-canvas > li img {height: 96%;}#sx-slides > .sequence-canvas li > * {position: absolute;-webkit-transition-property: left, bottom, right, top, -webkit-transform, opacity;-moz-transition-property: left, bottom, right, top, -moz-opacity;-ms-transition-property: left, bottom, right, top, -ms-opacity;-o-transition-property: left, bottom, right, top, -o-opacity;transition-property: left, bottom, right, top, transform, opacity;}#sx-slides .title {color: <?php echo $_smarty_tpl->tpl_vars['orange']->value;?>
;font-size: 2.25em;line-height: 1.1;font-weight: 700;left: 65%;opacity: 0;bottom: 22%;z-index: 50;margin-top: 0;}#sx-slides .animate-in .title {left: 12%;opacity: 1;-webkit-transition-duration: 0.8s;-moz-transition-duration: 0.8s;-ms-transition-duration: 0.8s;-o-transition-duration: 0.8s;transition-duration: 0.8s;}#sx-slides .animate-out .title {left: 35%;opacity: 0;-webkit-transition-duration: 0.3s;-moz-transition-duration: 0.3s;-ms-transition-duration: 0.3s;-o-transition-duration: 0.3s;transition-duration: 0.3s;}#sx-slides .subtitle {margin-top: 0;z-index: 5;color: <?php echo $_smarty_tpl->tpl_vars['dark_grey']->value;?>
;font-family: 'Oswald', Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;font-weight: 700;font-size: 1.8125em;left: 35%;opacity: 0;top: 72%;}#sx-slides .animate-in .subtitle {left: 20%;opacity: 1;-webkit-transition-duration: 1.3s;-moz-transition-duration: 1.3s;-ms-transition-duration: 1.3s;-o-transition-duration: 1.3s;transition-duration: 1.3s;}#sx-slides .animate-out .subtitle {left: 65%;opacity: 0;-webkit-transition-duration: 0.8s;-moz-transition-duration: 0.8s;-ms-transition-duration: 0.8s;-o-transition-duration: 0.8s;transition-duration: 0.8s;}#sx-slides .image {left: -10px;position: absolute;bottom: 800px;-webkit-transform: rotate(-90deg);-moz-transform: rotate(-90deg);-ms-transform: rotate(-90deg);-o-transform: rotate(-90deg);transform: rotate(-90deg);opacity: 0;max-width: 70%;height: auto !important;max-height: 275px !important;}#sx-slides .animate-in .image {left: 14%;bottom: -49%;opacity: 1;-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);transform: rotate(0deg);-webkit-transition-duration: 2s;-moz-transition-duration: 2s;-ms-transition-duration: 2s;-o-transition-duration: 2s;transition-duration: 2s;}#sx-slides .animate-out .image {left: -10px;bottom: -800px;opacity: 0;-webkit-transform: rotate(-90deg);-moz-transform: rotate(-90deg);-ms-transform: rotate(-90deg);-o-transform: rotate(-90deg);transform: rotate(-90deg);-webkit-transition-duration: 1s;-moz-transition-duration: 1s;-ms-transition-duration: 1s;-o-transition-duration: 1s;transition-duration: 1s;}@media only screen and (min-width: 768px) {#sx-slides .title {font-size: 3em;}#sx-slides .animate-in .title {left: 3%;}#sx-slides .subtitle {font-size: 2.5em;}#sx-slides .animate-in .subtitle {left: 8%;}#sx-slides .image {left: auto;right: -10px;position: absolute;max-width: 70%;height: auto !important;max-height: 300px !important;}#sx-slides .animate-in .image {left: auto;right: 5%;bottom: -45%;}#sx-slides .animate-out .image {left: auto;bottom: -800px;}}@media only screen and (min-width: 1050px) {#sx-slides {height: 440px;}#sx-slides .title {font-size: 3.25em;bottom: 15%;}#sx-slides .animate-in .title {left: 8%;}#sx-slides .subtitle {font-size: 2.875em;top: 78%}#sx-slides .animate-in .subtitle {left: 12%;}#sx-slides .image {max-width: 90%;height: auto !important;max-height: 400px !important;}}
<?php }} ?>
