<?php


class Pagination
{
    public function __construct()
    {
    }

    public static function generatePages($maxPage, $currentPage, $n)
    {
        echo "MAX_PAGE: $maxPage </br> CURRENT_PAGE: $currentPage </br> N: $n </br>";
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