<?php

require_once ("Models/Database.php");
require_once ("Models/Category.php");
class CategoryData
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
    }

    public function getAllCategories()
    {
        $sqlQuery = "SELECT *
                     FROM Categories";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $data = [];
        while($dbRow = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $data[] =  Category::Category($dbRow);
        }
    }
}