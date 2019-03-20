<?php
    class Telnet {
        public $con;

        public function connect() {
            $con = fsockopen("localhost", 4212, $errno, $errstr, 30);
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
            if (!stream_set_timeout($con, 1)) die("Could not set timeout");
            fseek($con, 69);
            fwrite($con, "\nC01 CAPABILITY\r\n");
            $output = '';
            while ($line = fgets($con)) {
                $output .= $line;
                $line = preg_split('/\s+/', $line, 0, PREG_SPLIT_NO_EMPTY);
                $code = $line[0];
                if (strtoupper($code) == 'C01') {
                    break;
                }
            }
            return $output;
        }
    }
?>