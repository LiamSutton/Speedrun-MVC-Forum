<?php


/**
 * Class Pagination
 */
class Pagination
{
    /**
     * Pagination constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $maxPage - the max page the results can go to
     * @param $currentPage - the page the user is currently on
     * @param $n - the number of items above and below to generate
     * @return array - an array of page numbers the pagination should render in the view
     */
    public static function generatePages($maxPage, $currentPage, $n)
    {
        $pages = array();

        for ($i = $currentPage -$n; $i <= $currentPage +$n && $i <= $maxPage; $i++)
        {
            if ($i > 0)
            {
                array_push($pages, $i);
            }
        }
        return $pages;
    }
}