<?php if ($m = flash('success')): ?><div class="alert ok" data-auto><?= e($m) ?></div><?php endif; ?>
<?php if ($m = flash('error')): ?><div class="alert err" data-auto><?= e($m) ?></div><?php endif; ?>
