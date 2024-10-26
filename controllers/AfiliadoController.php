<?php

class AfiliadoController {
	

    public function mostrarCapitulos(){
        require_once 'models/afiliado.php';
		
		$afiliado = new Afiliado();
		
		//$list_capitulos = $afiliado->conseguirTodos('capitulos');
		$list_capitulos = $afiliado->listar_capitulos();
		
		require 'header.php';
		require_once 'views/afiliado/mostrar_capitulos.php';
		require 'footer.php';
    }

    public function mostrarEspecialidades(){
        require_once 'models/afiliado.php';
		
		$afiliado = new Afiliado();
		
		//$list_capitulos = $afiliado->conseguirTodos('capitulos');
		$list_especialidades = $afiliado->listar_especialidades();
		
		require_once 'header.php';
		require_once 'views/afiliado/mostrar_especialidades.php';
		require 'footer.php';
    }
    public function mostrarAfiliados(){
        require_once 'models/afiliado.php';

		$afiliado = new Afiliado();

		//$list_capitulos = $afiliado->conseguirTodos('capitulos');
		$list_afiliados = $afiliado->listar_afiliados();

		require_once 'header.php';
		require_once 'views/afiliado/mostrar_afiliados.php';
		require 'footer.php';
    }

    public function adjuntar_excel(){
        
		require_once 'header.php';
		require_once 'views/afiliado/adjuntar_excel.php';
		require 'footer.php';
    }

