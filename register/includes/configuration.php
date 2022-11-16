<?php
//ALL THE CODE WAS WRITTEN BY SWEYZ (You're allowed to delete this)

//DB CONNEXION
try
{
  $bdd = new PDO("sqlsrv:Server=DESKTOP-K87077A\NOVA;Database=GunzDB", "sa", "pandemic");
	$bdd->exec("SET CHARACTER SET utf8");
}
catch (Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}

//VAR
define("gunz_name", "MaximoGunz"); // Gunz Server Name
define("dl_1", "https://mega.nz/#!LNI0WRyS!kzl2WBl009nzTzEuIvkHwwvN7qIu_E3sZLL7QdAkHlA"); // Downloading Link 1
define("dl_2", "https://www.sendspace.com/file/yc2bn5"); // Downloading Link 2
define("dl_3", "https://www.sendspace.com/filegroup/eTmFqFx1XcBg2k7bujrSamr54ksTJaU9"); // Downloading Link 3
define("dl_4", "http://www.mediafire.com/file/6oxd4jgqcu3ou78/MAX_GUNZ_18_4_18.exe"); // Downloading Link 4
?>
