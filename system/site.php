<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Access denied.');
}
if ($_SERVER['QUERY_STRING'] === '' || preg_match('/site-[0-9]*$/', $_SERVER['QUERY_STRING'])) {
    $category = 'site';
    include './system/posts.php';
    require_once './system/header.php';
    $count_segmentation = count($posts);
    if (preg_match('/site-[0-9]*$/', $_SERVER['QUERY_STRING'])) {
        //$current = explode('?',$_SERVER['REQUEST_URI']);
        $current = substr($_SERVER['QUERY_STRING'], 5);
    } else {
        $current = 1;
    }
    include './system/segmentation.php';
    if ($site_found) {
        for ($i = $count; $i >= $end; $i--) {
            if ($i <= 0) {
                break;
            }

            if (isset($posts[$i])) {
                $post        = $posts[$i];
                $post_number = array_search($post, $posts);
                include './system/content.php';
                include './system/preview.php';
            }
        }
        echo '<section class="sites">';
        if ($current != $previous) {
            echo '<a href="./?site-' . $previous . '" class="left">&#9664;</a>';
        }
        echo $current . ' / ' . $sites;
        if ($current != $next) {
            echo '<a href="./?site-' . $next . '" class="right">&#9654;</a>';
        }
        echo '</section>';
    }
    require_once './system/footer.php';
}
