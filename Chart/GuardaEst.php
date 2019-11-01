<?php
include_once("../Utilerias/BaseDatos.php");
$post=$_POST;
$no = $post['Nocontrol'];
$nom = $post['Nombre'];
$edad = $post['Edad'];   
$est=guardaEstudiante($no,$nom,$edad);
$response = array();
if ($est) {
$response['status']=1;
}else{
    $response['status']=0;
}
echo json_encode($response)
?>

