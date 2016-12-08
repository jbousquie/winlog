<?php
// Ce script lance un shutdown (ou restart) sur une machine
$host = $_POST['host'];
$action = $_POST['act'];

$command = 'shutdown /m \\\\' .$host.' /'.$action.' /f /t 0';
exec($command);
?>