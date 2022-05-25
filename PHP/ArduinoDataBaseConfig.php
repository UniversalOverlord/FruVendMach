<?php

class ArduinoDataBaseConfig
{
    // this file stores the information of your database so that you can easily change it if needed
    public $servername;
    public $username;
    public $password;
    public $databasename;

    public function __construct()
    {

        $this->servername = ''; //fill in your servername or IP details
        $this->username = '';  //fill out with database's administrator username
        $this->password = '';  //fill out with database's administrator password
        $this->databasename = '';  //fill in with the name of your newly created database 

    }
}

?>
