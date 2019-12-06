<?php

require_once("Models/Database.php");

class Category
{
    protected $_categoryID, $_categoryName;

    public function __construct()
    {

    }

    public static function Category($dbRow)
    {
        $instance = new self();
        $instance->_categoryID = $dbRow['c_id'];
        $instance->_categoryName = $dbRow['c_name'];

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
}