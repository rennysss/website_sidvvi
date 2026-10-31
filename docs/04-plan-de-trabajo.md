# 04 — Plan de trabajo (26 días hábiles)

> Cuenta a partir de la entrega completa de información del cliente.
> ✅ = ya construido con datos dummy · ⏳ = pendiente (tiene prompt en `prompts/`).

| Fase | Estado | Qué incluye |
|---|---|---|
| 0. Arranque (CI3, .env, BD, rutas, PWA base) | ✅ | |
| 1. Núcleo + login `/admin-panel` + roles + dashboard | ✅ | |
| 2. Layout público, SEO, sitemap, redes | ✅ | |
| 3. Inicio y banners | ✅ | |
| 4. Directorio de socios (filtros Ajax, detalle) | ✅ | Falta importación CSV → `prompts/fase-4b-importar-socios.md` |
| 5. Biblioteca Virtual | ✅ | |
| Extra: Nosotros, Mesa directiva, Membresía, Constancias | ✅ | Aprobado por el director (referencias AMIMC/AMMVIH) |
| **A. Vista previa 1 al cliente** | ⏳ | Mandar capturas/URL de pruebas para autorización (cláusula XI) |
| **B. Carga de contenido real** | ⏳ | `prompts/fase-B-contenido-real.md` |
| 6. Noticias: API de Instagram | ✅ | Token cifrado en la base. Falta pegar el token real en el panel. |
| 7. Integración real con el subsistema de Congresos | ⏳ | `prompts/fase-7-congresos.md` |
| 8. Contacto: SMTP + reCAPTCHA | ✅ parcial | Código listo. Falta SMTP real y llaves reCAPTCHA en `.env`. |
| 9. Notificaciones PUSH + Reportes PDF con QR | ✅ | Llaves VAPID en `.env`. Probar la campana en un navegador. |
| 10. QA y publicación en sidvvi.com | ⏳ | `prompts/fase-10-qa-produccion.md` |

## Hitos de autorización (cláusula XI de la cotización)
- Vista previa 1: diseño y navegación con dummy (ya posible).
- Vista previa 2: con contenido real.
- Vista previa 3: sitio completo en servidor de pruebas.
Cambios fuera de lo autorizado → cotización por separado.
