<?php

require_once("Models/Database.php");
require_once("Models/Category.php");

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
        $sqlQuery = "SELECT c_id, c_name, COUNT(p_id) AS c_postCount
                     FROM Categories
                    LEFT JOIN Posts P on Categories.c_id = P.p_categoryID WHERE p_parentID IS NULL
                    GROUP BY c_id, c_name";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $data = [];
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = Category::Category($dbRow);
        }

        $this->_dbInstance->destruct();

        return $data;
    }

    public function getCategoryName($id)
    {
        $sqlQuery = "SELECT c_name FROM Categories WHERE c_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$id]);

        return $statement->fetchColumn();
    }


}