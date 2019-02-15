<?php
    $presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

<?php if ($paginator->getLastPage() > 1): ?>
    <ul class="pagination" style="color:black;">
        <?php echo $presenter->render(); ?>
    </ul>
<?php endif; ?>