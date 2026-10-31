# 05 — Decisiones tomadas y pendientes por confirmar

## Supuestos adoptados (cámbialos si no aplican)
1. **Mismo servidor y misma BD** que el subsistema de Congresos; sus tablas con prefijo `cg_`.
2. **PHP 8.1 + CodeIgniter 3.1.13** (CI 3.1.13 es la versión con soporte para PHP 8.x). La cotización dice "PHP 7 en adelante", así que cumple.
3. **Bootstrap 4.6.2** y **jQuery 3.7** — confirmado por el director (se respeta la cotización).
4. Editor de artículos: **Summernote** (compatible con Bootstrap 4).
5. Gráficas: **Chart.js**. Tablas admin: **DataTables** con estilo BS4.
6. Push: **Web Push con VAPID** (`minishlink/web-push`) — requiere HTTPS.
7. QR: `chillerlan/php-qrcode` o `endroid/qr-code` (elegir el compatible con la versión de PHP del hosting).
8. Instagram: Instagram API con inicio de sesión de Instagram (la antigua *Basic Display API* ya fue retirada por Meta), requiere cuenta **Business o Creator**.

10. **Diseño:** acomodo inspirado en Cityvile; interacción de la información como amimc.org.mx y nuevo.ammvih.org. Blanco predominante, azul del logo, rojo solo en acentos (ver `07-guia-de-diseno.md`).
11. **Secciones agregadas** (aprobadas por el director): Nosotros / Mesa directiva, Membresía (solicitud en línea) y Constancias (puente al subsistema).
12. **Contenido dummy:** todo el texto e imágenes actuales son de ejemplo; se reemplazan en la fase B.
13. **Roles simplificados:** `superadmin`, `editor`, `viewer` (columna `users.role`), sin tabla de permisos.
9. **URLs confirmadas:** sitio en `https://sidvvi.com`, panel de control con login en `https://sidvvi.com/admin-panel`.

## Pendientes para el cliente (Luis Parra)
- [ ] ¿La cuenta de Instagram es Business/Creator? Acceso para generar token.
- [ ] Lista de socios (Excel/CSV) con campos: nombre, especialidad, institución, ciudad, foto, correo público.
- [ ] ¿Los socios tendrán login propio? (No está en la cotización → se asume **no**.)
- [ ] Fotos de la mesa directiva, textos de Nosotros/Misión/Visión/Valores y mensaje de la presidencia con firma.
- [ ] Beneficios, requisitos y cuotas de la membresía.
- [ ] Documentos descargables (estatutos, convocatorias).
- [ ] Correos destinatarios del formulario de contacto y cuenta SMTP.
- [ ] Logo en vector, paleta de colores, tipografías, textos de Inicio (quiénes somos, misión, visión).
- [ ] Aviso de privacidad.
- [ ] Redes sociales oficiales (URLs).
- [ ] Categorías iniciales de la Biblioteca Virtual.

## Pendientes técnicos (para ti)
- [ ] Compartir con Cursor el esquema real del subsistema de Congresos (`SHOW CREATE TABLE cg_...`) y su stack (¿es CI3 también?).
- [x] Dominio: **sidvvi.com** (GoDaddy). Se desarrolla en local y se publica en `https://sidvvi.com`.
- [ ] Confirmar el hosting: ¿GoDaddy (cPanel) u otro? Verificar PHP 8.1, MySQL, SSL, cron y SSH/Git.
- [ ] Definir si las notificaciones PUSH son solo para administradores (asumido) o también para visitantes/asistentes.
- [ ] La cotización menciona "departamentos del taller" en PUSH: texto heredado de otra plantilla, adaptarlo a "miembros de la mesa directiva/administradores".
