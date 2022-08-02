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
error_reporting(0);


    session_start();
    require_once("../modulos/navbar.php");
    include "../conexion.php" ;
    if(!isset($_SESSION['sesion'],$_SESSION['sesion_instructor'])){
      die("Acceso denegado");
    }
    //$conn->close();
    ?>


    <div class="mt-2 mx-5" style="">
    <div class="container-fluid">
      <div class="row">
<div class="col-md-12">
<h1 class="text-center"><i class="fas fa-chalkboard-teacher"></i> Lista de estudiantes</h1>
</div>
</div>
      <div class="row">

        <!-- MENU -->

        <div class="col-md-12">

          <form method="get">
          <div class="form-group form-row">
              <div class="col-sm-2"><input type="text" class="form-control" name="id" placeholder="ID"></div>
              <div class="col-sm-2"><input type="text" class="form-control" name="nombre" placeholder="Nombre"></div>
              <div class="col-sm-2"><input type="text" class="form-control" placeholder="Apellido Paterno" name="apellido1"></div>
              <div class="col-sm-2"><input type="text" class="form-control" placeholder="Apellido Materno" name="apellido2"></div>
              <div class="col-sm-2">
                <select class="custom-select" name="curso">
                  <option value="">Seleccionar Curso</option>';
                  <?php
                  if($buscar_cursos= $conn->prepare('SELECT idCurso,tit_c FROM CURSOS')){
                    $buscar_cursos->execute();
                    $buscar_cursos->store_result();
                    if($buscar_cursos->num_rows > 0){
                      $buscar_cursos->bind_result($idcurso,$tit);
                      while($buscar_cursos->fetch()){
                        echo '<option value="'.$idcurso.'">'.$tit.'</option>';
                      }
                      $buscar_cursos->free_result();

                    }

                  }
                  $buscar_cursos->close();
                  ?>

                  </select>


              </div>
              <div class="col-sm-2"><button value="buscar" name="action" type="submit" class="btn btn-info btn-block" >Buscar</button></div>
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
                    <th scope="col">Curso</th>
                    </tr>
                    </thead>
                    <tbody>

                      <?php

                      if(!isset($_SESSION['sesion'],$_SESSION['sesion_instructor'])){
                        die("Acceso denegado");
                      }else{
                         if($_SERVER['REQUEST_METHOD'] == "GET"){
                           if((isset($_GET['id']) && $_GET['id']!="") OR (isset($_GET['nombre']) && $_GET['nombre']!="") OR (isset($_GET['apellido1']) && $_GET['apellido1']!="") OR (isset($_GET['apellido2']) && $_GET['apellido2']!="") OR (isset($_GET['curso']) && $_GET['curso']!="")){
                             $sql = 'SELECT idUsuario,foto,CONCAT(nombre," ",apellido_p," ",apellido_m),tit_c FROM INSCRIPCIONES i INNER JOIN USUARIOS u ON i.idUsuario_i=u.idUsuario INNER JOIN CURSOS c ON i.idCurso_i=c.idCurso WHERE';

                           }else{
                             $sql = 'SELECT idUsuario,foto,CONCAT(nombre," ",apellido_p," ",apellido_m),tit_c FROM INSCRIPCIONES i INNER JOIN USUARIOS u ON i.idUsuario_i=u.idUsuario INNER JOIN CURSOS c ON i.idCurso_i=c.idCurso';
                           }
                           $where= [];

                             if(isset($_GET['id']) && $_GET['id']!=""){
                                $where[] = " u.idUsuario=".$_GET['id'];
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

                             if(isset($_GET['curso']) && $_GET['curso']!=""){
                                $where[] = ' i.idCurso_i='.$_GET['curso'].'';
                             }





                             if($todos= $conn->prepare($sql.implode(" AND ",$where))){
                                $todos->execute();
                                $todos->store_result();
                                if($todos->num_rows > 0){
                                  $todos->bind_result($id,$foto,$nombre,$curso_tit);
                                  while($todos->fetch()){
                                      echo ' <tr>
                                        <th scope="row">'.$id.'</th>
                                        <td> <img class="rounded-circle" src="../'.$foto.'" height="50" width="50" > </td>
                                        <td><a href="../perfil/?id='.$id.'">'.$nombre.'</a></td>


                                        <td>'.$curso_tit.'</td>
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


    <div class="py-3" style="">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <p class="mb-0">© 2019 COURSES IT. All rights reserved</p>
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
