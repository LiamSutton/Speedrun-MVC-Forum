<?php

require_once ("Models/Database.php");
require_once ("Models/Post.php");

// TODO: Possibly separate the Create / Insert operations into a static class
class PostDataset
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
        $this->_dbHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getBasicPosts()
    {
        $sqlQuery = "SELECT p_id, p_title, p_content, u_username 
                    FROM Posts 
                    JOIN Users
                    ON p_posterID = u_id
                    WHERE p_parentID IS NULL 
                    ORDER BY p_datecreated DESC";

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

    public function getPost($p_id)
    {
        $sqlQuery = "SELECT * 
                    FROM Posts
                    WHERE p_id = ?";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$p_id]);

        $post = $statement->fetch(PDO::FETCH_ASSOC);
        return Post::fullPost($post);
    }

    public function getReplies($p_id)
    {
        $sqlQuery = "SELECT p_id, p_title, p_content, p_parentID, u_username
                     FROM Posts
                     JOIN Users ON p_posterID = u_id
                     WHERE p_parentID = ?
                     ORDER BY p_datecreated";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$p_id]);

        $dataSet = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            array_push($dataSet, POST::basicPost($row));
        }
        return $dataSet;
    }

    // TODO: Maybe should return true if succeeds?
    public function createPost($posterID, $title, $content) {
        $sqlQuery = "INSERT INTO Posts
                     (p_posterID, p_title, p_content, p_parentID, p_datecreated) 
                     VALUES (?, ?, ?, NULL, NOW())";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$posterID, $title, $content]);

    }

    // TODO: Maybe should return true if succeeds?
    public function createReply($posterID, $title, $content, $parentID)
    {
        $sqlQuery = "INSERT INTO Posts
                     (p_posterID, p_title, p_content, p_parentID, p_datecreated) 
                     VALUES (?, ?, ?, ?, NOW())";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$posterID, $title, $content, $parentID]);
    }

}