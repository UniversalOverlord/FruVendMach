<?php

    //checks the index of the last payment on the website 
    function lastwebsite(){
        require_once "DataBase.php";
        $db = new DataBase();
        if ($db->dbConnect()) {
            $result = $db->last1();
            return $result;
            
        } else echo "Error: database connection";
            
    }
    
    //checks if databases are both updated
    function uptodate(){
        require_once "Arduino.php";
        
        $lastwb = lastwebsite();
        $lastard = lastarduino();
        
        if ($lastwb == $lastard) return true;
        else updatemachine($lastard,$lastwb);
    
    }
    
    //updates the user_info and machine_info databases
    function updatemachine($lastard,$lastwb){

        $db = new DataBase();
        if ($db->dbConnect()) {
            $result = $db->sendrecent($lastard,$lastwb);
            return $result;
            
        } else echo "Error: database connection";
    }
    
    uptodate();
    
?>

