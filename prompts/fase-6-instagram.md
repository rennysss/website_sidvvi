# Fase 6 — Noticias: conexión real con Instagram

> Pega esto en Cursor (modo Agent). Antes lee `AGENTS.md` y `docs/02-arquitectura.md`. Respeta el código existente: extiende, no reescribas.

Hoy `/noticias` y el bloque del Inicio leen la tabla `instagram_cache` (llenada a mano desde el panel). Conecta la API:
1. Crea `application/libraries/Instagram_feed.php` usando la **Instagram API con inicio de sesión de Instagram** (cuenta Business/Creator). Endpoint `GET https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,thumbnail_url,permalink,timestamp&access_token=...` (usa cURL, timeout 10 s).
2. Token en `.env` (`INSTAGRAM_TOKEN`). Implementa `refresh_token()` con `GET https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token` y guarda el nuevo token en un archivo `application/cache/instagram_token.json` (no en el repo).
3. `refresh()` hace upsert por `ig_media_id` en `instagram_cache`. Para VIDEO usa `thumbnail_url` como imagen.
4. Controlador CLI `controllers/Cli.php` (solo `is_cli()`): `php index.php cli instagram` → refresh + renovación del token si faltan < 10 días. Documenta el cron (cada hora) en `docs/06-checklist-entrega.md`.
5. Si la API falla, el sitio sigue mostrando la caché (no romper la página). Registra el error con `log_message`.
6. En el panel (`admin/Instagram`) agrega un botón «Sincronizar ahora» (POST, rol editor).
