<?php
try{
        $Cn = new PDO('pgsql:host=localhost;port=5432;dbname=estudiantes;user=postgres;password=hola');
        //$Cn = new PDO('mysql:host=localhost; dbname=bdalumnos','root','');
        $Cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $Cn->exec("SET CLIENT_ENCODING TO 'UTF8';");
        //$Cn->exec("SET CHARACTER SET utf8");
}catch(Exception $e){
    die("Error: " . $e->GetMessage());
}

// Función para ejecutar consultas SELECT
function Consulta($query)
{
    global $Cn;
    try{    
        $result =$Cn->query($query);
        $resultado = $result->fetchAll(PDO::FETCH_ASSOC); 
        $result->closeCursor();
        return $resultado;
    }catch(Exception $e){
        die("Error en la LIN: " . $e->getLine() . ", MSG: " . $e->GetMessage());
    }
}

// Función que recibe un insert y regresa el consecutivo que le genero
function EjecutaConsecutivo($sentencia){
    global $Cn;
    try {
        $result = $Cn->query($sentencia);
        $resultado = $result->fetchAll(PDO::FETCH_ASSOC);
        $result->closeCursor();
        return $resultado[0]['idcurso'];
    } catch (Exception $e) {
        die("Error en la linea: " + $e->getLine() + 
        " MSG: " + $e->GetMessage());
        return 0;
    }
}

function Ejecuta ($sentencia){
    global $Cn;
    try {
        $result = $Cn->query($sentencia);
        $result->closeCursor();
        return 1; // Exito  
    } catch (Exception $e) {
        //die("Error en la linea: " + $e->getLine() + " MSG: " + $e->GetMessage());
        return 0; // Fallo
    }
}
//------------------------------------------------------------
function registraUsr($post,$ids)
{
    $correo = $post["corr"];
    $nom = $post["nom"];
    $pwd = $post["pwd"];
    $pwdEnc = password_hash($pwd, PASSWORD_DEFAULT);
    $sql = 'INSERT INTO "Escuela".usuario(correo,nomusuario,contra,tipousr,idsession)' . 
    "values('$correo','$nom','$pwdEnc',1,'$ids')";

    return Ejecuta($sql);
}

function validaUsr($post,$idSess)
{
    $correo = $post["correo"];
    $contra = $post["contra"];
    $sql = 'SELECT contra,tipousr FROM "Escuela".usuario  WHERE correo like ' . "'" . $correo . "'";
    $res = Consulta($sql);
    $pwdEnc = "";
    $tipo = 0;
    foreach ($res as $tupla )
    {
        $pwdEnc = $tupla['contra'];
        $tipo = $tupla['tipousr'];
    }
    if (password_verify($contra, $pwdEnc) )
    {   
        $sql = 'UPDATE "Escuela".usuario SET ' . "idsession='$idSess' WHERE correo like'$correo'";
        //die($sql);
        if (Ejecuta($sql))
            return $tipo;
        else
            return 0;
    }
    else{
        return 0;
    }
}

function validaSess(&$correo, &$tu, &$idsess){
    $correo = $correo;
    $sql = 'SELECT idsession,tipousr FROM "Escuela".usuario  WHERE correo like ' . "'" . $correo . "'";
    $res = Consulta($sql);
    $tipo = 0;
    foreach ($res as $tupla )
    {
        $idsess = $tupla['idsession'];
        $tu = $tupla['tipousr'];
    }   
    return 0;
}

//------------------------------------------------------------
function tipoCursos(){
    $query = 'SELECT idtipo, nomtipo FROM "Escuela"."tipocurso"
    ORDER BY nomtipo';
    return Consulta($query);
}


function cargaCursos(){
    $query = 'SELECT idcurso, titcurso, descripcurso, costo, 
    imagen, nomtipo, A.idtipo 
    FROM "Escuela"."curso" A Inner Join "Escuela"."tipocurso" B 
    On (A.idtipo = B.idTipo)
    ORDER BY titcurso';
    return Consulta($query);
}

