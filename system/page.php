<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Access denied.');
}

if (substr($_SERVER['QUERY_STRING'], 0, 5) === 'page-') {
    $category = 'page';
    include './system/pages.php';
    $page = substr(urldecode($_SERVER['QUERY_STRING']), 5);
    if (!in_array($page, $pages)) {
        require_once './system/header.php';
        echo '<article><p>' . translation('Page doesn\'t exist') . '. <a href="./">' . translation('To the main page') . '</a></p></article>';
    } else {
        $post = $page;
        include './system/content.php';
        require_once './system/header.php';
        echo '<article>';
        echo markdown($content);
        echo '</article>';
    }
    require_once './system/footer.php';
}
