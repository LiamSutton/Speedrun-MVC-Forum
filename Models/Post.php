<?php


/**
 * Class Post
 */
class Post implements JsonSerializable
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
    protected
        $_id,
        $_posterID,
        $_posterFullName,
        $_title,
        $_content,
        $_parentID,
        $_dateCreated,
        $_image,
        $_categoryID,
        $_replyCount;

    /**
     * Post constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $dbRow - Db row containing data to create Post
     * @return Post - a fullPost object
     */
    public static function fullPost($dbRow)
    {
        $instance = new self();

        $instance->_id = $dbRow['p_id'];
        $instance->_posterID = $dbRow['p_posterID'];
        $instance->_title = $dbRow['p_title'];
        $instance->_posterID = $dbRow['p_posterID'];
        $instance->_content = $dbRow['p_content'];
        $instance->_parentID = $dbRow['p_parentID'];
        $instance->_dateCreated = $dbRow['p_datecreated'];
        $instance->_posterFullName = $dbRow['u_fullname'];
        $instance->_image = $dbRow['p_image'];
        $instance->_categoryID = $dbRow['p_categoryID'];

        return $instance;
    }

    /**
     * @param $dbRow - db information to create post
     * @return Post - a basicPost Object
     */
    public static function basicPost($dbRow)
    {
        $instance = new self();

        $instance->_id = $dbRow['p_id'];
        $instance->_title = $dbRow['p_title'];
        $instance->_posterID = $dbRow['p_posterID'];
        $instance->_posterFullName = $dbRow['u_fullname'];
        $instance->_content = $dbRow['p_content'];
        $instance->_replyCount = $dbRow['p_replycount'];

        return $instance;
    }

    // creates a Reply object using information from db
    public static function reply($dbRow)
    {
        $instance = new self();

        $instance->_id = $dbRow['p_id'];
        $instance->_posterID = $dbRow['p_posterID'];
        $instance->_title = $dbRow['p_title'];
        $instance->_posterFullName = $dbRow['u_fullname'];
        $instance->_content = $dbRow['p_content'];

        return $instance;

    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getPosterID()
    {
        return $this->_posterID;
    }

    public function getPosterFullName()
    {
        return $this->_posterFullName;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @return mixed
     */
    public function getParentID()
    {
        return $this->_parentID;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    public function getImage()
    {
        return $this->_image;
    }

    public function getCategoryID()
    {
        return $this->_categoryID;
    }

    public function getReplyCount()
    {
        return $this->_replyCount;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}