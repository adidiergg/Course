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
               $desuscribirse->bind_param('ii',$_SESSION['id'],$_GET['curso']);
               $desuscribirse->execute();
               if($restar_estudiante = $conn->prepare('UPDATE CURSOS c SET c.num_est=c.num_est-1 WHERE idCurso=?')){
               $restar_estudiante->bind_param('i',$_GET['curso']);
               $restar_estudiante->execute();
             }
             $restar_estudiante->close();


             }
             $desuscribirse->close();


           }

        }


    }else{

    }


    ?>





<div class="tab-content">

  <div  <?php echo (!(isset($_GET['curso']) or isset($_GET['leccion']))?'class="tab-pane active"':'class="tab-pane"') ?>>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-10">

          <div class="row" >

            <?php
             if(isset($_GET['category'])){
                $sql = 'SELECT idCurso,tit_c,portada_c,nombre FROM CURSOS c  INNER JOIN USUARIOS u ON c.instructor_c=u.idUsuario WHERE estado_c=3 AND categ_c=?';
             }elseif (isset($_GET['nivel'])){
                 $sql = 'SELECT idCurso,tit_c,portada_c,nombre FROM CURSOS c  INNER JOIN USUARIOS u ON c.instructor_c=u.idUsuario WHERE estado_c=3 AND nivel_c=?';
             }else{
               $sql = 'SELECT idCurso,tit_c,portada_c,nombre FROM CURSOS c  INNER JOIN USUARIOS u ON c.instructor_c=u.idUsuario WHERE estado_c=3';
             }




             if($buscar_cursos=$conn->prepare($sql)){


               if(isset($_GET['category'])){
                  $buscar_cursos->bind_param('i',$_GET['category']);
               }elseif (isset($_GET['nivel'])) {
                 $buscar_cursos->bind_param('i',$_GET['nivel']);
               }

                $buscar_cursos->execute();
                $buscar_cursos->store_result();
                if($buscar_cursos->num_rows > 0){
                  $buscar_cursos->bind_result($idCurso,$tit_c,$portada_c,$autor);
                  while($buscar_cursos->fetch()){
                    echo '<div class="col-md-4  my-2">

                      <div class="card"> <a href="?curso='.$idCurso.'"> <img class="card-img-top" src="../'.$portada_c.'" alt="Card image cap" width="500" height="200" ></a>
                          <div class="card-body">
                            <h5 class="card-title">'.$tit_c.'</h5>

                          </div>
                      </div>
                    </div>

                    ';

                  }

                }

             }
             $buscar_cursos->free_result();
             $buscar_cursos->close();


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
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active"> Niveles</a>
            <?php
            if($buscar_nivel= $conn->prepare('SELECT * FROM NIVELES WHERE idNivel!=1')){
              $buscar_nivel->execute();
              $buscar_nivel->store_result();
              if($buscar_nivel->num_rows > 0){
                $buscar_nivel->bind_result($idnivel,$nivel);
                while($buscar_nivel->fetch()){
                  echo '<a href="?nivel='.$idnivel.'" class="list-group-item list-group-item-action">'.$nivel.'</a>';
                }
                $buscar_nivel->free_result();

              }
            }
            $buscar_nivel->close();
             ?>

          </div>
        </div>
      </div>
    </div>
  </div>
<!--Panel Leccion-->
<div  <?php echo ((isset($_GET['curso']) and isset($_GET['leccion']))?'class="tab-pane active"':'class="tab-pane"') ?>>
  <?php
  if($datos_leccion = $conn->prepare('SELECT tit_l,descrip_l,cont_l FROM LECCIONES WHERE idLeccion=? AND idCurso_l=?')){

    $datos_leccion->bind_param('ii',$_GET['leccion'],$_GET['curso']);
    $datos_leccion->execute();
    $datos_leccion->store_result();
    if($datos_leccion->num_rows > 0){

      $datos_leccion->bind_result($tit_l,$descrip_l,$cont_l);
      $datos_leccion->fetch();

    }else{
      echo "dd";

        //die("Por el momento no hay contenido disponible");

    }
    $datos_leccion->free_result();
    $datos_leccion->close();

  }

   ?>


  <div class="py-5" >
     <div class="container">
       <div class="row">
         <div class="col-md-12">
           <h1 class=""><?php echo $tit_l ?></h1>
         </div>
       </div>
     </div>
     <div class="container">
       <div class="row">
         <div class="col-md-12">
           <div class="embed-responsive embed-responsive-16by9">
             <iframe <?php echo 'src="https://www.youtube.com/embed/'.$cont_l.'?controls=0"' ?> allowfullscreen="" class="embed-responsive-item"></iframe>
           </div>
         </div>
       </div>
     </div>
     <div class="container">
       <div class="row my-2">
         <div class="col-md-6">
         <?php
         if($anterior=$conn->prepare('SELECT idLeccion FROM LECCIONES WHERE idCurso_l=? AND idLeccion = (SELECT MAX(idLeccion) FROM LECCIONES WHERE idLeccion < ?)')){
           $anterior->bind_param('ii',$_GET['curso'],$_GET['leccion']);
           $anterior->execute();
           $anterior->store_result();
           if($anterior->num_rows > 0){
             $anterior->bind_result($idleccion);
             $anterior->fetch();
             echo '<a class="btn btn-dark" href="?curso='.$_GET['curso'].'&leccion='.$idleccion.'">Anterior</a>';
           }

          }

          $anterior->free_result();
          $anterior->close();
          ?>
          </div>

          <div class="col-md-6  text-right">
          <?php
          if($siguiente=$conn->prepare('SELECT idLeccion FROM LECCIONES WHERE idCurso_l=? AND idLeccion = (SELECT MIN(idLeccion) FROM LECCIONES WHERE idLeccion > ?)')){
            $siguiente->bind_param('ii',$_GET['curso'],$_GET['leccion']);
            $siguiente->execute();
            $siguiente->store_result();
            if($siguiente->num_rows > 0){
              $siguiente->bind_result($idleccion);
              $siguiente->fetch();
              echo '<a class="btn btn-dark" href="?curso='.$_GET['curso'].'&leccion='.$idleccion.'">Siguiente</a>';
            }

           }

           $siguiente->free_result();
           $siguiente->close();
           ?>
           </div>





       </div>
     </div>
     <div class="container">
       <div class="row">
         <div class="col-md-12">
           <div class="card">
             <div class="card-header"> Descripcion</div>
             <div class="card-body">
               <p><?php echo $descrip_l ?></p>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>



