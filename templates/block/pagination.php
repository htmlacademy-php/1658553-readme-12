<?php
/* @var array $pages */

/* @var float $pagesCount */
/* @var string $curPage */

?>
<?php
if ($pagesCount > 1): ?>

    <div class="popular__page-links">

        <a class="popular__page-link popular__page-link--prev button button--gray" <?php
        if ((int)$curPage <= 1): print 'style="visibility: hidden"'; endif; ?>
           href="?page=<?= (int)$curPage - 1 ?>">Предыдущая
            страница</a>

        <a class="popular__page-link popular__page-link--next button button--gray"<?php
        if ((int)$curPage >= (int)array_key_last($pages)
            + 1
        ): print 'style="visibility: hidden"'; endif; ?>
           href="?page=<?= (int)$curPage + 1 ?>">Следующая
            страница</a>

    </div>

<?php
endif; ?>
