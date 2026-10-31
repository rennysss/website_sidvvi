# Fase 10 — QA y publicación en https://sidvvi.com

> Pega esto en Cursor (modo Agent). Antes lee `AGENTS.md` y `docs/02-arquitectura.md`. Respeta el código existente: extiende, no reescribas.

1. Recorre `docs/06-checklist-entrega.md` y corrige lo que falle.
2. Verifica que `https://sidvvi.com/admin-panel` muestre el login, que `/admin` dé 404 y que el panel tenga `noindex`.
3. Seguridad: CSRF en todos los formularios, escapes `e()` en vistas, permisos por rol, `uploads/.htaccess`, `.env` fuera del webroot o bloqueado, contraseña del admin cambiada.
4. Rendimiento: imágenes WebP, `loading="lazy"`, Lighthouse ≥ 85 en Inicio, Biblioteca y Contacto.
5. Genera `docs/DEPLOY.md` para el hosting (GoDaddy cPanel u otro): PHP 8.1, subir archivos, `composer install --no-dev`, importar SQL, `.env` con `APP_ENV=production` y `BASE_URL=https://sidvvi.com/`, descomentar en `.htaccess` el bloque HTTPS/www, permisos de `uploads/` y `application/logs/`, cron de Instagram (cada hora) y respaldo diario `mysqldump`.
6. Genera `docs/MANUAL-USUARIO.md` breve para el cliente: cómo entrar a `/admin-panel` y usar cada sección del menú.
