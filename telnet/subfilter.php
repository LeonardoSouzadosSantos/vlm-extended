<?php
    include "telnet.php";
    $con = new Telnet();
    $time = $_GET['timefrom'];
    $time = date('Y/m/d-H:i:s', strtotime($time));
    $min = date('i', strtotime($time));
    $hour = date('H', strtotime($time));
    $day = date('d', strtotime($time));
    $month = date('m', strtotime($time));
    $year = date('Y', strtotime($time));
    $week = date('N', strtotime($time));
    if($_GET['acao'] == 'Logo') {
        $timeto = $_GET['timeto'];
        $timeto = date('Y/m/d-H:i:s', strtotime($timeto));
        $minto = date('i', strtotime($timeto));
        $hourto = date('H', strtotime($timeto));
        $dayto = date('d', strtotime($timeto));
        $monthto = date('m', strtotime($timeto));
        $yearto = date('Y', strtotime($timeto));
        $weekto = date('N', strtotime($timeto));
    }
    $nome = explode('.', end(explode('/', $check)))[0];
    $filename = md5(uniqid(rand(), true)).'.php';
    $position = $_GET['position'];
    if($_GET['acao'] == 'Logo') {
        $end = $_GET['address'];
        file_put_contents('./cron/'.$filename, '<?php include "telnet.php";'.PHP_EOL.'$con = new Telnet();'.PHP_EOL.'$con->sendCommand("@logo logo-file '.$end.'\r\n");'.PHP_EOL.'$con->sendCommand("@logo logo-position '.$position.'"); ?>');
        exec('echo "$min $hour $day $month $week /usr/bin/php /var/www/html/vlm-extended-master/cron/'.$filename.'\n" >> /etc/cron.d/root');
        exec('echo "$minto $hourto $dayto $monthto $weekto /usr/bin/php /var/www/html/vlm-extended-master/cron/'.$filename.'\n" >> /etc/cron.d/root');
    } else if($_GET['acao'] == 'Texto') {
        $texto = $_GET['text'];
        $timeout = 1000*$_GET['timeout'];
        file_put_contents('./cron/'.$filename, '<?php include "telnet.php";'.PHP_EOL.'$con = new Telnet();'.PHP_EOL.'$con->sendCommand("@marq marq-marquee '.$texto.'\r\n");'.PHP_EOL.'$con->sendCommand("@marq marq-position '.$position.'");'.PHP_EOL.'$con->sendCommand("@marq marq-timeout '.$timeout.'"); ?>');
        exec('echo "$min $hour $day $month $week /usr/bin/php /var/www/html/vlm-extended-master/cron/'.$filename.'\n" >> /etc/cron.d/root');
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>