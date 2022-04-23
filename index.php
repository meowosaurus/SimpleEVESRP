<?php
require_once("core.php");

$core = new Core("localhost", "root", "", "srp", "", "");

if(!$core->ConnectToSql())
{
    echo $core->GetSQLError();
}

$core->setAllianceID(1900696668);

$core->startHTML();
$core->loadHeader();

$core->startBody();
$core->loadNavigationBar();
$core->endBody();

$core->loadScripts();
$core->endHTML();

?>