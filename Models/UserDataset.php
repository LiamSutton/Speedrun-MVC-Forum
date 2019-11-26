<?php
require_once ("Models/Database.php");

class UserDataset
{
    protected $db_handle, $db_instance;

    /**
     * UserDataset constructor.
     */
    public function __construct()
    {
        $this->db_instance = Database::getInstance();
        $this->db_handle = $this->db_instance->getConnection();
    }


    /**
     * @param $username: username of account to authenticate
     * @param $password: un-hashed password of account to authenticate
     * @return bool: result of whether the login was successful
     */
    public function Login($username, $password)
    {
        $sqlQuery = "SELECT * FROM Users WHERE u_username = ?";
        $statement = $this->db_handle->prepare($sqlQuery);
        $statement->execute([$username]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $this->db_instance->destruct();
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