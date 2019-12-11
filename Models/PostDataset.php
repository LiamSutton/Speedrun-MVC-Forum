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
        $this->_dbHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    public function getAllPosts()
    {

    }

    public function getBasicPosts($categoryID, $limit, $page, $sortBy, $title)
    {
        // TODO: note: placeholders (ie: :sort) cannot be used in an ORDER BY clause therefore variable must be passed in as it cannot be bound :( luckily the variable im using cannot be injected as a conversion happens before hand

        // clamp limit value
        $limit = $limit > 25 ? 25 : $limit;

        // increment limit as LIMIT is exclusive not inclusive
        $limit++;

        switch ($sortBy):
            case 1:
                $sortBy = 'P.p_datecreated DESC';
                break;
            case 2:
                $sortBy = "P.p_datecreated";
                break;
            case 3:
                $sortBy = "p_replycount DESC";
                break;
            case 4:
                $sortBy = "p_replycount";
                break;
            default:
                $sort = "p_replycount DESC";
        endswitch;

        $offset = ($page - 1) * $limit;

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
                    WHERE P.p_parentID IS NULL AND P.p_categoryID = :categoryID AND  P.p_title LIKE concat(:title, '%')
                    ORDER BY $sortBy
                    LIMIT :limit OFFSET :offset";

        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);


            $statement->execute();



        $result = $statement->fetch();
        $dataSet = array();
        if ($statement->rowCount() != 0)
        {
            while ($dbRow = $statement->fetch(PDO::FETCH_ASSOC)) {
                array_push($dataSet, Post::basicPost($dbRow));
            }

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
                    WHERE P.p_id = :id
                    ORDER BY P.p_datecreated DESC";

        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $p_id, PDO::PARAM_INT);
        $statement->execute();

        $post = $statement->fetch(PDO::FETCH_ASSOC);
        $this->_dbInstance->destruct();
        return Post::fullPost($post);
    }
    public function getPageCount($categoryID, $limit, $title)
    {
        $sqlQuery = "SELECT COUNT(*)
                     FROM Posts
                     WHERE p_categoryID = :categoryID 
                     and p_parentID IS NULL
                     and p_title LIKE concat(:title, '%')";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);

        $statement->execute();

        $postCount = $statement->fetchColumn();
        $pageCount = ceil($postCount / $limit);

        return $pageCount;
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
                    WHERE p_posterID = :id
                    AND p_parentID IS NULL";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(':id', $u_id, PDO::PARAM_INT);

        $statement->execute();

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
                     WHERE p_parentID = :id
                     ORDER BY p_datecreated";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $p_id, PDO::PARAM_INT);

        $statement->execute();

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
                     VALUES (:id, :title, :content, NULL, current_timestamp(3), :image, :categoryID)";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $posterID, PDO::PARAM_INT);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        $statement->bindValue(':image', $image, PDO::PARAM_STR);
        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);

        $statement->execute();
        $this->_dbInstance->destruct();
    }

    // TODO: Maybe should return true if succeeds?
    public function createReply($posterID, $title, $content, $parentID, $categoryID)
    {
        $sqlQuery = "INSERT INTO Posts
                     (p_posterID, p_title, p_content, p_parentID, p_datecreated, p_categoryID) 
                     VALUES (:id, :title, :content, :parentID, NOW(), :categoryID)";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $posterID, PDO::PARAM_INT);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        $statement->bindValue(':parentID', $parentID, PDO::PARAM_INT);
        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);

        $statement->execute();
        $this->_dbInstance->destruct();
    }







}