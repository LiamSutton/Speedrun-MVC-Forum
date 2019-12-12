<?php

require_once("Models/Database.php");
require_once("Models/Category.php");

/**
 * Class CategoryData
 */
class CategoryData
{
    /**
     * @var PDO
     */
    /**
     * @var Database|PDO|null
     */
    protected $_dbHandle, $_dbInstance;

    /**
     * CategoryData constructor.
     */
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
    }

    /**
     * @return array - an array containing all the current categories for the forum
     */
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

    /**
     * @param $id - the id of the category we want
     * @return mixed - the name of the category of ID = $id
     */
    public function getCategoryName($id)
    {
        $sqlQuery = "SELECT c_name FROM Categories WHERE c_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$id]);

        return $statement->fetchColumn();
    }


}