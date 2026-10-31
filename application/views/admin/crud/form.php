<?php $is_edit = $row && ! empty($row->id); $readonly_all = ! $c->can_edit; ?>
<div class="card admin-card">
	<div class="card-header d-flex align-items-center">
		<a href="<?= admin_url($c->route) ?>" class="btn btn-sm btn-light mr-2"><i class="fas fa-arrow-left"></i></a>
		<h2 class="h6 mb-0"><i class="<?= $c->icon ?> text-primary mr-1"></i> <?= e($page_title) ?></h2>
	</div>
	<div class="card-body">
		<?php if ($e = validation_errors('<li>', '</li>')): ?><div class="alert alert-danger"><ul class="mb-0 pl-3"><?= $e ?></ul></div><?php endif; ?>
		<?php if ( ! empty($upload_error)): ?><div class="alert alert-danger"><?= e($upload_error) ?></div><?php endif; ?>

		<?= form_open_multipart(current_url()) ?>
		<fieldset<?= $readonly_all ? ' disabled' : '' ?>>
		<div class="form-row">
			<?php foreach ($fields as $k => $f):
				$type = $f['type'] ?? 'text';
				$val = $row->$k ?? '';
				$col = $f['col'] ?? 12;
				$req = strpos($f['rules'] ?? '', 'required') !== FALSE || ! empty($f['required']);
				$id = 'f_'.$k;
			?>
			<div class="form-group col-md-<?= $col ?>">
				<?php if ($type !== 'checkbox'): ?><label for="<?= $id ?>"><?= e($f['label']) ?><?= $req ? ' <span class="text-danger">*</span>' : '' ?></label><?php endif; ?>

				<?php switch ($type):
					case 'textarea': ?>
					<textarea class="form-control" id="<?= $id ?>" name="<?= $k ?>" rows="4"><?= e($val) ?></textarea>
				<?php break; case 'html': ?>
					<textarea class="form-control js-editor" id="<?= $id ?>" name="<?= $k ?>"><?= html_escape($val) ?></textarea>
				<?php break; case 'readonly': ?>
					<div class="form-control-plaintext readonly-box"><?= nl2br(e($k === 'created_at' && $val ? date('d/m/Y H:i', strtotime($val)) : ($val ?: '—'))) ?></div>
				<?php break; case 'select': ?>
					<select class="custom-select" id="<?= $id ?>" name="<?= $k ?>">
						<?php if ( ! $req): ?><option value="">— Ninguno —</option><?php endif; ?>
						<?php foreach ($options[$k] as $ov => $ol): ?><option value="<?= e($ov) ?>"<?= (string) $val === (string) $ov ? ' selected' : '' ?>><?= e($ol) ?></option><?php endforeach; ?>
					</select>
				<?php break; case 'checkbox': ?>
					<div class="custom-control custom-switch mt-md-4 pt-md-2">
						<input type="checkbox" class="custom-control-input" id="<?= $id ?>" name="<?= $k ?>" value="1"<?= ($is_edit ? $val : ($val === '' ? 1 : $val)) ? ' checked' : '' ?>>
						<label class="custom-control-label" for="<?= $id ?>"><?= e($f['label']) ?></label>
					</div>
				<?php break; case 'image': case 'file': ?>
					<?php if ($val): ?>
					<div class="current-file mb-2">
						<?php if ($type === 'image'): ?><img src="<?= img_url($val) ?>" alt="" class="preview-img"><?php else: ?><a href="<?= base_url($val) ?>" target="_blank"><i class="far fa-file"></i> <?= e(basename($val)) ?></a><?php endif; ?>
						<?php if (empty($f['required'])): ?>
						<div class="custom-control custom-checkbox mt-1"><input type="checkbox" class="custom-control-input" id="<?= $id ?>_rm" name="<?= $k ?>_remove" value="1"><label class="custom-control-label small" for="<?= $id ?>_rm">Quitar archivo</label></div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="<?= $id ?>" name="<?= $k ?>"<?= $type === 'image' ? ' accept="image/*"' : '' ?>>
						<label class="custom-file-label" for="<?= $id ?>" data-browse="Elegir"><?= $val ? 'Reemplazar archivo...' : 'Seleccionar archivo...' ?></label>
					</div>
				<?php break; case 'icon': ?>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text icon-preview"><i class="<?= e($val ?: 'fas fa-question') ?>"></i></span></div>
						<input type="text" class="form-control js-icon" id="<?= $id ?>" name="<?= $k ?>" value="<?= e($val) ?>" placeholder="fas fa-virus">
					</div>
				<?php break; case 'password': ?>
					<input type="password" class="form-control" id="<?= $id ?>" name="<?= $k ?>" autocomplete="new-password">
				<?php break; case 'datetime': ?>
					<input type="datetime-local" class="form-control" id="<?= $id ?>" name="<?= $k ?>" value="<?= $val ? date('Y-m-d\TH:i', strtotime($val)) : '' ?>">
				<?php break; case 'date': ?>
					<input type="date" class="form-control" id="<?= $id ?>" name="<?= $k ?>" value="<?= $val ? date('Y-m-d', strtotime($val)) : '' ?>">
				<?php break; default: ?>
					<input type="<?= in_array($type, array('email', 'url', 'number')) ? $type : 'text' ?>" class="form-control" id="<?= $id ?>" name="<?= $k ?>" value="<?= e($val) ?>">
				<?php endswitch; ?>

				<?php if ( ! empty($f['help'])): ?><small class="form-text text-muted"><?= e($f['help']) ?></small><?php endif; ?>
			</div>
			<?php endforeach; ?>
			<?= $extra ?>
		</div>
		</fieldset>
		<?php if ( ! $readonly_all): ?>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
			<a href="<?= admin_url($c->route) ?>" class="btn btn-light">Cancelar</a>
		</div>
		<?php endif; ?>
		<?= form_close() ?>
	</div>
</div>
