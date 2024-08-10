
<form action="<?= $dominio?>Afiliado/importar_Archivo" method="post" enctype="multipart/form-data">
    <h2>Adjunte el archivo que desea importar, tiene que ser CSV EXCEL</h2>
    <div>
        <label for="tipo_tabla">Tabla a importar</label>
        <select name="tipo_tabla" id="tipo_tabla" required>
            <option value="">Seleccionar...</option>
            <option value="1">AFILIADOS</option>
            <option value="2">PAGOS</option>
        </select>
    </div>
    <div>
        <label for="archivo">Adjunte Archivo</label>
        <input type="file" id="archivo" name="archivo" required>
    </div>
    <div>
        <button type="submit" class="form-button">IMPORTAR</button>
    </div>
</form>