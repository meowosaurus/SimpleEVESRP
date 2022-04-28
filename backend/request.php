<?php

class Request
{
    private $sqlConnection;

    private int $shipID;
    private string $shipName;
    private int $solarSystemID;
    private string $solarSystemName;
    private int $regionID;
    private string $regionName;

    public function __construct($sqlConnection)
    {
        $this->sqlConnection = $this->sqlConnection;
    }

    public function FindSQLNames(int $shipID, int $solarSystemID): void
    {
        $this->shipID = $shipID;
        $this->solarSystemID = $solarSystemID;

        $stmt = $this->sqlConnection->stmt_init();
        $stmt->prepare("SELECT mSS.solarSystemName AS solarSystemName,
                                                mR.regionName AS regionName, iT.typeName AS shipName
                                                FROM mapSolarSystems AS mSS, mapRegions AS mR, invTypes as iT
                                                WHERE mR.regionID = mSS.regionID
                                                AND iT.typeID = ?
                                                AND mSS.solarSystemID = ?");
        $stmt->bind_param("ii", $this->shipID, $this->solarSystemID);
        /*$stmt->execute();
        $stmtResult = $stmt->get_result();
        if($stmtResult->num_rows > 0)
        {
            echo "Yes!";
        }
        /*$row = $stmtResult->fetch_all(MYSQLI_ASSOC);

        if($stmtResult->num_rows == 1)
        {
            $this->solarSystemName = $row['solarSystemName'];
            $this->regionName = $row['regionName'];
            $this->shipName = $row['shipName'];
        }*/
    }


}

?>