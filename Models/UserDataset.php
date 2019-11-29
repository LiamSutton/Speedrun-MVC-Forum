<?php
require_once ("Models/Database.php");
require_once ("Models/User.php");

// TODO: Possibly separate the Create / Insert operations into a static class
class UserDataset
{
    protected $_dbHandle, $_dbInstance;

    /**
     * UserDataset constructor.
     */
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
    }


    /**
     * @param $username: username of account to authenticate
     * @param $password: un-hashed password of account to authenticate
     * @return bool: result of whether the login was successful
     */
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

    // TODO: Maybe should return true if succeeds?
    // TODO: username is guarunteed to be unique but maybe should do it from u_id?
    public function getUser($username)
    {
        $sqlQuery = "SELECT *
                     FROM Users
                     WHERE u_username = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return new User($user);
    }

    public function createUser($username, $password, $firstname, $lastname, $avatar=null)
    {
        $sqlQuery = "INSERT INTO Users (u_username, u_password, u_firstname, u_lastname, u_datecreated, u_avatar)
                     VALUES (?, ?, ?, ?, NOW(), ?)";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username, $password, $firstname, $lastname, $avatar]);
        return true;
    }
}