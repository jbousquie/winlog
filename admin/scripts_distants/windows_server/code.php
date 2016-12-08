<?php
// ce script sert à chiffrer le mot de passe administrateur du domaine
include('lib.php');
$Cle = "maSuperCle";
$MonTexte = "mot de passe admin";
$TexteCrypte = Crypte($MonTexte,$Cle);
$TexteClair = Decrypte($TexteCrypte,$Cle);
echo "Texte original : $MonTexte <Br>";
echo "Texte crypté : $TexteCrypte <Br>";
echo "Texte décrypté : $TexteClair <Br>";
?>