    public function importar_Archivo(){
        $dominio = 'http://127.0.0.1/ciploreto_import/';
        $afiliado = new Afiliado();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
            $archivo = $_FILES['archivo'];

            // Verificar si el archivo es CSV
            $fileType = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            if ($fileType != 'csv') {
                echo "Solo se permiten archivos CSV.";
                return;
            }

            // Ruta donde se guardará el archivo temporalmente
            $targetDir = "./media/";
            $targetFile = $targetDir . basename($archivo["name"]);
            $fecha_hora_hoy = date('Y-m-d H:i:s');
            $fecha_hoy = date('Y-m-d');
            //VARIABLE PARA GUARDAR EN TABLA DEUDAS
            $fecha_limite = date('Ym');

            if($_POST['tipo_tabla']==1){
                //SUBIR DATOS AFILIADOS
                // Mover el archivo a la carpeta de destino
                if (move_uploaded_file($archivo["tmp_name"], $targetFile)) {
                    echo "El archivo " . basename($archivo["name"]) . " ha sido subido.";

                    // Procesar el archivo CSV
                    if (($handle = fopen($targetFile, "r")) !== FALSE) {
                        // Obtener la primera fila como los nombres de las columnas
                        //$headers = fgetcsv($handle, 1000, ",");

                        $model = new Afiliado();
                        //IMPORTAR AFILIADOS
                        // Leer cada fila del CSV
                        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            // Asignar valores a variables
                            $cip = $data[1];
                            if($cip=='47911'){
                                $entra = true;
                            }
                            $tipo_documento = 2;
                            $nombre_completo = $data[2];
                            $resultado = $this->separarNombreCompleto($nombre_completo);
                            $apellidos = $resultado['apellidos'];
                            $nombres = $resultado['nombres'];
                            $capitulo_nombre = $data[3];
                            $id_capitulo = $this->conseguir_id_capitulo($capitulo_nombre);
                            $especialidad = $data[4];
                            $id_especialidad = $this->conseguir_array_especialidad($especialidad);
                            $sexo = ($data[5]=='F')?'F':'M';
                            $fecha_naci = $this->obtener_fecha($data[6]);
                            if(empty($id_capitulo) || empty($id_especialidad)){
                                echo "ERROR CAPITULO O ESPECIALIDAD <br>";
                                echo $cip."<br>";
                                echo $capitulo_nombre."<br>";
                                echo $especialidad."<br>";
                                break;

                            }
                            
                            $dni = $data[7];
                            $direccion = $data[8];
                            $telefono = $data[9];
                            $email = $data[10];
                            $sede = 'LORETO';
                            $condicion = $data[12];
                            if($condicion == 'ORDINARIO'){
                                $tipo_condicion = 1;
                            }else if($condicion == 'VITALICIO'){
                                $tipo_condicion = 2;
                            }else{
                                $tipo_condicion = 3;
                            }
                            $fecha_incor = $this->obtener_fecha($data[13]);
                            $estado = ($data[14]=='F')?'F':'A';
                            $dependencia = ($data[15]=='UNAP')?'1':'';
                            $estadoFinal  = 1;
                            $id_user_register  = 1;
                            $us_microtime = microtime();
                            $created_at   = $fecha_hora_hoy;
                            $updated_at   = $fecha_hora_hoy;
                            // Agrega más campos según tus necesidades
                            echo "<br>ESPECIALIDADES: <br>";
                            $conteo = 1;
                            for ($i=0; $i < count($id_especialidad); $i++) { 
                                echo "id ".$conteo.": $id_especialidad[$i]<br>";
                            }
                            echo "Codigo CIP: $cip<br>";
                            echo "Nombre Completo: $nombre_completo<br>";
                            echo "Apellidos: " . $apellidos . "<br>";
                            echo "Nombres: " . $nombres . "<br>";
                            echo "capitulo_nombre: " . $capitulo_nombre . "<br>";
                            echo "especialidad: " . $especialidad . "<br>";
                            echo "sexo: " . $sexo . "<br>";
                            echo "fecha_naci: " . $fecha_naci . "<br>";
                            echo "dni: " . $dni . "<br>";
                            echo "direccion: " . $direccion . "<br>";
                            echo "telefono: " . $telefono . "<br>";
                            echo "email: " . $email . "<br>";
                            echo "sede: " . $sede . "<br>";
                            echo "condicion: " . $condicion . "<br>";
                            echo "fecha_incor: " . $fecha_incor . "<br>";
                            echo "estado: " . $estado . "<br>";
                            echo "dependencia: " . $dependencia . "<br><br>";
                            // Insertar datos en la base de datos

                            //$result = $afiliado->guardar_afiliado($tipo_documento,$id_capitulo,$dni,$apellidos,$nombres,$cip,$tipo_condicion,$fecha_incor,$estado,$sexo,$fecha_naci,$direccion,$telefono,$email,$sede,$dependencia,$estadoFinal,$id_user_register,$us_microtime,$created_at,$updated_at);
                            $model->id_tipo_documento = $tipo_documento;
                            $model->id_capitulo  = $id_capitulo ;
                            $model->afiliado_numeroDoc = $dni;
                            $model->afiliado_apellidos = $apellidos;
                            $model->afiliado_nombres = $nombres;
                            $model->afiliado_cip = $cip;
                            $model->afiliado_condicion = $tipo_condicion;
                            $model->afiliado_fechaIncorporacion = (!empty($fecha_incor))?$fecha_incor:null;
                            $model->afiliado_estado = $estado;
                            $model->afiliado_sexo = $sexo;
                            $model->afiliado_fechaNacimiento = (!empty($fecha_naci))?$fecha_naci:null;
                            $model->afiliado_direccion = $direccion;
                            $model->afiliado_telefono = $telefono;
                            $model->afiliado_email = $email;
                            $model->afiliado_sede = $sede;
                            $model->id_dependencia = (!empty($dependencia))?$dependencia:null;
                            $model->afiliado_estadoFinal = $estadoFinal;
                            $model->id_user_register = $id_user_register;
                            $model->us_microtime = $us_microtime;
                            $model->created_at = $created_at;
                            $model->updated_at = $updated_at;
                            $result = $afiliado->guardar_afiliado($model);
                            if($result == 1){
                                echo "<br>----Guardado con Exito los afiiados----";
                                $dato_afiliado = $afiliado->listar_afiliado_x_mt($us_microtime);
                                if(!empty($dato_afiliado)){
                                    $id_afiliado = $dato_afiliado['id_afiliado'];
                                    $conteo = 1;
                                    for ($i=0; $i < count($id_especialidad); $i++) {
                                        $id_espe = $id_especialidad[$i];
                                        $afiliados_especialidad_estado = 1;
                                        $espe_created_at = $created_at;
                                        $espe_updated_at = $updated_at;
                                        $result = $afiliado->guardar_afiliado_especialidades($id_afiliado,$id_espe,$afiliados_especialidad_estado,$espe_created_at,$espe_updated_at);

                                    }
                                }
                            }else{
                                echo "<br>----No encuentra -----".$cip;
                            }
                            
                        }
                        
                        fclose($handle);
                    } else {
                        echo "No se puede abrir el archivo.";
                    }

                    // Eliminar el archivo temporal
                    unlink($targetFile);
                } else {
                    echo "Error al subir el archivo.";
                }
            }else{
                //SUBIR PAGOS A LA TABLA DEUDAS
                // Mover el archivo a la carpeta de destino
                if (move_uploaded_file($archivo["tmp_name"], $targetFile)) {
                    echo "El archivo " . basename($archivo["name"]) . " ha sido subido.";

                    // Abrir el archivo CSV
                    if (($handle = fopen($targetFile, 'r')) !== FALSE) {
                        // Leer encabezados (e.g., CIP, 1992, 1993, ...)
                        $header = fgetcsv($handle, 1500, ";");
                        // Recorrer cada línea (registro)
                        while (($data = fgetcsv($handle, 1500, ';')) !== FALSE) {
                            // Tomamos el código CIP (primer campo de cada fila)
                            $codigo_cip = $data[0];
                            // Obtener el id_afiliado a partir del código CIP
                            $afialiado_dato = $afiliado->listar_afiliado_x_codigo_cip($codigo_cip);
                            $id_afiliado = $afialiado_dato['id_afiliado'];
                            // Si no encuentra el id_afiliado, continuar con la siguiente fila
                            if (!empty($id_afiliado)) {
                                // Procesar los pagos año a año hasta 2009
                                if(!empty($afialiado_dato['afiliado_fechaIncorporacion'])){
                                    $anho_incorporacion = date('Y', strtotime($afialiado_dato['afiliado_fechaIncorporacion']));
                                    $mes_incorporacion = date('n', strtotime($afialiado_dato['afiliado_fechaIncorporacion'])); // Mes sin ceros a la izquierda
                                }else{
                                    $anho_incorporacion = '';
                                    $mes_incorporacion = ''; // Mes sin ceros a la izquierda
                                }
                                for ($i = 1; $i <= 18; $i++) { // De 1992 a 2009
                                    $anho = $header[$i];
                                    $entr = true;
                                    $deuda_costo = '';
                                    if(!empty($afialiado_dato['afiliado_fechaIncorporacion'])){
                                        if($anho >= $anho_incorporacion){
                                            if($data[$i]==='0'){
                                                //SI ES 0 SIGNNIFICA QUE NO TIENE DEUDA Y NO DEBE REGISTAR NADA
                                                $entr = false;
                                            }else if(empty($data[$i])){
                                                $deuda_costo = $this->obtenerPrecio_anho($anho);
                                            }else{
                                                $deuda_costo = str_replace(',', '.', $data[$i]); // Ejemplo: asignar valor por defecto si está vacío
                                            }
                                            $deuda_estado_pagado = 1;
                                            $deuda_estado = 1;
                                            if($entr){
                                                $deuda_costo_mensual = $deuda_costo / 12;
                                                //RECORRO LOS 12 MESES
                                                for ($j=1; $j<=12; $j++){
                                                    if($anho == $anho_incorporacion) {
                                                        if($j >= $mes_incorporacion){
                                                            $entr_2 = true;
                                                        }else{
                                                            $entr_2 = false;
                                                        }
                                                    }else{
                                                        $entr_2 = true;
                                                    }
                                                    if($entr_2){
                                                        $mes = $j;
                                                        // Insertar en la base de datos
                                                        $result = $afiliado->guardar_deuda($id_afiliado, $anho, $mes, $deuda_costo_mensual, $deuda_estado_pagado, $deuda_estado);

                                                    }
                                                }
                                            }
                                        }
                                    }else{
                                        if($data[$i]==='0'){
                                            //SI ES 0 SIGNNIFICA QUE NO TIENE DEUDA Y NO DEBE REGISTAR NADA
                                            $entr = false;
                                        }else if(empty($data[$i])){
                                            $deuda_costo = $this->obtenerPrecio_anho($anho);
                                        }else{
                                            $deuda_costo = str_replace(',', '.', $data[$i]); // Ejemplo: asignar valor por defecto si está vacío
                                        }
                                        $deuda_estado_pagado = 1;
                                        $deuda_estado = 1;
                                        if($entr){
                                            $deuda_costo_mensual = $deuda_costo / 12;
                                            //RECORRO LOS 12 MESES
                                            for ($j=1; $j<=12; $j++){
                                                $mes = $j;
                                                // Insertar en la base de datos
                                                $result = $afiliado->guardar_deuda($id_afiliado, $anho, $mes, $deuda_costo_mensual, $deuda_estado_pagado, $deuda_estado);
                                            }
                                        }
                                    }
                                }

                                // Procesar los pagos mes a mes desde 2010
                                for ($i = 19; $i < count($header); $i++) {

                                    $anho_mes = $header[$i];
                                    $anho = '20'.substr($anho_mes, -2);
                                    //$anho = date('Y', strtotime($string_anho));
                                    $string_mes = substr($anho_mes, 0,-2);
                                    $mes = $this->convertirMes($string_mes);

                                    $fecha_limite_excel = $anho.str_pad($mes, 2, '0', STR_PAD_LEFT);
                                    $entr = true;
                                    $deuda_costo = '';
                                    //SI LA FECHA DE LA COLUMNA DEL EXCEL ES MENOR QUE LA FECHA DE HOY
                                    //SE TIENE QUE GUARDAR EN TABLA DEUDA SINO EN TABLA MENSUALIDAD
                                    //SI LA COLUMNA DEL EXCEL ES UNA FECHA ANTES DE LA ACTUAL VA A ENTRAR A LLENAR EN DEUDA
                                    if($fecha_limite_excel <= $fecha_limite){
                                        if(!empty($afialiado_dato['afiliado_fechaIncorporacion'])) {
                                            if ($anho >= $anho_incorporacion) {
                                                if ($data[$i] === '0') {
                                                    //SI ES 0 SIGNNIFICA QUE NO TIENE DEUDA Y NO DEBE REGISTAR NADA
                                                    $entr = false;
                                                } else if (empty($data[$i])) {
                                                    $deuda_costo = $this->obtenerPrecio_anho_mes($anho, $mes);
                                                } else {
                                                    $deuda_costo = str_replace(',', '.', $data[$i]); // Ejemplo: asignar valor por defecto si está vacío
                                                }
                                                $deuda_estado_pagado = 1;
                                                $deuda_estado = 1;
                                                if ($entr) {
                                                    if ($anho == $anho_incorporacion) {
                                                        if ($mes >= $mes_incorporacion) {
                                                            $entr_2 = true;
                                                        } else {
                                                            $entr_2 = false;
                                                        }
                                                    } else {
                                                        $entr_2 = true;
                                                    }
                                                    if ($entr_2) {
                                                        // Insertar en la base de datos
                                                        $result = $afiliado->guardar_deuda($id_afiliado, $anho, $mes, $deuda_costo, $deuda_estado_pagado, $deuda_estado);
                                                    }
                                                }
                                            }
                                        }else{
                                            if($data[$i]==='0'){
                                                //SI ES 0 SIGNNIFICA QUE NO TIENE DEUDA Y NO DEBE REGISTAR NADA
                                                $entr = false;
                                            }else if(empty($data[$i])){
                                                $deuda_costo = $this->obtenerPrecio_anho_mes($anho,$mes);
                                            }else{
                                                $deuda_costo = str_replace(',', '.', $data[$i]); // Ejemplo: asignar valor por defecto si está vacío
                                            }
                                            $deuda_estado_pagado = 1;
                                            $deuda_estado = 1;
                                            if($entr){
                                                $result = $afiliado->guardar_deuda($id_afiliado, $anho, $mes, $deuda_costo, $deuda_estado_pagado, $deuda_estado);
                                            }
                                        }
                                    }else{
                                        //GUARDAR PAGO EN MENSUALIDAD
                                        if(!empty($afialiado_dato['afiliado_fechaIncorporacion'])) {
                                            if ($anho >= $anho_incorporacion) {
                                                if($data[$i]==='0'){
                                                    //SI ES 0 SIGNNIFICA QUE NO TIENE DEUDA Y NO DEBE REGISTAR NADA
                                                    $entr = true;
                                                    $deuda_costo = $this->obtenerPrecio_anho_mes($anho,$mes);
                                                }else{
                                                    $entr = false;
                                                }
                                                $mensualidad_estado_pagado = 1;
                                                $mensualidad_estado = 1;
                                                if($entr){
                                                    if($anho == $anho_incorporacion) {
                                                        if($mes >= $mes_incorporacion){
                                                            $entr_2 = true;
                                                        }else{
                                                            $entr_2 = false;
                                                        }
                                                    }else{
                                                        $entr_2 = true;
                                                    }
                                                    if($entr_2){
                                                        // Insertar en la base de datos en MENSUALIDAD
                                                        $result = $afiliado->guardar_mensualidad($id_afiliado, $anho, $mes, $deuda_costo, $mensualidad_estado, $mensualidad_estado_pagado);
                                                        //$result = $afiliado->guardar_deuda($id_afiliado, $anho, $mes, $deuda_costo, $deuda_estado_pagado, $deuda_estado);
                                                    }
                                                }
                                            }
                                        }else{
                                            if($data[$i]==='0'){
                                                //SI ES 0 SIGNNIFICA QUE NO TIENE DEUDA Y NO DEBE REGISTAR NADA
                                                $entr = true;
                                                $deuda_costo = $this->obtenerPrecio_anho_mes($anho,$mes);
                                            }else{
                                                $entr = false;
                                            }
                                            $mensualidad_estado_pagado = 0;
                                            $mensualidad_estado = 1;
                                            if($entr){
                                                // Insertar en la base de datos en MENSUALIDAD
                                                $result = $afiliado->guardar_mensualidad($id_afiliado, $anho, $mes, $deuda_costo, $mensualidad_estado, $mensualidad_estado_pagado);
                                            }
                                        }
                                    }
                                }

                            }else{
                                echo "No se encontró el afiliado con CIP: $codigo_cip. Saltando fila. <br>";
                            }

                        }
                        fclose($handle);
                        echo "Datos importados correctamente.";


                    } else {
                        echo "Error al abrir el archivo CSV.";
                    }
                } else {
                    echo "Error al subir el archivo.";
                }
                //echo "La importacion de la Tabla aún no está lista.";
            }
        } else {
            echo "No se ha recibido ningún archivo.";
        }
		
    }
    function conseguir_id_capitulo($capitulo){
        if($capitulo == 'AGRONOMO' || $capitulo == 'AGRONOMOS'){
            $id_capitulo = 1;
        }else if($capitulo == 'AMBIENTALES Y AFINES'){
            $id_capitulo = 2;
        }else if($capitulo == 'CIVILES'){
            $id_capitulo = 3;
        }else if($capitulo == 'ECOLOGOS Y AFINES'){
            $id_capitulo = 4;
        }else if($capitulo == 'FORESTALES' || $capitulo == 'FORESTALES - CIVILES'){
            $id_capitulo = 5;
        }else if($capitulo == 'INDUSTRIAL Y AFINES' || $capitulo == 'INDUSTRIALES Y AFINES' || $capitulo == 'INDUSTRIALES Y AFINES - CIVIL' || $capitulo == 'EN INDUSTRIAS ALIMENTARIAS Y AFINES'){
            $id_capitulo = 6;
        }else if($capitulo == 'MECANICOS ELECTRICISTAS'){
            $id_capitulo = 7;
        }else if($capitulo == 'NO ESPECIFICADO'){
            $id_capitulo = 8;
        }else if($capitulo == 'QUIMICOS' || $capitulo == 'QUIMICOS - CIVILES'){
            $id_capitulo = 9;
        }else if($capitulo == 'SISTEMAS, INFORMATICA, COMPUTACION Y AFINES'){
            $id_capitulo = 10;
        }
        /* switch($capitulo){
            case 'AGRONOMO':
                $capi_name = '';
                $id_capitulo = 1;
                break;
            case 'AGRONOMO':
                $capi_name = '';
                $id_capitulo = 1;
                break;
            case 'AGRONOMO':
                $capi_name = '';
                $id_capitulo = 1;
                break;
            case 'AGRONOMO':
                $capi_name = '';
                $id_capitulo = 1;
                break;
            case 'AGRONOMO':
                $capi_name = '';
                $id_capitulo = 1;
                break;
            case 'AGRONOMO':
                $capi_name = '';
                $id_capitulo = 1;
                break;
            case 'AGRONOMO':
                $capi_name = '';
                $id_capitulo = 1;
                break;
        } */
       return $id_capitulo;
    }
    function conseguir_array_especialidad($especialidad){

        $afiliado = new Afiliado();
        $existe = $afiliado->listar_especialidad_x_nombre($especialidad);
        $ids_especialidades = [];
        if(!empty($existe)){
            $ids_especialidades[] = $existe['id_especialidad'];
        }else{
            //VAN TODOS LOS QUE NO SE ENCUENTRAN POR EL NOMBRE
            if($especialidad == "QUIMICA"){
                $nombre_especialidad = "QUIMICO";
                $existe = $afiliado->listar_especialidad_x_nombre($nombre_especialidad);
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
            }else if($especialidad == "AGROINDUSTRIAL, CIVIL"){
                $existe = $afiliado->listar_especialidad_x_nombre('AGROINDUSTRIAL');
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
                $existe = $afiliado->listar_especialidad_x_nombre('CIVIL');
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
            }else if($especialidad == "AGRONOMA"){
                $existe = $afiliado->listar_especialidad_x_nombre('AGRONOMO');
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
            }else if($especialidad == "FORESTAL - CIVIL" || $especialidad == 'FORESTAL -CIVIL' || $especialidad == 'INGENIERIA FORESTAL'){
                $existe = $afiliado->listar_especialidad_x_nombre('FORESTAL');
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
                $existe = $afiliado->listar_especialidad_x_nombre('CIVIL');
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
            }else if($especialidad == "INFORMATICO Y DE SISTEMAS"){
                $existe = $afiliado->listar_especialidad_x_nombre('INFORMATICA Y DE SISTEMAS');
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
            }else if($especialidad == "MECANICO DE FLUIDO"){
                $existe = $afiliado->listar_especialidad_x_nombre('MECANICO DE FLUIDOS');
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
            }else if($especialidad == "QUIMICO - CIVIL" || $especialidad == 'QUIMICO- CIVIL'){
                $existe = $afiliado->listar_especialidad_x_nombre('QUIMICO');
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
                $existe = $afiliado->listar_especialidad_x_nombre('CIVIL');
                if(!empty($existe)){
                    $ids_especialidades[] = $existe['id_especialidad'];
                }
            }
            
        }
        return $ids_especialidades;

    }

    function obtener_fecha($fecha){
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $fecha_convertida = '';
        if(!empty($fecha)){
            // Intentar convertir usando el formato 'd/m/Y' directamente
            $date = DateTime::createFromFormat('d/m/Y', $fecha);
            
            if ($date) {
                $fecha_convertida = $date->format('Y-m-d');
            } else {
                $date = DateTime::createFromFormat('d.m.Y', $fecha);
                if ($date) {
                    $fecha_convertida = $date->format('Y-m-d');
                }else{
                    // Reemplazar manualmente los meses en español con sus equivalentes en inglés
                    $meses = array(
                        'ene.' => 'Jan', 'feb.' => 'Feb', 'mar.' => 'Mar', 'abr.' => 'Apr',
                        'may.' => 'May', 'jun.' => 'Jun', 'jul.' => 'Jul', 'ago.' => 'Aug',
                        'sep.' => 'Sep', 'oct.' => 'Oct', 'nov.' => 'Nov', 'dic.' => 'Dec'
                    );
                    $fecha_sin_punto = str_replace(array_keys($meses), array_values($meses), $fecha);
                    
                    // Intentar convertir con el formato 'd-M-y' y meses en inglés
                    $date = DateTime::createFromFormat('d-M-y', $fecha_sin_punto);
                    
                    if ($date) {
                        // Ajustar manualmente el siglo si el año es mayor a 69
                        $year = $date->format('y');
                        if ($year < 70 && $year > 24) {
                            $date->modify('-100 years');
                        }
                        $fecha_convertida = $date->format('Y-m-d');
                    } else {
                        $errors = DateTime::getLastErrors();
                        print_r($errors); // Depurar errores
                        $fecha_convertida = ''; // O un valor por defecto si lo prefieres
                    }
                }
                
            }
        }

        return $fecha_convertida;
    }

    function separarNombreCompleto($nombreCompleto){
        // Definir los apellidos compuestos comunes
        $prefijos = ['DE', 'DEL', 'DE LA', 'DA', 'DE LOS', 'DE LAS', 'LA', 'DO'];
        $prefijos_raro = 'DE OLIVEIRA DIAZ';

        // Verificar si el nombre completo contiene el apellido buscado
        if (strpos(strtoupper($nombreCompleto), $prefijos_raro) !== false) {
            $tipo = 1;
        } else {
            $tipo = 2;
        }

        if($tipo == 1){
            $partes = explode($prefijos_raro, strtoupper($nombreCompleto));
            
            $apellidos = $partes[0].' '.$prefijos_raro;
            $nombres = $partes[1];

        }else{
            $partes = explode(' ', strtoupper($nombreCompleto));
    
            $apellidos = [];
            $all_name = [];
            $apell = '';
    
            foreach ($partes as $parte) {
                
                if (in_array($parte, $prefijos)) {
                    // Si encontramos un prefijo, lo añadimos junto con la siguiente palabra
                    //$apellidos[] = $parte;
                    $apell .= "$parte ";
                } else {
                    // Añadimos la palabra como apellido
                    $all_name[] = $apell.$parte;
                    $apell = "";
                }
    
                // Verificar si tenemos al menos 2 apellidos
                
            }
            $apellidos = $all_name[0].' '.$all_name[1];
            $nombres = implode(' ',array_slice($all_name, 2));
        }

        return [
            'apellidos' => $apellidos,
            'nombres' => $nombres
        ];
    }

    public function prueba_separarApellido(){
        $ejemplos = [
            'BARRERA SOMMO LLERMAN JOSE DAVID',
            'DE LA CRUZ VASQUEZ RICARDO JOSE',
            'REATEGUI DEL CASTILLO ARCADIO',
            'PINTO DE OLIVEIRA DIAZ RUSSELL',
            'SAAVEDRA DO SANTOS JAVIER ANTONIO',
            'DEL CASTILLO TORRES DENNIS',
            'DA SILVA DE OLIVEIRA DIAZ RENZO GIANCARLO'
        ];
        
        foreach ($ejemplos as $ejemplo) {
            $resultado = $this->separarNombreCompleto($ejemplo);
            echo "Nombre Completo: $ejemplo<br>";
            echo "Apellidos: " . $resultado['apellidos'] . "<br>";
            echo "Nombres: " . $resultado['nombres'] . "<br><br>";
        }
    }

    // Función para convertir letras de meses a números
    function convertirMes($letraMes) {
        $meses = ['E' => '1', 'F' => '2', 'M' => '3', 'A' => '4', 'MA' => '5','MY' => '5', 'J' => '6',
            'JU' => '7', 'JL' =>'7', 'AG' => '8', 'S' => '9', 'SE' => '9', 'O' => '10', 'OC' => '10',
            'N' => '11', 'NO' => '11', 'D' => '12', 'DI' => '12'
            ];
        return $meses[$letraMes];
    }
    function obtenerPrecio_anho($anio) {
        // Datos de ejemplo organizados en un array multidimensional
        $precios = [
            1992 => 60,
            1993 => 60,
            1994 => 60,
            1995 => 60,
            1996 => 120,
            1997 => 120,
            1998 => 135.60,
            1999 => 180,
            2000 => 180,
            2001 => 180,
            2002 => 180,
            2003 => 180,
            2004 => 180,
            2005 => 180,
            2006 => 180,
            2007 => 215,
            2008 => 240,
            2009 => 240
            // Continúa agregando años y meses
        ];


        // Verificar si el año y el mes existen en los datos
        if (isset($precios[$anio])) {
            return $precios[$anio];
        } else {
            return "No se encontró el precio para el año $anio";
        }
    }
    function obtenerPrecio_anho_mes($anio, $mes) {
        // Datos de ejemplo organizados en un array multidimensional
        $precios = [
            2010 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2011 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2012 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2013 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2014 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2015 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2016 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2017 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2018 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2019 => [
                '1' => 20, '2' => 20, '3' => 20, '4' => 20,
                '5' => 20, '6' => 20, '7' => 20, '8' => 20,
                '9' => 20, '10' => 20, '11' => 20, '12' => 20
            ],
            2020 => [
                '1' => 25, '2' => 25, '3' => 25, '4' => 25,
                '5' => 25, '6' => 25, '7' => 25, '8' => 25,
                '9' => 25, '10' => 25, '11' => 25, '12' => 25
            ],
            2021 => [
                '1' => 25, '2' => 25, '3' => 25, '4' => 25,
                '5' => 25, '6' => 25, '7' => 25, '8' => 25,
                '9' => 25, '10' => 25, '11' => 25, '12' => 25
            ],
            2022 => [
                '1' => 25, '2' => 25, '3' => 25, '4' => 25,
                '5' => 25, '6' => 25, '7' => 25, '8' => 25,
                '9' => 25, '10' => 25, '11' => 25, '12' => 25
            ],
            2023 => [
                '1' => 25, '2' => 25, '3' => 25, '4' => 25,
                '5' => 25, '6' => 25, '7' => 25, '8' => 25,
                '9' => 25, '10' => 25, '11' => 25, '12' => 25
            ],
            2024 => [
                '1' => 25, '2' => 25, '3' => 25, '4' => 25,
                '5' => 25, '6' => 25, '7' => 25, '8' => 25,
                '9' => 25, '10' => 25, '11' => 25, '12' => 25
            ],
            2025 => [
                '1' => 25, '2' => 25, '3' => 25, '4' => 25,
                '5' => 25, '6' => 25, '7' => 25, '8' => 25,
                '9' => 25, '10' => 25, '11' => 25, '12' => 25
            ],
            2026 => [
                '1' => 25, '2' => 25, '3' => 25, '4' => 25,
                '5' => 25, '6' => 25, '7' => 25, '8' => 25,
                '9' => 25, '10' => 25, '11' => 25, '12' => 25
            ],
        ];

        // Verificar si el año y el mes existen en los datos
        if (isset($precios[$anio]) && isset($precios[$anio][$mes])) {
            return $precios[$anio][$mes];
        } else {
            return "No se encontró el precio para el año $anio y el mes $mes.";
        }
    }
}