# Fase 8 — Correo real y anti-spam

> Pega esto en Cursor (modo Agent). Antes lee `AGENTS.md` y `docs/02-arquitectura.md`. Respeta el código existente: extiende, no reescribas.

El formulario de contacto y el de membresía ya guardan en BD y envían correo si hay SMTP. Completa:
1. Configura en `.env` el SMTP real (cPanel de GoDaddy u otro) y prueba con un comando CLI `php index.php cli test_mail destinatario@correo.com`.
2. Agrega reCAPTCHA v3 opcional: si `RECAPTCHA_SITE_KEY` y `RECAPTCHA_SECRET` existen, carga el script en `contact.php` y `membership.php`, envía el token en el Ajax y valídalo en el servidor (score ≥ 0.5). Si no hay llaves, se queda el honeypot + límite por IP actual.
3. Agrega a la bandeja `admin/Messages` un botón «Exportar CSV» (filtrado por estado y fechas).
4. Crea los destinatarios reales en Configuración → Contacto.
