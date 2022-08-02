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
    }else{
      // PANEL INSTRUCTOR
      if($_SERVER['REQUEST_METHOD']== "POST"){
        $accion = $_POST['action'];
        $titulo = $_POST['tit'];
        $nivel = $_POST['nivel'];
        $categoria = $_POST['categ'];
        $descripcion = $_POST['descrip'];
        $idc = $_GET['idcurso'];
        $cont = $_POST['cont'];
        if($accion=="edit"){

        }elseif ($accion=="new") {


                if($crear_blog = $conn->prepare('INSERT INTO CURSOS(instructor_c) VALUES(?)')){

                  $crear_blog->bind_param('i',$_SESSION['id']);
                  $crear_blog->execute();
                  $crear_blog->close();

                  if($buscar_idcurso = $conn->prepare('SELECT idCurso FROM CURSOS WHERE idCurso=LAST_INSERT_ID()')){
                    $buscar_idcurso->execute();
                    $buscar_idcurso->store_result();
                    $buscar_idcurso->bind_result($idcurso);
                    $buscar_idcurso->fetch();
                    $buscar_idcurso->free_result();
                    $buscar_idcurso->close();
                    header('Refresh:0; url=index.php?action=edit&idcurso='.$idcurso);
                  }




            }
        }elseif ($accion=="addLeccion") {

          if(!isset($titulo,$descripcion,$cont,$idc)){

            /*
             corregir en un futuro
            */


          }else{

              if($nueva_leccion = $conn->prepare('INSERT INTO LECCIONES(tit_l,descrip_l, cont_l, idCurso_l) VALUES(?,?,?,?)')){
                $nueva_leccion->bind_param('sssi',$titulo,$descripcion,$cont,$idc);
                $nueva_leccion->execute();
                $nueva_leccion->close();

              }


          }
        }elseif ($accion=="save") {

          if($_FILES['portada']['size']!=0){
             if($_FILES['portada']['type']=="image/png" OR $_FILES['portada']['type']=="image/jpeg"){
                 $path = "imagenes/curso/portada/".$_GET['idcurso'].'.'.pathinfo($_FILES['portada']['name'],PATHINFO_EXTENSION);
                 if(move_uploaded_file($_FILES['portada']['tmp_name'],'../'.$path)){
                   if($actualizar_portada = $conn->prepare('UPDATE CURSOS SET portada_c=? WHERE idCurso=?')){
                     $actualizar_portada->bind_param('si',$path,$_GET['idcurso']);
                     $actualizar_portada->execute();

                   }
                   $actualizar_portada->close();
                 }

             }

          }



          if(!isset($titulo,$nivel,$categoria)){




          }else{


            if($guardar_curso = $conn->prepare("UPDATE CURSOS SET tit_c=? ,descrip_c=?,nivel_c=?,categ_c=? WHERE idCurso=?")){

               $guardar_curso->bind_param('ssiii',$titulo,$descripcion,$nivel,$categoria,$_GET['idcurso']);
               $guardar_curso->execute();
               $guardar_curso->close();

            }
          }



        }elseif ($accion=="public") {
          if(isset($_GET['idcurso'])){

            //Guardar Curso


            if($_FILES['portada']['size']!=0){
               if($_FILES['portada']['type']=="image/png" OR $_FILES['portada']['type']=="image/jpeg"){
                   $path = "imagenes/curso/portada/".$_GET['idcurso'].'.'.pathinfo($_FILES['portada']['name'],PATHINFO_EXTENSION);
                   if(move_uploaded_file($_FILES['portada']['tmp_name'],'../'.$path)){
                     if($actualizar_portada = $conn->prepare('UPDATE CURSOS SET portada_c=? WHERE idCurso=?')){
                       $actualizar_portada->bind_param('si',$path,$_GET['idcurso']);
                       $actualizar_portada->execute();

                     }
                     $actualizar_portada->close();
                   }

               }

            }



            if(!isset($titulo,$nivel,$categoria)){




            }else{


              if($guardar_curso = $conn->prepare("UPDATE CURSOS SET tit_c=? ,descrip_c=?,nivel_c=?,categ_c=? WHERE idCurso=?")){

                 $guardar_curso->bind_param('ssiii',$titulo,$descripcion,$nivel,$categoria,$_GET['idcurso']);
                 $guardar_curso->execute();
                 $guardar_curso->close();

              }
            }



            $id =$_GET['idcurso'];
            if($publicar = $conn->prepare('UPDATE CURSOS set estado_c=3 WHERE idCurso=?')){
              $publicar->bind_param('i',$id);
              $publicar->execute();


            }
            $publicar->close();


          }
        }elseif ($accion=="private") {
          if(isset($_GET['idcurso'])){
            $id =$_GET['idcurso'];
            if($publicar = $conn->prepare('UPDATE CURSOS set estado_c=2 WHERE idCurso=?')){
              $publicar->bind_param('i',$id);
              $publicar->execute();


            }
            $publicar->close();


          }
        }elseif ($accion=="updateLeccion") {
          if(isset($_GET['idcurso'],$_GET['leccion'])){

            if($actualizar_leccion = $conn->prepare('UPDATE LECCIONES SET tit_l=?,descrip_l=?,cont_l=? WHERE idCurso_l=? AND idLeccion=?')){

              $actualizar_leccion->bind_param('sssii',$_POST['tit'],$_POST['descrip'],$_POST['cont'],$_GET['idcurso'],$_GET['leccion']);
              $actualizar_leccion->execute();



            }
            $actualizar_leccion->close();

            header('Refresh:0; url=index.php?action=edit&idcurso='.$_GET['idcurso']);


          }
        }


      }elseif($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['action'])){
          if($_GET['action']=="delete"){
            if(isset($_GET['idcurso'])){

               if($remover_curso = $conn->prepare('DELETE FROM INSCRIPCIONES WHERE idCurso_i=?')){
                $remover_curso->bind_param('i',$_GET['idcurso']);
                $remover_curso->execute();



              }
              $remover_curso->close();

              if($remover_curso1 = $conn->prepare('DELETE FROM CURSOS WHERE idCurso=?')){
                $remover_curso1->bind_param('i',$_GET['idcurso']);
                $remover_curso1->execute();


              }
              $remover_curso1->close();
                header('Refresh:0; url=index.php');
            }

          }elseif ($_GET['action']=="removeleccion") {
              if(isset($_GET['idcurso'],$_GET['leccion'])){
                if($remover_leccion = $conn->prepare('DELETE FROM LECCIONES WHERE idLeccion=? AND idCurso_l=?')){
                  $remover_leccion->bind_param('ii',$_GET['leccion'],$_GET['idcurso']);
                  $remover_leccion->execute();


                }
                $remover_leccion->close();
                  header('Refresh:0; url=index.php?action=edit&idcurso='.$_GET['idcurso']);
              }
            }



           }

        }






    }
    //$conn->close();
    ?>


    <div class="mt-2 mx-5" style="">
    <div class="container-fluid">
      <div class="row">
