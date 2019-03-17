<?php
$year = date('Y');

echo <<<HEREDOC
  
      <title>$title</title>

      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.6/styles/default.min.css">
      <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.6/highlight.min.js"></script>
      
      <meta charset="$encoding">
      <meta name="robots" content="$robots">
      <meta name="viewport" content="width=device-width,initial-scale=1.0">
      <meta name="description" content="$description">

      <link rel="stylesheet" type="text/css" href="./themes/$theme/style.css">
      <link rel="icon" type="image/ico" href="./themes/$theme/favicon.ico">
      <link rel="alternate" type="application/rss+xml" title="$title" href="./?rss">
HEREDOC;
?>
