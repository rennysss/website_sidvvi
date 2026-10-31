<aside class="sidebar">
	<div class="widget">
		<h4 class="widget-title">Buscar</h4>
		<form action="<?= site_url('biblioteca') ?>" method="get">
			<div class="input-group">
				<input type="search" name="q" class="form-control" placeholder="Título, autor..." value="<?= e($f['q'] ?? '') ?>" aria-label="Buscar en la biblioteca">
				<div class="input-group-append"><button class="btn btn-primary" type="submit" aria-label="Buscar"><i class="fas fa-search"></i></button></div>
			</div>
		</form>
	</div>
	<div class="widget">
		<h4 class="widget-title">Categorías</h4>
		<ul class="widget-list">
			<?php foreach ($categories as $c): ?>
			<li><a href="<?= site_url('biblioteca/categoria/'.$c->slug) ?>"<?= ($f['category'] ?? '') === $c->slug ? ' class="active"' : '' ?>><?= e($c->name) ?> <span><?= (int) $c->total ?></span></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php if ( ! empty($recent)): ?>
	<div class="widget">
		<h4 class="widget-title">Recientes</h4>
		<?php foreach ($recent as $r): ?>
		<a class="mini-post" href="<?= site_url('biblioteca/'.$r->slug) ?>">
			<img src="<?= img_url($r->cover_image) ?>" alt="" loading="lazy" width="80" height="60">
			<span><strong><?= e(str_limit($r->title, 50)) ?></strong><small><?= fecha_es($r->published_at, 'short') ?></small></span>
		</a>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<?php if ( ! empty($tags)): ?>
	<div class="widget">
		<h4 class="widget-title">Temas</h4>
		<div class="tag-cloud">
			<?php foreach ($tags as $t): ?><a href="<?= site_url('biblioteca?tema='.$t->slug) ?>"><?= e($t->name) ?></a><?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
</aside>
