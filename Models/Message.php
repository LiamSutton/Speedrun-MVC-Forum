<?php


class Message
{
    protected $_messageID, $_messageSenderID, $_messageRecipientID, $_messageContent;

    public function __construct()
    {

    }

    public static function Message($dbRow)
    {
        $instance = new self();

        $instance->_messageID = $dbRow['m_id'];
        $instance->_messageSenderID = $dbRow['m_senderID'];
        $instance->_messageRecipientID = $dbRow['m_recipientID'];
        $instance->_messageContent = $dbRow['m_content'];
    }

    public function getMessageID()
    {
        return $this->_messageID;
    }

    public function getMessageSenderID()
    {
        return $this->_messageSenderID;
    }

    public function getMessageRecipientID()
    {
        return $this->_messageRecipientID;
    }
}