<?php

require_once ("Models/Database.php");
require_once ("Models/Post.php");

class PostDataset
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
    }

    public function getBasicPosts()
    {
        $sqlQuery = "SELECT p_id, p_title FROM Posts WHERE p_parentID IS NULL ORDER BY p_datecreated";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        $dataSet = array();
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC))
        {
            array_push($dataSet, Post::basicPost($dbRow));
        }

        $this->_dbInstance->destruct();

        return $dataSet;
    }
}