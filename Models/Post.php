<?php


class Post
{
    protected $_id, $_posterID, $_posterUsername, $_title, $_content, $_parentID, $_dateCreated;

    public function __construct()
    {

    }

    // TODO: fix difference between fullPost and basicPost (Basic Post contains posterUsername whilst full post doesnt)
    public static function fullPost($dbRow)
    {
        $instance = new self();

        $instance->_id = $dbRow['p_id'];
        $instance->_posterID = $dbRow['p_posterID'];
        $instance->_title = $dbRow['p_title'];
        $instance->_content = $dbRow['p_content'];
        $instance->_parentID = $dbRow['p_parentID'];
        $instance->_dateCreated = $dbRow['p_datecreated'];

        return $instance;
    }

    public static function basicPost($dbRow)
    {
        $instance = new self();

        $instance->_id = $dbRow['p_id'];
        $instance->_title = $dbRow['p_title'];
        $instance->_posterUsername = $dbRow['u_username'];
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

    public function getPosterUsername()
    {
        return $this->_posterUsername;
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