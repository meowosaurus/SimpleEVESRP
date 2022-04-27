<?php
require_once("backend/core.php");

// temp
$requesterID = 1;

$core = new Core();

if(!$core->ConnectToSql())
{
    $core->ShowErrorAlert($core->GetSQLError());
    return;
}
$sql = $core->GetSQLObject();

$stmt = $sql->prepare("SELECT rq.player AS player, rq.esiLink AS esiLink, rq.zKillLink AS zKillLink, rq.corporationID AS corpID,
                                        rq.allianceID AS allyID, rq.amount AS lossAmount, rq.broadcast AS broadcast
                                        FROM requests AS rq
                                        WHERE rq.requesterID = ?");
$stmt->bind_param("i", $requesterID);
$stmtResult = $stmt->execute();
$sqlResult = $stmt->get_result();

$core->startHTML();
$core->loadHeader();

$core->startBody();
$core->loadNavigationBar();

echo '
<br />
<div>
    <div class="col-md-12">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                <div class="row justify-content-md-center">
                    <h3 align="center">
                        <strong>
                            Last 15 Losses
                        </strong>
                    </h3>
                </div>
                <div class="row justify-content-md-center">
                    <div class="col-md-4" style="padding: .75rem 1.25rem">';
                        // ToDo: Only shows one row
                        while($row = $sqlResult->fetch_all(MYSQLI_ASSOC))
                        {
                            echo '
                            <div class="card text-white bg-dark">
                                <div class="card-header">
                                    <img src="https://images.evetech.net/types/12015/icon?size=32" width="30px" height="30px" /> <strong>Muninn</strong> lost in <strong>Jita</strong> on <strong>27-04-2022</strong>
                                </div>
                                <div class="card-body">
                                    <table class="table table-dark">
                                        <tbody>
                                            <tr>
                                                <td>Ship Name:</td>
                                                <td>Muninn</td>
                                            </tr>
                                            <tr>
                                                <td>Player:</td>
                                                <td>Meowlicious</td>
                                            </tr>
                                            <tr>
                                                <td>Loss amount:</td>
                                                <td>1.000 ISK</td>
                                            </tr>
                                            <tr>
                                                <td>Post time:</td>
                                                <td>Nope</td>
                                            </tr>
                                            <tr>
                                                <td>Submitted on:</td>
                                                <td>Nope</td>
                                            </tr>
                                            <tr>
                                                <td>Reimbursement:</td>
                                                <td>1.000 ISK</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    Broadcast: <br/>
                                    <p>
                                        Meowlicious — 04/24/2022
                                        @everyone Gatekeeper forming on Meow in FDZ4-A, Purple II, Im a bit busy because a friend is forcing me to watch Formula 1
                                    </p>
                                </div>
                            </div>
                            ';
                        }
                        echo '
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';

$core->endBody();

$core->loadScripts();

$core->endHTML();


?>