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
        $sqlQuery = "SELECT * FROM Users WHERE u_username = ?";
        $statement = $this->db_handle->prepare($sqlQuery);
        $statement->execute([$username]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if ($user == null)
        {
            return false;
        }
        else
        {
            $validPassword = password_verify($password, $user['u_password']);
            if ($validPassword)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}