<?php

require_once("Models/Database.php");
require_once ("Models/Message.php");

/**
 * Class MessageData
 */
class MessageData
{
    /**
     * @var PDO
     */
    /**
     * @var Database|PDO|null
     */
    protected $_dbHandle, $_dbInstance;

    /**
     * MessageData constructor.
     */
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
    }

    /** This function will insert a Message record into the database
     * @param $senderID : The ID of the user who sent the message
     * @param $recipientID : The ID of the user the message is for
     * @param $content : The text content contained in the body of the message
     */
    public function sendMessage($senderID, $recipientID, $content) {
        $sqlQuery = "INSERT INTO Messages
                     (m_senderID, m_recipientID, m_content, m_image, m_opened, m_datecreated) 
                     VALUES (:senderID, :recipientID, :content, NULL, FALSE, NOW())";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(":senderID", $senderID, PDO::PARAM_INT);
        $statement->bindValue(":recipientID", $recipientID, PDO::PARAM_INT);
        $statement->bindValue(":content", $content, PDO::PARAM_STR);
//        $statement->bindValue(":image", $imageURL, PDO::PARAM_STR);

        $statement->execute();
        $this->_dbInstance->destruct();
    }

    /** This function will search for any unopened messages for a given user, if found will return the number of messages
     * @param $recipientID : The recipient for the unopened messages
     * @return mixed : The number of unopened messages for a given user
     */
    public function getUnopenedMessages($recipientID) {
        $sqlQuery = "SELECT COUNT(m_id) AS count FROM Messages
                     WHERE m_recipientID = :recipientID AND m_opened IS FALSE";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(":recipientID", $recipientID, PDO::PARAM_INT);

        $statement->execute();

        $count = $statement->fetch(PDO::FETCH_ASSOC);

        $this->_dbInstance->destruct();

        return $count;
    }

    /** This function will set all unopened messages to opened for a given user (clearing notifications)
     * @param $recipientID : The ID of the user to clear notifications for
     */
    public function markAllAsRead($recipientID) {
        $sqlQuery = "UPDATE Messages
                     SET m_opened = TRUE
                     WHERE m_opened IS FALSE AND m_recipientID = :recipientID";

        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(":recipientID", $recipientID, PDO::PARAM_INT);

        $statement->execute();

        $this->_dbInstance->destruct();
    }

    public function getConversationList($id) {
        $sqlQuery = "SELECT DISTINCT u_id as id, u_username as username FROM Users
                     JOIN Messages M on Users.u_id = M.m_recipientID or Users.u_id = M.m_senderID
                     WHERE u_id != :id";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();

        $conversations = array();
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($conversations, $dbRow);
        }
        return $conversations;

    }
}
