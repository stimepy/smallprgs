<?php

class Stocks extends CMSModule{
    const MANAGE_PERM = 'manage_holidays';

    public function GetVersion() { return '0.1'; }
    public function GetFriendlyName() { return $this->Lang('friendlyname'); }
    public function GetAdminDescription() { return $this->Lang('admindescription'); }
    public function IsPluginModule() { return TRUE; }
    public function HasAdmin() { return TRUE; }
    public function VisibleToAdminUser() {
        return $this->CheckPermission(self::MANAGE_PERM);
    }
    public function GetAuthor() { return 'Kris Sherrerd'; }
    public function GetAuthorEmail() {
        return 'stimepy@aodhome.com';
    }
    public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }


}


?>