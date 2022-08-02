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
//iniciar sesion
session_start();
error_reporting(0);
 include "modulos/navbar.php";
if(!isset($_SESSION['sesion'])){

  //conexion a la base de datos
  require_once("conexion.php");


  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $correo =  htmlentities($_POST['correo']);
    $password = $_POST['password'];
  //En caso que este vacio la peticion regresamos a login
  if(!isset($correo,$password)){
    header("Location:login.php");
  }
  //Busqueda de correo del usuario
  if($login = $conn->prepare('SELECT nombre,idUsuario, clave FROM USUARIOS WHERE correo=?')){;
    $login->bind_param('s',$correo);
    $login->execute();
    $login->store_result();
  }
    if($login->num_rows > 0){
       $login->bind_result($name,$id,$pass);
       $login->fetch();
       //comprobacion de contraseña si son iguales = iniciar sesion
       if(password_verify($password,$pass)){
          //sessiones;

          $_SESSION['sesion'] = TRUE;
          $_SESSION['id'] = $id;
          $_SESSION['nombre'] =$name;


          $login->free_result();
          $login->close();
          //Buscamos los tipos de usuarios que tienen el usuario
          $perfiles = $conn->prepare('SELECT idUsuario_ut,idTipo_ut FROM USUARIOS_TIPOS WHERE idUsuario_ut=?');
          $perfiles->bind_param('i',$id);
          $perfiles->execute();
          $perfiles->store_result();

          if($perfiles->num_rows>0){
            $perfiles->bind_result($id,$perfil);
            while($perfiles->fetch()){
              if($perfil==2){
                $_SESSION['sesion_instructor'] =TRUE;
              }elseif ($perfil==3) {
                $_SESSION['sesion_editor'] =TRUE;
              }elseif ($perfil==4) {
                $_SESSION['sesion_administrador'] =TRUE;
              }

            }
            $perfiles->free_result();
            $perfiles->close();
          }





          $conn->close();
          header("location: index.php");
       }else{
           echo '<script> document.addEventListener("DOMContentLoaded",function(event){ hubo_fallo("Contraseña incorrenta"); });</script>';
       }



  }else{
    //Cerrar base de datos
    $login->close();
    $conn->close();
    echo '<script> document.addEventListener("DOMContentLoaded",function(event){ hubo_fallo("Correo incorrecto"); });</script>';
  }

  }




}else{
header("location: index.php");
}


 ?>




<body>


  <div class="py-5 text-center" style="	background-image: url(imagenes/inicio/fondo_login.jpg);	background-size: cover;	background-position: top left;	background-repeat: repeat;">
    <div class="container">
      <div class="row">
        <div class="mx-auto col-md-6 col-10 bg-white p-5">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-3"><img class="logo" src="logo.png"></div>
                <div class="col-md-9 ">
                  <h1 class="mb-4 text-left">POLICURSOS</h1>
                </div>
              </div>
            </div>
          </div>
          <form method="post" action="login.php">
            <div class="form-group"> <input type="email" class="form-control" placeholder="Correo" id="correo" style="" required="required" name="correo"> </div>
            <div class="form-group mb-3"> <input type="password" class="form-control" placeholder="Contraseña" id="password" style="" required="required" name="password"> <small class="form-text text-muted text-right">
              <!--  <a href="#" class="text-dark"> Recuperar contraseña</a>-->
              </small> </div> <button type="submit" class="btn btn-dark text-light text-center btn-lg btn-block">Iniciar</button>
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
