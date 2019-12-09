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

    public function getAllPosts()
    {

    }

    public function getBasicPosts($categoryID)
    {
        $sqlQuery = "SELECT P.p_id,
                            P.p_posterID,
                            P.p_title,
                           P.p_posterID,
                           P.p_content,
                           P.p_parentID,
                           P.p_datecreated,
                           concat(u_firstname, ' ', u_lastname) as 'u_fullname',
                           (SELECT COUNT(*) FROM Posts R WHERE R.p_parentID = P.p_id) as 'p_replycount'
                    FROM Posts P
                             JOIN Users on P.p_posterID = u_id
                    WHERE P.p_parentID IS NULL AND P.p_categoryID = ?
                    ORDER BY p_replycount, p_datecreated DESC ";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$categoryID]);
        $dataSet = array();
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($dataSet, Post::basicPost($dbRow));
        }

        $this->_dbInstance->destruct();

        return $dataSet;
    }

    public function getPost($p_id)
    {
        $sqlQuery = "SELECT P.p_id,
                            P.p_posterID,
                            P.p_title,
                           P.p_posterID,
                           P.p_content,
                           P.p_parentID,
                           P.p_datecreated,
                           P.p_image,
                           P.p_categoryID,
                           concat(u_firstname, ' ', u_lastname) as 'u_fullname',
                           (SELECT COUNT(*) FROM Posts R WHERE R.p_parentID = P.p_id) as 'p_replycount'
                    FROM Posts P
                             JOIN Users on P.p_posterID = u_id
                    WHERE P.p_id = ?
                    ORDER BY P.p_datecreated DESC";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$p_id]);

        $post = $statement->fetch(PDO::FETCH_ASSOC);
        $this->_dbInstance->destruct();
        return Post::fullPost($post);
    }

    public function getAllUserPosts($u_id)
    {
        $sqlQuery = "SELECT 
                     P.p_id, 
                     P.p_title, 
                     P.p_posterID, 
                     P.p_content, 
                     concat(u_firstname, ' ', u_lastname) 
                     AS 'u_fullname', 
                    (SELECT COUNT(*) 
                        FROM Posts R 
                            WHERE R.p_parentID = P.p_id
                    ) 
                    AS 'p_replycount'
FROM Posts P
         JOIN Users U on  P.p_posterID = U.u_id
WHERE p_posterID = ?
  AND p_parentID IS NULL";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$u_id]);

        $dataSet = array();
        while ($row = $statement->fetch()) {
            array_push($dataSet, Post::basicPost($row));
        }
        $this->_dbInstance->destruct();
        return $dataSet;
    }


    public function getReplies($p_id)
    {
        $sqlQuery = "SELECT p_id, p_title, p_posterID, p_content, p_parentID, concat(u_firstname, ' ', u_lastname) as 'u_fullname', p_image, p_categoryID
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
    public function createPost($posterID, $title, $content, $image, $categoryID)
    {
        $sqlQuery = "INSERT INTO Posts
                     (p_posterID, p_title, p_content, p_parentID, p_datecreated, p_image, p_categoryID) 
                     VALUES (?, ?, ?, NULL, current_timestamp(3), ?, ?)";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$posterID, $title, $content, $image, $categoryID]);
        $this->_dbInstance->destruct();
    }

    // TODO: Maybe should return true if succeeds?
    public function createReply($posterID, $title, $content, $parentID, $image, $categoryID)
    {
        $sqlQuery = "INSERT INTO Posts
                     (p_posterID, p_title, p_content, p_parentID, p_datecreated, p_image, p_categoryID) 
                     VALUES (?, ?, ?, ?, NOW(), ?, ?)";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$posterID, $title, $content, $parentID, $image, $categoryID]);
        $this->_dbInstance->destruct();
    }

    public function getWatchlist($userID)
    {
        $sqlQuery = "SELECT 
                            p_id,
                           p_title,
                           p_posterID,
                           p_content,
                           concat(u_firstname, ' ', u_lastname) as 'u_fullname',
                           (SELECT COUNT(*) FROM Posts R WHERE R.p_parentID = P.p_id) AS 'p_replycount'
                                FROM Watchlist W
                                    JOIN Posts P on W.w_postID = P.p_id AND w_userID = ?
                                    JOIN Users U on P.p_posterID = U.u_id
                                ORDER BY w_datecreated DESC";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$userID]);

        $dataSet = array();
        while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($dataSet, Post::basicPost($dbRow));
        }

        $this->_dbInstance->destruct();

        return $dataSet;
    }

    public function addToWatchlist($userID, $postID)
    {
        $sqlQuery = "INSERT INTO Watchlist (w_userID, w_postID, w_datecreated) 
                     VALUES (?, ?, CURRENT_TIMESTAMP(3))";
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