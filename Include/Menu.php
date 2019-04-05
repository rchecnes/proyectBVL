<?php
if(!isset($_SESSION)){
  session_start();
}

$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);
$url = str_replace(" ","",$url);
//echo $url."<br>";
//echo $_SERVER["REQUEST_URI"]
?>

<header>
<nav class="navbar navbar-default menu-header" role="navigation">
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
        <li class="<?=($url=='/Controller/CotizacionC.php?accion=index')?'active':''?>"><a href="../Controller/CotizacionC.php?accion=index">Importar De BVL</a></li>
        <li class="<?=($url=='/Controller/CierreDelDiaC.php?accion=index')?'active':''?>"><a href="../Controller/CierreDelDiaC.php?accion=index">Cierre Del Dia</a></li>
        <li class="<?=($url=='/Controller/EmpresaC.php?accion=index')?'active':''?>"><a href="../Controller/EmpresaC.php?accion=index">Empresa</a></li>
        <li class="<?=($url=='/Controller/GraficoC.php?accion=index')?'active':''?>"><a href="../Controller/GraficoC.php?accion=index">Gráficos</a></li>
        <li class="<?=($url=='/Controller/FavoritoC.php?accion=index')?'active':''?>"><a href="../Controller/FavoritoC.php?accion=index">Favoritos</a></li>
        <li class="<?=($url=='/Controller/ComisionC.php?accion=index')?'active':''?>"><a href="../Controller/ComisionC.php?accion=index">Comisión</a></li>
        <li class="<?=($url=='/Controller/SimuladorC.php?accion=index')?'active':''?>"><a href="../Controller/SimuladorC.php?accion=index">Simulador</a></li>
        <li class="<?=($url=='/Controller/PortafolioC.php?accion=index')?'active':''?>"><a href="../Controller/PortafolioC.php?accion=index">Portafolio</a></li>
        <li class="<?=($url=='/Controller/DepositoEmpresaC.php?accion=index')?'active':''?>"><a href="../Controller/DepositoEmpresaC.php?accion=index">Deposito - Plazo</a></li>
      <?php elseif($_SESSION['nom_role']=='ROLE_ANONIMO'):?>
        <li class="<?=($url=='/Controller/GraficoC.php?accion=index')?'active':''?>"><a href="../Controller/GraficoC.php?accion=index">Gráficos</a></li>
        <li class="<?=($url=='/Controller/FavoritoC.php?accion=index')?'active':''?>"><a href="../Controller/FavoritoC.php?accion=index">Favoritos</a></li>
        <li class="<?=($url=='/Controller/SimuladorC.php?accion=index')?'active':''?>"><a href="../Controller/SimuladorC.php?accion=index">Simulador</a></li>
        <li class="<?=($url=='/Controller/PortafolioC.php?accion=index')?'active':''?>"><a href="../Controller/PortafolioC.php?accion=index">Portafolio</a></li>
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