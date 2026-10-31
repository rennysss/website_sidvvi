<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 mb-4">
				<?php section_title(array('eyebrow' => $intro->eyebrow, 'title' => $intro->title)); ?>
				<div class="rich-text check-list"><?= $intro->body ?></div>
			</div>
			<div class="col-lg-7">
				<div class="content-box">
					<h3 class="h4 mb-1">Solicitud de membresía</h3>
					<p class="text-muted small mb-4">Completa el formulario y la mesa directiva se pondrá en contacto contigo.</p>
					<form class="ajax-form" action="<?= site_url('membresia/enviar') ?>" method="post" data-recaptcha="membresia" novalidate>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
						<div class="hp-field" aria-hidden="true"><label>Sitio web <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
						<div class="form-row">
							<div class="form-group col-md-12"><label for="m-name">Nombre completo *</label><input type="text" class="form-control" id="m-name" name="full_name" required><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="m-email">Correo *</label><input type="email" class="form-control" id="m-email" name="email" required><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="m-phone">Teléfono *</label><input type="tel" class="form-control" id="m-phone" name="phone" required><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="m-type">Tipo de membresía *</label>
								<select class="custom-select" id="m-type" name="member_type_id" required><option value="">Selecciona...</option>
								<?php foreach ($types as $t): ?><option value="<?= $t->id ?>"><?= e($t->name) ?></option><?php endforeach; ?></select><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="m-spec">Especialidad *</label>
								<input type="text" class="form-control" id="m-spec" name="specialty" list="specList" required>
								<datalist id="specList"><?php foreach ($specialties as $s): ?><option value="<?= e($s->name) ?>"><?php endforeach; ?></datalist><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="m-inst">Institución</label><input type="text" class="form-control" id="m-inst" name="institution"><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="m-ced">Cédula profesional</label><input type="text" class="form-control" id="m-ced" name="professional_id"><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="m-city">Ciudad</label><input type="text" class="form-control" id="m-city" name="city"><div class="invalid-feedback"></div></div>
							<div class="form-group col-md-6"><label for="m-state">Estado</label><input type="text" class="form-control" id="m-state" name="state"><div class="invalid-feedback"></div></div>
							<div class="form-group col-12"><label for="m-com">Comentarios</label><textarea class="form-control" id="m-com" name="comments" rows="3"></textarea><div class="invalid-feedback"></div></div>
							<div class="form-group col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="m-privacy" name="privacy" value="1" required>
									<label class="custom-control-label" for="m-privacy">Acepto el <a href="<?= site_url('aviso-de-privacidad') ?>" target="_blank">aviso de privacidad</a></label>
									<div class="invalid-feedback"></div>
								</div>
							</div>
						</div>
						<div class="form-alert"></div>
						<button type="submit" class="btn btn-accent btn-lg">Enviar solicitud</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
