<?php

require_once ("Models/Database.php");
require_once ("Models/Post.php");
class WatchlistData
{
    protected  $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
    }

    public function getUserWatchlist($userID)
    {
        $sqlQuery = "SELECT 
                            p_id,
                           p_title,
                           p_posterID,
                           p_content,
                           concat(u_firstname, ' ', u_lastname) as 'u_fullname',
                           (SELECT COUNT(*) FROM Posts R WHERE R.p_parentID = P.p_id) AS 'p_replycount'
                                FROM Watchlist W
                                    JOIN Posts P on W.w_postID = P.p_id AND w_userID = :id
                                    JOIN Users U on P.p_posterID = U.u_id
                                ORDER BY w_datecreated DESC";

        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $userID, PDO::PARAM_INT);

        $statement->execute();

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
                     VALUES (:userID, :postID, CURRENT_TIMESTAMP(3))";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->bindValue(':postID', $postID, PDO::PARAM_INT);

        $statement->execute();

        $this->_dbInstance->destruct();

        return true;
    }

    public function removeFromWatchlist($userID, $postID)
    {
        $sqlQuery = "DELETE FROM Watchlist
                     WHERE w_userID = :userID AND w_postID = :postID";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->bindValue(':postID', $postID, PDO::PARAM_INT);

        $statement->execute();

        $this->_dbInstance->destruct();

        return true;
    }

    public function isOnWatchlist($userID, $postID)
    {
        $sqlQuery = "SELECT * FROM Watchlist
                     WHERE w_userID = :userID AND w_postID = :postID";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->bindValue(':postID', $postID, PDO::PARAM_INT);

        $statement->execute();

        $this->_dbInstance->destruct();

        return $statement->rowCount() > 0;
    }
}