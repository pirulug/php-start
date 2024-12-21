<?php

require_once "core.php";

function listTables($connect) {
  $query  = $connect->query("SHOW TABLES");
  $tables = $query->fetchAll(PDO::FETCH_COLUMN);

  foreach ($tables as $table) {
    echo $table . "\n";
  }
}

function generateCRUD($table, $connect) {
  // Directorios
  $controllerDir = __DIR__ . '/adm/controller/' . $table;
  $viewsDir      = __DIR__ . '/adm/views/' . $table;

  // Crear directorios si no existen
  if (!file_exists($controllerDir)) {
    mkdir($controllerDir, 0777, true);
  }
  if (!file_exists($viewsDir)) {
    mkdir($viewsDir, 0777, true);
  }

  // Obtener columnas de la tabla
  $query = $connect->prepare("DESCRIBE $table");
  $query->execute();
  $columns = $query->fetchAll(PDO::FETCH_COLUMN);

  // Crear archivos CRUD en admin/controller
  createControllerFiles($controllerDir, $table, $columns);

  // Crear formularios HTML en admin/views
  createViewFiles($viewsDir, $table, $columns);
}

function createControllerFiles($dir, $table, $columns) {
  // Crear el archivo new.php
  $create_file    = fopen("$dir/new.php", "w");
  $create_content = "<?php\n";
  $create_content .= "\n\n";
  $create_content .= "if (\$_POST) {\n\n";

  foreach (array_slice($columns, 1) as $column) {
    $create_content .= "    \$$column=\$_POST['$column'];\n";
  }

  $create_content .= "\n";

  $create_content .= "    \$stmt = \$connect->prepare(\"INSERT INTO $table (" . implode(", ", array_slice($columns, 1)) . ") VALUES (:" . implode(", :", array_slice($columns, 1)) . ")\");\n";

  foreach (array_slice($columns, 1) as $column) {
    $create_content .= "    \$stmt->bindParam(':$column', \$$column);\n";
  }

  $create_content .= "\n    if (\$stmt->execute()) {\n";
  $create_content .= "        echo 'Registro creado exitosamente.';\n";
  $create_content .= "    } else {\n";
  $create_content .= "        echo 'Error al crear el registro.';\n";
  $create_content .= "    }\n";
  $create_content .= "}\n";
  $create_content .= "?>\n";
  fwrite($create_file, $create_content);
  fclose($create_file);

  // Crear el archivo list.php
  $read_file    = fopen("$dir/list.php", "w");
  $read_content = "<?php\n\n";
  $read_content .= "require_once \"../../core.php\";\n\n";
  // $read_content .= "\$search=\"\";\n\n";

  $read_content .= "\$search = isset(\$_GET['search']) ? \$_GET['search'] : '';\n";
  $read_content .= "\$page   = isset(\$_GET['page']) ? (int) \$_GET['page'] : 1;\n";
  $read_content .= "\$limit  = 10;\n";
  $read_content .= "\$offset = (\$page - 1) * \$limit;\n\n";

  $read_content .= "// Columnas a buscar\n";
  $read_content .= "\$searchColumns = [" . implode(", ", array_map(function ($column) {
    return '"' . $column . '"';
  }, array_slice($columns, 1))) . "];\n\n";

  $read_content .= "\$additionalConditions = [\n";
  $read_content .= "  /*[\n";
  $read_content .= "    'sql'   => 'user_role != 0',\n";
  $read_content .= "    'param' => null,\n";
  $read_content .= "    'value' => null,\n";
  $read_content .= "    'type'  => null,\n";
  $read_content .= "  ],*/\n";
  $read_content .= "];\n\n";

  // Obtener el total de resultados y calcular el número de páginas
  $read_content .= "\$total_results = getTotalResults('$table', \$searchColumns, \$search, \$additionalConditions, \$connect);\n";
  $read_content .= "\$total_pages   = ceil(\$total_results / \$limit);\n\n";

  // Obtener los resultados paginados
  $read_content .= "\$$table = getPaginatedResults('$table', \$searchColumns, \$search, \$additionalConditions, \$limit, \$offset, \$connect);\n";

  $read_content .= "\n/* ========== Theme config ========= */\n";
  $read_content .= "\$theme_title = \"Lista de < $table >\";\n";
  $read_content .= "\$theme_path  = \"$table-list\";\n";
  $read_content .= "include \"../../views/$table/list.view.php\";\n";
  $read_content .= "/* ================================= */\n\n";

  /*$read_content .= "?>\n";*/
  fwrite($read_file, $read_content);
  fclose($read_file);

  // Crear el archivo edit.php
  $update_file    = fopen("$dir/edit.php", "w");
  $update_content = "<?php\n";
  $update_content .= "if (\$_POST) {\n\n";

  foreach ($columns as $column) {
    $update_content .= "    \$$column=\$_POST['$column'];\n";
  }

  $update_content .= "\n    \$stmt = \$connect->prepare(\"UPDATE $table SET ";

  $update_pairs = [];
  foreach (array_slice($columns, 1) as $column) {
    $update_pairs[] = "$column = :$column";
  }
  $update_content .= implode(", ", $update_pairs);
  $update_content .= " WHERE " . $columns[0] . " = :" . $columns[0] . "\");\n";

  foreach ($columns as $column) {
    $update_content .= "    \$stmt->bindParam(':$column', \$$column);\n";
  }

  $update_content .= "\n    if (\$stmt->execute()) {\n";
  $update_content .= "        echo 'Registro actualizado exitosamente.';\n";
  $update_content .= "    } else {\n";
  $update_content .= "        echo 'Error al actualizar el registro.';\n";
  $update_content .= "    }\n";
  $update_content .= "}\n";
  $update_content .= "?>\n";
  fwrite($update_file, $update_content);
  fclose($update_file);

  // Crear el archivo delete.php
  $delete_file    = fopen("$dir/delete.php", "w");
  $delete_content = "<?php\n";
  $delete_content .= "if (\$_POST) {\n";
  $delete_content .= "    \$stmt = \$connect->prepare(\"DELETE FROM $table WHERE " . $columns[0] . " = :" . $columns[0] . "\");\n";
  $delete_content .= "    \$stmt->bindParam(':" . $columns[0] . "', \$_POST['" . $columns[0] . "']);\n";
  $delete_content .= "    if (\$stmt->execute()) {\n";
  $delete_content .= "        echo 'Registro eliminado exitosamente.';\n";
  $delete_content .= "    } else {\n";
  $delete_content .= "        echo 'Error al eliminar el registro.';\n";
  $delete_content .= "    }\n";
  $delete_content .= "}\n";
  $delete_content .= "?>\n";
  fwrite($delete_file, $delete_content);
  fclose($delete_file);
}

