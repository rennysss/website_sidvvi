# 01 — PRD (Requisitos del producto) · SIDVVI

Fuente: Cotización de WebSite SIDVVI, 19 de octubre de 2026.

## Objetivos
- Exposición de la marca y centralizar la información de la sociedad en una sola plataforma.
- Base de datos centralizada, captura automática de datos, estructura por módulos configurables.
- 100% responsivo, SEO, integración con redes sociales.
- Congresos: creación, registro de asistentes e impresión de certificados (subsistema existente).

## Usuarios
| Rol | Descripción |
|---|---|
| Visitante | Navega el sitio público, consulta directorio, biblioteca, congresos, envía contacto |
| Socio | (Opcional, confirmar) Accede a su perfil en el directorio y a contenido restringido |
| Editor | Administra contenido (banners, artículos, socios, congresos visibles) |
| Superadmin | Todo lo anterior + usuarios, roles, configuración, notificaciones, reportes |

---

## Sitio público

### M1. Inicio
- Carrusel de banners administrables (imagen desktop + móvil, título, texto, botón con enlace, orden, vigencia).
- Bloques de información de la sociedad (quiénes somos, misión, visión) editables desde Configuración.
- Destacados: últimos 3 artículos de Biblioteca, próximo congreso, últimas publicaciones de Instagram.
- **Aceptación:** banners se muestran solo si están publicados y dentro de su vigencia; el orden se respeta; en móvil se usa la imagen móvil.

### M2. Directorio de socios
- Listado en tarjetas: foto, nombre, especialidad/cargo, institución, ciudad/estado, redes/contacto público.
- Búsqueda por nombre y filtros (especialidad, estado, tipo de socio). Paginación Ajax.
- Página de detalle del socio (`/directorio/{slug}`).
- **Aceptación:** solo socios activos y con `is_public = 1`; búsqueda responde < 1 s con 1,000 registros.

### M3. Biblioteca Virtual
- Estilo blog: artículos y reportes con categoría, etiquetas, autor (puede ser socio), imagen, resumen, contenido enriquecido, adjuntos PDF.
- Listado con filtros por categoría/etiqueta/año y buscador. Detalle `/biblioteca/{slug}` con compartir en redes.
- **Aceptación:** artículos en `draft` no son visibles; los PDF se descargan con contador de descargas.

### M4. Noticias (Instagram)
- Feed de las publicaciones de Instagram de la sociedad incrustado en el sitio (grid responsive).
- Implementación: Instagram API con inicio de sesión de Instagram (cuenta Business/Creator) vía librería `Instagram_feed`, con caché en BD/archivo (refresco cada 1 h) y renovación del token de larga duración.
- Respaldo: si no hay token válido, mostrar embeds oficiales o un enlace al perfil.
- **Aceptación:** el sitio no falla si Instagram no responde (se muestra caché).

### M5. Congresos
- Listado histórico (próximos y pasados) y detalle: nombre, fechas, sede, cartel, programa, ponentes, galería.
- Botones a registro de asistentes y descarga de certificado → subsistema existente.
- **Aceptación:** datos leídos del subsistema sin duplicar información (ver regla 50).

### M6. Redes Sociales
- Iconos en header y footer, administrables (red, URL, orden, activo).
- Botones de compartir en artículos y congresos. Meta Open Graph correctos.

### M7. Contacto
- Formulario: nombre, correo, teléfono, asunto, mensaje, aviso de privacidad (checkbox).
- Envía correo (SMTP configurable) al/los destinatario(s) definidos en Configuración y guarda el mensaje en BD.
- Respuesta automática al remitente. Anti-spam (reCAPTCHA v3 / honeypot + rate limit).
- **Aceptación:** mensaje guardado aunque falle el SMTP; el admin lo ve en bandeja.

### M8. Nosotros y Mesa directiva *(agregado)*
- Quiénes somos, misión, visión, valores, áreas de trabajo, cifras y tarjetas de la mesa directiva (foto, cargo, periodo, redes).

### M9. Membresía *(agregado)*
- Beneficios y requisitos (texto editable) + formulario de solicitud que llega a la bandeja «Solicitudes de membresía» y por correo.

### M10. Constancias *(agregado)*
- Acceso directo desde el encabezado. Búsqueda por correo y lista de eventos que envían al subsistema de Congresos.

---

## Backend (https://sidvvi.com/admin-panel)

Acceso: `https://sidvvi.com/admin-panel` muestra el login; sin sesión, cualquier ruta `/admin-panel/*` redirige al login. Tras autenticarse → `/admin-panel/dashboard`.

### B1. Autenticación y usuarios
Login en `/admin-panel`, recuperar contraseña (`/admin-panel/recuperar`) por correo, CRUD de usuarios, roles y permisos por módulo, bitácora.

### B2. Dashboard
Indicadores: socios activos, artículos publicados, mensajes sin leer, próximos congresos, descargas. Gráficas (Chart.js).

### B3. CRUDs de contenido
Banners, Socios (con importación CSV), Categorías, Etiquetas, Artículos (editor WYSIWYG, p. ej. Summernote para BS4), Redes sociales, Páginas/bloques de Inicio.

### B4. Mensajes de contacto
Bandeja con estados (nuevo, leído, respondido, archivado), filtros, exportar.

### B5. Configuración
Nombre, logo, colores, datos de contacto, correos destinatarios, SMTP, llaves de reCAPTCHA, token de Instagram, metadatos SEO por defecto, aviso de privacidad.

### B6. Notificaciones PUSH
Suscripción de navegadores (Web Push/VAPID) para usuarios del admin; envío de notificación al crear mensaje de contacto o eventos de congreso; envío manual a segmentos.

### B7. Reportes
- Reportes en modo gráfico y exportables a **PDF (TCPDF)**.
- Cada PDF lleva **código QR en la parte superior** con el folio/URL de verificación del reporte.
- Reportes mínimos: socios por especialidad/estado, artículos y descargas por periodo, mensajes de contacto por periodo, asistentes por congreso (desde subsistema).

---

## Requisitos no funcionales
- PWA instalable, responsivo, Lighthouse ≥ 85 en Performance, Accesibilidad, SEO.
- Compatibilidad: últimas 2 versiones de Chrome, Safari, Firefox, Edge; iOS 15+, Android 9+.
- Respaldo diario de BD (script cron) en hosting.
- Tiempo de desarrollo: **26 días hábiles** a partir de la entrega completa de información.
