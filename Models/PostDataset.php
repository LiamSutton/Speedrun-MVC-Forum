<?php

require_once("Models/Database.php");
require_once("Models/Post.php");

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
        $sqlQuery = "SELECT p_id, p_title, p_posterID, p_content, u_username 
                    FROM Posts 
                    JOIN Users
                    ON p_posterID = u_id
                    WHERE p_parentID IS NULL 
                    ORDER BY p_datecreated DESC";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        $dataSet = array();
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($dataSet, Post::basicPost($dbRow));
        }

        $this->_dbInstance->destruct();

        return $dataSet;
    }

    public function getPost($p_id)
    {
        $sqlQuery = "SELECT p_id, p_posterID, p_title, p_posterID,p_content, p_parentID, p_datecreated, p_image, u_username
                    FROM Posts
                    JOIN Users on p_posterID = u_id
                    WHERE p_id = ?";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$p_id]);

        $post = $statement->fetch(PDO::FETCH_ASSOC);
        $this->_dbInstance->destruct();
        return Post::fullPost($post);
    }

    public function getUserPostCount($u_id)
    {
        $sqlQuery = "SELECT COUNT(*)
                     FROM Posts
                     WHERE p_posterID = ? AND p_parentID IS NULL";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$u_id]);
        $postCount = $statement->fetchColumn();
        $this->_dbInstance->destruct();
        return $postCount;
    }

    public function getAllUserPosts($u_id)
    {
        $sqlQuery = "SELECT p_id, p_title, p_posterID, p_content, u_username
                     FROM Posts
                     LEFT JOIN Users on u_id = ?
                     WHERE p_posterID = ?
                     AND p_parentID IS NULL";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$u_id, $u_id]);

        $dataSet = array();
        while ($row = $statement->fetch()) {
            array_push($dataSet, Post::basicPost($row));
        }
        $this->_dbInstance->destruct();
        return $dataSet;
    }

    public function getUserReplyCount($u_id)
    {
        $sqlQuery = "SELECT COUNT(*)
                     FROM Posts
                     WHERE p_posterID = ? AND p_parentID IS NOT NULL";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$u_id]);
        $replyCount = $statement->fetchColumn();
        $this->_dbInstance->destruct();
        return $replyCount;
    }

    public function getReplies($p_id)
    {
        $sqlQuery = "SELECT p_id, p_title, p_posterID, p_content, p_parentID, u_username, p_image
                     FROM Posts
                     JOIN Users ON p_posterID = u_id
                     WHERE p_parentID = ?
                     ORDER BY p_datecreated";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$p_id]);

        $dataSet = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($dataSet, POST::reply($row));
        }
        $this->_dbInstance->destruct();
        return $dataSet;
    }

    // TODO: Maybe should return true if succeeds?
    public function createPost($posterID, $title, $content, $image)
    {
        $sqlQuery = "INSERT INTO Posts
                     (p_posterID, p_title, p_content, p_parentID, p_datecreated, p_image) 
                     VALUES (?, ?, ?, NULL, NOW(), ?)";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$posterID, $title, $content, $image]);
        $this->_dbInstance->destruct();
    }

    // TODO: Maybe should return true if succeeds?
    public function createReply($posterID, $title, $content, $parentID, $image)
    {
        $sqlQuery = "INSERT INTO Posts
                     (p_posterID, p_title, p_content, p_parentID, p_datecreated, p_image) 
                     VALUES (?, ?, ?, ?, NOW(), ?)";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$posterID, $title, $content, $parentID, $image]);
        $this->_dbInstance->destruct();
    }
    public function getWatchlist($userID)
    {
        $sqlQuery = "SELECT p_id, p_title, p_posterID, p_content, u_username
                    FROM Watchlist
                    JOIN Posts P on Watchlist.w_postID = P.p_id
                    and w_userID = ?
                    join Users U on P.p_posterID = U.u_id;";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$userID]);

        $dataSet = array();
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC))
        {
            array_push($dataSet, Post::basicPost($dbRow));
        }

        $this->_dbInstance->destruct();

        return $dataSet;
    }

    public function addToWatchlist($userID, $postID)
    {
        $sqlQuery = "INSERT INTO Watchlist (w_userID, w_postID) 
                     VALUES (?, ?)";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$userID, $postID]);

        $this->_dbInstance->destruct();

        return true;
    }

    public function removeFromWatchlist($userID, $postID)
    {
        $sqlQuery = "DELETE FROM Watchlist
                     WHERE w_userID = ? AND w_postID = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$userID, $postID]);

        $this->_dbInstance->destruct();

        return true;
    }

    public function isOnWatchlist($userID, $postID)
    {
        $sqlQuery = "SELECT * FROM Watchlist
                     WHERE w_userID = ? AND w_postID = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$userID, $postID]);

        $this->_dbInstance->destruct();

        return $statement->rowCount() > 0;
    }

}