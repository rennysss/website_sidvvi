# 02 — Arquitectura · SIDVVI (estado real del código)

## Capas
```
Navegador (Bootstrap 4.6 + jQuery + Service Worker)
   │ HTML / JSON (Ajax con token CSRF)
CodeIgniter 3:  Controllers → Libraries/Models → Views
   │
MySQL: tablas del sitio  +  tablas del subsistema de Congresos (solo lectura, config/congress.php)
```

## Árbol
```
index.php                 front controller (+ carga .env y zona horaria)
router.php                router SOLO para `php -S` en desarrollo
.htaccess                 reescritura, bloqueo de carpetas internas, caché (HTTPS comentado)
manifest.json · service-worker.js · offline.html · robots.txt
composer.json             codeigniter/framework 3.1.13, tcpdf, php-qrcode
database/sidvvi.sql       esquema + datos dummy (fuente de verdad del esquema)
application/
  config/   config.php, routes.php, database.php, email.php, congress.php (mapeo del subsistema)
  core/
    MY_Controller.php          base (settings + zona horaria MySQL) y require de los demás
    Public_Controller.php      sitio: render(), not_found(), SEO, redes sociales
    Admin_Controller.php       panel: exige login, roles viewer < editor < superadmin, render()
    Admin_Crud_Controller.php  CRUD genérico declarativo (listado, búsqueda, paginación, alta, edición, borrado, uploads)
    MY_Model.php               CRUD base, soft delete, unique_slug()
  controllers/
    Home, Pages (nosotros, mesa-directiva, aviso-de-privacidad), Directory_members, Library,
    Congresses, News, Membership, Certificates (constancias), Contact, Sitemap, Errors
    admin/  Access (login/logout/recuperar), Dashboard, Banners, Blocks, Areas, Quick_links,
            Documents, Board, Members, Specialties, Member_types, Memberships, Articles,
            Categories, Tags, Instagram, Congresses (solo consulta), Messages, Social_links,
            Users, Settings
  libraries/  Auth (login, bloqueo por intentos, reset), Seo (meta/OG/JSON-LD), Mailer (SMTP + plantillas)
  models/     Setting_model, Content_model, Member_model, Article_model, Congress_model
  helpers/    sidvvi_helper.php  → admin_url, setting, e, img_url, link_url, slugify, fecha_es,
                                   rango_fechas_es, status_label, json_response, audit_log,
                                   section_title, str_limit
  language/spanish/  mensajes de validación y subida en español
  views/
    layouts/   public.php, admin.php, auth.php
    partials/  header, footer, page_header, social_icons, section_title, card_article,
               card_congress, card_board, stats, cta_membership
    public/    home, about, board, simple, 404, contact, membership, certificates, news,
               directory/{index,_list,show}, library/{index,show,_sidebar}, congresses/{index,show}
    admin/     dashboard, settings, congresses, crud/{index,form}, auth/{login,forgot,reset}
    emails/    layout, contact_notification, contact_ack, membership_notification, password_reset
assets/
  css/sidvvi.css (sitio) · css/admin.css (panel)
  js/app.js (sitio) · js/admin.js (panel)
  img/  logo.png, logo-white.png, icon-192/512, favicon, og-default.jpg, placeholders/ (dummy)
  vendor/ bootstrap 4.6.2, jquery 3.7.1, fontawesome 6.5, chart.js 4, summernote 0.8 (bs4), fonts
uploads/   archivos subidos desde el panel (.htaccess impide ejecutar PHP)
```

## Rutas públicas
| URL | Controlador |
|---|---|
| `/` | `home/index` |
| `/nosotros` · `/mesa-directiva` · `/aviso-de-privacidad` | `pages/about` · `pages/board` · `pages/privacy` |
| `/directorio` · `/directorio/buscar` (Ajax) · `/directorio/{slug}` | `directory_members/*` |
| `/biblioteca` · `/biblioteca/categoria/{slug}` · `/biblioteca/{slug}` · `/biblioteca/descargar/{id}` | `library/*` |
| `/congresos` · `/congresos/{slug}` | `congresses/*` |
| `/noticias` | `news/index` |
| `/membresia` · POST `/membresia/enviar` | `membership/*` |
| `/constancias` | `certificates/index` |
| `/contacto` · POST `/contacto/enviar` | `contact/*` |
| `/sitemap.xml` | `sitemap/index` |

## Rutas del panel
| URL | Destino |
|---|---|
| `/admin-panel` | `admin/access/login` |
| POST `/admin-panel/login` | `admin/access/do_login` |
| `/admin-panel/logout` · `/admin-panel/recuperar` · `/admin-panel/restablecer/{token}` | `admin/access/*` |
| `/admin-panel/{modulo}[/create|/edit/{id}|/delete/{id}]` | `admin/{modulo}/...` |
| `/admin`, `/admin/*` | 404 |

> El controlador de login se llama `Access` (no `Auth`) para no chocar con la librería `Auth`.

## Cómo agregar un módulo al panel (ejemplo)
```php
class Sponsors extends Admin_Crud_Controller {
    protected $table = 'sponsors';  protected $route = 'sponsors';
    protected $title = 'Patrocinadores'; protected $singular = 'patrocinador';
    protected $icon = 'fas fa-handshake'; protected $order_by = 'sort_order ASC';
    protected $fields = array(
        'logo' => array('label' => 'Logo', 'type' => 'image', 'list' => TRUE),
        'name' => array('label' => 'Nombre', 'type' => 'text', 'rules' => 'required', 'list' => TRUE),
        'url'  => array('label' => 'Sitio', 'type' => 'url'),
        'is_active' => array('label' => 'Visible', 'type' => 'checkbox', 'list' => TRUE),
    );
}
```
Luego agrega la tabla a `database/sidvvi.sql` y el enlace en el menú de `views/layouts/admin.php`.
Hooks disponibles: `list_query()`, `before_save()`, `after_save()`, `form_extra()`, `collect()`, `set_rules()`.

## Flujos clave
- **Contacto / Membresía:** POST Ajax → validación → honeypot + límite por IP → guarda en BD → correo SMTP (si `SMTP_HOST` está en `.env`) → JSON `{ok, message, data, csrf}`.
- **Congresos:** `Congress_model` lee la tabla y columnas configuradas en `config/congress.php`. Hoy apunta a la tabla demo `cg_congresses`.
- **Instagram:** las vistas leen `instagram_cache`. La fase 6 agrega la librería que la llena desde la API cada hora.
- **Zona horaria:** `APP_TIMEZONE` (America/Mexico_City) en PHP y `SET time_zone` en MySQL en cada petición.

## Entornos
- **Local:** `php -S localhost:8080 router.php` o MAMP/Laragon. `BASE_URL=http://localhost:8080/`.
- **Producción:** `https://sidvvi.com` (dominio en GoDaddy). PHP 8.1, HTTPS obligatorio (PWA/Push), cron.
