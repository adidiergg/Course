<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/fontawesome/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="css/theme.css">
  <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.4.1.slim.js"></script>



</head>


<body>


<?php
session_start();
require_once("conexion.php");
include "modulos/navbar.php";

if(!isset($_SESSION['sesion'])){
  include "inicio.html";
}

 ?>
 <div class="py-5">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
         <?php
         if(isset($_SESSION['sesion'])){
        echo '<h3 class="text-center">Cursos en los que está inscripto </h3>';
          }
         ?>

         <div class="row" >

           <?php
           if(isset($_SESSION['sesion'])){

            if($cursos_inscripto=$conn->prepare('SELECT idCurso,tit_c,portada_c FROM INSCRIPCIONES i INNER JOIN CURSOS c ON i.idCurso_i=c.idCurso  WHERE idUsuario_i=?')){

               $cursos_inscripto->bind_param('i',$_SESSION['id']);
               $cursos_inscripto->execute();
               $cursos_inscripto->store_result();
               if($cursos_inscripto->num_rows > 0){
                 $cursos_inscripto->bind_result($idCurso,$tit_c,$portada_c);
                 while($cursos_inscripto->fetch()){
                   echo '<div class="col-md-4  my-2">

                     <div class="card"> <a href="curso/?curso='.$idCurso.'"> <img class="card-img-top" src="'.$portada_c.'" alt="Card image cap" width="500" height="200" ></a>
                         <div class="card-body">
                           <h6 class="card-title">'.$tit_c.'</h6>
                         </div>
                     </div>
                   </div>

                   ';

                 }

               }

            }

            $cursos_inscripto->free_result();
            $cursos_inscripto->close();

          }


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
         <p class="mb-0">© 2019 POLICURSOS. DERECHOS RESERVADOS</p>
       </div>
     </div>
   </div>
 </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" style=""></script>
</body>

</html>
