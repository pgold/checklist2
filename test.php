<html>
    <head></head>
    <body>
        <pre>
<?php
    require 'laHandler.php';

    $l = new LaHandler();
    print_r($l->getSolvedProblemsForUsers(array(9242, 20748)));
?>
        </pre>
    </body>
</html>
