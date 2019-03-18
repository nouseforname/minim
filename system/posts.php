<?php
function dateCmp($a, $b)
{
    if ($a[1] == $b[1]) {
        return 0;
    }
    return ($a[1] < $b[1]) ? -1 : 1;
}

function sortByDate(&$files)
{
    usort($files, 'dateCmp');
}

function loadFiles($dir)
{
    $files = array();
    foreach (array_slice(scandir($dir), 2) as $value) {
        $files[] = array($dir . $value, filemtime($dir . $value));
    }
    return $files;
}

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Access denied.');
}

$postTimes = loadFiles('./posts/');
sortByDate($postTimes);

foreach ($postTimes as $file) {
    $posts[] = str_replace($fileExt, '', basename($file[0]));
}

if (count($posts) > 0) {
    array_unshift($posts, '');
}