</div>

 <!--Panel datos de curso-->
 <div  <?php echo ((isset($_GET['curso']) and !isset($_GET['leccion']))?'class="tab-pane active"':'class="tab-pane"') ?>>
   <?php
   if(isset($_GET['curso'])){
     if($datos_curso = $conn->prepare('SELECT tit_c,descrip_c,num_est,categ,nivel,portada_c,estado_c FROM CURSOS c INNER JOIN CATEGORIAS ca ON ca.idCategoria=c.categ_c INNER JOIN NIVELES n ON n.idNivel=c.nivel_c  WHERE idCurso=? and estado_c=3')){
        $datos_curso->bind_param('i',$_GET['curso']);
        $datos_curso->execute();
        $datos_curso->store_result();
        if($datos_curso->num_rows > 0){
          $datos_curso->bind_result($titulo,$descripcion,$num_est,$categoria,$nivel,$portada_curso,$estado);
          $datos_curso->fetch();
          $datos_curso->free_result();
        }else{
          die("Por el momento este curso no se encuentra disponible");

        }
        $datos_curso->close();

     }




   }

    ?>


   <div class="text-center text-white align-items-center d-flex h-50" style="	background-image: linear-gradient(to bottom, rgba(0, 0, 0, .75), rgba(0, 0, 0, .75)), <?php echo 'url(../'.$portada_curso.');'  ?>	background-position: center center, center center;	background-size: cover, cover;	background-repeat: repeat, repeat;">
       <div class="container py-5">
         <div class="row">
           <div class="mx-auto col-lg-8 col-md-10">
             <h1 class="mb-4 text-left display-4"><?php echo $titulo; ?></h1>
             <h4 class="text-left">Nivel: <?php echo $nivel; ?></h4>
             <h4 class="text-left">Categoria: <?php echo $categoria; ?></h4>
             <h4 class="text-left" > Estudiantes registrado: <?php echo $num_est; ?></h4>
             <form method="post">
               <?php
               if(isset($_SESSION['sesion'])){
                 if($inscripto = $conn->prepare('SELECT * FROM INSCRIPCIONES WHERE idUsuario_i=? AND idCurso_i=?')){
                   $inscripto->bind_param('ii',$_SESSION['id'],$_GET['curso']);
                   $inscripto->execute();
                   $inscripto->store_result();

                   if($inscripto->num_rows > 0){
                     echo '<button type="submit" class="btn btn-lg mx-1 border-light btn-outline-light"  value="desuscribirse" name="action" style="">SUSCRITO</button>';

                   }else{
                     echo '<button class="btn btn-lg mx-1 border-light btn-outline-light"  value="inscribirse" name="action" style="">SUSCRIBIRSE</button>';
                   }


                 }
                 $inscripto->free_result();
                 $inscripto->close();

               }else{
                 echo '<a class="btn btn-lg mx-1 border-light btn-outline-light" href="https://google.com" style="">REGISTRARTE GRATIS</a>';
               }

                ?>

           </form>
           </div>
         </div>
       </div>
     </div>
     <div class="py-5">
       <div class="container">
         <div class="row">
           <div class="col-md-12">
             <div class="card">
               <div class="card-body">
                 <h3 class="card-title">Acerca de curso</h3>
                 <p class="card-text"> <?php echo $descripcion; ?> </p>
               </div>
               <div class="card-body">
                 <h3 class="card-title">Lecciones</h3>
               </div>
               <ul class="list-group list-group-flush">
                 <?php
                   if($estado==3){
                     if($buscar_lecciones = $conn->prepare('SELECT idLeccion,tit_l FROM LECCIONES WHERE idCurso_l=?')){
                        $curso = $_GET['curso'];
                        $buscar_lecciones->bind_param('i',$curso);
                        $buscar_lecciones->execute();
                        $buscar_lecciones->store_result();

                        if($buscar_lecciones->num_rows > 0){

                          $buscar_lecciones->bind_result($idleccion,$tit_l);

                          while($buscar_lecciones->fetch()){

                             echo '<a href="?curso='.$_GET['curso'].'&leccion='.$idleccion.'"  class="list-group-item list-group-item-action"> '.$tit_l.' </a>';
                          }
                        }else{
                          die("Por el momento no hay contenido disponible");
                        }
                     }
                     $buscar_lecciones->free_result();
                     $buscar_lecciones->close();
                   }
                  ?>


               </ul>
             </div>
           </div>
         </div>
       </div>
     </div>




 </div>





</div>





  <!-- libreria bootstrap y jquery -->
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery-3.4.1.slim.js"></script>

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
