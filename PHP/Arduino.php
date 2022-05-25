<?php
    require "ArduinoDataBase.php";
    
    //checks the index of the last balance update on the user table 
    function lastarduino(){
        $dbard = new ArduinoDataBase();
        if ($dbard->dbConnect()) {
            $result = $dbard->last2();
            return $result;
            
        } else echo "Error: database connection";
        
    }
    
    //adds balance to users who recently used website
    function insertarduino($userid, $value){
        
        $dbard = new ArduinoDataBase();
        if ($dbard->dbConnect()) {
            $dbard->insert($userid, $value);
        } else echo "Error: database connection";

    }
    
    //updates the machine_info table
    function savelastorder($wbsindex){
        $dbard = new ArduinoDataBase();
        if ($dbard->dbConnect()) {
            $dbard->lastorder($wbsindex);
        } else echo "Error: database connection";

    }
    
    //decreases balance by 1 unit
    function removearduino($rfid){
        $dbard = new ArduinoDataBase();
        if ($dbard->dbConnect()) {
            $dbard->remove($rfid);
        } else echo "Error: database connection";

    }
?>