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

<body>
   <?php
    session_start();
    error_reporting(0);
    require_once("../modulos/navbar.php");
    include "../conexion.php" ;
    if(!isset($_SESSION['sesion'],$_SESSION['sesion_administrador'])){
      die("Acceso denegado");
    }else{
      // PANEL INSTRUCTOR
      if($_SERVER['REQUEST_METHOD']== "POST"){
        $accion = $_POST['action'];
        if ($accion=="add") {
            $usuario = $_POST['usuario'];
            $tipo = $_POST['tipo'];
            if(!isset($usuario,$tipo)){


            }else{
              if($buscar_id = $conn->prepare('SELECT idUsuario FROM USUARIOS WHERE idUsuario=? OR correo=?')){
                $buscar_id->bind_param('is',$usuario,$usuario);
                $buscar_id->execute();
                $buscar_id->store_result();
                if($buscar_id->num_rows > 0){
                  $buscar_id->bind_result($id);
                  $buscar_id->fetch();
                  if($nuevo_privilegio = $conn->prepare('INSERT INTO USUARIOS_TIPOS(idUsuario_ut,idTipo_ut) VALUES(?,?)')){
                    $nuevo_privilegio->bind_param('ii',$id,$tipo);
                    $nuevo_privilegio->execute();

                    echo '<script> document.addEventListener("DOMContentLoaded",function(event){ registro_correcto(); });</script>';

                  }
                  $nuevo_privilegio->close();


                }else{
                  echo '<script> document.addEventListener("DOMContentLoaded",function(event){ hubo_fallo("No existe el usuario"); });</script>';

                }
                $buscar_id->free_result();
                $buscar_id->close();

              }


            }
        }


      }elseif($_SERVER['REQUEST_METHOD'] == "GET"){
         if(isset($_GET['action'])){
           if($_GET['action']=="delete"){
             if(isset($_GET['usuario'],$_GET['tipo'])){
                 echo $_GET['usuario'];
                 echo $_GET['tipo'];
                 if($eliminar_privilegio = $conn->prepare('DELETE FROM USUARIOS_TIPOS WHERE idUsuario_ut=? AND idTipo_ut=?')){
                   $eliminar_privilegio->bind_param('ii',$_GET['usuario'],$_GET['tipo']);
                   $eliminar_privilegio->execute();


                 }
                 $eliminar_privilegio->close();
                 header("location: index.php");
               }


           }else if($_GET['action']=="removeusuario"){
             if(isset($_GET['usuario'])){
               if($eliminar_privilegio = $conn->prepare('DELETE FROM USUARIOS_TIPOS WHERE idUsuario_ut=?')){
                 $eliminar_privilegio->bind_param('i',$_GET['usuario']);
                 $eliminar_privilegio->execute();


               }
               $eliminar_privilegio->close();

               if($eliminar_privilegio = $conn->prepare('DELETE FROM INSCRIPCIONES WHERE idUsuario_i=?')){
                 $eliminar_privilegio->bind_param('i',$_GET['usuario']);
                 $eliminar_privilegio->execute();


               }
               $eliminar_privilegio->close();

               if($eliminar_privilegio = $conn->prepare('DELETE FROM USUARIOS WHERE idUsuario=?')){
                 $eliminar_privilegio->bind_param('i',$_GET['usuario']);
                 $eliminar_privilegio->execute();


               }
               $eliminar_privilegio->close();
                 header('Refresh:0; url=index.php');;





             }
           }

         }

        }






    }
    //$conn->close();
    ?>


    <div class="mt-2 mx-2" style="">
    <div class="container-fluid">
      <div class="row">
