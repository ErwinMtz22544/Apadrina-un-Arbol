<?php
include 'db.php';

$correo = $_POST['email'];
$dominio = substr(strrchr($correo, "@"), 1);
$query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$correo'");
if (!checkdnsrr($dominio, "MX")) {
    echo "inventado"; // El dominio no existe o no puede recibir correos
    exit();
}
if(mysqli_num_rows($query) > 0){
    echo "existe";
} else {
    echo "disponible";
}
?>