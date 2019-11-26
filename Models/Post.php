<?php


class Post
{
    protected $_id, $_posterID, $_title, $_content, $_parentID, $_dateCreated;

    public function __construct($dbRow)
    {
        $this->_id = $dbRow['p_id'];
        $this->_posterID = $dbRow['p_posterID'];
        $this->_title = $dbRow['p_title'];
        $this->_content = $dbRow['p_content'];
        $this->_parentID = $dbRow['p_parentID'];
        $this->_dateCreated = $dbRow['p_datecreated'];
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