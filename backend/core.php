<?php
//require_once("config.php");
include("config.php");

class Core
{
    private string $sqlHost;
    private string $sqlUser;
    private string $sqlPassword;
    private string $sqlDatabase;
    private mysqli $sqlConnection;
    private mysqli_sql_exception $sqlError;

    private string $websiteTitle;
    private string $navigationTitle;

    // EVE Online related variables
    private int $allianceID = 0;
    private int $corporationID = 0;
    private bool $useAllianceIcon = true;

    /**
     * Create the base core class for a website, pulls data from config.php
     * You have to call Core::ConnectToSql to establish a connection!
     */
    public function __construct()
    {
        /*
         * Pulls variables from config.php
         */
        $this->sqlHost = $GLOBALS['sqlHost'];
        $this->sqlUser = $GLOBALS['sqlUser'];
        $this->sqlPassword = $GLOBALS['sqlPassword'];
        $this->sqlDatabase = $GLOBALS['sqlDatabase'];

        $this->allianceID = $GLOBALS['allianceID'];
        $this->corporationID = $GLOBALS['corporationID'];
        $this->useAllianceIcon = $GLOBALS['useAllianceIcon'];

        $this->websiteTitle = $GLOBALS['websiteTitle'];
        $this->navigationTitle = $GLOBALS['navigationTitle'];

        /*
         * Use exception handling instead of posting error message on website
         * and give all errors
         */
        //mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);
        mysqli_report(MYSQLI_REPORT_STRICT);
    }

    /**
     * Displays an error message inside a red error box, it adds the needed bootstrap library unless specified
     * @param string $errorMessage String error message, will be displayed inside the box
     * @param bool $includeBootstrap Bool if it should include bootstrap or not: default is TRUE
     * @return void
     */
    public function ShowErrorAlert(string $errorMessage, bool $includeBootstrap = true): void
    {
        if(!$includeBootstrap)
        {
            echo '
            <br />
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <span class="badge bg-danger">Error!</span> '. $errorMessage .'
                </div>
            </div>
            ';
        }
        else
        {
            $this->startHTML();
            $this->loadHeader();
            $this->startBody();
            echo '
            <br />
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <h3><span class="badge bg-danger">Error!</span></h3>
                    <br />
                     '. $errorMessage .'
                </div>
            </div>
            ';
            $this->loadScripts();
            $this->endBody();
            $this->endHTML();
        }
    }

    /**
     * Set (My/maria db)SQL login information but won't connect
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
        catch (mysqli_sql_exception $e)
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
        return "<strong>SQL error</strong> in <strong>" . $this->sqlError->getFile() . "</strong> in line <strong>" .
                $this->sqlError->getLine() . "</strong> -> <strong>" . $this->sqlError->getMessage() . "</strong>";
    }

    /**
     * Returns the mysqli connection object, doesn't check if it's available
     * @return mysqli Returns the mysqli object
     */
    public function GetSQLObject(): mysqli
    {
        return $this->sqlConnection;
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
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <!-- Customer CSS -->
            <!--<link rel="stylesheet" type="text/css" href="css/style.css">-->';

            // In case allianceID is not correct the icon will be replaced by a placeholder
            if($this->checkAllianceID())
                echo '<link rel="shortcut icon" sizes="16x16" href="https://images.evetech.net/alliances/'. $this->allianceID .'/logo?size=32" />';
            else
                echo '<link rel="shortcut icon" sizes="16x16" href="https://zkillboard.com/img/eve-question.png" />';

            echo '
            <title>'.
                $this->websiteTitle
            .'</title>
        </head>
        ';
    }

    /**
     * Starts body html tag
     * @return void
     */
    function startBody(): void
    {
        echo '<body>';
    }

    /**
     * Loads the main navigation bar
     * @return void
     */
    function loadNavigationBar(): void
    {
        echo '
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="https://images.evetech.net/alliances/' . $this->allianceID .'/logo?size=32" alt="" width="30" height="30" class="d-inline-block align-text-top">
                    '. $this->navigationTitle . '
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="payouts.php">View Payouts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="my_losses.php">My Lossmails</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="submit_lossmail.php">Submit Lossmail</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Open lossmails</a></li>
                                <li><a class="dropdown-item" href="#">Closed lossmails</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        ';
    }

    /**
     * Ends body html tag
     * @return void
     */
    function endBody(): void
    {
        echo '</body>';
    }

    /**
     * Loads all JavaScript files
     * @return void
     */
    function loadScripts(): void
    {
        echo '
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
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