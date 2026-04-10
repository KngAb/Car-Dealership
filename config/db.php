<?php
$config = require __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function get_mysqli(){
    global $config;
      try{
        $mysqli = new mysqli(
            $config['db_host'],
            $config['db_user'],
            $config['db_pass'],
            $config['db_name'],
            $config['db_port'],
        );
        return $mysqli ;
      }catch(mysqli_sql_exception $e){
        error_log("Database Connection Error:". $e->getMessage());
        die("Database Connection Failed");
      }
}
?>