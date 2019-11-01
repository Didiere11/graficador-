<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="../css/default.css" />
    <link rel="icon" type="image/x-icon" href="../fonts/favicon.ico" />

</head>

<body background="../img/e.png">
    <div class="row">
        <div class="col s12 m8 offset-m2">
            <div class="card">
            <div class="card-image">
          <img src="../img/d.jpg">
          <span class="card-title">Catalogo Estudiantes</span>
        </div>
                <div class="card-content">
                    <form action="actEst.php" name="frm1" id="frm1" method="GET">
                        <div class="row">
                            <div class="input-field col s10">
                                <input type="text" name="Nocontrol" id="Nocontrol">
                                <label for="Nocontrol">Numero de control:</label>
                            </div>
                            <div class="input-field col s2">
                                <button id="btnBuscar" name="btnBuscar" type="button" class="btn waves-effect waves-light purple" value="Buscar">
                                        <i class="material-icons right">search</i>
                                </button>
                            </div>
                            <div class="input-field col s12">
                                <input type="text" name="Nombre" id="Nombre">
                                <label for="Nombre">Nombre: </label>
                            </div>
                            <div class="input-field col s12">
                                <input type="number" name="Edad" id="Edad" value="0">
                                <label for="Edad">Edad: </label>
                            </div>
                            <div class="input-field col s3">
                                <button id="btnGuardar" name="btnGuardar" type="button" class="btn waves-effect waves-light purple" value="Guardar">
                                        <i class="material-icons right" >save</i>Guardar</button>
                            </div>
                            <div class="input-field col s3">
                                <button id="btnEliminar" name="btnEliminar" type="button" class="btn waves-effect waves-light purple" value="Eliminar">
                                        <i class="material-icons right">delete</i>Eliminar</button>
                            </div>
                            <div class="input-field col s3">

                                <button id="btnLimpiar" name="btnLimpiar" type="button" class="btn waves-effect waves-light purple" value="Limpiar">
                                        <i class="material-icons right">clear_all</i>Limpiar</button>
                            </div>
                            <div class="input-field col s3">

                                <button id="btnConsultar" name="btnConsultar" type="button" class="btn waves-effect waves-light purple" value="Cunsultar">
                                        <i class="material-icons right">cloud_done</i>Cunsultar</button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------------------- Ventana Modal---------------------------------------------------------------------------->
<div class="modal" id="TablaEstudiantes">
        <div class="modal-content">
            <h4 align="center">Consulta de Estudiantes</h4>
            <br>
            <div class="row" id="tabla">
               
            </div>
        </div>
        <div class="modal-foter">
            <a id="Cerrar" class="modal-action waves-effect waves-orange btn-flat">Cerrar</a>
        </div>
</div>
<!--------------------------------------------------------------------------------- FIN VENTANA MODAL---------------------------------------------------------------------->


    <script type="text/javascript" src="../js/jquery-3.0.0.min.js"></script>
    <script type="text/javascript" src="../js/materialize.min.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
    <script>
 //INICIALIZA LA VENTANA MODAL--------------------------------------------------------------------------------------------------------------------------------------------
            $("#TablaEstudiantes").modal();
            $("#tabla").load("TablaEstudiantes.php");
            $("#btnConsultar").click(function(){
                $("#tabla").load("TablaEstudiantes.php");
                $("#TablaEstudiantes").modal('open');
                
            });
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            $("#tabla").on("click",".edit",function(){

                var no = $(this).attr("data-no");
                var nom = $(this).attr("data-nom");
                var edad = $(this).attr("data-edad");
                $("#Nocontrol").val(no);
                $("#Nombre").val(nom);
                $("#Edad").val(edad);
                $("#TablaEstudiantes").modal('close');
                $("#no").focus();


            });

$("#Cerrar").click(function(){
    $("#TablaEstudiantes").modal('close');
    $("#no").focus();  
});
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $("#btnGuardar").click(function() {
            $.ajax({
                type: "post",
                url: "GuardaEst.php",
                dataType: 'json',
                data: $("#frm1").serialize(),
                success: function(response) {
                        if (response['status'] == 1) {
                            $("#Nombre").val(response['Nombre']);
                            $("#Edad").val(response['Edad']);
                            $("#Nocontrol").focus();
                            M.toast({
                                html: 'Estudiante Guardado',
                                classes: 'rounded',
                                displayLenght: 4000
                            });
                            $("#Nocontrol").val("");
                            $("#Nombre").val("");
                            $("#Edad").val("0");
                            $("#Nocontrol").focus();
                        } else {
                            M.toast({
                                html: 'Estudiante no Guardado',
                                classes: 'rounded',
                                displayLenght: 4000
                            });
                        } //fin del else
                    } //din success
            }); //fin del ajax
        });

        $("#btnEliminar").click(function() {

            $.ajax({
                type: "post",
                url: "EliminaEst.php",
                dataType: 'json',
                data: $("#frm1").serialize(),
                success: function(response) {
                        if (response['status'] == 1) {
                            $("#Nocontrol").val("");
                            $("#Nombre").val("");
                            $("#Edad").val("0");
                            $("#Nocontrol").focus();
                            M.toast({
                                html: 'Estudiante Eliminado',
                                classes: 'rounded',
                                displayLenght: 4000
                            });
                        } else {
                            M.toast({
                                html: 'Estudiante no Eliminado',
                                classes: 'rounded',
                                displayLenght: 4000
                            });
                        } //fin del else
                    } //din success
            }); //fin del ajax
            /* M.toast({
                 html: 'Diste CLick en Eliminar',
                 classes: 'rounded',
                 displayLenght: 4000
             });*/
        });
        $("#btnLimpiar").click(function() {
            $("#Nocontrol").val("");
            $("#Nombre").val("");
            $("#Edad").val("0");
            $("#Nocontrol").focus();
            M.toast({
                html: 'CAJAS LIMPIAS',
                classes: 'rounded',
                displayLenght: 4000
            });
        });
        $("#btnBuscar").click(function() {
            $.ajax({
                type: "post",
                url: "BuscaEst.php",
                dataType: 'json',
                data: $("#frm1").serialize(),
                success: function(response) {
                        if (response['status'] == 1) {
                            $("#Nombre").val(response['Nombre']);
                            $("#Edad").val(response['Edad']);
                            $("#Nocontrol").focus();
                            M.toast({
                                html: 'Estudiante encontrado',
                                classes: 'rounded',
                                displayLenght: 4000
                            });
                        } else {
                            M.toast({
                                html: 'Estudiante no encontrado',
                                classes: 'rounded',
                                displayLenght: 4000
                            });
                        } //fin del else
                    } //din success
            }); //fin del ajax
            /* M.toast({
                html: 'Diste CLick en Buscar',
                classes: 'rounded',
                displayLenght: 4000
            });*/
        }); //fin del click
    </script>
</body>

</html>