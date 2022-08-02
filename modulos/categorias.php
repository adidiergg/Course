<?php
if($buscar_categoria= $conn->prepare('SELECT * FROM CATEGORIAS')){
  $buscar_categoria->execute();
  $buscar_categoria->store_result();
  if($buscar_categoria->num_rows > 0){
    $buscar_categoria->bind_result($idcateg,$categ);
    while($buscar_categoria->fetch()){
      echo '<option value="'.$idcateg.'">'.$categ.'</option>';
    }
    $buscar_categoria->free_result();

  }

}
$buscar_categoria->close();
?>
