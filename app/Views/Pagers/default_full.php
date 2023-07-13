<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>


<nav aria-label="Navegadro">
	<ul class="pagination justify-content-center" style="display: flex;">
		<?php if ($pager->hasPrevious()) : ?>
			<li class="page-item">
				<a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="Primera">
					<span aria-hidden="true">Primera</span>
				</a>
			</li>
			<li class="page-item">
				<a  class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Anterior">
					<span aria-hidden="true">Anterior></span>
				</a>
			</li>
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<li class="page-item" <?= $link['active'] ? 'class="active"' : '' ?>>
				<a class="page-link" href="<?= $link['uri'] ?>">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<li class="page-item">
				<a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Siguiente">
					<span aria-hidden="true">Siguiente</span>
				</a>
			</li>
			<li class="page-item">
				<a class="page-link" href="<?= $pager->getLast() ?>" aria-label="Ultima">
					<span aria-hidden="true">Ultima</span>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>
