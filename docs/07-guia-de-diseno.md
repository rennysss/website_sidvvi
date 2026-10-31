# 07 — Guía de diseño · SIDVVI

## Referencias
| Referencia | Qué se toma |
|---|---|
| **Cityvile** (templatekit.jegtheme.com/cityvile) | Diseño y acomodo: topbar, header con botón de acción, hero con 2 botones, bloque con firma, tarjetas de servicios, eventos con fecha destacada, equipo con redes, accesos rápidos + documentos, blog, footer oscuro. |
| **amimc.org.mx** | Cómo interactúa la información: constancias de congresos como acceso directo, publicaciones con portada, membresía y directorio en el menú, bloque de informes/contacto. |
| **nuevo.ammvih.org** | Mesa directiva, carrusel con botón de registro al evento, artículos de interés, bloque de colaboración con datos de contacto. |

No se copian textos, imágenes ni código de esas páginas: solo estructura e interacción.

## Colores (del logo)
| Token CSS | Hex | Uso |
|---|---|---|
| `--sv-primary` | `#23477A` | Color principal: botones, títulos, íconos |
| `--sv-primary-dark` | `#142C4F` | Topbar, títulos, hover |
| `--sv-primary-darker` | `#0E1F38` | Footer, sidebar del panel |
| `--sv-accent` | `#B8143F` | **Solo acentos**: eyebrows, botón CTA, fecha de eventos, subrayado del menú |
| `--sv-sky` | `#4F8FC9` | Íconos secundarios, bordes decorativos |
| `--sv-soft` | `#F4F7FB` | Fondo alterno de secciones |
| Fondo | `#FFFFFF` | **Color predominante** |

Regla: blanco domina; azul estructura; el rojo no debe pasar de ~5% de la pantalla.

## Tipografía
- Títulos: **Poppins** 600/700 · Texto: **Inter** 400/500 (archivos locales en `assets/vendor/fonts`).
- H2 de sección 2.2rem (1.7rem en móvil). Eyebrow: 0.82rem, mayúsculas, rojo, con línea a la izquierda.

## Componentes (clases)
| Componente | Clase / parcial |
|---|---|
| Encabezado de sección | `section_title(['eyebrow'=>..,'title'=>..,'text'=>..,'center'=>TRUE])` |
| Sección | `.section` (90px arriba/abajo) · `.bg-soft` para alternar |
| Encabezado de página interna | `partials/page_header` (se activa pasando `$header` a `render()`) |
| Tarjeta de servicio/área | `.card.card-service` + `.icon-circle` |
| Tarjeta de evento | `partials/card_congress` (`.date-badge`) |
| Tarjeta de artículo | `partials/card_article` |
| Tarjeta de equipo | `partials/card_board` (redes aparecen al pasar el mouse) |
| Franja de cifras | `partials/stats` (contador animado) |
| Banda CTA | `partials/cta_membership` |
| Accesos rápidos | `.quick-links` |
| Documento descargable | `.doc-item` |
| Botones | `.btn-primary` (azul), `.btn-accent` (rojo CTA), `.btn-outline-primary` |

## Inicio — orden de secciones
1. Hero / carrusel de banners (admin: Banners)
2. Mensaje de la presidencia con firma (admin: Textos → `president_message`)
3. Áreas de investigación y docencia, 6 tarjetas + franja «Hazte socio» (admin: Áreas)
4. Cifras (calculadas + año de fundación en Configuración)
5. Próximos congresos (subsistema de Congresos)
6. Mesa directiva, 4 integrantes (admin: Mesa directiva)
7. Accesos rápidos + Avisos y documentos (admin: Accesos rápidos, Documentos)
8. Biblioteca: 3 publicaciones recientes
9. Instagram: 6 publicaciones
10. CTA de membresía (admin: Textos → `cta_membership`)

## Menú
Inicio · Nosotros (Quiénes somos, Mesa directiva) · Directorio · Biblioteca · Congresos · Noticias · Membresía · Contacto · botón **Constancias** (configurable).

## Responsivo
Probado en 390, 768, 1024, 1366 px. Breakpoints de Bootstrap 4. En móvil: menú colapsable, botones del hero a ancho completo, sin flechas en carrusel.

## Imágenes (para cuando llegue el material del cliente)
| Uso | Tamaño |
|---|---|
| Banner escritorio | 1920×900 |
| Banner móvil | 800×1000 |
| Portada de artículo / congreso | 1200×780 |
| Foto de socio / mesa directiva | 600×600 cuadrada |
| Imagen «Mensaje de la presidencia» | 900×1000 |
| Logo | SVG o PNG transparente (versión azul y versión blanca) |
