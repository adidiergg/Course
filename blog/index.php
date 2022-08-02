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

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['action'])){
           $accion = $_POST['action'];
           if($accion=="inscribirse"){
               if($inscribirse = $conn->prepare('INSERT INTO INSCRIPCIONES(idCurso_i,idUsuario_i) VALUES (?,?)')){
                 $inscribirse->bind_param('ii',$_GET['curso'],$_SESSION['id']);
                 $inscribirse->execute();
               }
               $inscribirse->close();


               if($sumar_estudiante = $conn->prepare('UPDATE CURSOS c SET c.num_est=c.num_est+1 WHERE idCurso=?')){
                 $sumar_estudiante->bind_param('i',$_GET['curso']);
                 $sumar_estudiante->execute();
               }
               $sumar_estudiante->close();
           }elseif ($accion=="desuscribirse") {
             if($desuscribirse = $conn->prepare('DELETE FROM INSCRIPCIONES WHERE idUsuario_i=? AND idCurso_i=?')){
               $desuscribirse->bind_param('ii',$_GET['curso'],$_GET['curso']);
               $desuscribirse->execute();


             }
             $desuscribirse->close();

             if($restar_estudiante = $conn->prepare('UPDATE CURSOS c SET c.num_est=c.num_est-1 WHERE idCurso=?')){
               $restar_estudiante->bind_param('i',$_GET['curso']);
               $restar_estudiante->execute();
             }
             $restar_estudiante->close();
           }

        }


    }


    ?>





<div class="tab-content">

  <div  <?php echo (!(isset($_GET['post']))?'class="tab-pane active"':'class="tab-pane"') ?>>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-10">

          <div class="row" >

            <?php
             if(isset($_GET['category'])){
                $sql = 'SELECT idBlog,tit_b,portada_b,CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m),DATE_FORMAT(publ_b,"%d-%m-%Y") FROM BLOG b  INNER JOIN USUARIOS u ON b.editor=u.idUsuario WHERE estado_b=3 AND categ_b=?';
             }else{
               $sql = 'SELECT idBlog,tit_b,portada_b,CONCAT(u.nombre," ",u.apellido_p," ",u.apellido_m),DATE_FORMAT(publ_b,"%d-%m-%Y") FROM BLOG b  INNER JOIN USUARIOS u ON b.editor=u.idUsuario WHERE estado_b=3';
             }




             if($buscar_blogs=$conn->prepare($sql)){


               if(isset($_GET['category'])){
                  $buscar_blogs->bind_param('i',$_GET['category']);
               }

                $buscar_blogs->execute();
                $buscar_blogs->store_result();
                if($buscar_blogs->num_rows > 0){
                  $buscar_blogs->bind_result($idBlog,$tit_b,$portada_b,$autor,$fecha_publicacion);
                  while($buscar_blogs->fetch()){
                    echo '<div class="col-md-4  my-2">

                      <div class="card"> <a href="?post='.$idBlog.'"> <img class="card-img-top" src="../'.$portada_b.'" alt="Card image cap" width="550" height="250" ></a>
                          <div class="card-body">
                            <h5 class="card-title font-weight-bold"> '.$tit_b.'</h5>
                              <div class="row ml-1">
                              <i class="fas fa-calendar"></i>
                                    <h6 class="card-text ">  '.$fecha_publicacion. ' </h6>
                              <i class="fas fa-user "></i>  <h6 class="card-text ">  '.$autor.' </h6>
                              </div>
                          </div>
                      </div>
                    </div>

                    ';

                  }

                }

             }
             $buscar_blogs->free_result();
             $buscar_blogs->close();


             ?>






          </div>

         </div>
        <div class="col-md-2">
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active"> Categorias</a>
            <?php
            if($buscar_categoria= $conn->prepare('SELECT * FROM CATEGORIAS WHERE idCategoria!=1')){
              $buscar_categoria->execute();
              $buscar_categoria->store_result();
              if($buscar_categoria->num_rows > 0){
                $buscar_categoria->bind_result($idcateg,$categ);
                while($buscar_categoria->fetch()){
                  echo '<a href="?category='.$idcateg.'" class="list-group-item list-group-item-action">'.$categ.'</a>';
                }
                $buscar_categoria->free_result();

              }

            }
            $buscar_categoria->close();
            ?>
          </div>
          </br>

        </div>
      </div>
    </div>
  </div>

 <!-- Publicacion -->
 <div  <?php echo ((isset($_GET['post']))?'class="tab-pane active"':'class="tab-pane"') ?>>
   <?php
   if(isset($_GET['post'])){

     if($datos_curso = $conn->prepare('SELECT tit_b,cuerpo,num_visitas,categ,portada_b,estado_b,DATE_FORMAT(publ_b,"%d-%m-%Y") FROM BLOG b INNER JOIN CATEGORIAS ca ON ca.idCategoria=b.categ_b WHERE idBlog=? and estado_b=3')){

        $datos_curso->bind_param('i',$_GET['post']);

        $datos_curso->execute();
        $datos_curso->store_result();

        if($datos_curso->num_rows > 0){
          $datos_curso->bind_result($titulo,$cuerpo,$num_visitas,$categoria,$portada_b,$estado,$fecha_publicacion);
          $datos_curso->fetch();


        }else{
          die("Por el momento esta publicación no se encuentra disponible");

        }


     }
     $datos_curso->free_result();
     $datos_curso->close();




   }

    ?>


   <div class="text-center text-white align-items-center d-flex h-75" style="	background-image: linear-gradient(to bottom, rgba(0, 0, 0, .75), rgba(0, 0, 0, .75)), <?php echo 'url(../'.$portada_b.');'  ?>	background-position: center center, center center;	background-size: cover, cover;	background-repeat: repeat, repeat;">
       <div class="container py-5">
         <div class="row">
           <div class="mx-auto col-lg-8 col-md-10">
             <h3 class="mb-4 text-left display-4"><?php echo $titulo; ?></h3>
             <h6 class="text-left">Fecha de publicación: <?php echo $fecha_publicacion; ?></h6>
             <h6 class="text-left">Categoria: <?php echo $categoria; ?></h6>
             <h6 class="text-left">Autor: <?php echo $autor; ?></h6>


           </div>
         </div>
       </div>
     </div>


     <div class="py-5">
       <div class="container">
             <?php
                 echo $cuerpo;
              ?>
         </div>
       </div>
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
