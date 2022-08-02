<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/fontawesome/css/all.css" type="text/css">
  <link rel="stylesheet" href="../css/theme.css">
  <!-- libreria bootstrap,jquery y popper.js-->
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery-3.4.1.slim.js"></script>
  <script src="../js/popper.js"></script>

  <script src="../js/sweetalert.min.js"></script>
  <script src="../js/alertas.js"></script>
</head>

<body >

  <?php
  error_reporting(0);
   session_start();
   require_once("../modulos/navbar.php");
   include "../conexion.php" ;
   if(!isset($_SESSION['sesion'])){
     die("Acceso denegado");
   }else{
     // CONFIGURACION
     if($_SERVER['REQUEST_METHOD']== "POST"){
       if((isset($_GET['id']) && $_GET['id']!="") AND $_SESSION['sesion_administrador']){
         $id = $_GET['id'];
       }else{
         $id = $_SESSION['id'];
       }

         if(isset($_POST['action'])){
           if($_POST['action']=="update"){



             if(isset($_POST['name'],$_POST['lastname1'],$_POST['lastname2'],$_POST['correo'],$_POST['nac'],$_POST['acerca'])){
               if($actualizar_datos_usuario = $conn->prepare('UPDATE USUARIOS SET nombre=?,apellido_p=?,apellido_m=?,correo=?,nac=?,acerca_de=? WHERE idUsuario=?')){
                 $actualizar_datos_usuario->bind_param('ssssssi',$_POST['name'],$_POST['lastname1'],$_POST['lastname2'],$_POST['correo'],$_POST['nac'],$_POST['acerca'],$id);
                 $actualizar_datos_usuario->execute();
                 echo '<script> document.addEventListener("DOMContentLoaded",function(event){ registro_correcto(); });</script>';


               }
               $actualizar_datos_usuario->close();

             }

           }elseif ($_POST['action']=="updatepassword") {
             if(isset($_POST['passworda'],$_POST['passwordn'],$_POST['passwordnc'])){
              if($_POST['passwordn']==$_POST['passwordnc']){

                if($comprobar_password = $conn->prepare('SELECT clave FROM USUARIOS WHERE idUsuario=?')){
                  $comprobar_password->bind_param('i',$id);
                  $comprobar_password->execute();
                  $comprobar_password->store_result();
                  if($comprobar_password->num_rows > 0){
                    $comprobar_password->bind_result($password);
                    $comprobar_password->fetch();
                    if(password_verify($_POST['passworda'],$password)){
                       if($cambiar_password = $conn->prepare('UPDATE USUARIOS SET clave=? where idUsuario=?')){
                         $nueva_password = password_hash($_POST['passwordn'],PASSWORD_BCRYPT);
                         $cambiar_password->bind_param('si',$nueva_password,$_SESSION['id']);
                         $cambiar_password->execute();


                       }
                       $cambiar_password->close();
                       echo '<script> document.addEventListener("DOMContentLoaded",function(event){ registro_correcto(); });</script>';

                    }else{
                      echo '<script> document.addEventListener("DOMContentLoaded",function(event){ hubo_fallo("Contraseña incorrecta"); });</script>';

                    }
                  }

                }
                $comprobar_password->free_result();
                $comprobar_password->close();

              }else{
                echo '<script> document.addEventListener("DOMContentLoaded",function(event){ hubo_fallo("El campo confirmar nueva contraseña es diferente a campo ingresar nueva contraseña"); });</script>';

              }
             }
             // code...
           }elseif ($_POST['action']=="updatephoto") {

             if($_FILES['photo']['size']!=0){

                if($_FILES['photo']['type']=="image/png" OR $_FILES['photo']['type']=="image/jpeg"){

                    $path = "imagenes/perfil/fotos/".$id.'.'.pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION);

                    if(move_uploaded_file($_FILES['photo']['tmp_name'],'../'.$path)){

                      if($actualizar_photo = $conn->prepare('UPDATE USUARIOS SET foto=? WHERE idUsuario=?')){
                        $actualizar_photo->bind_param('si',$path,$id);
                        $actualizar_photo->execute();
                        echo '<script> document.addEventListener("DOMContentLoaded",function(event){ registro_correcto(); });</script>';


                      }
                      $actualizar_photo->close();
                    }

                }

             }
           }
         }



     }

       }







   //$conn->close();
   ?>




  <div class="mx-5 mt-2" style="">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action text-center"> Opciones</a>
            <a href="#a" class="list-group-item list-group-item-action active" data-toggle="tab"><i class="fa fa-user fa-fw"></i>Configuracion general</a>
            <a href="#b" class="list-group-item list-group-item-action" data-toggle="tab"><i class="fa fa-user fa-fw"></i>Cambiar contraseña</a>
            <a href="#c" class="list-group-item list-group-item-action" data-toggle="tab"><i class="fa fa-user fa-fw"></i>Cambiar foto</a>
          </div>
        </div>
        <!-- MENU -->
        <div class="col-md-8 tab-content">
          <!-- CONFIGURACION GENERAL -->
          <div id="a" class="tab-pane active">
             <?php
             if((isset($_GET['id']) && $_GET['id']!="") AND $_SESSION['sesion_administrador']){
               $id = $_GET['id'];
             }else{
               $id = $_SESSION['id'];
             }
             if($obtener_datos = $conn->prepare('SELECT nombre,apellido_p,apellido_m,correo,acerca_de,genero,DATE_FORMAT(nac,"%Y-%m-%d") FROM USUARIOS WHERE idUsuario=?')){
               $obtener_datos->bind_param('s',$id);
               $obtener_datos->execute();
               $obtener_datos->store_result();
               if($obtener_datos->num_rows >0){
                 $obtener_datos->bind_result($nombre,$apellido1,$apellido2,$correo,$acerca_de_mi,$genero,$nac);
                 $obtener_datos->fetch();
                  $obtener_datos->free_result();
                  $obtener_datos->close();
               }


             }





              ?>

            <h4>Configuracion general</h4>
            <form method="post" class="text-left">
              <div class="form-group"> <label for="form16">Nombre</label> <input <?php echo 'value="'.$nombre.'"' ;?> type="text" class="form-control" id="name" name="name"> </div>
              <div class="form-row">
                <div class="form-group col-md-6"> <label for="form19">Apellido Paterno</label> <input <?php echo 'value="'.$apellido1.'"' ;?> input type="text" class="form-control" id="lastname1" name="lastname1"> </div>
                <div class="form-group col-md-6"> <label for="form20">Apellido Materno</label> <input <?php echo 'value="'.$apellido2.'"' ;?> input type="text" class="form-control" id="lastname2" style="" name="lastname2"> </div>
              </div>
              <div class="form-group"> <label for="form18">Correo</label> <input  <?php echo 'value="'.$correo.'"' ;?> type="email" class="form-control" id="correo" name="correo"> </div>
              <div class="form-group"> <label for="form18">Fecha de nacimiento</label> <input <?php echo 'value="'.$nac.'"' ;?> type="date" class="form-control" id="nac" name="nac"> </div>
              <div class="form-group"> <label for="form18">Acerca de mi</label>
                <textarea type="text" id="acerca" class="form-control form-control-sm" name="acerca"><?php echo $acerca_de_mi;?></textarea>
              </div>
              <button name="action" value="update" type="submit" class="btn btn-dark btn-block">Guardar</button>
            </form>
          </div>
          <!-- CAMBIAR CONTRASEÑA -->
          <div id="b" class="tab-pane">
            <h4>Cambiar contraseña</h4>
            <form method="post" class="text-left">
              <div class="form-group"> <label for="form16">Ingresar contraseña anterior</label> <input type="password" class="form-control" id="passworda" name="passworda"> </div>
              <div class="form-group"> <label for="form18">Ingresar nueva contraseña</label> <input type="password" class="form-control" id="passwordn" name="passwordn"> </div>
              <div class="form-group"> <label for="form18">Confirmar nueva contraseña</label> <input type="password" class="form-control" id="passwordnc" name="passwordnc"> </div>
              <button name="action" value="updatepassword" type="submit" class="btn btn-dark btn-block">Cambiar contraseña</button>
            </form>
          </div>
          <!-- Cambiar foto -->
          <div id="c" class="tab-pane">
            <h4>Configuracion general</h4>
            <form  enctype="multipart/form-data" method="post" class="text-left">
              <h4>Cambiar foto</h4>
              <div class="form-group"> <label for="form16">Foto de perfil</label> <input type="file" class="form-control-file" id="photo" name="photo"> </div>
              <button name="action" value="updatephoto" type="submit" class="btn btn-dark btn-block">Guardar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <p class="mb-0">© 2019 POLICURSOS. DERECHOS RESERVADOS</p>
        </div>
      </div>
    </div>
  </div>

  <!-- libreria bootstrap y jquery -->
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery-3.4.1.slim.js"></script>

</body>

</html>
