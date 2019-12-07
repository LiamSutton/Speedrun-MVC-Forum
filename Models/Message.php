<?php


class Message
{
    protected $_messageID, $_messageSenderID, $_messageRecipientID, $_messageContent, $_messageSenderUsername, $_messageRecipientUsername;

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
        $instance->_messageSenderUsername = $dbRow['sender'];
        $instance->_messageRecipientUsername = $dbRow['recipient'];

        return $instance;
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

    public function getMessageContent()
    {
        return $this->_messageContent;
    }

    public function getMessageSenderUsername()
    {
        return $this->_messageSenderUsername;
    }

    public function getMessageRecipientUsername()
    {
        return $this->_messageRecipientUsername;
    }
}