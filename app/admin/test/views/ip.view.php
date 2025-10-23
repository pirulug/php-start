<h2>Subir archivo CSV IP2Location</h2>
<form method="post" enctype="multipart/form-data">
  <label>Archivo CSV: <input type="file" name="csvfile" accept=".csv,.txt" required></label><br><br>
  <label>Fila con encabezado: <input type="checkbox" name="has_header" checked></label><br><br>
  <label>Delimitador:
    <select name="delimiter">
      <option value="">Auto (mejor)</option>
      <option value=",">Coma (,)</option>
      <option value=";">Punto y coma (;)</option>
      <option value="\t">Tab (\t)</option>
    </select>
  </label><br><br>

  <label>Modo de carga:
    <select name="mode">
      <option value="insert">Insertar nuevo</option>
      <option value="update">Actualizar existente</option>
    </select>
  </label><br><br>

  <button type="submit">Subir e importar</button>
</form>