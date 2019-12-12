<?php


/**
 * Class Message
 */
class Message
{
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    protected $_messageID, $_messageSenderID, $_messageRecipientID, $_messageContent, $_messageSenderUsername, $_messageRecipientUsername;

    /**
     * Message constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $dbRow - dbRow containing data to create message
     * @return Message - a new message object containing information from the db
     */
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

    /**
     * @return mixed
     */
    public function getMessageID()
    {
        return $this->_messageID;
    }

    /**
     * @return mixed
     */
    public function getMessageSenderID()
    {
        return $this->_messageSenderID;
    }

    /**
     * @return mixed
     */
    public function getMessageRecipientID()
    {
        return $this->_messageRecipientID;
    }

    /**
     * @return mixed
     */
    public function getMessageContent()
    {
        return $this->_messageContent;
    }

    /**
     * @return mixed
     */
    public function getMessageSenderUsername()
    {
        return $this->_messageSenderUsername;
    }

    /**
     * @return mixed
     */
    public function getMessageRecipientUsername()
    {
        return $this->_messageRecipientUsername;
    }
}