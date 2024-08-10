
<body>
<h1>Listado de Capitulos</h1>

<table class="styled-table">
    <thead>
    <tr>
        <th>Items</th>
        <th>Nombre Capitulo</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $a = 1;
    foreach($list_capitulos as $li){
        ?>
        <tr>
            <td><?= $a;?></td>
            <td><?=$li['capitulo_nombre']?></td>
        </tr>
        <?php
        $a++;
    }
    ?>
    </tbody>
</table>
    
</body>

