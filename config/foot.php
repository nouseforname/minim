<?php
echo <<<HEREDOC

    <footer class="footer">
      <a href="./?page-Impressum.md" >Impressum</a> | 
      <a href="./?page-Disclaimer.md" >Disclaimer</a> |
      <a href="./?page-Datenschutz.md" >Datenschutz</a>
      <hr>
      &copy; by <a href="http://nouseforname.de" onClick="this.blur()" onFocus="if (this.blur) this.blur();">nouseforname.de</a> $year
    </footer>
    <script>
      hljs.initHighlighting();
    </script>
HEREDOC;
?>