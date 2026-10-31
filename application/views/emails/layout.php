<!DOCTYPE html>
<html lang="es"><head><meta charset="utf-8"><title><?= e($subject) ?></title></head>
<body style="margin:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#1d2b3a">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:24px 0"><tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden">
<tr><td style="padding:24px;text-align:center;border-bottom:4px solid #23477A"><img src="<?= base_url('assets/img/logo.png') ?>" alt="SIDVVI" width="220"></td></tr>
<tr><td style="padding:28px 32px;font-size:15px;line-height:1.6"><?= $content ?></td></tr>
<tr><td style="padding:16px 32px;background:#142C4F;color:#c9d6e8;font-size:12px;text-align:center"><?= e(setting('site_full_name')) ?> · <?= e(setting('email')) ?></td></tr>
</table></td></tr></table></body></html>
