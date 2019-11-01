<?php
include_once("../Utilerias/BaseDatos.php");
$post=$_POST;
$no = $post['Nocontrol'];
$est=cargaEstudiante($no);
$response = array();
if ($est) {
$response['status']=1;
foreach ($est as $tupla) {
    $response['Nocontrol']=$tupla['nocontrol'];
    $response['Nombre']=$tupla['nombre'];
    $response['Edad']=$tupla['edad'];
}
}else{
    $response['status']=0;
    $response['Nocontrol']="";
    $response['Nombre']="";
    $response['Edad']="0";

}
echo json_encode($response)
?>

