<?php
shell_exec("echo '@logo logo-file ".$_GET['logo']."' | nc -U /Users/tmwtelecom/Documents/vlc.sock");
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>