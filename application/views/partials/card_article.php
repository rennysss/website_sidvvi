<article class="card card-post h-100">
	<a href="<?= site_url('biblioteca/'.$a->slug) ?>" class="card-img-wrap">
		<img src="<?= img_url($a->cover_image) ?>" class="card-img-top" alt="<?= e($a->title) ?>" loading="lazy" width="800" height="520">
		<?php if ( ! empty($a->category)): ?><span class="card-tag"><?= e($a->category) ?></span><?php endif; ?>
	</a>
	<div class="card-body">
		<ul class="post-meta list-inline">
			<li class="list-inline-item"><i class="far fa-calendar"></i> <?= fecha_es($a->published_at, 'short') ?></li>
			<?php if ($a->author_name): ?><li class="list-inline-item"><i class="far fa-user"></i> <?= e(str_limit($a->author_name, 28)) ?></li><?php endif; ?>
		</ul>
		<h3 class="card-title h5"><a href="<?= site_url('biblioteca/'.$a->slug) ?>"><?= e($a->title) ?></a></h3>
		<p class="card-text"><?= e(str_limit($a->excerpt, 120)) ?></p>
		<a href="<?= site_url('biblioteca/'.$a->slug) ?>" class="link-more">Leer más <i class="fas fa-arrow-right"></i></a>
	</div>
</article>
