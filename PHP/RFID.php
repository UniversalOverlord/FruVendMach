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
    
    $sql = "select balance from ethrfrui_userDatabase.user_info where rfid =" . $id . ";";
    
    $result1 = mysqli_query($conn, $sql);
    $row1 = mysqli_fetch_assoc($result1);
    
    echo $row1["balance"];
        
    if (mysqli_num_rows($result1) != 0) {
        $updatedbalance = $row1['balance'] - 1;
        
        $sql = "update ethrfrui_userDatabase.user_info set balance = $updatedbalance where rfid =" . $id .";";
        
        $result2 = mysqli_query($conn, $sql);
        $row2 = mysqli_fetch_assoc($result2);

    }
    
    $conn->close();  
   
} else {
   echo "id not set";
}
?>