# Fase 9 — Notificaciones PUSH y Reportes PDF con QR

> Pega esto en Cursor (modo Agent). Antes lee `AGENTS.md` y `docs/02-arquitectura.md`. Respeta el código existente: extiende, no reescribas.

Parte A — PUSH (para usuarios del panel):
1. `composer require minishlink/web-push`. Comando CLI para generar llaves VAPID → `.env` (`VAPID_PUBLIC`, `VAPID_PRIVATE`).
2. Botón «Activar notificaciones» en el topbar del panel → `PushManager.subscribe` → POST `admin-panel/push/subscribe` → tabla `push_subscriptions` (ya existe).
3. Librería `Push`: `send_to_role($role, $title, $body, $url)`, borra suscripciones expiradas (404/410).
4. Disparadores: nuevo mensaje de contacto, nueva solicitud de membresía. Pantalla `admin/Notifications` para envío manual (tabla `notifications`).
5. El `service-worker.js` ya maneja `push` y `notificationclick`; regístralo también en el layout del panel con scope `/admin-panel/`.

Parte B — Reportes (cotización: modo gráfico + PDF con QR arriba):
1. Librería `Pdf_report` (extiende TCPDF): encabezado con logo, título, fecha y **QR en la esquina superior** que codifica `https://sidvvi.com/verificar/{folio}`; pie con paginación.
2. Cada reporte genera folio único en `report_logs` (ya existe). Ruta pública `/verificar/{folio}` que muestra tipo, fecha y quién lo generó.
3. `admin/Reports`: socios por especialidad y estado, publicaciones y descargas por periodo, mensajes por periodo, solicitudes de membresía por estatus. Cada uno con filtros, gráfica Chart.js en pantalla y botones PDF / CSV.
4. Agrega «Reportes» al menú del panel (rol viewer en adelante).
