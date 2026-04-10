<?php
session_start();

//Destroy Session
session_unset(); // removes all variables from current session
session_destroy(); //terminates the entire session and deletes all session data stored on the server

header("Location: ../public/login.php");

exit();
?>