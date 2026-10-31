# Fase B — Cargar el contenido real del cliente

> Pega esto en Cursor (modo Agent). Antes lee `AGENTS.md` y `docs/02-arquitectura.md`. Respeta el código existente: extiende, no reescribas.

El cliente ya entregó su material (logos, textos, fotos, socios). Haz lo siguiente:
1. Logos: reemplaza `assets/img/logo.png` y `assets/img/logo-white.png` (versión para fondos oscuros). Regenera `icon-192.png`, `icon-512.png`, `favicon.ico` y `og-default.jpg` (1200×630).
2. Crea `database/contenido-real.sql` con UPDATE/INSERT para: `settings` (teléfono, correo, dirección, horario, mapa, año de fundación, destinatarios), `page_blocks` (about, mission, vision, values, president_message con firma «Nombre|Cargo», membership_intro, privacy, cta_membership), `areas`, `board_members`, `social_links`, `categories`.
3. Borra los datos dummy: socios, artículos e Instagram con «(dummy)» o `Ejemplo` en el nombre, y las imágenes de `assets/img/placeholders/` que ya no se usen.
4. Verifica que ninguna página muestre «Lorem ipsum», «dummy» o «Ejemplo» (búscalo en BD y vistas).
5. Revisa contraste y que las fotos respeten los tamaños de `docs/07-guia-de-diseno.md`.
