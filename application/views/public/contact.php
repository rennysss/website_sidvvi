<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 mb-4">
				<div class="contact-card">
					<span class="icon-circle"><i class="fas fa-map-marker-alt"></i></span>
					<div><h3 class="h6">Dirección</h3><p><?= e(setting('address')) ?></p></div>
				</div>
				<div class="contact-card">
					<span class="icon-circle"><i class="fas fa-phone-alt"></i></span>
					<div><h3 class="h6">Teléfono</h3><p><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></p></div>
				</div>
				<div class="contact-card">
					<span class="icon-circle"><i class="fas fa-envelope"></i></span>
					<div><h3 class="h6">Correo</h3><p><a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></p></div>
				</div>
				<div class="contact-card">
					<span class="icon-circle"><i class="far fa-clock"></i></span>
					<div><h3 class="h6">Horario</h3><p><?= e(setting('schedule')) ?></p></div>
				</div>
				<?php if (setting('whatsapp')): ?>
				<a href="https://wa.me/<?= e(setting('whatsapp')) ?>" class="btn btn-success btn-block" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Escríbenos por WhatsApp</a>
				<?php endif; ?>
			</div>
			<div class="col-lg-8">
				<div class="content-box">
					<?php section_title(array('eyebrow' => 'Escríbenos', 'title' => 'Envíanos un mensaje')); ?>
					<form class="ajax-form" action="<?= site_url('contacto/enviar') ?>" method="post" data-recaptcha="contacto" novalidate>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
						<div class="hp-field" aria-hidden="true"><label>Sitio web <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
						<div class="form-row">
							<div class="form-group col-md-6"><label for="c-name">Nombre *</label><input type="text" class="form-control" id="c-name" name="name" required maxlength="150"><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="c-email">Correo *</label><input type="email" class="form-control" id="c-email" name="email" required><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="c-phone">Teléfono</label><input type="tel" class="form-control" id="c-phone" name="phone" maxlength="30"><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="c-subject">Asunto *</label><input type="text" class="form-control" id="c-subject" name="subject" required maxlength="200"><div class="invalid-feedback"></div></div>
							<div class="form-group col-12"><label for="c-message">Mensaje *</label><textarea class="form-control" id="c-message" name="message" rows="5" required></textarea><div class="invalid-feedback"></div></div>
							<div class="form-group col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="c-privacy" name="privacy" value="1" required>
									<label class="custom-control-label" for="c-privacy">Acepto el <a href="<?= site_url('aviso-de-privacidad') ?>" target="_blank">aviso de privacidad</a></label>
									<div class="invalid-feedback"></div>
								</div>
							</div>
						</div>
						<div class="form-alert"></div>
						<button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-paper-plane mr-1"></i> Enviar mensaje</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<?php if (setting('map_embed')): ?>
<div class="map-embed"><iframe src="<?= e(setting('map_embed')) ?>" title="Ubicación de SIDVVI" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe></div>
<?php endif; ?>
