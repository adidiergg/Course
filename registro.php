




<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/fontawesome/css/all.css" type="text/css">
  <link rel="stylesheet" href="css/theme.css">
  <!-- libreria bootstrap,jquery y popper.js-->
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery-3.4.1.slim.js"></script>
  <script src="js/popper.js"></script>

  <script src="js/sweetalert.min.js"></script>
  <script src="js/alertas.js"></script>
</head>


<?php
error_reporting(0);
include "modulos/navbar.php";
require_once("conexion.php");






if($_SERVER['REQUEST_METHOD']== "POST"){

  $name = $_POST['name'];
  $lastname1 = $_POST['lastname1'];
  $lastname2 = $_POST['lastname2'];
  $correo = $_POST['correo'];
  $nac = $_POST['nacimiento'];
  $genero = $_POST['genero'];
  $password = password_hash($_POST['password'],PASSWORD_BCRYPT);

  if(isset($name,$lastname1,$lastname2,$correo,$nac,$genero,$password)){

    //En caso que no existe el correo = Nueva cuenta sino Mensaje de error

    if($buscar_correo= $conn->prepare('SELECT correo FROM USUARIOS where correo=?')){
       $buscar_correo->bind_param('s',$correo);
       $buscar_correo->execute();
       $buscar_correo->store_result();

       if($buscar_correo->num_rows==0){
         $buscar_correo->close();
         if($insertar_usuario = $conn->prepare('INSERT INTO USUARIOS (nombre,apellido_p,apellido_m,correo,genero,nac,clave) VALUES (?,?,?,?,?,?,?)')){
           $insertar_usuario->bind_param('sssssss',$name,$lastname1,$lastname2,$correo,$genero,$nac,$password);
           $insertar_usuario->execute();
           $insertar_usuario->close();
           if($tipo_usuario = $conn->prepare('SELECT idUsuario FROM USUARIOS where idUsuario=LAST_INSERT_ID()')){
              $tipo_usuario->execute();
              $tipo_usuario->store_result();
              if($tipo_usuario->num_rows >0){
                $tipo_usuario->bind_result($id_usuario);
                $tipo_usuario->fetch();
              }
            if($tipo_usuario->prepare('INSERT INTO USUARIOS_TIPOS(idTipo_ut,idUsuario_ut) values (1,?)')){
              $tipo_usuario->bind_param('s',$id_usuario);
              $tipo_usuario->execute();
            }
            $tipo_usuario->close();
            echo '<script> document.addEventListener("DOMContentLoaded",function(event){ registro_correcto(); });</script>';



           }

         }else{

           echo '<script> document.addEventListener("DOMContentLoaded",function(event){ hubo_fallo("No se puede crear el usuario"); });</script>';

         }


       }else{
         $buscar_correo->close();
         echo '<script> document.addEventListener("DOMContentLoaded",function(event){ hubo_fallo("Esta cuenta ya existe"); });</script>';
       }

    }





  }



}


$conn->close();
 ?>







<body>

  <div class="py-5 text-center" style="	background-image: url(imagenes/inicio/security-265130_1920.jpg);	background-position: top left;	background-size: cover;	background-repeat: no-repeat;">
    <div class="container">
      <div class="row" style="">
        <div class="mx-auto col-md-6 col-10 bg-white p-4">
          <h1>¡Regístrate gratis y empieza ya a aprender!</h1>
          <form action="registro.php" method="post" class="text-left">
            <div class="form-group"> <label for="form16">Nombre</label> <input type="text" class="form-control" id="name" name="name"required="required"> </div>
            <div class="form-row">
              <div class="form-group col-md-6"> <label for="form19">Apellido Paterno</label> <input type="text" class="form-control" id="lastname1" name="lastname1" required="required"> </div>
              <div class="form-group col-md-6"> <label for="form20">Apellido Materno</label> <input type="text" class="form-control" id="lastname2"  name="lastname2" required="required"> </div>
            </div>
            <div class="form-group"> <label for="form18">Correo</label> <input type="email" class="form-control" id="correo" name="correo" required="required"> </div>
            <div class="form-group"> <label for="form18">Genero</label> <select class="custom-select" id="genero" name="genero" required="required">
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
              </select> </div>
            <div class="form-group"> <label for="form18">Nacimiento</label> <input type="date" class="form-control" id="nacimiento" name="nacimiento" required="required"> </div>

              <div class="form-group"> <label for="form19">Contraseña</label> <input type="password" class="form-control" id="password" name="password" required="required"  onchange="form.passwordc = RegExp.escape(this.value);"> </div>


            <div class="form-group">
              <div class="form-check"> <input class="form-check-input" type="checkbox" id="acept"  value="on" required="required"> <label class="form-check-label" for="form21">He leído y acepto los Términos y condiciones de servicio y Política de privacidad</label> </div>
            </div>


            <button type="submit" class="btn btn-dark btn-block">Crear cuenta</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- libreria bootstrap y jquery -->
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery-3.4.1.slim.js"></script>

</body>

</html>
