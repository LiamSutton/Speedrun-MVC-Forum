<?php


class Post
{
    protected $_id, $_posterID, $_title, $_content, $_parentID, $_dateCreated;

    public function __construct()
    {

    }

    public static function fullPost($dbRow)
    {
        $instance = new self();

        $instance->_id = $dbRow['p_id'];
        $instance->_posterID = $dbRow['p_posterID'];
        $instance->_title = $dbRow['p_title'];
        $instance->_content = $dbRow['p_content'];
        $instance->_parentID = $dbRow['p_parentID'];
        $instance->_dateCreated = $dbRow['p_datecreated'];
    }

    public static function basicPost($dbRow)
    {
        $instance = new self();

        $instance->_id = $dbRow['p_id'];
        $instance->_title = $dbRow['p_title'];

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


}