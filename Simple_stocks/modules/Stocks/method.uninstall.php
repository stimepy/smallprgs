<?php
if( !defined('CMS_VERSION') ) exit;
$this->RemovePermission(Holidays::MANAGE_PERM);
$db = $this->GetDb();
$dict = NewDataDictionary( $db );
$sqlarray = $dict->DropTableSQL(CMS_DB_PREFIX.'SP_City');
$dict->ExecuteSQLArray($sqlarray);