function createViewFiles($dir, $table, $columns) {

  // edit.view.php
  $edit_view_file    = fopen("$dir/edit.view.php", "w");
  $edit_view_content = "<?php require BASE_DIR_ADMIN_VIEW . '/partials/top.partial.php'; ?>\n";
  $edit_view_content .= "<?php require BASE_DIR_ADMIN_VIEW . '/partials/navbar.partial.php'; ?>\n\n";
  $edit_view_content .= "<?php display_messages(); ?>\n\n";
  $edit_view_content .= "<div class=\"card mb-3\">\n";
  $edit_view_content .= "  <div class=\"card-body\">\n";
  $edit_view_content .= "    <form id=\"formNewUser\" enctype=\"multipart/form-data\" action=\"\" method=\"post\">\n";

  foreach (array_slice($columns, 1) as $column) {
    $edit_view_content .= "      <div class=\"mb-3\">\n";
    $edit_view_content .= "        <label for=\"$column\" class=\"form-label\">" . ucfirst($column) . "</label>\n";
    $edit_view_content .= "        <input type=\"text\" class=\"form-control\" id=\"$column\" name=\"$column\">\n";
    $edit_view_content .= "      </div>\n";
  }

  $edit_view_content .= "      <button type=\"submit\" class=\"btn btn-primary\">Guardar</button>\n";
  $edit_view_content .= "    </form>\n";
  $edit_view_content .= "  </div>\n";
  $edit_view_content .= "</div>\n\n";
  $edit_view_content .= "<?php require BASE_DIR_ADMIN_VIEW . '/partials/footer.partial.php'; ?>\n";
  $edit_view_content .= "<?php require BASE_DIR_ADMIN_VIEW . '/partials/bottom.partial.php'; ?>\n";
  fwrite($edit_view_file, $edit_view_content);
  fclose($edit_view_file);

  // list.view.php
  $list_view_file    = fopen("$dir/list.view.php", "w");
  $list_view_content = "<?php require BASE_DIR_ADMIN_VIEW . '/partials/top.partial.php'; ?>\n";
  $list_view_content .= "<?php require BASE_DIR_ADMIN_VIEW . '/partials/navbar.partial.php'; ?>\n\n";
  $list_view_content .= "<?php display_messages(); ?>\n\n";
  $list_view_content .= "<div class=\"card mb-3\">\n";
  $list_view_content .= "  <div class=\"card-body\">\n";
  $list_view_content .= "    <form method=\"GET\" action=\"\">\n";
  $list_view_content .= "      <div class=\"input-group\">\n";
  $list_view_content .= "        <input class=\"form-control\" type=\"text\" name=\"search\" value=\"<?= htmlspecialchars(\$search) ?>\">\n";
  $list_view_content .= "        <button class=\"btn btn-primary\" type=\"submit\">Buscar</button>\n";
  $list_view_content .= "      </div>\n";
  $list_view_content .= "    </form>\n";
  $list_view_content .= "    <div class=\"table-responsibe mt-3\">\n";
  $list_view_content .= "      <table class=\"table table-bordered\">\n";
  $list_view_content .= "        <thead>\n";
  $list_view_content .= "          <tr>\n";
  foreach (array_slice($columns, 1) as $column) {
    $list_view_content .= "            <th>$column</th>\n";
  }
  $list_view_content .= "            <th>Acciones</th>\n";
  $list_view_content .= "          </tr>\n";
  $list_view_content .= "        </thead>\n";
  $list_view_content .= "        <tbody>\n";
  $list_view_content .= "          <?php foreach (\$$table as \$data): ?>\n";
  $list_view_content .= "          <tr>\n";
  foreach (array_slice($columns, 1) as $column) {
    $list_view_content .= "            <td><?= \$data->$column ?></td>\n";
  }
  $list_view_content .= "            <td>\n";
  $list_view_content .= "              <a class=\"btn btn-sm btn-success\" href=\"edit.php?id=<?= \$data->$columns[0] ?>\">\n";
  $list_view_content .= "                <i class=\"fa fa-pen\"></i>\n";
  $list_view_content .= "              </a>\n";
  $list_view_content .= "              <a class=\"btn btn-sm btn-danger\" href=\"delete.php?id=<?= \$data->$columns[0] ?>\">\n";
  $list_view_content .= "                <i class=\"fa fa-trash\"></i>\n";
  $list_view_content .= "              </a>\n";
  $list_view_content .= "            </td>\n";
  $list_view_content .= "          </tr>\n";
  $list_view_content .= "          <?php endforeach; ?>\n";
  $list_view_content .= "        </tbody>\n";
  $list_view_content .= "      </table>\n";
  $list_view_content .= "    </div>\n";
  $list_view_content .= "    <?php renderPagination(\$offset, \$limit, \$total_results, \$page, \$search, \$total_pages) ?>\n";
  $list_view_content .= "  </div>\n";
  $list_view_content .= "</div>\n\n";
  $list_view_content .= "<?php require BASE_DIR_ADMIN_VIEW . '/partials/footer.partial.php'; ?>\n";
  $list_view_content .= "<?php require BASE_DIR_ADMIN_VIEW . '/partials/bottom.partial.php'; ?>\n";
  fwrite($list_view_file, $list_view_content);
  fclose($list_view_file);

  // new.view.php
  $new_view_file    = fopen("$dir/new.view.php", "w");
  $new_view_content = "<?php require BASE_DIR_ADMIN_VIEW . '/partials/top.partial.php'; ?>\n";
  $new_view_content .= "<?php require BASE_DIR_ADMIN_VIEW . '/partials/navbar.partial.php'; ?>\n\n";
  $new_view_content .= "<?php display_messages(); ?>\n\n";
  $new_view_content .= "<div class=\"card mb-3\">\n";
  $new_view_content .= "  <div class=\"card-body\">\n";
  $new_view_content .= "    <form id=\"formNewUser\" enctype=\"multipart/form-data\" action=\"\" method=\"post\">\n";

  foreach (array_slice($columns, 1) as $column) {
    $new_view_content .= "      <div class=\"mb-3\">\n";
    $new_view_content .= "        <label for=\"$column\" class=\"form-label\">" . ucfirst($column) . "</label>\n";
    $new_view_content .= "        <input type=\"text\" class=\"form-control\" id=\"$column\" name=\"$column\">\n";
    $new_view_content .= "      </div>\n";
  }

  $new_view_content .= "      <button type=\"submit\" class=\"btn btn-primary\">Guardar</button>\n";
  $new_view_content .= "    </form>\n";
  $new_view_content .= "  </div>\n";
  $new_view_content .= "</div>\n\n";
  $new_view_content .= "<?php require BASE_DIR_ADMIN_VIEW . '/partials/footer.partial.php'; ?>\n";
  $new_view_content .= "<?php require BASE_DIR_ADMIN_VIEW . '/partials/bottom.partial.php'; ?>\n";
  fwrite($new_view_file, $new_view_content);
  fclose($new_view_file);

}

// Comprobar si el script se ejecuta desde la línea de comandos
if (php_sapi_name() == "cli") {
  if (isset($argv[1])) {
    switch ($argv[1]) {
      case '-l':
        listTables($connect);
        break;

      case '-g':
        if (isset($argv[2])) {
          $table = $argv[2];
          generateCRUD($table, $connect);
          echo "CRUD y vistas generados para la tabla '$table'.\n";
        } else {
          echo "Por favor, proporciona el nombre de la tabla después de '-g'.\n";
        }
        break;

      default:
        echo "Parámetro no reconocido. Usa '-l' para listar las tablas o '-g [tabla]' para generar el CRUD y las vistas.\n";
    }
  } else {
    echo "Por favor, proporciona un parámetro.\n";
  }
} else {
  echo "Este script debe ejecutarse desde la línea de comandos.\n";
}
?>