<?php 
// Ce script renvoie au format json la liste des tâches en cours sur une machine $host
header('Content-type: application/json; charset=utf-8');
include('lib.php');
$hote = $_GET['host'];
// Récupérer sur un POST, récupérer en https
// ajouter une clé partagée à tester
//$command = "tasklist /fo csv /nh /v /u IUT\Administrateur /p ".Decrypte($mdp, $cle)." /s ".$hote." | findstr IUT | sort";
$command = "tasklist /fo csv /nh /v /u IUT\Administrateur /p ".Decrypte($mdp, $cle)." /s ".$hote." | sort";
//$command = 'task.bat "'.Decrypte($mdp, $cle).'" '.$hote;
$res = array();
$tab_task = array();
$i = 0;
exec($command, $res);
foreach($res as $li) {
    $tab_task[$i] = utf8_encode($li);
    $i++;
}
$tasks = json_encode($tab_task);
echo $tasks;
?>