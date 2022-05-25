<?php
require "DataBaseConfig.php";

class DataBase
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
        $dbc = new DataBaseConfig();
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

    function last1(){
    
        $this->sql = "select MAX(order_item_id) as max from ethrfrui_WPDVW.9bY_wc_order_product_lookup";
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        
        if (mysqli_num_rows($result) != 0) {
            return $row['max'];
        }
        
    }
    
    function sendrecent($ardindex, $wbsindex){
        require_once "Arduino.php";
        
        $ardindex = $this->prepareData($ardindex);
            
        $this->sql = "select 9bY_wc_order_product_lookup.order_item_id as orderid, 9bY_wc_customer_lookup.user_id as userid, 9bY_wc_order_product_lookup.product_net_revenue as value from ethrfrui_WPDVW.9bY_wc_order_product_lookup inner join ethrfrui_WPDVW.9bY_wc_customer_lookup on 9bY_wc_order_product_lookup.customer_id=9bY_wc_customer_lookup.customer_id where order_item_id >" . $ardindex . ";";
            
        $result = mysqli_query($this->connect, $this->sql);
        
        while($row = mysqli_fetch_array($result)){
                insertarduino($row['userid'], $row['value']);
                
                if ($row['orderid']==$wbsindex){
                    savelastorder($wbsindex);
                }
        }
            
    }

}

?>


