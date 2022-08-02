<?php
if($buscar_nivel= $conn->prepare('SELECT * FROM NIVELES')){
  $buscar_nivel->execute();
  $buscar_nivel->store_result();
  if($buscar_nivel->num_rows > 0){
    $buscar_nivel->bind_result($idnivel,$nivel);
    while($buscar_nivel->fetch()){
      echo '<option value="'.$idnivel.'">'.$nivel.'</option>';
    }
    $buscar_nivel->free_result();

  }
}
$buscar_nivel->close();
 ?>
