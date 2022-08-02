
<?php

if(!isset($_SESSION['sesion'])){
  echo '<nav class="navbar navbar-expand-lg  ">
    <div class="container-fluid"> <a class="navbar-brand text-dark" href="http://localhost/cu/">
        <img class="logo" src="http://localhost/cu/logo.png">
        <b>POLICURSOS</b>
      </a> <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar4" style="">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar4">
        <ul class="navbar-nav ml-0">
          <li class="nav-item text-primary"> <a class="nav-link" href="http://localhost/cu/curso">Cursos</a> </li>
          <li class="nav-item"> <a class="nav-link" href="http://localhost/cu/blog">Blog</a> </li>
        </ul>
        <ul class="navbar-nav ml-auto"></ul>
        <a href="http://localhost/cu/login.php" class="btn navbar-btn ml-md-2 btn-light">Iniciar Sesión</a><a href="http://localhost/cu/registro.php" class="btn navbar-btn ml-md-2 btn-light">Crear Cuenta</a>
      </div>
    </div>
  </nav>
  ';
}else{

  echo ' <nav class="navbar navbar-expand-lg ">
    <div class="container-fluid"> <a class="navbar-brand text-dark" href="http://localhost/cu/">
        <img class="logo" src="http://localhost/cu/logo.png">
        <b>POLICURSOS</b>
      </a> <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar4" style="">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar4">
        <ul class="navbar-nav">
          <li class="nav-item"> <a class="nav-link" href="http://localhost/cu/curso#">Cursos</a> </li>
          <li class="nav-item"> <a class="nav-link" href="http://localhost/cu/blog">Blog</a> </li>
        </ul>

        <ul class="navbar-nav ml-auto">
          '.((!isset($_SESSION['sesion_instructor']))?'':
          '<li class="nav-item"> <a class="nav-link" href="http://localhost/cu/instructor"><i class="fas fa-chalkboard-teacher"></i> Instructor</a> </li>')
          .((!isset($_SESSION['sesion_editor']))?'':
          '<li class="nav-item"> <a class="nav-link" href="http://localhost/cu/editor"><i class="fas fa-book"></i> Editor</a> </li>')
          .((!isset($_SESSION['sesion_administrador']))?'':'<li class="nav-item"> <a class="nav-link" href="http://localhost/cu/admin"><i class="fa fa-fw fa-lock"></i>Administracion</a> </li>')
          .'
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-fw"></i> '. $_SESSION['nombre'].'</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="http://localhost/cu/perfil">Perfil</a>
              <a class="dropdown-item" href="http://localhost/cu/perfil/configuracion.php">Configuración</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="http://localhost/cu/logout.php">Cerrar sesión</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>';


}

 ?>
