
<?php

session_start();
session_unset();
session_destroy();

header("Location: /UIT/index.php"); 
exit();

?>