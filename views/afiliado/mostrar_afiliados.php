<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 09/08/2024
 * Time: 23:11
 */
?>

<h1>Listado de Afiliados</h1>

<table class="styled-table">
    <thead>
    <tr>
        <th>Items</th>
        <th>Cod. Cip</th>
        <th>Apellido</th>
        <th>Nombre</th>
        <th>Condición</th>
        <th>Fecha Incor.</th>
        <th>Estado</th>
        <th>Sexo</th>
        <th>Fecha Naci.</th>
        <th>Especialidad</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $a = 1;
    foreach($list_afiliados as $li){
        if($li['afiliado_apellidos'] == 1){
            $tipo_condicion = 'ORDINARIO';
        }else if($li['afiliado_apellidos'] == 2){
            $tipo_condicion = 'VITALICIO';
        }else{
            $tipo_condicion = 'TEMPORAL';
        }
        $estado = ($li['afiliado_estado']=='2')?'F':'A';
        ?>
        <tr>
            <td><?= $a;?></td>
            <td><?=$li['afiliado_cip']?></td>
            <td><?=$li['afiliado_apellidos']?></td>
            <td><?=$li['afiliado_nombres']?></td>
            <td><?=$tipo_condicion?></td>
            <td><?= (!empty($li['afiliado_fechaIncorporacion']))?$li['afiliado_fechaIncorporacion']:'-'?></td>
            <td><?=$estado?></td>
            <td><?=$li['afiliado_sexo']?></td>
            <td><?=(!empty($li['afiliado_fechaNacimiento']))?$li['afiliado_fechaNacimiento']:'-'?></td>
            <td>
                <?php
                $especialidades = $afiliado->listar_afiliados_especialidados_x_id_afiliado($li['id_afiliado']);
                foreach ($especialidades as $es){
                    $nombre = $es['especialidad_nombre'];
                    echo "$nombre";
                }
                ?>
            </td>
        </tr>
        <?php
        $a++;
    }
    ?>
    </tbody>
</table>




