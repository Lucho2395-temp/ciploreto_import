<?php
require_once './database/conexion.php';

class Afiliado {

	public $db;
	
	public function __construct() {
		$this->db = Connection::getInstance()->getConnection();
	}

	public function guardar_afiliado($model){
		$sql = "insert into afiliados (id_tipo_documento, id_capitulo, afiliado_numeroDoc, afiliado_apellidos, afiliado_nombres, 
                       afiliado_cip, afiliado_condicion, afiliado_fechaIncorporacion, afiliado_estado, afiliado_sexo, 
                       afiliado_fechaNacimiento, afiliado_direccion, afiliado_telefono, afiliado_email, afiliado_sede, 
                       id_dependencia, afiliado_estadoFinal, id_user_register, us_microtime, created_at, updated_at) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stm = $this->db->prepare($sql);
        $stm->execute([
            $model->id_tipo_documento,
            $model->id_capitulo,
            $model->afiliado_numeroDoc,
            $model->afiliado_apellidos,
            $model->afiliado_nombres,
            $model->afiliado_cip,
            $model->afiliado_condicion,
            $model->afiliado_fechaIncorporacion,
            $model->afiliado_estado,
            $model->afiliado_sexo,
            $model->afiliado_fechaNacimiento,
            $model->afiliado_direccion,
            $model->afiliado_telefono,
            $model->afiliado_email,
            $model->afiliado_sede,
            $model->id_dependencia,
            $model->afiliado_estadoFinal,
            $model->id_user_register,
            $model->us_microtime,
            $model->created_at,
            $model->updated_at
        ]);
        $result = 1;
		return $result;
	}
	public function guardar_afiliado_especialidades($id_afiliado,$id_espe,$afiliados_especialidad_estado,$espe_created_at,$espe_updated_at){
		$sql = "insert into afiliados_especialidades (id_afiliado, id_especialidad, afiliados_especialidad_estado, created_at, updated_at) 
                VALUES (?,?,?,?,?)";

        $stm = $this->db->prepare($sql);
        $stm->execute([
            $id_afiliado,$id_espe,$afiliados_especialidad_estado,$espe_created_at,$espe_updated_at
        ]);
        $result = 1;
		return $result;
	}
	public function guardar_deuda($id_afiliado, $anho, $mes, $deuda_costo_mensual, $deuda_estado_pagado, $deuda_estado){
		$sql = "insert into deudas (id_afiliado, deuda_anho, deuda_mes, deuda_costo, deuda_estado_pagado, deuda_estado) 
                VALUES (?,?,?,?,?,?)";

        $stm = $this->db->prepare($sql);
        $stm->execute([
            $id_afiliado, $anho, $mes, $deuda_costo_mensual, $deuda_estado_pagado, $deuda_estado
        ]);
        $result = 1;
		return $result;
	}
	public function guardar_mensualidad($id_afiliado, $anho, $mes, $costo_mensual, $mensualidad_estado,$mensualidad_estado_pagado){
        $fecha = "{$anho}-{$mes}-01";
		$sql = "insert into mensualidades (id_afiliado, mensualidad_monto, mensualidad_anho, mensualidad_mes, mensualidad_fecha_ultimo_pago,
                           mensualidad_estado, mensualidad_estado_pagado) 
                VALUES (?,?,?,?,?,?,?)";

        $stm = $this->db->prepare($sql);
        $stm->execute([
            $id_afiliado, $costo_mensual, $anho, $mes,$fecha, $mensualidad_estado,$mensualidad_estado_pagado
        ]);
        $result = 1;
		return $result;
	}

	public function listar_capitulos(){
		$sql = "select * from capitulos";
	
		$guardado = $this->db->query($sql);
		$result = $guardado->fetchAll();
		return $result;
	}
	public function listar_afiliado_x_mt($us_microtime){
		$sql = "select * from afiliados where us_microtime = ?";
        $stm = $this->db->prepare($sql);
        $stm->execute([$us_microtime]);
        $result = $stm->fetch();
        return $result;
	}

	public function listar_especialidades(){
		$sql = "select * from especialidades e inner join capitulos c on e.id_capitulo = c.id_capitulo ORDER BY especialidad_nombre asc";
	
		$guardado = $this->db->query($sql);
		$result = $guardado->fetchAll();
		return $result;
	}
	public function listar_especialidad_x_nombre($especialidad){
		$sql = "select * from especialidades where especialidad_nombre LIKE ? limit 1";

        $stm = $this->db->prepare($sql);
        $stm->execute([$especialidad]);
        $result = $stm->fetch();
		return $result;
	}
	public function listar_afiliados(){
		$sql = "select * from afiliados order by afiliado_apellidos asc";

        $stm = $this->db->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();
		return $result;
	}
	public function listar_afiliado_x_codigo_cip($codigo){
		$sql = "select * from afiliados where afiliado_cip = ?";

        $stm = $this->db->prepare($sql);
        $stm->execute([$codigo]);
        $result = $stm->fetch();
		return $result;
	}
	public function listar_afiliados_especialidados_x_id_afiliado($id_afiliado){
		$sql = "select * from afiliados_especialidades ae inner join especialidades e on ae.id_especialidad = e.id_especialidad
                where ae.id_afiliado = ?";

        $stm = $this->db->prepare($sql);
        $stm->execute([$id_afiliado]);
        $result = $stm->fetchAll();
		return $result;
	}

}

