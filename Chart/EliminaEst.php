<?php
include_once("../Utilerias/BaseDatos.php");
$post=$_POST;
$no = $post['Nocontrol'];
$est=eliminarEstudiante($no);
$response = array();
if ($est) {
$response['status']=1;
}else{
    $response['status']=0;
}
echo json_encode($response)
?>

