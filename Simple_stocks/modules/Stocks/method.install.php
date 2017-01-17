<?php
if( !defined('CMS_VERSION') ){
    exit;
}
$this->CreatePermission(Holidays::MANAGE_PERM,'Manage Holidays');
$db = $this->GetDb();
$dict = NewDataDictionary($db);
$taboptarray = array('mysql' => 'TYPE=MyISAM');
$flds = "
   id I KEY AUTO,
   name C(255) KEY NOTNULL,
   description X,
   published I1,
   the_date I NOTNULL
";
$sqlarray = $dict->CreateTableSQL(
    CMS_DB_PREFIX
    .'mod_holidays',$flds,$taboptarray);
$dict->ExecuteSQLArray($sqlarray);