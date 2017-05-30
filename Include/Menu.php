<?php
session_start();
?>
<!--<header>
    <nav class="navbar navbar-default" role="navigation">
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
     

      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
          <li><a href="../Controller/CotizacionC.php?accion=index">Importar De BVL</a></li>
          <li><a href="../Controller/EmpresaC.php?accion=index">Empresa</a></li>
          <li><a href="../Controller/GraficoC.php?accion=index">Gráficos</a></li>
          <li><a href="../Controller/FavoritoC.php?accion=index">Favoritos</a></li>
          <li><a href="../Controller/LoginC.php?accion=logout" role="button" style="color:red">Cerrar Sesión</a></li>
          <li><a href="#" role="button"><strong>Usuario:<?=strtoupper($_SESSION['nomb_user']);?></strong></a></li>
        </ul>
      </div>
    </nav>
</header>-->

<header>
<nav class="navbar navbar-default" role="navigation">
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
      <?php if($_SESSION['nom_role']=='ROLE_ADMIN'):?>
        <li class="active"><a href="../Controller/CotizacionC.php?accion=index">Importar De BVL</a></li>
        <li><a href="../Controller/EmpresaC.php?accion=index">Empresa</a></li>
        <li><a href="../Controller/GraficoC.php?accion=index">Gráficos</a></li>
        <li><a href="../Controller/FavoritoC.php?accion=index">Favoritos</a></li>
        <li><a href="../Controller/ComisionC.php?accion=index">Comisión</a></li>
      <?php elseif($_SESSION['nom_role']=='ROLE_ANONIMO'):?>
        <li><a href="../Controller/GraficoC.php?accion=index">Gráficos</a></li>
        <li><a href="../Controller/FavoritoC.php?accion=index">Favoritos</a></li>
      <?php endif; ?>
      <!--<li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          Menú #1 <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">Acción #1</a></li>
          <li><a href="#">Acción #2</a></li>
          <li><a href="#">Acción #3</a></li>
          <li class="divider"></li>
          <li><a href="#">Acción #4</a></li>
          <li class="divider"></li>
          <li><a href="#">Acción #5</a></li>
        </ul>
      </li>-->
    </ul>
 
    <ul class="nav navbar-nav navbar-right">
      <li><span><strong>Usuario:<?=strtoupper($_SESSION['nomb_user'])." ".$_SESSION['desc_role'];?></strong></span></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          &nbsp;<b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="javascript:location.reload()"><i class="ace-icon fa fa-refresh fa-2x"></i>&nbsp;&nbsp;Actualizar</a></li>
          <li><a href="../Controller/LoginC.php?accion=logout"><i class="ace-icon fa fa-power-off fa-2x"></i>&nbsp;&nbsp;Cerra Sesión</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
</header>