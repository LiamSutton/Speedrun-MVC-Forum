<?php


class Database
{
    protected static $_dbInstance = null;
    protected $_dbHandle;

    // Creates a new PDO Object relating to the database
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
    public static function getInstance()
    {
        $username = "sgb959";
        $password = "MaxLex2018!";
        $host = "poseidon.salford.ac.uk";
        $dbname = "sgb959_forum";

        if (self::$_dbInstance == null)
        {
            self::$_dbInstance = new self($username, $password, $host, $dbname);
        }
        return self::$_dbInstance;
    }

    public function getConnection()
    {
        return $this->_dbHandle;
    }

    public function destruct()
    {
        $this->_dbHandle = null;
    }
}