<div class="col-md-12">
<h1 class="text-center"><i class="fas fa-chalkboard-teacher"></i> Panel de Instructor</h1>
</div>
</div>
      <div class="row">

        <!-- MENU -->
        <div class="col-md-12 tab-content">

         <div id="d" <?php echo (isset($_GET['idcurso'])?'class="tab-pane active"':'class="tab-pane"') ?> >

           <?php
           if(isset($_GET['action'])){

            if($datos_curso = $conn->prepare('SELECT tit_c,descrip_c,categ_c,nivel_c,portada_c,estado_c,categ,nivel FROM CURSOS c INNER JOIN CATEGORIAS ca on c.categ_c=ca.idCategoria INNER JOIN NIVELES n ON c.nivel_c=n.idNivel WHERE idCurso=?')){
               $datos_curso->bind_param('i',$_GET['idcurso']);
               $datos_curso->execute();
               $datos_curso->store_result();
               if($datos_curso->num_rows > 0){
                 $datos_curso->bind_result($tit_c,$descrip_c,$categ_c,$nivel_c,$portada_c,$estado_c,$categ,$nivel);
                 $datos_curso->fetch();




               }
               $datos_curso->free_result();
               $datos_curso->close();

            }
          }



            ?>

           <h3 class="text-center">Editar curso</h3>




            <form  enctype="multipart/form-data" method="post">
              <div class="row">
              <div class="col-md-6">

                <?php

                if($estado_c==3){
                  echo '<h5>Estado: Publicado</h5>';
                }elseif ($estado_c==2) {
                    echo '<h5>Estado: Privado</h5>';
                }elseif ($estado_c==1) {
                    echo '<h5>Estado: No publicado</h5>';
                }


                 ?>
            </div>


              <div class="col-md-6 text-right">
                <button value="save" name="action" type="submit" class="btn btn-info">Guardar</button>

              <!--  <button value="preview" name="action" type="submit" class="btn btn-light">Vista previa</button>-->
                <?php
                if($estado_c==3){
                  echo '<button value="private" name="action" type="submit" class="btn btn-primary m-2">Ocultar</button>';
                }else{
                  echo '<button value="public" name="action" type="submit" class="btn btn-primary m-2">Publicar</button>';
                }

                 ?>


              </div>
            </div>



              <div class="form-group form-row"> <label class="col-sm-2 col-form-label" for="form16">Titulo</label>
                <div class="col-sm-10"><input type="text" value=<?php echo '"'.$tit_c.'"' ?> class="form-control" id="tit1" name="tit"></div>
              </div>
              <div class="form-group form-row"> <label class="col-sm-2 col-form-label" for="form16">Portada</label>
                <div class="col-sm-10">
                  <img <?php echo 'src=../'.$portada_c.'' ?> width="500" height="200" class="" >
                  <input type="file" class="form-control-file" id="portada" name="portada">
                </div>


              </div>
              <div class="form-group form-row"> <label for="form16" class="col-sm-2 col-form-label">Nivel</label>
                <div class="col-sm-10"><select class="custom-select" id="nivel1" name="nivel">
                     <!--Obtener los niveles disponible  -->
                    <?php
                    echo '<option value="'.$nive_c.'">'.$nivel.'</option>';
                    require("../modulos/niveles.php");
                    ?>
                  </select></div>
              </div>
              <div class="form-group form-row"> <label for="form16" class="col-sm-2 col-form-label">Categorias</label>
                <div class="col-sm-10"><select class="custom-select" name="categ" id="categoria1">
                  <?php
                  echo '<option value="'.$categ_c.'">'.$categ.'</option>';
                  require("../modulos/categorias.php");
                  ?>
                  </select></div>
              </div>
              <div class="form-group"> <label class="" for="form16">Descripcion</label>
                <textarea type="text" class="form-control" name="descrip" id="descrip"><?php echo $descrip_c ?></textarea>
              </div>
            </form>


            <h4 class="text-center">Lecciones</h4>

            <table class="table">
            <thead class="thead-light">
            <tr>

            <th scope="col">Titulo</th>
            <th scope="col">Descripción</th>
            <th scope="col">Video</th>
            <th scope="col">Opciones</th>
            </tr>
            </thead>
            <tbody>

              <?php
               if($lista_lecciones= $conn->prepare('SELECT tit_l,descrip_l,cont_l,idLeccion FROM LECCIONES WHERE idCurso_l=?')){
                  $lista_lecciones->bind_param('i',$_GET['idcurso']);
                  $lista_lecciones->execute();
                  $lista_lecciones->store_result();
                  if($lista_lecciones->num_rows > 0){
                    $lista_lecciones->bind_result($tit_l,$descrip_l,$cont_l,$idleccion);
                    while($lista_lecciones->fetch()){
                        echo ' <tr>

                          <th scope="row">'.$tit_l.'</th>
                          <td>'.$descrip_l.'</td>
                          <td><a href="https://www.youtube.com/watch?v='.$cont_l.'">Abrir</a></td>
                          <td><div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="">
                              <a class="dropdown-item" href="?action=edit&idcurso='.$_GET['idcurso'].'&leccion='.$idleccion.'">Editar</a>
                              <a class="dropdown-item" href="?action=removeleccion&idcurso='.$_GET['idcurso'].'&leccion='.$idleccion.'">Eliminar</a>
                            </div>
                          </div></td>
                        </tr>';
                    }
                    $lista_lecciones->close();
                  }

                }

               ?>









            </tbody>
            </table>

            <div class="tab-content">
               <div <?php echo ((isset($_GET['idcurso']) AND !isset($_GET['leccion']))?'class="tab-pane active"':'class="tab-pane"') ?>>
                <h4 class="text-center">Añadir lección</h4>
                <form method="post"  method="index.php"class="text-left">
                  <div class="form-group form-row"> <label class="col-sm-2 col-form-label" for="form16">Titulo</label>
                     <div class="col-sm-10"><input type="text" class="form-control" id="tit3" name="tit" placeholder="Titulo" required></div>
                   </div>
                   <div class="form-group form-row"> <label class="col-sm-2 col-form-label" for="form16">Video de Youtube</label>
                     <div class="col-sm-10"><input type="text" class="form-control" placeholder="ID de video(CpWL96ftD-I)" name="cont" id="cont" required></div>
                   </div>
                   <div class="form-group"> <label class="" for="form16">Descripcion</label>
                     <textarea type="text" class="form-control" name="descrip" id="descrip1" placeholder="Descripcion de curso" required ></textarea>
                   </div>
                   <div class="text-right my-2">
                     <button  name="action" value="addLeccion" type="submit" class="btn btn-primary" >Añadir Lección</button>
                   </div>
                </form>


              </div>


              <div <?php echo (($_GET['action']=="edit"   AND isset($_GET['idcurso']) AND isset($_GET['leccion']))?'class="tab-pane active"':'class="tab-pane"') ?>>
                 <?php
                  if(isset($_GET['idcurso']) AND isset($_GET['leccion'])){
                    if($datos_leccion= $conn->prepare('SELECT tit_l,descrip_l,cont_l FROM LECCIONES WHERE idLeccion=? AND idCurso_l=?')){
                      $datos_leccion->bind_param('ii',$_GET['leccion'],$_GET['idcurso']);
                      $datos_leccion->execute();
                      $datos_leccion->store_result();
                      $datos_leccion->bind_result($tit_l,$descrip_l,$cont_l);
                      $datos_leccion->fetch();

                    }
                    $datos_leccion->free_result();
                    $datos_leccion->close();
                  }
                 ?>

                <h4 class="text-center">Modificar leccion</h4>
                <form method="post"  method="index.php"class="text-left">
                  <div class="form-group form-row"> <label class="col-sm-2 col-form-label" for="form16">Titulo</label>
                     <div class="col-sm-10"><input <?php echo 'value="'.$tit_l.'"'; ?> type="text" class="form-control" id="tit3" name="tit" placeholder="Titulo" required></div>
                   </div>
                   <div class="form-group form-row"> <label class="col-sm-2 col-form-label" for="form16">Video de Youtube</label>
                     <div class="col-sm-10"><input <?php echo 'value="'.$descrip_l.'"'; ?> type="text" placeholder="ID de video(CpWL96ftD-I)"  class="form-control" name="cont" id="cont" required></div>
                   </div>
                   <div class="form-group"> <label class="" for="form16">Descripcion</label>
                     <textarea type="text" class="form-control" name="descrip" id="descrip1" placeholder="Descripcion de curso" required><?php echo $descrip_l; ?></textarea>
                   </div>
                   <div class="text-right my-2">
                     <button  name="action" value="updateLeccion" type="submit" class="btn btn-primary" >Guardar Lección</button>
                   </div>
                </form>


              </div>



            </div>






