<?php
include_once("../Utilerias/BaseDatos.php");
$res = consultaEstudiantes();
echo "<table class ='highlight bordered'>
<thead>
    <tr><th>No control</th><th>Nombre Estudiante</th><th>Edad</th><th>Selecciona</th></tr>
    </thead>
    <tbody>";
foreach ($res as $tupla) {
    $no = $tupla['nocontrol'];
    $nom = $tupla['nombre'];
    $edad = $tupla['edad'];
    echo "<tr><td>$no</td><td>$nom</td><td>$edad</td><td>
    <i class='material-icons edit' data-no = '$no' data-nom='$nom' data-edad = '$edad'> create </i>
    </td></tr>";
}
echo "</tbody>
</table>";
?>    