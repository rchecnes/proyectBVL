<?php
session_start();
?>
<header>
    <nav class="navbar navbar-default" role="navigation">
      <!-- El logotipo y el icono que despliega el menú se agrupan
           para mostrarlos mejor en los dispositivos móviles -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse"
                data-target=".navbar-ex1-collapse">
          <span class="sr-only">Desplegar navegación</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Logotipo</a>
      </div>
     
      <!-- Agrupar los enlaces de navegación, los formularios y cualquier
           otro elemento que se pueda ocultar al minimizar la barra -->
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
          <li><a href="../Controller/CotizacionC.php?accion=index">Importar De BVL</a></li>
          <li><a href="../Controller/EmpresaC.php?accion=index">Empresa</a></li>
          <li><a href="../Controller/GraficoC.php?accion=index">Gráficos</a></li>
          <li><a href="../Controller/FavoritoC.php?accion=index">Favoritos</a></li>
          <li><a href="../Controller/LoginC.php?accion=logout" role="button" style="color:red">Cerrar Sesión</a></li>
          <li><a href="#" role="button"><strong>Usuario:<?=strtoupper($_SESSION['nomb_user']);?></strong></a></li>
        </ul>
     
        <!--<form class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Buscar">
          </div>
          <button type="submit" class="btn btn-default">Enviar</button>
        </form>-->
      </div>
    </nav>
</header>