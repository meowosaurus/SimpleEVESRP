<?php
require_once("backend/core.php");

$core = new Core();

if(!$core->ConnectToSql())
{
    $core->ShowErrorAlert($core->GetSQLError());
    return;
}
$sql = $core->GetSQLObject();

/*
 * Pulls shipID and payout amount from payouts
 * Checks invtypes for the correct name
 */
$sqlResult = $sql->query("SELECT iv.typeName AS shipName, po.shipID AS shipID, po.amount AS amount
                                FROM invtypes AS iv, payouts AS po
                                WHERE iv.typeID = po.shipID");

$core->startHTML();
$core->loadHeader();

$core->startBody();
$core->loadNavigationBar();
echo '
<br />
<div class="container-fluid">
    <div class="card text-white bg-dark">
        <div class="card-header">
            <h3 align="center">
                Payouts
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-sm table-dark text-white">
                <thead>
                    <tr>
                        <th>Shipname</th>
                        <th>Strategic</th>
                    </tr>
                </thead>
                <tbody>';
                while($row = $sqlResult->fetch_array(MYSQLI_ASSOC))
                {
                echo '
                <tr>
                    <td><img src="https://images.evetech.net/types/'. $row['shipID'] .'/icon?size=32" /> '. $row['shipName'] .'</td>
                    <td>'. number_format($row['amount'], 0, "", ".") .' ISK</td>
                </tr>';
                }
                echo '
                </tbody>
            </table>
        </div>
    </div>
</div>
';
$core->loadScripts();
$core->endBody();
$core->endHTML();

?>