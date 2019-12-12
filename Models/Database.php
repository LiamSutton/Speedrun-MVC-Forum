<?php


/**
 * Class Database
 */
class Database
{
    /**
     * @var null
     */
    protected static $_dbInstance = null;
    /**
     * @var PDO
     */
    protected $_dbHandle;

    // Creates a new PDO Object relating to the database

    /**
     * Database constructor.
     * @param $username - username of the DB
     * @param $password - Password to access the DB
     * @param $host - the hostname for the DB
     * @param $dbname - the Name of the DB
     */
    public function __construct($username, $password, $host, $dbname)
    {
        try
        {
            $this->_dbHandle = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    // If there is an active instance of the database retrieve it, else create a new instance and retrieve it

    /**
     * @return Database|null - uses the singleton pattern to either create an instance of a DB or return a current one
     */
    public static function getInstance()
    {
        $username = "sgb959";
        // not my paypal password
        $password = "MaxLex2018!";
        $host = "poseidon.salford.ac.uk";
        $dbname = "sgb959_forum";

        if (self::$_dbInstance == null)
        {
            self::$_dbInstance = new self($username, $password, $host, $dbname);
        }
        return self::$_dbInstance;
    }

    /**
     * @return PDO - a connection to the current instance
     */
    public function getConnection()
    {
        return $this->_dbHandle;
    }

    /**
     * RELEASE THE Kracke... DB
     */
    public function destruct()
    {
        $this->_dbHandle = null;
    }
}