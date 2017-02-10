<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 12/6/14
 * Time: 1:20 PM
 */
class Pagination {

    public function sehifelenme($total, $page, $limit, $targetpage = '') {

        $adjacents = 1;
        $targetpage = $targetpage != '' ? $targetpage : URL;
        $total_pages = $total;

        $page = preg_replace('(\D)', '', $page);

        $first = 1;
        $second = 2;
        if ($page) {
            $start = ($page - 1) * $limit;
        } else {
            $start = 0;
        }

        if ($page == 0) $page = 1;

        $prev = $page - 1;
        $next = $page + 1;

        $lastpage = ceil($total_pages / $limit);
        $lpm1 = $lastpage - 1;
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<div class=\"pagination\">";
            if ($page > 1)
                $pagination .= "<a href=\"$targetpage$prev\">«</a>";
            else
                $pagination .= "<span class=\"disabled\">«</span>";

            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class=\"current\">$counter</span>";
                    else
                        $pagination .= "<a href=\"$targetpage$counter\">$counter</a>";
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class=\"current\">$counter</span>";
                        else
                            $pagination .= "<a href=\"$targetpage$counter\">$counter</a>";
                    }
                    $pagination .= "<span class='dots'>...</span>";
                    $pagination .= "<a href=\"$targetpage$lpm1\">$lpm1</a>";
                    $pagination .= "<a href=\"$targetpage$lastpage\">$lastpage</a>";
                } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<a href=\"$targetpage$first\">1</a>";
                    $pagination .= "<a href=\"$targetpage$second\">2</a>";
                    $pagination .= "<span class='dots'>...</span>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class=\"current\">$counter</span>";
                        else
                            $pagination .= "<a href=\"$targetpage$counter\">$counter</a>";
                    }
                    $pagination .= "<span class='dots'>...</span>";
                    $pagination .= "<a href=\"$targetpage$lpm1\">$lpm1</a>";
                    $pagination .= "<a href=\"$targetpage$lastpage\">$lastpage</a>";
                } else {
                    $pagination .= "<a href=\"$targetpage$first\">1</a>";
                    $pagination .= "<a href=\"$targetpage$second\">2</a>";
                    $pagination .= "<span class='dots'>...</span>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class=\"current\">$counter</span>";
                        else
                            $pagination .= "<a href=\"$targetpage$counter\">$counter</a>";
                    }
                }
            }

            if ($page < $counter - 1)
                $pagination .= "<a href=\"$targetpage$next\">»</a>";
            else
                $pagination .= "<span class=\"disabled\"> »</span>";
            $pagination .= "</div>\n";
        }

        return array(
            "start" => $start,
            "limit" => $limit,
            "pages" => $pagination,
            "last_page" => $lastpage,
            "next_page" => $next,
            "prev_page" => $prev
        );
    }
}