function agregaCurso($post,$nomArch){
    $tit = $post['tit'];
    $des = $post['descrip'];
    $cos = $post['costo'];
    $idtipo= $post['idtip'];
    $sentencia = 'INSERT INTO "Escuela".curso(titcurso,descripcurso,costo,imagen,idtipo)' . 
                 "values('$tit','$des',$cos,'$nomArch',$idtipo) RETURNING idcurso";
    return EjecutaConsecutivo($sentencia);
}

function borraCurso($post){
    $id = $post["pk"];
    $sentencia = 'DELETE FROM "Escuela".curso WHERE idcurso=' . $id;
    return Ejecuta($sentencia);
}

function actualizaCurso($post){
    $id = $post["pk"];
    $tit = $post["tit"];
    $des = $post["descrip"];
    $cos = $post["costo"];
    $idtip = $post["idtip"];
    $sentencia = 'UPDATE "Escuela".curso SET ' . "titcurso='$tit', descripcurso='$des',
    costo=$cos, idtipo= $idtip WHERE idcurso=$id"; 
    //echo $sentencia;
    //die("hjgjgjg");
    return Ejecuta($sentencia);
}


function consultaAlumnos(){
    $query = 'SELECT nocontrol,carrera,nombre,telefono FROM "Escuela"."alumno" ORDER BY nombre';   
    return Consulta($query);
}

function consultaAlumno($nocon){
    $query = 'SELECT nocontrol,carrera,nombre,telefono FROM "Escuela"."alumno" WHERE ' . " nocontrol = '$nocon' ORDER BY nombre";
    return Consulta($query);
}

function insertaAlumno($post){
    $no = $post['nocontrol'];
    $carr = $post['carrera'];
    $nom = $post['nombre'];
    $tel= $post['telefono'];
    $sentencia = 'INSERT INTO "Escuela".alumno(nocontrol,carrera,
                  nombre,telefono)' . 
                 "values('$no','$carr','$nom','$tel')";
     
    return Ejecuta($sentencia);
}

function actualizarAlumno($post){
    $no = $post['nocontrol'];
    $carr = $post['carrera'];
    $nom = $post['nombre'];
    $tel= $post['telefono'];
    $sentencia = 'UPDATE"Escuela".alumno SET ' . 
                 "carrera='$carr',nombre='$nom',
                 telefono='$tel' WHERE nocontrol='$no'"; 
    
    return Ejecuta($sentencia);
}

function borrarAlumno($post){
    $no = $post['nocontrol'];

    $sentencia = 'DELETE FROM "Escuela".alumno ' . 
                 " WHERE nocontrol='$no'"; 
    
    return Ejecuta($sentencia);
}

function cargaEstudiante($no){
    $query = "SELECT nocontrol,nombre,edad FROM consejo.estudiantes WHERE nocontrol= '{$no}'";
    return Consulta($query);
}
function guardaEstudiante($no,$nom,$edad){
    $sentencia = "INSERT INTO consejo.estudiantes (nocontrol,nombre,edad) VALUES ('{$no}','{$nom}',{$edad})";
    if (Ejecuta($sentencia)) {
        return 1;
    } else{
        $sentencia = "UPDATE consejo.estudiantes SET nombre='{$nom}',edad={$edad} WHERE nocontrol='{$no}'";
       // echo $sentencia;
        //die('sosjosj');
        return Ejecuta($sentencia);

    }

}

function eliminarEstudiante($no){
$sentencia = "DELETE FROM consejo.estudiantes WHERE nocontrol = '{$no}'";
return Ejecuta($sentencia);
}

function consultaEstudiantes(){
$query = "SELECT nocontrol,nombre,edad FROM consejo.estudiantes ORDER BY nocontrol";
return Consulta($query);
}