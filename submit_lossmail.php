<?php
require_once("core.php");

$core = new Core();

if(!$core->ConnectToSql())
{
    echo $core->GetSQLError();
}

$core->setAllianceID(1900696668);

$core->startHTML();
$core->loadHeader();

$core->startBody();
$core->loadNavigationBar();
echo '
<br />
<div class="container">
    <div class="card text-white bg-dark">
        <div class="card-header">
            <h3 align="center">
                Submit new lossmail
            </h3>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">zKillboard.com killmail:</label>
                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="https://zkillboard.com/kill/..">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Fleet broadcast:</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-secondary" type="button">Cancel</button>
                    <button class="btn btn-success" type="button">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
';
$core->endBody();

$core->loadScripts();
$core->endHTML();

?>