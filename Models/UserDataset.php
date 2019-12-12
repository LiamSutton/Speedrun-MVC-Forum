<?php
require_once ("Models/Database.php");
require_once ("Models/User.php");


/**
 * Class UserDataset
 */
class UserDataset
{
    /**
     * @var PDO
     */
    /**
     * @var Database|PDO|null
     */
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

    /**
     * @param $username
     * @return User
     */
    public function getUser($username)
    {
        $sqlQuery = "SELECT *
                     FROM Users
                     WHERE u_username = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $this->_dbInstance->destruct();
        return new User($user);
    }

    /**
     * @param $id - the ID of the user to retrieve
     * @return User - a user object for the given user
//     */
    public function getUserByID($id)
    {
        $sqlQuery = "SELECT u_id,
                            u_username,
                            u_password,
                            concat(u_firstname, ' ', u_lastname) as 'u_fullname',
                            u_datecreated,
                            u_avatar,
                            (
                                SELECT COUNT(*) 
                                FROM Posts 
                                    WHERE p_posterID = u_id AND p_parentID IS NULL
                            ) 
                            AS 'u_postcount',
                            (
                                SELECT COUNT(*) 
                                FROM Posts 
                                    WHERE p_posterID = u_id AND p_parentID IS NOT NULL
                            ) 
                            AS 'u_replycount'
                            FROM Users
                                WHERE u_id = :id";

        $statement = $this->_dbHandle->prepare($sqlQuery);

        // bind values to prevent SQL INJECTION
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $user = $statement->fetch();
        return new User($user);
    }


    /**
     * @param $username - the username of the new user
     * @param $password - the unhashed password of the new user
     * @param $firstname - the firstname of the new user
     * @param $lastname - the lastname of the new user
     * @param null $avatar - an optional user avatar image
     * @return bool - whether creating a new user was a success
     */
    public function createUser($username, $password, $firstname, $lastname, $avatar=null)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sqlQuery = "INSERT INTO Users (u_username, u_password, u_firstname, u_lastname, u_datecreated, u_avatar)
                     VALUES (:username, :password, :firstname, :lastname, NOW(), :avatar)";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->bindValue(':password', $password, PDO::PARAM_STR);
        $statement->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $statement->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $statement->bindValue(':avatar', $avatar, PDO::PARAM_STR);

        $statement->execute();

        $this->_dbInstance->destruct();

        return true;
    }
}