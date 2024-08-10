
<h1>Listado de Capitulos</h1>

<table class="styled-table">
    <thead>
    <tr>
        <th>Items</th>
        <th>Nombre Especialidad</th>
        <th>Nombre Capitulo</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $a = 1;
    foreach($list_especialidades as $li){
        ?>
        <tr>
            <td><?= $a;?></td>
            <td><?=$li['especialidad_nombre']?></td>
            <td><?=$li['capitulo_nombre']?></td>
        </tr>
        <?php
        $a++;
    }
    ?>
    </tbody>
</table>
    


