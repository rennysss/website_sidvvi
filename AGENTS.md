# AGENTS.md — Contexto maestro del proyecto SIDVVI

## Qué es
Sitio web institucional + panel de control de **SIDVVI — Sociedad de Investigación y Docencia en Virología, Vacunas e Infectología**.
Cliente: Luis Parra. Proveedor: Atomoclip / Mandala Group (Director: Renny S. Salirrosas Sandoval).

El **subsistema de Congresos** (creación de congresos, registro de asistentes, constancias PDF con QR) **ya existe** en un proyecto aparte.
Este repositorio es **todo lo demás** y solo **lee** los congresos (ver `.cursor/rules/50-integracion-congresos.mdc`).

## URLs
- **Sitio público:** https://sidvvi.com
- **Panel de control:** https://sidvvi.com/admin-panel → login → `/admin-panel/dashboard`
- Todas las pantallas del panel viven en `/admin-panel/...`. Internamente los controladores están en `application/controllers/admin/`, pero `/admin/...` responde 404.
- Genera enlaces del panel SIEMPRE con `admin_url('ruta')`.

## Estado actual (31-oct-2026)
La base ya está construida y funcionando con **información dummy**:
- Sitio público completo y responsivo: Inicio, Nosotros, Mesa directiva, Directorio (filtros Ajax), Biblioteca Virtual, Congresos, Noticias (Instagram desde caché), Membresía (solicitud), Constancias (puente al subsistema), Contacto (Ajax), Aviso de privacidad, 404, sitemap.xml, PWA.
- Panel `/admin-panel` con login, roles, dashboard con gráficas y CRUD de todos los módulos.
- Pendiente: ver `docs/04-plan-de-trabajo.md` (contenido real, API de Instagram, PUSH, reportes PDF/QR, integración real de Congresos, QA y despliegue).

## Diseño
- **Bootstrap 4.6.2** (decisión confirmada por el cliente; NO migrar a Bootstrap 5) + jQuery 3.7.
- Predomina el **blanco**; azul del logo `#23477A` como color principal; rojo `#B8143F` solo como acento.
- Acomodo inspirado en la plantilla **Cityvile** (templatekit.jegtheme.com/cityvile).
- Interacción con la información inspirada en **amimc.org.mx** y **nuevo.ammvih.org**.
- Detalle completo en `docs/07-guia-de-diseno.md`. Variables CSS en `assets/css/sidvvi.css`.

## Stack obligatorio (no cambiar sin autorización)
- PHP 7.4+ (objetivo 8.1) · **CodeIgniter 3.1.13** (vía Composer, en `vendor/codeigniter/framework`) · MySQL/MariaDB utf8mb4
- Bootstrap 4.6 · jQuery 3 · Font Awesome 6 Free · Chart.js 4 · Summernote (BS4) — todos locales en `assets/vendor/`
- TCPDF + QR (`chillerlan/php-qrcode`) para reportes · Web Push (`minishlink/web-push`) para notificaciones
- Sin otras dependencias de frontend (no npm build, no SASS): CSS plano con variables.

## Reglas de trabajo para el agente
1. Lee `docs/02-arquitectura.md` antes de crear archivos: casi todo ya tiene su lugar.
2. Para un nuevo módulo del panel **extiende `Admin_Crud_Controller`** y declara `$table` y `$fields` (ver `controllers/admin/Banners.php`). No dupliques vistas de CRUD.
3. Páginas públicas: extienden `Public_Controller`, usan `$this->render('vista', $data, $page_header)` y `$this->seo->set(...)`.
4. Cambios de base de datos → actualiza `database/sidvvi.sql` (esquema + dummy) y crea migración si ya hay datos reales.
5. Textos de interfaz en español de México; código en inglés.
6. No agregues funcionalidades fuera de la cotización: todo cambio fuera de las vistas previas autorizadas se cotiza aparte.
7. Al terminar una tarea: lista de archivos tocados, cómo probarlo y pendientes.
