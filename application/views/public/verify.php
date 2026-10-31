<section class="section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="content-box">
					<?php section_title(array('eyebrow' => 'Comprobante', 'title' => 'Reporte verificado')); ?>
					<p>Este folio fue generado por el panel de SIDVVI. El código QR del PDF apunta a esta página.</p>
					<dl class="row mb-0">
						<dt class="col-sm-4">Folio</dt><dd class="col-sm-8"><code><?= e($report->folio) ?></code></dd>
						<dt class="col-sm-4">Reporte</dt><dd class="col-sm-8"><?= e($label) ?></dd>
						<dt class="col-sm-4">Fecha</dt><dd class="col-sm-8"><?= fecha_es($report->created_at) ?></dd>
						<dt class="col-sm-4">Generado por</dt><dd class="col-sm-8"><?= e($report->author ?: 'Administrador') ?></dd>
					</dl>
				</div>
			</div>
		</div>
	</div>
</section>
