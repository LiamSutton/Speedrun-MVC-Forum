<?php
require_once ("Models/Database.php");

class UserDataset
{
    protected $db_handle, $db_instance;

    public function __construct()
    {
        $this->db_instance = Database::getInstance();
        $this->db_handle = $this->db_instance->getConnection();
    }

    public function Login($username, $password)
    {
        return true;
    }
}