<?php

if(isset($_GET["id"])){
    $id = $_GET["id"]; 
    
    $servername = "e3fruits.space";
    $username = "ethrfrui_dev";
    $password = "new98765";
    $dbname = "ethrfrui_userDatabase";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "select balance from ethrfrui_userDatabase.user_info where rfid = $userid";

   if ($conn->query($sql)!=0) {
       $result1 = mysqli_query($this->connect, $this->sql);
       $row1 = mysqli_fetch_assoc($result1);
       $updatedbalance = $row1['balance'] - 1;
       
       $sql = "update ethrfrui_userDatabase.user_info set balance = $updatedbalance where rfid = $userid";
       
        if ($conn->query($sql) === TRUE) {
            echo "done";
        }
        else {
            echo "not done";
        }
       
   } else {
      echo "error: " . $sql . " => " . $conn->error;
   }

   $conn->close();  
   
} else {
   echo "id not set";
}
?>