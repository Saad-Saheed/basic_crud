<?php 
include('config.php');

class DataObject{

    
    public static function connect()
    {      
            $conn = new mysqli(SERVER, USERNAME, PASSWORD, DB_NAME);
            if(!$conn->connect_error){
                return $conn;
            }
            return $conn;
        
    }

    public static function disConnect($conn)
    {
       $conn = null;
    }


}