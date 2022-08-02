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

  <!-- include summernote css/js -->
  <link href="../modulos/summernote/summernote-lite.css" rel="stylesheet">
  <script src="../modulos/summernote/summernote-lite.js"></script>

</head>





<body>
   <?php
   //error_reporting(0);
   error_reporting(0);
    session_start();
    require_once("../modulos/navbar.php");
    include "../conexion.php" ;
    if(!isset($_SESSION['sesion'],$_SESSION['sesion_editor'])){
      die("Acceso denegado");
    }else{
      // PANEL INSTRUCTOR
      if($_SERVER['REQUEST_METHOD']== "POST"){
        $accion = $_POST['action'];
        $titulo = $_POST['tit'];
        $categoria = $_POST['categ'];
        $cuerpo = $_POST['cuerpo'];
        if($accion=="edit"){


        }elseif ($accion=="new") {

                if($crear_blog = $conn->prepare('INSERT INTO BLOG(editor) VALUES(?)')){

                  $crear_blog->bind_param('i',$_SESSION['id']);
                  $crear_blog->execute();

                  if($buscar_idpost = $conn->prepare('SELECT idBlog FROM BLOG WHERE idBlog=LAST_INSERT_ID()')){
                    $buscar_idpost->execute();
                    $buscar_idpost->store_result();
                    $buscar_idpost->bind_result($idpost);
                    $buscar_idpost->fetch();
                    $buscar_idpost->free_result();
                    $buscar_idpost->close();
                    header('Refresh:0; url=index.php?action=edit&idpost='.$idpost);
                  }

                }
                $crear_blog->close();


        }elseif ($accion=="save") {

          if($_FILES['portada']['size']!=0){
             if($_FILES['portada']['type']=="image/png" OR $_FILES['portada']['type']=="image/jpeg"){
                 $path = "imagenes/blog/portada/".$_GET['idpost'].'.'.pathinfo($_FILES['portada']['name'],PATHINFO_EXTENSION);
                 if(move_uploaded_file($_FILES['portada']['tmp_name'],'../'.$path)){
                   if($actualizar_portada = $conn->prepare('UPDATE BLOG SET portada_b=? WHERE idBlog=?')){
                     $actualizar_portada->bind_param('si',$path,$_GET['idpost']);
                     $actualizar_portada->execute();

                   }
                   $actualizar_portada->close();
                 }

             }

          }

          if(!isset($titulo,$categoria)){




          }else{

            if($guardar_post = $conn->prepare('UPDATE BLOG SET tit_b=?,cuerpo=?,categ_b=? WHERE idBlog=?')){
               $guardar_post->bind_param('ssii',$titulo,$cuerpo,$categoria,$_GET['idpost']);
               $guardar_post->execute();
               $guardar_post->close();

            }
          }



        }elseif ($accion=="public") {
          if($_FILES['portada']['size']!=0){
             if($_FILES['portada']['type']=="image/png" OR $_FILES['portada']['type']=="image/jpeg"){
                 $path = "imagenes/blog/portada/".$_GET['idpost'].'.'.pathinfo($_FILES['portada']['name'],PATHINFO_EXTENSION);
                 if(move_uploaded_file($_FILES['portada']['tmp_name'],'../'.$path)){
                   if($actualizar_portada = $conn->prepare('UPDATE BLOG SET portada_b=? WHERE idBlog=?')){
                     $actualizar_portada->bind_param('si',$path,$_GET['idpost']);
                     $actualizar_portada->execute();

                   }
                   $actualizar_portada->close();
                 }

             }

          }

          if(!isset($titulo,$categoria)){




          }else{

            if($guardar_post = $conn->prepare('UPDATE BLOG SET tit_b=?,cuerpo=?,categ_b=? WHERE idBlog=?')){
               $guardar_post->bind_param('ssii',$titulo,$cuerpo,$categoria,$_GET['idpost']);
               $guardar_post->execute();
               $guardar_post->close();

            }
          }




          if(isset($_GET['idpost'])){

            if($buscar_fecha = $conn->prepare('SELECT * FROM BLOG where idBlog=? AND publ_b IS NULL')){
              $buscar_fecha->bind_param('i',$_GET['idpost']);
              $buscar_fecha->execute();
              $buscar_fecha->store_result();

              if($buscar_fecha->num_rows > 0){

                    $buscar_fecha->close();

                    if($incluir_fecha= $conn->prepare('UPDATE BLOG SET publ_b=NOW() WHERE idBlog=?')){
                      $incluir_fecha->bind_param('i',$_GET['idpost']);
                      $incluir_fecha->execute();
                      $incluir_fecha->close();



                    }


              }

            }


            $id =$_GET['idpost'];
            if($publicar = $conn->prepare('UPDATE BLOG set estado_b=3 WHERE idBlog=?')){
              $publicar->bind_param('i',$id);
              $publicar->execute();


            }
            $publicar->close();


          }
        }elseif ($accion=="private") {
          if(isset($_GET['idpost'])){
            $id =$_GET['idpost'];
            if($publicar = $conn->prepare('UPDATE BLOG set estado_b=2 WHERE idBlog=?')){
              $publicar->bind_param('i',$id);
              $publicar->execute();


            }
            $publicar->close();


          }
        }


      }elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
        if(isset($_GET['action'])){
          echo "d";
          if($_GET['action']=="delete"){
            if(isset($_GET['idpost'])){
              if($remover_blog = $conn->prepare('DELETE FROM BLOG WHERE idBlog=?')){
                $remover_blog->bind_param('i',$_GET['idpost']);
                $remover_blog->execute();


              }
              $remover_blog->close();
                header('Refresh:0; url=index.php');
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
<h1 class="text-center"><i class="fas fa-book"></i> Panel de Editor</h1>
</div>
</div>
      <div class="row">

        <!-- MENU -->
        <div class="col-md-12 tab-content">


         <div id="d" <?php echo (isset($_GET['action'])?'class="tab-pane active"':'class="tab-pane"') ?> >

           <?php
           if(isset($_GET['action'])){

            if($datos_post = $conn->prepare('SELECT tit_b,cuerpo,categ_b,portada_b,estado_b,categ FROM BLOG b INNER JOIN CATEGORIAS ca ON b.categ_b=ca.idCategoria  WHERE idBlog=?')){
               $datos_post->bind_param('i',$_GET['idpost']);
               $datos_post->execute();
               $datos_post->store_result();
               if($datos_post->num_rows > 0){
                 $datos_post->bind_result($tit_b,$cuerpo,$categ_b,$portada_b,$estado_b,$categ);
                 $datos_post->fetch();
                 $datos_post->free_result();
               }
               $datos_post->close();

            }
          }



            ?>

           <h4 class="text-center">Editar publicación</h4>
            <form class="text-left" enctype="multipart/form-data" method="post">
              <div class="row">
              <div class="col-md-6">

                <?php

                if($estado_b==3){
                  echo '<h5>Estado: Publicado</h5>';
                }elseif ($estado_b==2) {
                    echo '<h5>Estado: Privado</h5>';
                }elseif ($estado_b==1) {
                    echo '<h5>Estado: No publicado</h5>';
                }


                 ?>
            </div>


              <div class="col-md-6 text-right">
                <button value="save" name="action" type="submit" class="btn btn-info">Guardar</button>

              <!--  <button value="preview" name="action" type="submit" class="btn btn-light">Vista previa</button>-->
                <?php
                if($estado_b==3){
                  echo '<button value="private" name="action" type="submit" class="btn btn-primary m-2">Ocultar</button>';
                }elseif ($estado_b==2){
                  echo '<button value="public" name="action" type="submit" class="btn btn-primary m-2">Publicar</button>';
                }elseif ($estado_b==1){
                  echo '<button value="public" name="action" type="submit" class="btn btn-primary m-2">Publicar</button>';
                }

                 ?>


              </div>
            </div>


            <div class="row">
              <div class="col-md-9">
                <div class="form-group form-row"> <label class="col-sm-2 col-form-label" for="form16">Titulo</label>
                  <div class="col-sm-10"><input type="text" value=<?php echo '"'.$tit_b.'"' ?> class="form-control" id="tit1" name="tit"></div>
                </div>

                <textarea id="cuerpo" name="cuerpo">
                   <?php echo $cuerpo; ?>
                </textarea>



              </div>
              <div class="col-md-3">

                <div class="form-group">
                  <label class="col-form-label" for="form16">Portada:</label>

                    <img <?php echo 'src="../'.$portada_b.'"' ?> width="150" height="100" class="m-2" >

                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="portada" name="portada">
                      <label class="custom-file-label" for="customFile">Seleccionar imagen</label>
                     </div>


                </div>
                <div class="form-group"> <label for="form16" class="col-sm-2 col-form-label">Categorias</label>
                  <div ><select class="custom-select" name="categ" id="categoria1">
                    <?php
                    echo '<option value="'.$categ_b.'">'.$categ.'</option>';
                    require("../modulos/categorias.php");
                    ?>
                    </select></div>
                </div>



              </div>


            </div>






            </form>
       </div>




          <!-- Lista de publicaciones -->
          <div id="a" <?php echo (!isset($_GET['action'])?'class="tab-pane active"':'class="tab-pane"') ?> >


            <div class="row">
            <div class="col-md-6">
              <form method="post">
                <div class="form-row">
                  <h3>PUBLICACIONES  </h3> <button value="new" name="action" type="submit" class="btn btn-info mx-2">Añadir publicación</button>

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
                    <th scope="col">Autor</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Numero de visitas</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                      <?php
                       if($todos= $conn->prepare('SELECT idBlog,tit_b, CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m)  ,ca.categ,es.estado,num_visitas FROM BLOG b INNER JOIN USUARIOS u on b.editor=u.idUsuario  INNER JOIN CATEGORIAS ca on b.categ_b=ca.idCategoria INNER JOIN ESTADOS es on b.estado_b=es.idEstado')){
                          $todos->execute();
                          $todos->store_result();
                          if($todos->num_rows > 0){
                            $todos->bind_result($idBlog,$tit_b,$editor,$categ_b,$estado_b,$num_visitas);
                            while($todos->fetch()){
                                echo ' <tr>
                                  <th scope="row">'.$tit_b.'</th>
                                  <td>'.$editor.'</td>
                                  <td>'.$categ_b.'</td>
                                  <td>'.$num_visitas.'</td>
                                  <td>'.$estado_b.'</td>
                                  <td><div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="">
                                      <a class="dropdown-item" href="?action=edit&idpost='.$idBlog.'">Editar</a>
                                      <a class="dropdown-item" href="?action=delete&idpost='.$idBlog.'">Eliminar</a>
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
                    <th scope="col">Numero de visitas</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                      <?php
                       if($mio= $conn->prepare('SELECT idBlog,tit_b,  CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m) ,ca.categ,es.estado,num_visitas FROM BLOG b INNER JOIN USUARIOS u on b.editor=u.idUsuario  INNER JOIN CATEGORIAS ca on b.categ_b=ca.idCategoria INNER JOIN ESTADOS es on b.estado_b=es.idEstado where b.editor=?')){
                          $mio->bind_param("i",$_SESSION['id']);
                          $mio->execute();
                          $mio->store_result();
                          if($mio->num_rows > 0){
                            $mio->bind_result($idBlog,$tit_b,$editor,$categ_b,$estado_b,$num_visitas);
                            while($mio->fetch()){
                                echo ' <tr>
                                  <th scope="row">'.$tit_b.'</th>
                                  <td>'.$editor.'</td>
                                  <td>'.$categ_b.'</td>
                                  <td>'.$num_visitas.'</td>
                                  <td>'.$estado_b.'</td>
                                  <td><div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="">
                                      <a class="dropdown-item" href="?action=edit&idpost='.$idBlog.'">Editar</a>
                                      <a class="dropdown-item" href="?action=delete&idpost='.$idBlog.'">Eliminar</a>
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
<th scope="col">Numero de visitas</th>
<th scope="col">Estado</th>
<th scope="col">Opciones</th>
</tr>
</thead>
<tbody>

  <?php
   if($publicado= $conn->prepare('SELECT idBlog,tit_b,  CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m) ,ca.categ,es.estado,num_visitas FROM BLOG b INNER JOIN USUARIOS u on b.editor=u.idUsuario  INNER JOIN CATEGORIAS ca on b.categ_b=ca.idCategoria INNER JOIN ESTADOS es on b.estado_b=es.idEstado where b.estado_b=3')){
      $publicado->execute();
      $publicado->store_result();
      if($publicado->num_rows > 0){
        $publicado->bind_result($idBlog,$tit_b,$editor,$categ_b,$estado_b,$num_visitas);
        while($publicado->fetch()){
            echo ' <tr>
              <th scope="row">'.$tit_b.'</th>
              <td>'.$editor.'</td>
              <td>'.$categ_b.'</td>
              <td>'.$num_visitas.'</td>
              <td>'.$estado_b.'</td>

              <td><div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="">
                  <a class="dropdown-item" href="?action=edit&idpost='.$idBlog.'">Editar</a>
                  <a class="dropdown-item" href="?action=delete&idpost='.$idBlog.'">Eliminar</a>
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
          <!-- Crear curso -->
          <div id="b" class="tab-pane">
            <h4 class="text-center">Crear publicación</h4>
            <form method="post" action="index.php"  class="text-left">
              <div class="form-group"> <label for="form16">Titulo</label> <input type="" class="form-control" id="tit" name="tit" required="required"> </div>

              <div class="form-group"> <label for="form18">Categoria</label> <select class="custom-select" id="categ" name="categ" required="required">
                <?php
                require("../modulos/categorias.php");
                ?>
                </select></div>
              <button name="action" value="new" type="submit" class="btn btn-dark btn-block">Crear publicación</button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>


  <!-- libreria bootstrap y jquery -->
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery-3.4.1.slim.js"></script>
  <script src="../modulos/summernote/summernote-lite.js"></script>
  <script>
        $('#cuerpo').summernote({
            minHeight: 1000,
            focus: true
        });
  </script>
  <div class="py-3" style="">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <p class="mb-0">© 2019 POLICURSOS. DERECHOS RESERVADOS</p>
        </div>
      </div>
    </div>
  </div>
 </body>

<?php

$conn->close();
 ?>
