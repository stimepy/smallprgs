<?php
/**
 * Created by PhpStorm.
 * User: Stimepy
 * Date: 6/5/16
 * Time: 7:59 PM
 */
require_once('CTemplate_class.php');
$template = new CTemplate();
$tid=$template->AddTemplate('Main_Content.tpl');
$template->AddVariables($tid,'hello world');
$template->RenderTemplate($tid);