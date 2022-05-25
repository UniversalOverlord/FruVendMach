<?php
require "ArduinoDataBaseConfig.php";

class ArduinoDataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $username;
    protected $password;
    protected $databasename;

    public function __construct()
    {
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
        $dbc = new ArduinoDataBaseConfig();
        $this->servername = $dbc->servername;
        $this->username = $dbc->username;
        $this->password = $dbc->password;
        $this->databasename = $dbc->databasename;
    }

    function dbConnect()
    {
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data)
    {
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function last2(){
        
        $this->sql = "select MAX(lastwcorder) as max from ethrfrui_userDatabase.machine_info";
    
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        
        if (mysqli_num_rows($result) != 0) {
            return $row['max'];
        }
        
    }
    
    function insert($userid, $value){
        $userid = $this->prepareData($userid);
        $value = $this->prepareData($value);
        
        
        $this->sql = "select balance from ethrfrui_userDatabase.user_info where id =" . $userid . ";";
        $result1 = mysqli_query($this->connect, $this->sql);
        $row1 = mysqli_fetch_assoc($result1);
        
        
        $value = $value/0.5;    
        $updatedbalance = $row1['balance'] + $value;
        
        
        $this->sql = "update ethrfrui_userDatabase.user_info set balance = " . $updatedbalance . " where id = " . $userid . ";";
        $result2 = mysqli_query($this->connect, $this->sql);
        
        
        return $result2;

    }
    
    function lastorder($wbsindex){
        $wbsindex = $this->prepareData($wbsindex);
        
        $this->sql = "insert into ethrfrui_userDatabase.machine_info (lastwcorder) values (" . $wbsindex . ");";
        $result = mysqli_query($this->connect, $this->sql);
        
        echo $result;
    }
    
    function remove($rfid){
        $userid = $this->prepareData($rfid);
        
        
        $this->sql = "select balance from ethrfrui_userDatabase.user_info where rfid =" . $userid . ";";
        $result1 = mysqli_query($this->connect, $this->sql);
        $row1 = mysqli_fetch_assoc($result1);
           
        $updatedbalance = $row1['balance'] - 1;
        
        
        $this->sql = "update ethrfrui_userDatabase.user_info set balance = " . $updatedbalance . " where rfid = " . $userid . ";";
        $result2 = mysqli_query($this->connect, $this->sql);
        
        
        return $result2;

    }

}

?>