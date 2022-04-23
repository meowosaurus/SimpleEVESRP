<?php

class Core
{
    private string $sqlHost;
    private string $sqlUser;
    private string $sqlPassword;
    private string $sqlDatabase;
    private mysqli $sqlConnection;
    private mysqli_sql_exception $sqlError;

    private string $websiteTitle = "Test SRP System";
    private string $navigationTitle = "Test SRP System";

    // EVE Online related variables
    private int $allianceID = 0;

    /**
     * Create the base core class for a website, needs an (My/maria db)sql connection.
     * You have to call Core::ConnectToSql to establish a connection!
     * @param string $sqlHost Hostname of your database, for exmaple localhost:3306 or your docker container name
     * @param string $sqlUser Name of your sql user
     * @param string $sqlPassword Password for sql user
     * @param string $sqlDatabase Database for sql, sometimes same as user
     */
    public function __construct(string $sqlHost, string $sqlUser,
                                string $sqlPassword, string $sqlDatabase)
    {
        $this->sqlHost = $sqlHost;
        $this->sqlUser = $sqlUser;
        $this->sqlPassword = $sqlPassword;
        $this->sqlDatabase = $sqlDatabase;

        /* Use exception handling instead of posting error message on website
         * and give all errors
         */
        mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);
    }

    /**
     * Set (My/maria db)SQL login information and won't connect
     * @param string $sqlHost Hostname of your database, for exmaple localhost:3306 or your docker container name
     * @param string $sqlUser Name of your sql user
     * @param string $sqlPassword Password for sql user
     * @param string $sqlDatabase Database for sql, sometimes same as user
     * @return void
     */
    public function SetSQLLogin(string $sqlHost, string $sqlUser,
                                string $sqlPassword, string $sqlDatabase): void
    {
        $this->sqlHost = $sqlHost;
        $this->sqlUser = $sqlUser;
        $this->sqlPassword = $sqlPassword;
        $this->sqlDatabase = $sqlDatabase;
    }

    /**
     * Connects to your SQL database via the login data from the constructor or Core::SetSQLLogin
     * @return bool Returns false if a connection didn't work
     */
    public function ConnectToSql(): bool
    {
        try
        {
            $this->sqlConnection = new mysqli($this->sqlHost, $this->sqlUser, $this->sqlPassword, $this->sqlDatabase);
        }
        catch(mysqli_sql_exception $e)
        {
            $this->sqlError = $e;

            return false;
        }
        return true;
    }

    /**
     * Returns the last sql error message as string. Format:
     * SQL error in: :file: in line :line: -> :sql error message:
     * @return string Returns a formatted string
     */
    public function GetSQLError(): string
    {
        return "SQL error in " . $this->sqlError->getFile() . " in line " .
                $this->sqlError->getLine() . " -> " . $this->sqlError->getMessage();
    }

    /**
     * Sets website-tab-title on all pages
     * @param string $title
     * @return void
     */
    public function setWebsiteTitle(string $title): void
    {
        $this->websiteTitle = $title;
    }

    /**
     * Sets top-left title for navigation bar on all pages
     * @param string $title
     * @return void
     */
    public function setNavigationTitle(string $title): void
    {
        $this->navigationTitle = $title;
    }

    /**
     * Sets eve related alliance id
     * @param int $id Alliance ID
     * @return void
     */
    public function setAllianceID(int $id): void
    {
        $this->allianceID = $id;
    }

    /**
     * Checks if alliance id is not 0.
     * @return bool Returns false if alliance id is 0
     */
    private function CheckAllianceID(): bool
    {
        if($this->allianceID == 0)
            return false;
        return true;
    }

    /**
     * Start of html tag with DOCTYPE (in HTML5)
     * @return void
     */
    function startHTML(): void
    {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        ';
    }

    /**
     * Loading of head data for website, such as CSS/charset/metadata.
     * Use Core::setWebsiteTitle to change title.
     * @return void
     */
    function loadHeader(): void
    {
        echo '
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- Bootstrap CSS -->
            <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <!-- Customer CSS -->
            <!--<link rel="stylesheet" type="text/css" href="css/style.css">-->';


            // In case allianceID is not correct the icon will be replaced by a placeholder
            if($this->checkAllianceID())
                echo '<link rel="shortcut icon" sizes="16x16" href="https://images.evetech.net/alliances/'. $this->allianceID .'/logo?size=32" />';
            else
                echo '<link rel="shortcut icon" sizes="16x16" href="https://zkillboard.com/img/eve-question.png" />';


            echo '<title>'
            . $this->websiteTitle .
            '</title>
        </head>
        ';
    }

    /**
     * End of html tag (in HTML5)
     * @return void
     */
    function endHTML(): void
    {
        echo '
        </html>
        ';
    }


}

?>