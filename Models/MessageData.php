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

    /** This function gets a list of all users that the currently logged in user has recieved messages from and orders them by the number of unopened messages and the
     * time the last message was sent
     * @param $id: the id of the logged in user
     * @return array: an associative array of all the messages
     */
    public function getConversationList($id) {
        $sqlQuery = "SELECT distinct u_id as id, u_username as username, concat(u_firstname, ' ', u_lastname) as fullname,    (SELECT MAX(m_datecreated) FROM Messages WHERE (m_senderID = :id AND m_recipientID = u_id) OR (m_senderID = u_id AND m_recipientID = :id)) as lastmsg,
                (SELECT count(m_id) FROM Messages where m_senderID = u_id AND m_recipientID = :id and m_opened = false) as unopened
FROM Users
WHERE u_id IN (SELECT m_recipientID FROM Messages WHERE (m_senderID = u_id AND m_recipientID = :id) OR (m_senderID = :id AND m_recipientID = u_id)) or u_id IN
      (SELECT m_senderID FROM Messages WHERE (m_senderID = u_id AND m_recipientID = :id) OR (m_senderID = :id AND m_recipientID = u_id))
order by unopened desc, lastmsg desc";
        
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();

        $conversations = array();
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($conversations, $dbRow);
        }
        return $conversations;

    }

    public function openMessages($sender, $recipient) {
        $sqlQuery = "UPDATE Messages
                     SET m_opened = TRUE WHERE m_senderID = :sender AND m_recipientID = :recipient";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(":sender", $sender, PDO::PARAM_INT);
        $statement->bindValue(":recipient", $recipient, PDO::PARAM_INT);

        $statement->execute();
        $this->_dbInstance->destruct();
    }

    public function getSentMessages($sender, $recipient) {
        $sqlQuery = "SELECT m_id, m_senderID, m_recipientID, m_content, m_datecreated, concat(A.u_firstname, ' ', A.u_lastname) as 'senderName', concat(B.u_firstname, ' ', B.u_lastname) as 'recipientName' FROM Messages
                     JOIN Users A on Messages.m_senderID = A.u_id
                     JOIN Users B on Messages.m_recipientID = B.u_id
                     WHERE m_senderID = :sender AND m_recipientID = :recipient
                     ORDER BY m_datecreated";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(":sender", $sender, PDO::PARAM_INT);
        $statement->bindValue(":recipient", $recipient, PDO::PARAM_INT);

        $statement->execute();

        $sent = [];
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC)) {
            $sent[] = Message::Message($dbRow);
        }
        $this->_dbInstance->destruct();
        return $sent;
    }

    public function getConversationHistory($user, $other) {
        $userSent = $this->getSentMessages($user, $other);
        $otherSent = $this->getSentMessages($other, $user);

        $history = array_merge($userSent, $otherSent);

        usort($history, function($a, $b) {
           $ad = new DateTime($a->getMessageDatecreated());
           $bd = new DateTime($b->getMessageDatecreated());
           return $ad < $bd ? -1 : 1;
        });
        return $history;
    }
}
