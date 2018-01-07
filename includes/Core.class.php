<?php

class Core
{
    # @object, The PDO object
    public $pdo;

    private static $instance;

    # @array,  The database settings
    private $settings;

    /**
     *   Default Constructor
     *
     *    Connect to database.
     */
    public function __construct()
    {
        $this->ConnectDB();
    }

    /**
     *    This method makes connection to the database.
     *
     *    1. Reads the database settings from a ini file.
     *    2. Puts  the ini content into the settings array.
     *    3. Tries to connect to the database.
     *    4. If connection failed, exception is displayed and a log file gets created.
     */
    private function ConnectDB()
    {
        $this->settings = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "/settings.ini.php");
        $dsn = 'mysql:dbname=' . $this->settings["dbname"] . ';host=' . $this->settings["host"] . ';charset=latin1';
        try {
            // We can uncomment below when we'll wanna switch to UTF-8
//            # Read settings from INI file, set UTF8
//            $this->pdo = new PDO($dsn, $this->settings["user"], $this->settings["password"], array(
//                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
//            ));

            # Read settings from INI file
            $this->pdo = new PDO($dsn, $this->settings["user"], $this->settings["password"]);

            # We can now log any exceptions on Fatal error.
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Disable emulation of prepared statements, use REAL prepared statements instead.
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            # Connection succeeded, set the boolean to true.
            $this->bConnected = true;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    /*
     *   You can use this little method if you want to close the PDO connection
     *
     */
    public function CloseConnection()
    {
        # Set the PDO object to null to close the connection
        # http://www.php.net/manual/en/pdo.connections.php
        $this->pdo = null;
    }
}