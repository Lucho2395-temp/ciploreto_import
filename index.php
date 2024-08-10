
<?php

require_once 'autoload.php';

if(isset($_GET['url'])) {
    $url = explode('/', $_GET['url']);
    $controllerName = ucfirst($url[0]) . 'Controller';
    $action = isset($url[1]) ? $url[1] : 'index';

    if(class_exists($controllerName)) {
        $controller = new $controllerName();
        if(method_exists($controller, $action)) {
            $controller->$action();
        } else {
            echo "Método no encontrado";
        }
    } else {
        echo "Controlador no encontrado";
    }
} else {
    require 'header.php';
    ?>
    <body>
        <div>
            <h3>Ir a ver Capitulos</h3>
            <a href="<?= $dominio;?>Afiliado/mostrarCapitulos" class="button">CAPITULOS</a>
        </div>
        <br>
        <div>
            <h3>Ir a ver Especialidades</h3>
            <a href="<?= $dominio;?>Afiliado/mostrarEspecialidades" class="button">ESPECIALIDADES</a>

        </div>
        <br>
        <div>
            <h3>Ver Afiliados</h3>
            <a href="<?= $dominio;?>Afiliado/mostrarAfiliados" class="button">AFILIADOS</a>

        </div>
        <br>
        <h3>Adjuntar CSV EXCEL para Importar</h3>
        <a href="<?= $dominio;?>Afiliado/adjuntar_excel" style="background: green" class="button">Ir a adjuntar</a>
        <br>
        

    </body>
    <?php
}


/* require_once 'autoload.php';

if(isset($_GET['controller'])){
	$nombre_controlador = $_GET['controller'].'Controller';
}else{
	echo "La pagina que buscas no existe";
	exit();
}

if(class_exists($nombre_controlador)){	
	$controlador = new $nombre_controlador();
	
	if(isset($_GET['action']) && method_exists($controlador, $_GET['action'])){
		$action = $_GET['action'];
		$controlador->$action();
	}else{
		echo "La pagina que buscas no existe";
	}
}else{
	echo "La pagina que buscas no existe";
} */

