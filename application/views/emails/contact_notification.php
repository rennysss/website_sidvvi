<h2 style="color:#23477A;margin-top:0">Nuevo mensaje desde el sitio web</h2>
<p><strong>Nombre:</strong> <?= e($name) ?><br><strong>Correo:</strong> <?= e($email) ?><br><strong>Teléfono:</strong> <?= e($phone) ?><br><strong>Asunto:</strong> <?= e($subject) ?></p>
<p style="background:#f4f7fb;padding:16px;border-radius:6px"><?= nl2br(e($message)) ?></p>
<p><a href="<?= admin_url('messages') ?>" style="color:#B8143F">Ver en el panel de control</a></p>
