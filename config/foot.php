<?php
echo <<<HEREDOC

    <footer class="footer">
      <a href="./?page-Impressum" >Impressum</a> |
      <a href="./?page-Disclaimer" >Disclaimer</a> |
      <a href="./?page-Datenschutz" >Datenschutz</a>
      <hr>
      &copy; by <a href="https://nouseforname.de" onClick="this.blur()" onFocus="if (this.blur) this.blur();">nouseforname.de</a> $year
    </footer>
    <script>
      hljs.initHighlighting();
    </script>
HEREDOC;
