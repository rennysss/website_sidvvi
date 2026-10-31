# Fase 4b — Importar socios desde CSV/Excel

> Pega esto en Cursor (modo Agent). Antes lee `AGENTS.md` y `docs/02-arquitectura.md`. Respeta el código existente: extiende, no reescribas.

Agrega al panel la importación masiva de socios:
1. En `controllers/admin/Members.php` agrega los métodos `import()` (GET formulario, POST procesa) y un botón «Importar CSV» en el listado (puedes sobrescribir `index()` o pasar un botón extra a la vista `crud/index`).
2. Acepta CSV UTF-8 (separador coma o punto y coma) con encabezados: `numero_socio, titulo, nombre, apellidos, especialidad, tipo, cargo, institucion, ciudad, estado, correo_publico, telefono_publico`.
3. Paso 1: sube el archivo y muestra una vista previa con validación por fila (errores en rojo). Paso 2: confirmar e insertar.
4. Si la especialidad o el tipo no existen, créalos. Si `numero_socio` ya existe, actualiza en lugar de duplicar.
5. Genera slug con `$this->model->unique_slug()`. Registra `audit_log("import", "members", NULL, ["filas" => N])`.
6. Agrega una plantilla descargable `assets/templates/socios.csv`.
