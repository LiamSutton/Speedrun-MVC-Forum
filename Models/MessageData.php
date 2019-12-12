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

    // i know this isnt worth any marks but i built it before i got your email saying you just need to hide contact details
    // feels-bad-man.jpg
    /**
     * @param $userID - the id of the logged in user to retrieve messages for
     * @return array - an array of Messages sent to the user
     */
    public function getRecievedMessages($userID)
    {
        $sqlQuery = "SELECT m.m_id, m.m_senderID, m.m_recipientID, m.m_content, s.u_username as 'sender', r.u_username as 'recipient'
        from Messages m
            join Users s on m.m_senderID = s.u_id
            join Users r on m.m_recipientID = r.u_id
                where m.m_recipientID = :id
        ORDER BY m_datecreated DESC";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $userID, PDO::PARAM_INT);

        $statement->execute();

        $data = [];
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = Message::Message($dbRow);
        }

        $this->_dbInstance->destruct();

        return $data;
    }

    /**
     * @param $userID - the id of the logged in user who sent the messages
     * @return array - an array of messages the user sent
     */
    public function getSentMessages($userID)
    {
        $sqlQuery = "SELECT m.m_id, m.m_senderID, m.m_recipientID, m.m_content, s.u_username as 'sender', r.u_username as 'recipient'
                     FROM Messages m
                        JOIN Users s on s.u_id = m.m_senderID
                        JOIN Users r on r.u_id = m.m_senderID
                            WHERE m.m_senderID = :id
                     ORDER BY m.m_datecreated DESC";

        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $userID, PDO::PARAM_INT);

        $statement->execute();

        $data = [];

        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = Message::Message($dbRow);
        }

        $this->_dbInstance->destruct();

        return $data;
    }
}
