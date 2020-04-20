<?php


/**
 * Class Message
 */
class Message implements JsonSerializable
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
    protected $_messageID, $_messageSenderID, $_messageRecipientID, $_messageContent, $_messageDatecreated, $_messageSenderName, $_messageRecipientName, $_messageImage;

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
        $instance->_messageDatecreated = $dbRow['m_datecreated'];
        $instance->_messageSenderName = $dbRow['senderName'];
        $instance->_messageRecipientName = $dbRow['recipientName'];
        $instance->_messageImage = $dbRow['m_image'];

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

    public function getMessageDatecreated() {
        return $this->_messageDatecreated;
    }

    public function getMessageSenderName() {
        return $this->_messageSenderName;
    }

    public function getMessageRecipientName() {
        return $this->_messageRecipientName;
    }

    /**
     * @return mixed
     */
    public function getMessageImage()
    {
        return $this->_messageImage;
    }
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}