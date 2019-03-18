<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Access denied.');
}
if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) == 'rss') {

    header('content-type:application/rss+xml;charset=' . $encoding);

    $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);

    $output = '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n";
    $output .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
    $output .= '<channel>' . "\n";
    $output .= '<title>' . $title . '</title>' . "\n";
    $output .= '<link>' . $url . '/</link>' . "\n";
    $output .= '<description>' . $description . '</description>' . "\n";
    $output .= '<language>' . $language . '</language>' . "\n";
    $output .= '<atom:link href="' . $url . '?rss" rel="self" type="application/rss+xml"/>' . "\n";
    include './system/posts.php';
    if (count($posts) > 0) {
        require_once './system/markdown.php';
        if ($rss == 0 || count($posts) - 1 - $rss < 0) {
            $limit = 0;
        } else {
            $limit = count($posts) - 1 - $rss;
        }
        for ($i = count($posts) - 1; $i > $limit; $i--) {
            $category = 'rss';
            $post     = $posts[$i];
            include './system/content.php';
            $output .= '<item>' . "\n";
            $output .= '<title>' . $title . ' - Post #' . $i . '</title>' . "\n";
            $output .= '<link>' . $url . '/?post-' . $post . '</link>' . "\n";
            $output .= '<description>' . str_replace(array('<', '>', './uploads/'), array('&lt;', '&gt;', $url . '/uploads/'), markdown($content)) . '</description>' . "\n";
            $output .= '<pubDate>' . date('r', strtotime($post_date)) . '</pubDate>' . "\n";
            $output .= '<guid>' . $url . '/?post-' . $post . '</guid>' . "\n";
            $output .= '</item>' . "\n";
        }
    }
    $output .= '</channel>' . "\n";
    $output .= '</rss>';

    echo $output;
}
