<?php

require_once("Models/Database.php");
require_once ("Models/Message.php");

class MessageData
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
    }

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
