<?php
if( !defined('CMS_VERSION') ){
    exit;
}
$this->CreatePermission(Holidays::MANAGE_PERM,'Portfolio');
$db = $this->GetDb();
$dict = NewDataDictionary($db);
$taboptarray = array('mysql' => 'TYPE=MyISAM');
$flds = "
   city_id I KEY AUTO,
   name C(255) KEY NOTNULL
";
$tablename = CMS_DB_PREFIX.'PM_city';
$sqlarray = $dict->CreateTableSQL(
    $tablename ,$flds,$taboptarray);
$dict->ExecuteSQLArray($sqlarray);


?>