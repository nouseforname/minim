<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Access denied.');
}

if (substr($_SERVER['QUERY_STRING'], 0, 5) === 'post-') {
    $category = 'post';
    include './system/posts.php';
    $post = substr(urldecode($_SERVER['QUERY_STRING']), 5);
    if (!in_array($post, $posts)) {
        require_once './system/header.php';
        echo '<article><p>' . translation('Post doesn\'t exist') . '. <a href="./">' . translation('To the main page') . '</a></p></article>';
    } else {
        include './system/content.php';
        require_once './system/header.php';
        echo '<article>';
        echo markdown($content);
        $back        = ceil((count($posts) - (array_search($post, $posts))) / $segmentation);
        $post_number = array_search($post, $posts);
        echo '<div class="information">' . $post_tags . '<time>' . $post_date . '</time> |
    <a href="?post-' . $post . '">#' . $post_number . '</a>
    </div></article>
    <section class="sites"><a href="./?site-' . $back . '">' . translation('Back') . '</a></section>';
    }
    require_once './system/footer.php';
}
