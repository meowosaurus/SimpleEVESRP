<?php
require_once("backend/core.php");

$core = new Core();

if(!$core->ConnectToSql())
{
    $core->ShowErrorAlert($core->GetSQLError());
    return;
}

$core->startHTML();
$core->loadHeader();

$core->startBody();
$core->loadNavigationBar();
$core->endBody();

$core->loadScripts();

$core->endHTML();

?>