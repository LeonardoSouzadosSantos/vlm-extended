<?php
class RC {
    public $con;

    public function connect() {
        $con = fsockopen("unix:///Users/tmwtelecom/Documents/vlc.sock", -1, $errno, $errstr, 30);
        if (!$con) {
            return "$errstr ($errno)<br />\r\n";
        } else {
            $pass = "123\r\n";
            $cmd1 = fputs($con, $pass);
            if($cmd1) {
                return $con;
            }
        }
    }

    public function sendCommand($command) {
        $con = $this->connect();
        $cmd = fputs($con, $command);
        return fgets($con);
    }
}