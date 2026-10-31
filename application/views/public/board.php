<section class="section">
	<div class="container">
		<?php section_title(array('eyebrow' => 'Nuestro equipo', 'title' => 'Mesa directiva', 'center' => TRUE, 'text' => 'Profesionales comprometidos con la investigación y la docencia.')); ?>
		<div class="row">
			<?php foreach ($board as $b): ?>
			<div class="col-sm-6 col-lg-4 col-xl-3 mb-4"><?php $this->load->view('partials/card_board', array('b' => $b)); ?></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
