<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Access denied.');
}

$pages = array_slice(scandir('./pages/'), 2);
for ($i = 0; $i < count($pages); $i++) {
    $pages[$i] = str_replace($fileExt, '', $pages[$i]);
}

if (count($pages) > 0) {
    sort($pages);
    array_unshift($pages, '');
}
