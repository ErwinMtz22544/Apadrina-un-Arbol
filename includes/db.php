
<?php
/*
NO BORRAR ES PARA PODER SUBIR A LA WEB
$host = "sql101.infinityfree.com"; 
$user = "if0_41463102";  
$pass = "41t2FsNK5dOJT"; 
$db   = "if0_41463102_arboldb"; 
*/
//local
$host = "localhost";      
$user = "root";         
$pass = "";               
$db   = "arbolbd";         
$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Para que las eñes y acentos se vean bien
mysqli_set_charset($conexion, "utf8");
?>
