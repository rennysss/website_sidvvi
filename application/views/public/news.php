<section class="section">
	<div class="container">
		<?php section_title(array('eyebrow' => 'Instagram', 'title' => 'Lo más reciente de SIDVVI', 'center' => TRUE, 'text' => 'Publicaciones de nuestra cuenta oficial @'.setting('instagram_user', 'sidvvi'))); ?>
		<div class="row">
			<?php foreach ($posts as $p): ?>
			<div class="col-6 col-md-4 col-lg-3 mb-4">
				<a href="<?= e($p->permalink) ?>" target="_blank" rel="noopener" class="card card-insta h-100">
					<div class="insta-item"><img src="<?= img_url($p->media_url) ?>" alt="" loading="lazy" width="600" height="600"><span><i class="fab fa-instagram"></i></span></div>
					<div class="card-body">
						<small class="text-muted"><?= fecha_es($p->posted_at, 'short') ?></small>
						<p class="small mb-0"><?= e(str_limit($p->caption, 90)) ?></p>
					</div>
				</a>
			</div>
			<?php endforeach; ?>
			<?php if ( ! $posts): ?><div class="col-12"><div class="empty-state"><i class="fab fa-instagram"></i><p>Aún no hay publicaciones sincronizadas.</p></div></div><?php endif; ?>
		</div>
		<div class="text-center mt-3">
			<a href="https://www.instagram.com/<?= e(setting('instagram_user', 'sidvvi')) ?>" target="_blank" rel="noopener" class="btn btn-primary"><i class="fab fa-instagram"></i> Seguir en Instagram</a>
		</div>
	</div>
</section>
