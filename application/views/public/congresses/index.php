<section class="section">
	<div class="container">
		<?php section_title(array('eyebrow' => 'Agenda', 'title' => 'Próximos congresos y eventos')); ?>
		<div class="row">
			<?php foreach ($upcoming as $c): ?>
			<div class="col-md-6 col-lg-4 mb-4"><?php $this->load->view('partials/card_congress', array('c' => $c)); ?></div>
			<?php endforeach; ?>
			<?php if ( ! $upcoming): ?><div class="col-12"><div class="empty-state"><i class="far fa-calendar"></i><p>Muy pronto anunciaremos nuevos eventos.</p></div></div><?php endif; ?>
		</div>
	</div>
</section>
<section class="section bg-soft" id="historico">
	<div class="container">
		<div class="d-md-flex justify-content-between align-items-end mb-4">
			<?php section_title(array('eyebrow' => 'Histórico', 'title' => 'Ediciones anteriores')); ?>
			<div class="year-filter mb-4">
				<a href="<?= site_url('congresos') ?>#historico" class="<?= ! $year ? 'active' : '' ?>">Todos</a>
				<?php foreach ($years as $y): ?><a href="<?= site_url('congresos?anio='.$y) ?>#historico" class="<?= $year == $y ? 'active' : '' ?>"><?= $y ?></a><?php endforeach; ?>
			</div>
		</div>
		<div class="row">
			<?php foreach ($past as $c): ?>
			<div class="col-md-6 col-lg-4 mb-4"><?php $this->load->view('partials/card_congress', array('c' => $c)); ?></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
