<?php $f = array('category' => $a->category_slug); $share = urlencode(current_url()); $st = urlencode($a->title); ?>
<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 mb-5">
				<article class="post-single">
					<img src="<?= img_url($a->cover_image) ?>" alt="<?= e($a->title) ?>" class="img-fluid rounded-lg mb-4" width="800" height="520">
					<ul class="post-meta list-inline">
						<li class="list-inline-item"><span class="badge badge-accent"><?= e(Article_model::$types[$a->type] ?? $a->type) ?></span></li>
						<?php if ($a->category): ?><li class="list-inline-item"><i class="far fa-folder"></i> <a href="<?= site_url('biblioteca/categoria/'.$a->category_slug) ?>"><?= e($a->category) ?></a></li><?php endif; ?>
						<li class="list-inline-item"><i class="far fa-calendar"></i> <?= fecha_es($a->published_at) ?></li>
						<?php if ($a->author_name): ?><li class="list-inline-item"><i class="far fa-user"></i> <?= e($a->author_name) ?></li><?php endif; ?>
						<li class="list-inline-item"><i class="far fa-eye"></i> <?= (int) $a->views + 1 ?></li>
					</ul>
					<h2 class="post-title"><?= e($a->title) ?></h2>
					<?php if ($a->excerpt): ?><p class="lead"><?= e($a->excerpt) ?></p><?php endif; ?>
					<div class="rich-text"><?= $a->body ?></div>

					<?php if ($a->attachment): ?>
					<div class="doc-item mt-4">
						<span class="doc-icon"><i class="far fa-file-pdf"></i></span>
						<div class="doc-body"><strong>Documento completo</strong><small><?= (int) $a->downloads ?> descargas</small></div>
						<a href="<?= site_url('biblioteca/descargar/'.$a->id) ?>" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Descargar</a>
					</div>
					<?php endif; ?>

					<div class="post-footer">
						<div class="tag-cloud"><?php foreach ($tags as $t): ?><a href="<?= site_url('biblioteca?tema='.$t->slug) ?>">#<?= e($t->name) ?></a><?php endforeach; ?></div>
						<div class="share">
							<span>Compartir:</span>
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
							<a href="https://x.com/intent/tweet?url=<?= $share ?>&text=<?= $st ?>" target="_blank" rel="noopener" aria-label="X"><i class="fab fa-x-twitter"></i></a>
							<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $share ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
							<a href="https://wa.me/?text=<?= $st.'%20'.$share ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
						</div>
					</div>
				</article>

				<?php if ($related): ?>
				<h3 class="h4 mt-5 mb-4">Publicaciones relacionadas</h3>
				<div class="row">
					<?php foreach ($related as $r): ?><div class="col-md-4 mb-4"><?php $this->load->view('partials/card_article', array('a' => $r)); ?></div><?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
			<div class="col-lg-4"><?php $this->load->view('public/library/_sidebar', array('f' => $f, 'tags' => array())); ?></div>
		</div>
	</div>
</section>