</div>





          <!-- Lista de curso -->
          <div id="a" <?php echo ((!isset($_GET['action']))?'class="tab-pane active"':'class="tab-pane"') ?> >




            <div class="row">
            <div class="col-md-6">
              <form method="post">
                <div class="form-row">
                  <h4>CURSOS  </h4> <button value="new" name="action" type="submit" class="btn btn-info mx-2">Añadir curso</button>  <a  href="lista.php" class="btn btn-info mx-2">Lista de estudiantes</a>

            </div>
            </form>
              </div>
            <div class="col-md-6 text-right">
              <ul class="nav nav-pills justify-content-end">
                <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabone">Todos</a> </li>
                <li class="nav-item"> <a class="nav-link" href="" data-toggle="pill" data-target="#tabtwo"> Mío </a> </li>
                <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabthree">Publicado</a> </li>
              </ul>
             </div>
            </div>

            <form class="text-left">
              <div class="tab-content mt-2">
                <div class="tab-pane fade show active" id="tabone" role="tabpanel" style="">
                  <div class="row">
                    <table class="table">
                    <thead class="thead-light">
                    <tr>
                    <th scope="col">Titulo</th>
                    <th scope="col">Instructor</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Numero de estudiantes</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                      <?php
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
                    <th scope="col">Titulo</th>
                    <th scope="col">Instructor</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Numero de estudiantes</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                      <?php
                       if($mio= $conn->prepare('SELECT idCurso,tit_c, CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m) ,ca.categ,es.estado,num_est FROM CURSOS c INNER JOIN USUARIOS u on c.instructor_c=u.idUsuario  INNER JOIN CATEGORIAS ca on c.categ_c=ca.idCategoria INNER JOIN ESTADOS es on c.estado_c=es.idEstado where c.instructor_c=?')){
                          $mio->bind_param("i",$_SESSION['id']);
                          $mio->execute();
                          $mio->store_result();
                          if($mio->num_rows > 0){
                            $mio->bind_result($idCurso,$tit_c,$instructor_c,$categ_c,$estado_c,$num_est);
                            while($mio->fetch()){
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
                            $mio->free_result();
                            $mio->close();
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
<th scope="col">Titulo</th>
<th scope="col">Instructor</th>
<th scope="col">Categoria</th>
<th scope="col">Numero de estudiantes</th>
<th scope="col">Estado</th>
<th scope="col">Opciones</th>
</tr>
</thead>
<tbody>

  <?php
   if($publicado= $conn->prepare('SELECT idCurso,tit_c, CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m) ,ca.categ,es.estado,num_est FROM CURSOS c INNER JOIN USUARIOS u on c.instructor_c=u.idUsuario  INNER JOIN CATEGORIAS ca on c.categ_c=ca.idCategoria INNER JOIN ESTADOS es on c.estado_c=es.idEstado where c.estado_c=3')){
      $publicado->execute();
      $publicado->store_result();
      if($publicado->num_rows > 0){
        $publicado->bind_result($idCurso,$tit_c,$instructor_c,$categ_c,$estado_c,$num_est);
        while($publicado->fetch()){
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
        $publicado->free_result();
        $publicado->close();
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
