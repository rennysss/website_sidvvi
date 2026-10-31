# Fase 7 — Integración real con el subsistema de Congresos

> Pega esto en Cursor (modo Agent). Antes lee `AGENTS.md` y `docs/02-arquitectura.md`. Respeta el código existente: extiende, no reescribas.

Te comparto el esquema del subsistema de Congresos:
[PEGA AQUÍ los SHOW CREATE TABLE de sus tablas y la URL donde vivirá, p. ej. https://sidvvi.com/congresos-app/]

Tareas:
1. Ajusta `application/config/congress.php`: `table` y el mapeo `columns` (id, name, slug, starts_at, ends_at, venue, city, modality, image, summary, description, registration_url, certificates_url, is_published). Si un dato está en otra tabla, extiende `Congress_model::select()` con el JOIN necesario. **No escribas en las tablas del subsistema.**
2. Si el subsistema usa otra base de datos, agrega un grupo `congress` en `config/database.php` y úsalo en `Congress_model` (`$this->load->database("congress", TRUE)`).
3. Ajusta en `.env` `CONGRESS_APP_URL` y revisa que `registration_url` / `certificates_url` apunten a las rutas reales del subsistema.
4. En `controllers/Certificates.php` y `views/public/certificates.php` ajusta el formulario de búsqueda de constancias a la ruta real del subsistema.
5. Elimina la tabla demo `cg_congresses` de `database/sidvvi.sql` cuando todo funcione.
6. Prueba: `/congresos`, `/congresos/{slug}`, Inicio (próximos 3), `/constancias`, `/admin-panel/congresses`, `/sitemap.xml`.
