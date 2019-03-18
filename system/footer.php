<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Access denied.');
}

echo <<<HEREDOC

    </main>
HEREDOC;

$footerContent = markdown(file_get_contents('./config/footer.md'));
$footer        = <<<HEREDOC

    <footer>
      $footerContent
    </footer>
HEREDOC;

if ($footerContent !== '') {
    echo $footer;
}

if (is_file('./config/foot.php')) {
    include './config/foot.php';
}

echo <<<HEREDOC

  </body>
<html>
HEREDOC;
