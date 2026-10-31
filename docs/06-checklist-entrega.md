# 06 — Checklist de entrega

## Por cada módulo
- [ ] CRUD admin completo con validaciones y mensajes en español
- [ ] Permisos por rol verificados
- [ ] Vista pública responsiva (360 / 768 / 1024 / 1440 px)
- [ ] `title`, `meta description`, OG y canonical correctos
- [ ] Sin errores en consola ni en `application/logs/`
- [ ] Registro en `audit_logs` al crear/editar/eliminar

## Seguridad
- [ ] CSRF activo y funcionando en Ajax
- [ ] Login con límite de intentos
- [ ] `uploads/` sin ejecución PHP
- [ ] `.env` fuera del repo, `ENVIRONMENT=production`
- [ ] Contraseñas por defecto cambiadas

## SEO / PWA
- [ ] `sitemap.xml` y `robots.txt`
- [ ] JSON-LD Organization / Article / Event
- [ ] Lighthouse ≥ 85 (Performance, Accesibilidad, Buenas prácticas, SEO)
- [ ] PWA instalable (manifest + SW + íconos) y página offline
- [ ] Notificación push de prueba recibida en Chrome escritorio y Android

## Integraciones
- [ ] Instagram muestra feed y cae a caché si falla la API
- [ ] Contacto: correo recibido, auto-respuesta, mensaje en bandeja
- [ ] Congresos: listado e historial con datos reales del subsistema
- [ ] Reporte PDF con logo y QR en la parte superior; el QR abre la URL de verificación

## Pase a producción
- [ ] `sidvvi.com` con DNS apuntando al hosting y SSL activo; `http://` y `www` redirigen a `https://sidvvi.com`
- [ ] `https://sidvvi.com/admin-panel` muestra el login; `/admin` responde 404; el panel tiene `noindex`
- [ ] Migraciones ejecutadas en el servidor, datos maestros cargados
- [ ] Cron: refresco de Instagram (cada hora) y respaldo diario de BD
      Instagram: `php /ruta/sidvvi/index.php cli instagram` (ejemplo cada hora: `0 * * * *`).
      El token vive cifrado en la base (panel → Conexión Instagram), no en el código.
      Respaldo: `mysqldump sidvvi > respaldo-$(date +\%F).sql`
- [ ] Capacitación a usuarios y acta firmada
- [ ] Inicio del periodo de soporte (mínimo 1 mes)
