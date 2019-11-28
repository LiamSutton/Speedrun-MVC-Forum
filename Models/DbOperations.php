<?php

require_once ("Models/Database.php");
class DbOperations
{
    protected $_dbInstance, $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
    }

    public function Login($username, $password)
    {
        $sqlQuery = "SELECT * FROM Users WHERE u_username = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $this->_dbInstance->destruct();
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