<div class="col-md-12">
<h1 class="text-center"><i class="fas fa-book"></i> Administrador</h1>
</div>
</div>


      <div class="row">
        <div class="col-md-2">
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action text-center  disabled"> Opciones</a>
            <a href="#c"  <?php echo (!isset($_GET['action'])?'class="list-group-item list-group-item-action  active"':'class="list-group-item list-group-item-action"') ?> data-toggle="tab"><i class="fas fa-arrow-up"></i> Lista de usuarios</a>

            <a href="#a" class="list-group-item list-group-item-action" data-toggle="tab">  <i class="fa fa-user fa-fw"></i>Privilegios</a>
            <a href="#b"  class="list-group-item list-group-item-action" data-toggle="tab"><i class="fas fa-arrow-up"></i> Elevar privilegio</a>

          </div>
        </div>
        <!-- MENU -->
        <div class="col-md-10 tab-content">




          <!-- Lista de privilegios -->
          <div id="a" class="tab-pane" >



            <ul class="nav nav-pills">
              <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabone">Instructores</a> </li>
              <li class="nav-item"> <a class="nav-link" href="" data-toggle="pill" data-target="#tabtwo"> Editores </a> </li>
              <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabthree">Administradores </a> </li>
            </ul>
            <form class="text-left">
              <div class="tab-content mt-2">
                <div class="tab-pane fade show active" id="tabone" role="tabpanel" style="">
                  <div class="row">
                    <table class="table">
                    <thead class="thead-light">
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Usuario</th>

                    <th scope="col">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                      <?php
                       if($instructores= $conn->prepare('SELECT CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m),u.foto,u.idUsuario FROM USUARIOS_TIPOS ut INNER JOIN USUARIOS u on ut.idUsuario_ut=u.idUsuario WHERE ut.idTipo_ut=2')){
                          $instructores->execute();
                          $instructores->store_result();
                          if($instructores->num_rows > 0){
                            $instructores->bind_result($usuario,$foto,$idusuario);
                            while($instructores->fetch()){
                                echo ' <tr>
                                  <th scope="row">'.$idusuario.'</th>
                                  <td> <img class="rounded-circle" src="../'.$foto.'" height="50" width="50" > </td>

                                    <td><a href="../perfil/?id='.$idusuario.'">'.$usuario.'</a></td>
                                  <td><div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="">
                                      <a class="dropdown-item" href="?action=delete&tipo=2&usuario='.$idusuario.'">Eliminar</a>
                                    </div>
                                  </div></td>
                                </tr>';
                            }
                            $instructores->close();
                          }

                        }

                       ?>


                    </tbody>
                    </table>


                  </div>
                </div>
                <div class="tab-pane fade" id="tabtwo" role="tabpanel">
                  <div class="row">
                    <table class="table">
                    <thead class="thead-light">
                    <tr>
                    <th scope="col">ID</th>
                      <th scope="col">Foto</th>
                    <th scope="col">Usuario</th>

                    <th scope="col">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                      <?php
                       if($editores= $conn->prepare('SELECT CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m),u.foto,u.idUsuario FROM USUARIOS_TIPOS ut INNER JOIN USUARIOS u on ut.idUsuario_ut=u.idUsuario WHERE ut.idTipo_ut=3')){
                          $editores->execute();
                          $editores->store_result();
                          if($editores->num_rows > 0){
                            $editores->bind_result($usuario,$foto,$idusuario);
                            while($editores->fetch()){
                                echo ' <tr>
                                  <th scope="row">'.$idusuario.'</th>
                                  <td> <img class="rounded-circle" src="../'.$foto.'" height="50" width="50" > </td>

                                    <td><a href="../perfil/?id='.$idusuario.'">'.$usuario.'</a></td>
                                  <td><div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="">
                                      <a class="dropdown-item" href="?action=delete&tipo=3&usuario='.$idusuario.'">Eliminar</a>
                                    </div>
                                  </div></td>
                                </tr>';
                            }
                            $editores->close();
                          }

                        }

                       ?>


                    </tbody>
                    </table>


                  </div>

                </div>
                <div class="tab-pane fade" id="tabthree" role="tabpanel">
                  <div class="row">
                    <table class="table">
                    <thead class="thead-light">
                    <tr>
                    <th scope="col">ID</th>
                      <th scope="col">Foto</th>
                    <th scope="col">Usuario</th>

                    <th scope="col">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                      <?php
                       if($administradores= $conn->prepare('SELECT CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m),u.foto,u.idUsuario FROM USUARIOS_TIPOS ut INNER JOIN USUARIOS u on ut.idUsuario_ut=u.idUsuario WHERE ut.idTipo_ut=4')){
                          $administradores->execute();
                          $administradores->store_result();
                          if($administradores->num_rows > 0){
                            $administradores->bind_result($usuario,$foto,$idusuario);
                            while($administradores->fetch()){
                                echo ' <tr>
                                  <th scope="row">'.$idusuario.'</th>
                                  <td> <img class="rounded-circle" src="../'.$foto.'" height="50" width="50" > </td>

                                  <td><a href="../perfil/?id='.$idusuario.'">'.$usuario.'</a></td>
                                  <td><div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="">
                                      <a class="dropdown-item" href="?action=delete&tipo=4&usuario='.$idusuario.'">Eliminar</a>
                                    </div>
                                  </div></td>
                                </tr>';
                            }
                            $administradores->close();
                          }

                        }

                       ?>


                    </tbody>
                    </table>


                  </div>


                </div>
              </div>
            </form>
          </div>


          <div id="b" class="tab-pane">
            <h4 class="text-center">Elevar privilegio</h4>
            <form class="text-left" method="post">
              <div class="form-group form-row"> <label class="col-sm-2 col-form-label" for="form16">Usuario</label>
                <div class="col-sm-10"><input type="text" class="form-control" id="usuario" placeholder="Correo o ID" required="required" name="usuario"></div>
              </div>
              <div class="form-group form-row"> <label for="form16" class="col-sm-2 col-form-label">Tipo</label>
                <div class="col-sm-10">
                  <select class="custom-select" name="tipo" id="tipo" required="required">
                    <?php
                    if($buscar_tipo= $conn->prepare('SELECT * FROM TIPOS WHERE idTipo!=1')){
                      $buscar_tipo->execute();
                      $buscar_tipo->store_result();
                      if($buscar_tipo->num_rows > 0){
                      $buscar_tipo->bind_result($idTipo,$tipo);
                        while($buscar_tipo->fetch()){
                          echo '<option value="'.$idTipo.'">'.$tipo.'</option>';
                        }
                        $buscar_tipo->free_result();

                      }

                    }
                    $buscar_tipo->close();
                    ?>


                  </select></div>
              </div>
              <button type="submit" value="add" name="action" class="btn btn-primary btn-block">Elevar</button>
            </form>


          </div>

            <div id="c" class="tab-pane active">

              <form method="get">
              <div class="form-group form-row">
                  <div class="col-sm-1"><input type="text" class="form-control" name="id" placeholder="ID"></div>
                  <div class="col-sm-2"><input type="text" class="form-control" name="correo" placeholder="CORREO"></div>
                  <div class="col-sm-2"><input type="text" class="form-control" name="nombre" placeholder="Nombre"></div>
                  <div class="col-sm-2"><input type="text" class="form-control" placeholder="Apellido Paterno" name="apellido1"></div>
                  <div class="col-sm-2"><input type="text" class="form-control" placeholder="Apellido Materno" name="apellido2"></div>
                  <div class="col-sm-2">
                    <select class="custom-select" name="genero">
                      <option value="">Seleccionar genero</option>';
                      <option value="Masculino">Masculino</option>';
                      <option value="Femenino">Femenino</option>';
                      <option value="Otro">Otro</option>';
                      </select>


                  </div>
                  <div class="col-sm-1"><button value="buscar" name="action" type="submit" class="btn btn-info btn-block" >Buscar</button></div>
                </div>

              </form>

                <form class="text-left">
                  <div class="tab-content mt-2">
                    <div class="tab-pane fade show active" id="tabone" role="tabpanel" style="">
                      <div class="row">
                        <table class="table">
                        <thead class="thead-light">
                        <tr>
                        <th scope="col">ID </th>
                        <th scope="col">Foto</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Fecha de nacimiento</th>
                        <th scope="col">Genero</th>
                        <th scope="col">Opciones</th>
                        </tr>
                        </thead>
                        <tbody>

                          <?php

                          if(!isset($_SESSION['sesion'],$_SESSION['sesion_instructor'])){
                            die("Acceso denegado");
                          }else{
                             if($_SERVER['REQUEST_METHOD'] == "GET"){
                               if((isset($_GET['id']) && $_GET['id']!="") OR (isset($_GET['nombre']) && $_GET['nombre']!="") OR (isset($_GET['apellido1']) && $_GET['apellido1']!="") OR (isset($_GET['apellido2']) && $_GET['apellido2']!="") OR (isset($_GET['genero']) && $_GET['genero']!="") OR (isset($_GET['correo']) && $_GET['correo']!="")){
                                 $sql = 'SELECT idUsuario,foto,CONCAT(nombre," ",apellido_p," ",apellido_m),correo,DATE_FORMAT(nac,"%Y-%m-%d"),genero FROM USUARIOS u WHERE';

                               }else{
                                 $sql = 'SELECT idUsuario,foto,CONCAT(nombre," ",apellido_p," ",apellido_m),correo,DATE_FORMAT(nac,"%Y-%m-%d"),genero FROM USUARIOS u';
                               }
                               $where= [];

                                 if(isset($_GET['id']) && $_GET['id']!=""){
                                    $where[] = " u.idUsuario=".$_GET['id'];
                                 }

                                 if(isset($_GET['correo']) && $_GET['correo']!=""){
                                    $where[] =' u.correo like "%'.$_GET['correo'].'%"';
                                 }

                                 if(isset($_GET['nombre']) && $_GET['nombre']!=""){
                                      $where[] = ' u.nombre like "%'.$_GET['nombre'].'%"';
                                 }

                                 if(isset($_GET['apellido1']) && $_GET['apellido1']!=""){
                                      $where[] = ' u.apellido_p like "%'.$_GET['apellido1'].'%"';
                                 }

                                 if(isset($_GET['apellido2']) && $_GET['apellido2']!=""){
                                    $where[] =' u.apellido_m like "%'.$_GET['apellido2'].'%"';
                                 }



                                 if(isset($_GET['genero']) && $_GET['genero']!=""){
                                    $where[] = ' u.genero="'.$_GET['genero'].'"';
                                 }





                                 if($todos= $conn->prepare($sql.implode(" AND ",$where))){
                                    $todos->execute();
                                    $todos->store_result();
                                    if($todos->num_rows > 0){
                                      $todos->bind_result($id,$foto,$nombre,$correo,$fecha_nacimiento,$genero);
                                      while($todos->fetch()){
                                          echo ' <tr>
                                            <th scope="row">'.$id.'</th>
                                            <td> <img class="rounded-circle" src="../'.$foto.'" height="50" width="50" > </td>
                                            <td><a href="../perfil/?id='.$id.'">'.$nombre.'</a></td>
                                              <td>'.$correo.'</td>
                                              <td>'.$fecha_nacimiento.'</td>
                                              <td>'.$genero.'</td>


                                            <td> <div class="nav-item dropdown">
                                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                              <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="">

                                                <a class="dropdown-item" href="../perfil/configuracion.php?id='.$id.'">Editar usuario</a>
                                                <a class="dropdown-item" href="index.php?action=removeusuario&usuario='.$id.'">Eliminar</a>
                                              </div>
                                            </div> </td>
                                          </tr>';
                                      }
                                      $todos->close();
                                    }else{
                                      echo '<script> document.addEventListener("DOMContentLoaded",function(event){ hubo_fallo("No se encontraron resultados"); });</script>';

                                    }

                             }


                          }

                        }



                          /*
                           if($todos= $conn->prepare('SELECT idCurso,tit_c, CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m) ,ca.categ,es.estado,num_est FROM CURSOS c INNER JOIN USUARIOS u on c.instructor_c=u.idUsuario  INNER JOIN CATEGORIAS ca on c.categ_c=ca.idCategoria INNER JOIN ESTADOS es on c.estado_c=es.idEstado')){
                              $todos->execute();
                              $todos->store_result();
                              if($todos->num_rows > 0){
                                $todos->bind_result($idCurso,$tit_c,$instructor_c,$categ_c,$estado_c,$num_est);
                                while($todos->fetch()){
                                    echo ' <tr>
                                      <th scope="row">'.$tit_c.'</th>
                                      <td>'.$instructor_c.'</td>
                                      <td>'.$categ_c.'</td>
                                      <td>'.$num_est.'</td>
                                      <td>'.$estado_c.'</td>
                                      <td><div class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="">
                                          <a class="dropdown-item" href="?action=edit&idcurso='.$idCurso.'">Editar</a>
                                          <a class="dropdown-item" href="?action=delete&idcurso='.$idCurso.'">Eliminar</a>
                                        </div>
                                      </div></td>
                                    </tr>';
                                }
                                $todos->close();
                              }

                            }
                            */

                           ?>


                        </tbody>
                        </table>


                      </div>
                    </div>


                  </div>
                </form>




            </div>



        </div>
      </div>
    </div>
  </div>
  <div class="py-3" style="">
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

<?php

$conn->close();
 ?>
