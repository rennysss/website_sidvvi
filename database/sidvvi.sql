-- =========================================================================
-- SIDVVI · Sociedad de Investigación y Docencia en Virología, Vacunas e Infectología
-- Esquema + datos DUMMY  ·  MySQL 5.7+ / MariaDB 10.4+  ·  InnoDB · utf8mb4
--
-- Instalación:   mysql -u USUARIO -p sidvvi < database/sidvvi.sql
-- Usuario panel: admin@sidvvi.com  /  Sidvvi2026!   (CAMBIAR AL PUBLICAR)
--
-- NOTA: la tabla cg_congresses es una TABLA DEMO que simula al subsistema de
-- Congresos. Al integrar el subsistema real, elimínala y ajusta
-- application/config/congress.php para apuntar a sus tablas.
-- =========================================================================
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS ci_sessions, login_attempts, audit_logs, users, settings, page_blocks,
  banners, areas, quick_links, documents, board_members, member_types, specialties, members,
  categories, tags, article_tags, article_attachments, articles, instagram_cache, social_links,
  contact_messages, membership_requests, cg_congresses, push_subscriptions, notifications, report_logs,
  webauthn_credentials, integration_secrets;

-- ---------------------------------------------------------------- Seguridad
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role ENUM('superadmin','editor','viewer') NOT NULL DEFAULT 'editor',
  name VARCHAR(150) NOT NULL,
  email VARCHAR(191) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  reset_token VARCHAR(100) NULL,
  reset_expires_at DATETIME NULL,
  last_login_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE login_attempts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(191) NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email_ip (email, ip_address, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ci_sessions (
  id VARCHAR(128) NOT NULL PRIMARY KEY,
  ip_address VARCHAR(45) NOT NULL,
  timestamp INT UNSIGNED NOT NULL DEFAULT 0,
  data BLOB NOT NULL,
  INDEX ci_sessions_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  action VARCHAR(50) NOT NULL,
  entity VARCHAR(50) NOT NULL,
  entity_id INT UNSIGNED NULL,
  details TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_entity (entity, entity_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- Configuración
CREATE TABLE settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT NULL,
  `group` VARCHAR(50) NOT NULL DEFAULT 'general',
  label VARCHAR(150) NOT NULL,
  type VARCHAR(20) NOT NULL DEFAULT 'text',
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE page_blocks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL UNIQUE,
  eyebrow VARCHAR(100) NULL,
  title VARCHAR(200) NULL,
  body MEDIUMTEXT NULL,
  image VARCHAR(255) NULL,
  extra VARCHAR(255) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- Inicio
CREATE TABLE banners (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  eyebrow VARCHAR(100) NULL,
  title VARCHAR(200) NOT NULL,
  subtitle VARCHAR(300) NULL,
  image_desktop VARCHAR(255) NOT NULL,
  image_mobile VARCHAR(255) NULL,
  button_text VARCHAR(60) NULL,
  button_url VARCHAR(255) NULL,
  button2_text VARCHAR(60) NULL,
  button2_url VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  starts_at DATETIME NULL,
  ends_at DATETIME NULL,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'published',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE areas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description VARCHAR(400) NULL,
  icon VARCHAR(60) NOT NULL DEFAULT 'fas fa-virus',
  url VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE quick_links (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  url VARCHAR(255) NOT NULL,
  icon VARCHAR(60) NOT NULL DEFAULT 'fas fa-arrow-right',
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE documents (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  description VARCHAR(300) NULL,
  file_path VARCHAR(255) NULL,
  published_at DATE NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- Nosotros / Mesa directiva
CREATE TABLE board_members (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  position VARCHAR(150) NOT NULL,
  photo VARCHAR(255) NULL,
  bio TEXT NULL,
  email VARCHAR(191) NULL,
  linkedin VARCHAR(255) NULL,
  twitter VARCHAR(255) NULL,
  period VARCHAR(30) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- Directorio
CREATE TABLE member_types (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE specialties (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE members (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  member_type_id INT UNSIGNED NULL,
  specialty_id INT UNSIGNED NULL,
  member_number VARCHAR(30) NULL,
  title_prefix VARCHAR(20) NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(150) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  position VARCHAR(150) NULL,
  institution VARCHAR(200) NULL,
  city VARCHAR(100) NULL,
  state VARCHAR(100) NULL,
  email_public VARCHAR(191) NULL,
  phone_public VARCHAR(30) NULL,
  website VARCHAR(255) NULL,
  photo VARCHAR(255) NULL,
  bio TEXT NULL,
  is_public TINYINT(1) NOT NULL DEFAULT 1,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  joined_at DATE NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  INDEX idx_public (is_active, is_public),
  INDEX idx_state (state),
  FOREIGN KEY (member_type_id) REFERENCES member_types(id),
  FOREIGN KEY (specialty_id) REFERENCES specialties(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- Biblioteca Virtual
CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  description TEXT NULL,
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tags (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE articles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  type ENUM('article','report','publication','guide') NOT NULL DEFAULT 'article',
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  author_name VARCHAR(150) NULL,
  excerpt VARCHAR(500) NULL,
  body MEDIUMTEXT NULL,
  cover_image VARCHAR(255) NULL,
  attachment VARCHAR(255) NULL,
  downloads INT UNSIGNED NOT NULL DEFAULT 0,
  views INT UNSIGNED NOT NULL DEFAULT 0,
  meta_description VARCHAR(160) NULL,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  published_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  INDEX idx_status_pub (status, published_at),
  FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE article_tags (
  article_id INT UNSIGNED NOT NULL,
  tag_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (article_id, tag_id),
  FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- Noticias (Instagram)
CREATE TABLE instagram_cache (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ig_media_id VARCHAR(64) NOT NULL UNIQUE,
  media_type VARCHAR(30) NOT NULL DEFAULT 'IMAGE',
  media_url TEXT NOT NULL,
  permalink VARCHAR(255) NOT NULL,
  caption TEXT NULL,
  posted_at DATETIME NOT NULL,
  fetched_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_posted (posted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- Redes sociales
CREATE TABLE social_links (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  network VARCHAR(30) NOT NULL,
  url VARCHAR(255) NOT NULL,
  icon_class VARCHAR(60) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- Contacto y Membresía
CREATE TABLE contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(191) NOT NULL,
  phone VARCHAR(30) NULL,
  subject VARCHAR(200) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','replied','archived','spam') NOT NULL DEFAULT 'new',
  mail_sent TINYINT(1) NOT NULL DEFAULT 0,
  notes TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status, created_at),
  INDEX idx_ip (ip_address, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE membership_requests (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  member_type_id INT UNSIGNED NULL,
  full_name VARCHAR(200) NOT NULL,
  email VARCHAR(191) NOT NULL,
  phone VARCHAR(30) NULL,
  specialty VARCHAR(150) NULL,
  institution VARCHAR(200) NULL,
  city VARCHAR(100) NULL,
  state VARCHAR(100) NULL,
  professional_id VARCHAR(50) NULL,
  comments TEXT NULL,
  status ENUM('new','in_review','approved','rejected') NOT NULL DEFAULT 'new',
  notes TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status, created_at),
  FOREIGN KEY (member_type_id) REFERENCES member_types(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- Congresos (TABLA DEMO)
CREATE TABLE cg_congresses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  edition VARCHAR(50) NULL,
  starts_at DATE NOT NULL,
  ends_at DATE NULL,
  venue VARCHAR(200) NULL,
  city VARCHAR(150) NULL,
  modality ENUM('presencial','virtual','hibrido') NOT NULL DEFAULT 'presencial',
  image VARCHAR(255) NULL,
  summary VARCHAR(500) NULL,
  description MEDIUMTEXT NULL,
  registration_url VARCHAR(255) NULL,
  certificates_url VARCHAR(255) NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- PUSH y reportes (fase 9)
CREATE TABLE push_subscriptions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  endpoint TEXT NOT NULL,
  endpoint_hash CHAR(64) NOT NULL UNIQUE,
  p256dh VARCHAR(255) NOT NULL,
  auth VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  body VARCHAR(500) NOT NULL,
  url VARCHAR(255) NULL,
  sent_count INT UNSIGNED NOT NULL DEFAULT 0,
  created_by INT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE report_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  folio CHAR(12) NOT NULL UNIQUE,
  report_type VARCHAR(50) NOT NULL,
  params TEXT NULL,
  generated_by INT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Llaves del equipo (Face ID, Touch ID, Windows Hello) para el panel
CREATE TABLE webauthn_credentials (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  credential_id VARCHAR(512) NOT NULL,
  public_key TEXT NOT NULL,
  sign_count INT UNSIGNED NOT NULL DEFAULT 0,
  label VARCHAR(120) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_used_at DATETIME NULL,
  UNIQUE KEY uq_credential (credential_id),
  INDEX idx_user (user_id),
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Credenciales de integraciones, cifradas. Solo las edita el superadministrador.
CREATE TABLE integration_secrets (
  `key` VARCHAR(100) NOT NULL PRIMARY KEY,
  value_enc TEXT NULL,
  expires_at DATETIME NULL,
  updated_at DATETIME NULL,
  updated_by INT UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================================
-- DATOS DUMMY  (reemplazar con la información que entregue el cliente)
-- =========================================================================

-- Contraseña: Sidvvi2026!
INSERT INTO users (role, name, email, password_hash) VALUES
('superadmin', 'Administrador SIDVVI', 'admin@sidvvi.com', '$2y$10$jyzRP0LZV30v4KpjHagvk.gPXrR2S0EAeI5ereR/Ci5vE6ICKH3/m');

INSERT INTO settings (`key`, `value`, `group`, label, type, sort_order) VALUES
('site_name', 'SIDVVI', 'general', 'Nombre corto', 'text', 1),
('site_full_name', 'Sociedad de Investigación y Docencia en Virología, Vacunas e Infectología', 'general', 'Nombre completo', 'text', 2),
('tagline', 'Ciencia, docencia y prevención al servicio de la salud', 'general', 'Lema', 'text', 3),
('footer_about', 'Somos una sociedad médica y científica dedicada a la investigación, la docencia y la difusión del conocimiento en virología, vacunas e infectología.', 'general', 'Texto del pie de página', 'textarea', 4),
('founded_year', '2015', 'general', 'Año de fundación', 'text', 5),
('phone', '+52 (449) 000 0000', 'contact', 'Teléfono', 'text', 10),
('whatsapp', '524490000000', 'contact', 'WhatsApp (solo números)', 'text', 11),
('email', 'contacto@sidvvi.com', 'contact', 'Correo público', 'text', 12),
('address', 'Av. Ejemplo 123, Col. Centro, Aguascalientes, Ags. C.P. 20000', 'contact', 'Dirección', 'textarea', 13),
('schedule', 'Lun - Vie 09:00 - 17:00', 'contact', 'Horario de atención', 'text', 14),
('map_embed', 'https://www.google.com/maps?q=Aguascalientes,Ags&output=embed', 'contact', 'URL del mapa (embed de Google Maps)', 'text', 15),
('contact_recipients', 'contacto@sidvvi.com', 'contact', 'Destinatarios del formulario (separados por coma)', 'text', 16),
('membership_recipients', 'membresia@sidvvi.com', 'contact', 'Destinatarios de solicitudes de membresía', 'text', 17),
('seo_description', 'SIDVVI: Sociedad de Investigación y Docencia en Virología, Vacunas e Infectología. Directorio de socios, biblioteca virtual, congresos y constancias.', 'seo', 'Descripción SEO por defecto', 'textarea', 20),
('seo_keywords', 'virología, vacunas, infectología, congresos médicos, sociedad médica, México', 'seo', 'Palabras clave', 'text', 21),
('instagram_user', 'sidvvi', 'social', 'Usuario de Instagram', 'text', 30),
('header_cta_text', 'Constancias', 'general', 'Botón del encabezado (texto)', 'text', 6),
('header_cta_url', 'constancias', 'general', 'Botón del encabezado (URL)', 'text', 7);

INSERT INTO page_blocks (code, eyebrow, title, body, image, extra) VALUES
('president_message', 'Mensaje de la presidencia', 'Impulsamos la ciencia que protege a nuestra comunidad',
 '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla.</p><p>Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur. Vestibulum id ligula porta felis euismod semper.</p><ul><li>Investigación clínica y básica de alto nivel.</li><li>Formación continua para profesionales de la salud.</li><li>Divulgación responsable sobre vacunas e infecciones.</li></ul>',
 'assets/img/placeholders/about.jpg', 'Dra. Nombre Apellido|Presidenta de SIDVVI'),
('about', 'Quiénes somos', 'Sociedad de Investigación y Docencia en Virología, Vacunas e Infectología',
 '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam id dolor id nibh ultricies vehicula ut id elit. Maecenas faucibus mollis interdum.</p><p>Curabitur blandit tempus porttitor. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod.</p>',
 'assets/img/placeholders/about.jpg', NULL),
('mission', NULL, 'Misión', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fomentar la investigación y la docencia en virología, vacunas e infectología para mejorar la salud de la población.</p>', NULL, 'fas fa-bullseye'),
('vision', NULL, 'Visión', '<p>Ser la sociedad científica de referencia en México en el estudio y la prevención de enfermedades infecciosas. Lorem ipsum dolor sit amet.</p>', NULL, 'fas fa-eye'),
('values', NULL, 'Valores', '<p>Ética, rigor científico, colaboración, compromiso social y actualización permanente. Lorem ipsum dolor sit amet.</p>', NULL, 'fas fa-hand-holding-heart'),
('membership_intro', 'Membresía', 'Forma parte de SIDVVI',
 '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Únete a una comunidad de especialistas comprometidos con la ciencia y la salud pública.</p><h5>Beneficios</h5><ul><li>Descuentos en congresos y cursos.</li><li>Acceso a la Biblioteca Virtual completa.</li><li>Aparición en el Directorio de socios.</li><li>Constancias con valor curricular.</li></ul><h5>Requisitos</h5><ul><li>Título y cédula profesional.</li><li>Carta de motivos.</li><li>Pago de cuota anual.</li></ul>',
 NULL, NULL),
('privacy', NULL, 'Aviso de privacidad', '<p><strong>Texto pendiente de entregar por el cliente.</strong></p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Los datos personales recabados a través de este sitio se utilizarán exclusivamente para atender su solicitud.</p>', NULL, NULL),
('cta_membership', '¿Eres profesional de la salud?', 'Únete a la comunidad científica de SIDVVI', '<p>Accede a congresos, cursos, biblioteca virtual y una red de especialistas.</p>', 'assets/img/placeholders/page-header.jpg', NULL);

INSERT INTO banners (eyebrow, title, subtitle, image_desktop, button_text, button_url, button2_text, button2_url, sort_order) VALUES
('Bienvenidos a SIDVVI', 'Investigación y docencia en Virología, Vacunas e Infectología', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus.', 'assets/img/placeholders/hero-1.jpg', 'Conoce SIDVVI', 'nosotros', 'Ver congresos', 'congresos', 1),
('Próximo congreso', 'Congreso Nacional SIDVVI 2027', 'Lorem ipsum dolor sit amet. Aguascalientes, Ags. · 18 al 20 de marzo de 2027.', 'assets/img/placeholders/hero-2.jpg', 'Registrarme', 'congresos/congreso-nacional-sidvvi-2027', 'Programa', 'congresos/congreso-nacional-sidvvi-2027', 2),
('Biblioteca Virtual', 'Conocimiento científico a tu alcance', 'Artículos, guías y reportes elaborados por nuestros socios.', 'assets/img/placeholders/hero-3.jpg', 'Explorar biblioteca', 'biblioteca', 'Hazte socio', 'membresia', 3);

INSERT INTO areas (title, description, icon, url, sort_order) VALUES
('Investigación', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proyectos y redes de investigación.', 'fas fa-microscope', 'biblioteca', 1),
('Docencia', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cursos, diplomados y sesiones académicas.', 'fas fa-chalkboard-teacher', 'congresos', 2),
('Virología', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Estudio de virus y su impacto clínico.', 'fas fa-virus', 'biblioteca', 3),
('Vacunas', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vacunología y esquemas de inmunización.', 'fas fa-syringe', 'biblioteca', 4),
('Infectología', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Diagnóstico y tratamiento de infecciones.', 'fas fa-bacterium', 'biblioteca', 5),
('Salud pública', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Prevención y divulgación a la comunidad.', 'fas fa-users', 'noticias', 6);

INSERT INTO quick_links (title, url, icon, sort_order) VALUES
('Descargar constancias de congresos', 'constancias', 'fas fa-certificate', 1),
('Solicitar membresía', 'membresia', 'fas fa-user-plus', 2),
('Consultar el directorio de socios', 'directorio', 'fas fa-address-book', 3),
('Próximos congresos y eventos', 'congresos', 'fas fa-calendar-alt', 4),
('Contactar a la mesa directiva', 'contacto', 'fas fa-envelope', 5);

INSERT INTO documents (title, description, file_path, published_at, sort_order) VALUES
('Estatutos de la sociedad', 'Documento oficial (PDF de ejemplo).', NULL, '2026-01-15', 1),
('Convocatoria Congreso 2027', 'Bases y fechas para envío de trabajos.', NULL, '2026-09-01', 2),
('Lineamientos de membresía', 'Requisitos y cuotas vigentes.', NULL, '2026-06-10', 3);

INSERT INTO board_members (name, position, photo, period, linkedin, twitter, email, sort_order) VALUES
('Dra. Nombre Apellido', 'Presidenta', 'assets/img/placeholders/avatar-1.jpg', '2025-2027', '#', '#', 'presidencia@sidvvi.com', 1),
('Dr. Nombre Apellido', 'Vicepresidente', 'assets/img/placeholders/avatar-2.jpg', '2025-2027', '#', '#', NULL, 2),
('Dra. Nombre Apellido', 'Secretaria', 'assets/img/placeholders/avatar-3.jpg', '2025-2027', '#', NULL, NULL, 3),
('Dr. Nombre Apellido', 'Tesorero', 'assets/img/placeholders/avatar-4.jpg', '2025-2027', '#', NULL, NULL, 4),
('Dra. Nombre Apellido', 'Vocal de Investigación', 'assets/img/placeholders/avatar-5.jpg', '2025-2027', '#', NULL, NULL, 5),
('Dr. Nombre Apellido', 'Vocal de Docencia', 'assets/img/placeholders/avatar-6.jpg', '2025-2027', '#', NULL, NULL, 6);

INSERT INTO member_types (name) VALUES ('Titular'), ('Adherente'), ('Honorario'), ('Residente / Estudiante');
INSERT INTO specialties (name) VALUES ('Infectología'), ('Virología'), ('Pediatría'), ('Medicina Interna'), ('Microbiología'), ('Epidemiología'), ('Vacunología');

INSERT INTO members (member_type_id, specialty_id, member_number, title_prefix, first_name, last_name, slug, position, institution, city, state, email_public, photo, bio, joined_at) VALUES
(1,1,'SIDVVI-001','Dra.','Ana','Ejemplo Uno','ana-ejemplo-uno','Jefa de Infectología','Hospital General (dummy)','Aguascalientes','Aguascalientes','socio1@ejemplo.com','assets/img/placeholders/avatar-1.jpg','Lorem ipsum dolor sit amet, consectetur adipiscing elit.','2016-02-01'),
(1,2,'SIDVVI-002','Dr.','Luis','Ejemplo Dos','luis-ejemplo-dos','Investigador','Universidad Autónoma (dummy)','Guadalajara','Jalisco','socio2@ejemplo.com','assets/img/placeholders/avatar-2.jpg','Lorem ipsum dolor sit amet, consectetur adipiscing elit.','2017-03-10'),
(1,3,'SIDVVI-003','Dra.','María','Ejemplo Tres','maria-ejemplo-tres','Pediatra infectóloga','Hospital Infantil (dummy)','Ciudad de México','Ciudad de México',NULL,'assets/img/placeholders/avatar-3.jpg','Lorem ipsum dolor sit amet.','2018-05-12'),
(2,4,'SIDVVI-004','Dr.','Carlos','Ejemplo Cuatro','carlos-ejemplo-cuatro','Internista','Clínica Regional (dummy)','Monterrey','Nuevo León','socio4@ejemplo.com','assets/img/placeholders/avatar-4.jpg','Lorem ipsum dolor sit amet.','2019-01-20'),
(1,5,'SIDVVI-005','Q.F.B.','Laura','Ejemplo Cinco','laura-ejemplo-cinco','Jefa de laboratorio','Laboratorio Estatal (dummy)','León','Guanajuato',NULL,'assets/img/placeholders/avatar-5.jpg','Lorem ipsum dolor sit amet.','2019-08-02'),
(1,6,'SIDVVI-006','Dr.','Jorge','Ejemplo Seis','jorge-ejemplo-seis','Epidemiólogo','Secretaría de Salud (dummy)','Querétaro','Querétaro','socio6@ejemplo.com','assets/img/placeholders/avatar-6.jpg','Lorem ipsum dolor sit amet.','2020-02-14'),
(3,7,'SIDVVI-007','Dra.','Patricia','Ejemplo Siete','patricia-ejemplo-siete','Investigadora emérita','Instituto Nacional (dummy)','Ciudad de México','Ciudad de México',NULL,'assets/img/placeholders/avatar-7.jpg','Lorem ipsum dolor sit amet.','2015-06-01'),
(2,1,'SIDVVI-008','Dr.','Miguel','Ejemplo Ocho','miguel-ejemplo-ocho','Infectólogo','Hospital Privado (dummy)','Aguascalientes','Aguascalientes',NULL,'assets/img/placeholders/avatar-8.jpg','Lorem ipsum dolor sit amet.','2021-04-11'),
(4,3,'SIDVVI-009','Dra.','Sofía','Ejemplo Nueve','sofia-ejemplo-nueve','Residente de Pediatría','Hospital Universitario (dummy)','San Luis Potosí','San Luis Potosí',NULL,'assets/img/placeholders/avatar-9.jpg','Lorem ipsum dolor sit amet.','2023-03-01'),
(1,2,'SIDVVI-010','Dr.','Ricardo','Ejemplo Diez','ricardo-ejemplo-diez','Virólogo','Centro de Investigación (dummy)','Mérida','Yucatán','socio10@ejemplo.com','assets/img/placeholders/avatar-10.jpg','Lorem ipsum dolor sit amet.','2022-09-09'),
(2,4,'SIDVVI-011','Dra.','Elena','Ejemplo Once','elena-ejemplo-once','Médica internista','Hospital General (dummy)','Zacatecas','Zacatecas',NULL,'assets/img/placeholders/avatar-11.jpg','Lorem ipsum dolor sit amet.','2024-01-15'),
(1,7,'SIDVVI-012','Dr.','Andrés','Ejemplo Doce','andres-ejemplo-doce','Coordinador de vacunación','Jurisdicción Sanitaria (dummy)','Aguascalientes','Aguascalientes',NULL,'assets/img/placeholders/avatar-12.jpg','Lorem ipsum dolor sit amet.','2024-07-20');

INSERT INTO categories (name, slug, description, sort_order) VALUES
('Artículos', 'articulos', 'Artículos científicos y de revisión.', 1),
('Guías clínicas', 'guias-clinicas', 'Guías y recomendaciones.', 2),
('Reportes', 'reportes', 'Reportes epidemiológicos y técnicos.', 3),
('Boletines', 'boletines', 'Boletines informativos de la sociedad.', 4);

INSERT INTO tags (name, slug) VALUES ('VIH','vih'),('Influenza','influenza'),('Vacunación','vacunacion'),('Resistencia antimicrobiana','resistencia-antimicrobiana'),('Dengue','dengue'),('COVID-19','covid-19');

INSERT INTO articles (category_id, type, title, slug, author_name, excerpt, body, cover_image, status, published_at, views) VALUES
(1,'article','Avances recientes en vacunas de ARNm (dummy)','avances-recientes-en-vacunas-de-arnm','Dra. Ana Ejemplo Uno','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Revisión de los avances en plataformas de ARNm.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis.</p><h3>Introducción</h3><p>Maecenas sed diam eget risus varius blandit sit amet non magna. Donec ullamcorper nulla non metus auctor fringilla.</p><h3>Conclusiones</h3><p>Vestibulum id ligula porta felis euismod semper.</p>','assets/img/placeholders/article-1.jpg','published','2026-10-20 10:00:00',124),
(2,'guide','Guía práctica de esquemas de vacunación en adultos (dummy)','guia-practica-esquemas-vacunacion-adultos','Comité Académico SIDVVI','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Recomendaciones actualizadas.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><p>Etiam porta sem malesuada magna mollis euismod.</p>','assets/img/placeholders/article-2.jpg','published','2026-10-05 10:00:00',87),
(3,'report','Reporte epidemiológico de influenza temporada 2026 (dummy)','reporte-epidemiologico-influenza-2026','Dr. Jorge Ejemplo Seis','Lorem ipsum dolor sit amet. Panorama de la temporada de influenza.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>','assets/img/placeholders/article-3.jpg','published','2026-09-18 10:00:00',203),
(1,'article','Resistencia antimicrobiana: retos para México (dummy)','resistencia-antimicrobiana-retos-mexico','Q.F.B. Laura Ejemplo Cinco','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Análisis de la situación actual.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>','assets/img/placeholders/article-4.jpg','published','2026-08-30 10:00:00',65),
(4,'publication','Boletín SIDVVI · Número 3, 2026 (dummy)','boletin-sidvvi-numero-3-2026','SIDVVI','Lorem ipsum dolor sit amet. Noticias y actividades de la sociedad.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>','assets/img/placeholders/article-5.jpg','published','2026-07-15 10:00:00',41),
(1,'article','Dengue: diagnóstico oportuno en primer nivel (dummy)','dengue-diagnostico-oportuno-primer-nivel','Dr. Ricardo Ejemplo Diez','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Claves clínicas.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>','assets/img/placeholders/article-6.jpg','published','2026-06-02 10:00:00',98);

INSERT INTO article_tags VALUES (1,3),(1,6),(2,3),(3,2),(4,4),(6,5),(5,3);

INSERT INTO instagram_cache (ig_media_id, media_url, permalink, caption, posted_at) VALUES
('demo1','assets/img/placeholders/gallery-1.jpg','https://www.instagram.com/','Publicación de ejemplo · Sesión académica mensual (dummy)','2026-10-28 12:00:00'),
('demo2','assets/img/placeholders/gallery-2.jpg','https://www.instagram.com/','Publicación de ejemplo · Campaña de vacunación (dummy)','2026-10-21 12:00:00'),
('demo3','assets/img/placeholders/gallery-3.jpg','https://www.instagram.com/','Publicación de ejemplo · Congreso 2026 (dummy)','2026-10-14 12:00:00'),
('demo4','assets/img/placeholders/gallery-4.jpg','https://www.instagram.com/','Publicación de ejemplo · Nueva mesa directiva (dummy)','2026-10-07 12:00:00'),
('demo5','assets/img/placeholders/gallery-5.jpg','https://www.instagram.com/','Publicación de ejemplo · Curso de infectología (dummy)','2026-09-30 12:00:00'),
('demo6','assets/img/placeholders/gallery-6.jpg','https://www.instagram.com/','Publicación de ejemplo · Día mundial contra la hepatitis (dummy)','2026-09-23 12:00:00'),
('demo7','assets/img/placeholders/gallery-7.jpg','https://www.instagram.com/','Publicación de ejemplo · Webinar (dummy)','2026-09-16 12:00:00'),
('demo8','assets/img/placeholders/gallery-8.jpg','https://www.instagram.com/','Publicación de ejemplo · Convocatoria (dummy)','2026-09-09 12:00:00');

INSERT INTO social_links (network, url, icon_class, sort_order) VALUES
('Facebook','https://www.facebook.com/','fab fa-facebook-f',1),
('Instagram','https://www.instagram.com/','fab fa-instagram',2),
('X','https://x.com/','fab fa-x-twitter',3),
('YouTube','https://www.youtube.com/','fab fa-youtube',4),
('LinkedIn','https://www.linkedin.com/','fab fa-linkedin-in',5);

INSERT INTO cg_congresses (name, slug, edition, starts_at, ends_at, venue, city, modality, image, summary, description, registration_url, certificates_url) VALUES
('Congreso Nacional SIDVVI 2027','congreso-nacional-sidvvi-2027','VIII','2027-03-18','2027-03-20','Centro de Convenciones (dummy)','Aguascalientes, Ags.','hibrido','assets/img/placeholders/congress-1.jpg','Lorem ipsum dolor sit amet, consectetur adipiscing elit. El encuentro anual de la sociedad.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><h4>Ejes temáticos</h4><ul><li>Virología clínica</li><li>Vacunas e inmunización</li><li>Infecciones asociadas a la atención</li></ul>',NULL,NULL),
('Curso de Actualización en Vacunas 2027','curso-actualizacion-vacunas-2027','II','2027-05-22','2027-05-23','Auditorio Universitario (dummy)','Guadalajara, Jal.','presencial','assets/img/placeholders/congress-2.jpg','Lorem ipsum dolor sit amet. Curso intensivo de vacunología.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',NULL,NULL),
('Simposio de Infectología Pediátrica','simposio-infectologia-pediatrica-2027','I','2027-08-14','2027-08-14',NULL,'Virtual','virtual','assets/img/placeholders/congress-3.jpg','Lorem ipsum dolor sit amet. Simposio en línea.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',NULL,NULL),
('Congreso Nacional SIDVVI 2026','congreso-nacional-sidvvi-2026','VII','2026-03-19','2026-03-21','Centro de Convenciones (dummy)','Aguascalientes, Ags.','presencial','assets/img/placeholders/congress-4.jpg','Lorem ipsum dolor sit amet. Edición 2026.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',NULL,NULL),
('Congreso Nacional SIDVVI 2025','congreso-nacional-sidvvi-2025','VI','2025-03-20','2025-03-22','Hotel Sede (dummy)','León, Gto.','presencial','assets/img/placeholders/congress-2.jpg','Lorem ipsum dolor sit amet. Edición 2025.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',NULL,NULL),
('Congreso Nacional SIDVVI 2024','congreso-nacional-sidvvi-2024','V','2024-03-14','2024-03-16','Hotel Sede (dummy)','Querétaro, Qro.','presencial','assets/img/placeholders/congress-3.jpg','Lorem ipsum dolor sit amet. Edición 2024.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',NULL,NULL);

INSERT INTO contact_messages (name, email, phone, subject, message, status, ip_address) VALUES
('Persona de Ejemplo','ejemplo@correo.com','4490000000','Información sobre membresía','Mensaje de prueba (dummy).','new','127.0.0.1');

INSERT INTO membership_requests (member_type_id, full_name, email, phone, specialty, institution, city, state, comments, status, ip_address) VALUES
(1,'Dr. Solicitante de Ejemplo','solicitante@correo.com','4490000000','Infectología','Hospital (dummy)','Aguascalientes','Aguascalientes','Solicitud de prueba (dummy).','new','127.0.0.1');
