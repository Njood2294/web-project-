<?php
session_unset();
session_destroy();


header("Location: HomePage.php");
exit();
?>
