<?php

class DataBaseConfig
{
    public $servername;
    public $username;
    public $password;
    public $databasename;

    public function __construct()
    {

        $this->servername = ''; //fill in your servername or IP details
        $this->username = '';  //fill out with database's administrator username
        $this->password = '';  //fill out with database's administrator password
        $this->databasename = '';  //fill in with the name of your woocommerce (or other) native database... 
                                    //it should be something like **shortened domain name*_WPDVW

    }
}

?>
