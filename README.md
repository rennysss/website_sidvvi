# SIDVVI — Sitio web + Panel de control

Sociedad de Investigación y Docencia en Virología, Vacunas e Infectología.

- Sitio: **https://sidvvi.com**
- Panel: **https://sidvvi.com/admin-panel**

Stack: CodeIgniter 3.1.13 · PHP 8.1 · MySQL · Bootstrap 4.6 · jQuery.

## Instalación local (Mac)

```bash
# 1. Dependencias (descarga CodeIgniter, TCPDF y QR en /vendor)
composer install

# 2. Base de datos con información dummy
mysql -u root -p -e "CREATE DATABASE sidvvi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
mysql -u root -p sidvvi < database/sidvvi.sql

# 3. Variables de entorno
cp .env.example .env        # ajusta DB_USER / DB_PASS

# 4. Levantar el sitio
php -S localhost:8080 router.php
```

Abre http://localhost:8080 y el panel en http://localhost:8080/admin-panel

| Usuario panel | Contraseña |
|---|---|
| admin@sidvvi.com | `Sidvvi2026!` |

> **Cambia la contraseña antes de publicar.** (Panel → Usuarios)

También funciona con MAMP / Laragon / XAMPP apuntando el VirtualHost a esta carpeta (usa `.htaccess`).

## Trabajar con Cursor

1. Abre la carpeta en Cursor. Lee primero `AGENTS.md` y `.cursor/rules/` (se cargan solos).
2. Sigue `docs/04-plan-de-trabajo.md`: las fases pendientes tienen su prompt en `prompts/`.
3. Guía visual: `docs/07-guia-de-diseno.md`.

## Estructura rápida

```
application/
  controllers/          páginas públicas (Home, Pages, Directory_members, Library, ...)
  controllers/admin/    panel /admin-panel (Access = login, Dashboard, CRUDs)
  core/                 MY_Controller, Public_Controller, Admin_Controller, Admin_Crud_Controller, MY_Model
  models/ libraries/ helpers/ views/
assets/                 css, js, img (logo, placeholders), vendor (bootstrap, jquery, fontawesome...)
database/sidvvi.sql     esquema + datos dummy
docs/ prompts/ .cursor/ documentación para Cursor
```

## Publicar en sidvvi.com
Ver `docs/06-checklist-entrega.md` y la fase 10 en `prompts/`. Resumen: subir archivos, `composer install --no-dev`, importar SQL, `.env` con `APP_ENV=production` y `BASE_URL=https://sidvvi.com/`, descomentar en `.htaccess` el bloque de HTTPS, dar permisos de escritura a `uploads/` y `application/logs/`.
