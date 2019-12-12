<?php

require_once("Models/Database.php");
require_once("Models/Post.php");

// This Model is used to perform DataBase queries on data that can be structured into post objects

/**
 * Class PostDataset
 */
class PostDataset
{
    /**
     * @var PDO
     */
    /**
     * @var Database|PDO|null
     */
    protected $_dbHandle, $_dbInstance;

    // Get reference to the database connection and set PDO to display errors

    /**
     * PostDataset constructor.
     */
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getConnection();
        $this->_dbHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }


    /**
     * @param $categoryID - the category we want posts from
     * @param $limit - the upper limit of how many posts we want to return
     * @param $page - the current page the user is on
     * @param $dateOrder - how to order posts by their date created
     * @param $title - a string to get Posts with title similar to this
     * @param $commentOrder - how to order Posts by their number of comments
     * @return array - an array containing BasicPost Objects
     */
    public function getBasicPosts($categoryID, $limit, $page, $dateOrder, $title, $commentOrder)
    {


        // clamp limit value
        $limit = $limit > 25 ? 25 : $limit;


        // convert date order into SQL syntax
        switch ($dateOrder):
            case 1:
                $dateOrder = 'P.p_datecreated DESC';
                break;
            case 2:
                $dateOrder = "P.p_datecreated";
                break;
            default:
                $dateOrder = "p_replycount DESC";
        endswitch;

        // Convert commentOrder into SQL syntax
        switch ($commentOrder):
            case 1:
                $commentOrder = "p_replycount DESC";
                break;
            case 2:
                $commentOrder = "p_replycount";
                break;
            default:
                $commentOrder = "p_replycount DESC";
            endswitch;

        // the row to start gathering data from, we -1 the current page so that page 1 -> 1 -1 = 0 and 0 * anything will be 0 so it always starts from the first posts
        $offset = ($page - 1) * $limit;

        $sqlQuery = "SELECT 
                P.p_id,
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
                WHERE p_categoryID = :categoryID
                AND p_parentID IS NULL
                AND p_title LIKE CONCAT(:title, '%')
                ORDER BY $dateOrder, $commentOrder
                LIMIT :limit OFFSET :offset";

        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Bind the values used in our query to prevent SQL Injection
        // ORDER BY clause requires string literals that's why we dont bind it (its still safe as users cannot interfere with it as it is converted)
        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);

        $statement->execute();

        $data = array();

        // Iterate over the data and convert them into BasicPost Objects
        while ($dbRow = $statement->fetch())
        {
            array_push($data, POST::basicPost($dbRow));
        }

        // Release the current connection
        $this->_dbInstance->destruct();


        return $data;

    }

    /**
     * @param $p_id - the ID of the post we want to find
     * @return Post - The found post object
     */
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

        // Bind Values to prevent SQL INJECTION
        $statement->bindValue(':id', $p_id, PDO::PARAM_INT);

        $statement->execute();

        $post = $statement->fetch(PDO::FETCH_ASSOC);
        $this->_dbInstance->destruct();
        return Post::fullPost($post);
    }

    /**
     * @param $categoryID - the category we want information from
     * @param $limit - used to calculate how many pages are required
     * @param $title - a string to get Posts with title similar to this
     * @return float - the number of pages needed to generate pagination for
     */
    public function getPageCount($categoryID, $limit, $title)
    {
        $sqlQuery = "SELECT COUNT(*)
                     FROM Posts
                     WHERE p_categoryID = :categoryID 
                     and p_parentID IS NULL
                     and p_title LIKE concat(:title, '%')";

        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Bind values
        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);

        $statement->execute();

        $postCount = $statement->fetchColumn();

        // gives us the number of pages the results will span over
        $pageCount = ceil($postCount / $limit);

        $this->_dbInstance->destruct();

        return $pageCount;
    }

    /**
     * @param $u_id - the ID of a user
     * @return array - returns an array of BasicPost Objects posted by a given user
     */
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

        //Bind values
        $statement->bindValue(':id', $u_id, PDO::PARAM_INT);

        $statement->execute();

        $dataSet = array();
        while ($row = $statement->fetch()) {
            array_push($dataSet, Post::basicPost($row));
        }
        $this->_dbInstance->destruct();
        return $dataSet;
    }


    /**
     * @param $p_id - the ID of the current post
     * @return array - an array of Reply Objects that are children of the current post
     */
    public function getReplies($p_id)
    {
        $sqlQuery = "SELECT p_id, p_title, p_posterID, p_content, p_parentID, concat(u_firstname, ' ', u_lastname) as 'u_fullname', p_image, p_categoryID
                     FROM Posts
                     JOIN Users ON p_posterID = u_id
                     WHERE p_parentID = :id
                     ORDER BY p_datecreated DESC";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Bind Values
        $statement->bindValue(':id', $p_id, PDO::PARAM_INT);

        $statement->execute();

        $dataSet = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($dataSet, POST::reply($row));
        }
        $this->_dbInstance->destruct();
        return $dataSet;
    }


    /**
     * @param $posterID - the ID of the posting user
     * @param $title - the title of the post
     * @param $content - the content for the post
     * @param $image - an optional image supplied
     * @param $categoryID - the category the post fits into
     * @return bool - returns whether creating the post was a success
     */
    public function createPost($posterID, $title, $content, $image, $categoryID)
    {
        $sqlQuery = "INSERT INTO Posts
                     (p_posterID, p_title, p_content, p_parentID, p_datecreated, p_image, p_categoryID) 
                     VALUES (:id, :title, :content, NULL, current_timestamp(3), :image, :categoryID)";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Bind Values against SQL Injection
        $statement->bindValue(':id', $posterID, PDO::PARAM_INT);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        $statement->bindValue(':image', $image, PDO::PARAM_STR);
        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);

        try
        {
            $statement->execute();
            $this->_dbInstance->destruct();
            return true;
        }
        catch(PDOException $ex)
        {
            error_log($ex);
            $this->_dbInstance->destruct();
            return false;
        }

    }


    /**
     * @param $posterID - the ID of the posting user
     * @param $title - the title of the post
     * @param $content - the content of the post
     * @param $parentID - the ID of the post being replied to
     * @param $categoryID - the category the parent post is in
     * @return bool - whether creating the reply was a success
     */
    // i know i could have made one function and changed things around :( (ran out of time)
    public function createReply($posterID, $title, $content, $parentID, $categoryID)
    {
        $sqlQuery = "INSERT INTO Posts
                     (p_posterID, p_title, p_content, p_parentID, p_datecreated, p_categoryID) 
                     VALUES (:id, :title, :content, :parentID, NOW(), :categoryID)";

        $statement = $this->_dbHandle->prepare($sqlQuery);

        //  Bind the values
        $statement->bindValue(':id', $posterID, PDO::PARAM_INT);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        $statement->bindValue(':parentID', $parentID, PDO::PARAM_INT);
        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);

        try
        {
            $statement->execute();
            $this->_dbInstance->destruct();
            return true;
        }
        catch(PDOException $ex)
        {
            error_log($ex);
            $this->_dbInstance->destruct();
            return false;
        }
    }


}