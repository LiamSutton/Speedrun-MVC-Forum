<?php

require_once("Models/Database.php");

class Category
{
    protected $_categoryID, $_categoryName, $_categoryPostCount;

    public function __construct()
    {

    }

    public static function Category($dbRow)
    {
        $instance = new self();
        $instance->_categoryID = $dbRow['c_id'];
        $instance->_categoryName = $dbRow['c_name'];
        $instance->_categoryPostCount = $dbRow['c_postCount'];

        return $instance;
    }

    public function getCategoryID()
    {
        return $this->_categoryID;
    }

    public function getCategoryName()
    {
        return $this->_categoryName;
    }

    public function getCategoryPostCount()
    {
        return $this->_categoryPostCount;
    }
}