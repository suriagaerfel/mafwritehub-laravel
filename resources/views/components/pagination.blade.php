<div class="pagination">

        <?php if ($pageName!='Search' && !$u) {?>
        <?php if ($page > 1): ?>
        <a href="?page=1">« First</a>

        <a href="?page=<?= $page - 1 ?>">Prev</a>

        <?php endif; ?>
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="?page=<?= $p ?>" class="<?= ($p == $page) ? 'active' : '' ?>"><?= $p ?></a>

        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>">Next</a>

        <a href="?page=<?= $totalPages ?>">Last »</a>

        <?php endif; ?>
        <?php } ?>

        
        <?php if ($pageName!='Search' && $u) {?>
        <?php if ($page > 1): ?>
        <a href="<?php echo $u;?>?page=1<?php if ($showShared) {echo '&show-shared=yes';}?>">« First</a>

        <a href="<?php echo $u;?>?page=<?= $page - 1 ?><?php if ($showShared) {echo '&show-shared=yes';}?>">Prev</a>

        <?php endif; ?>
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="<?php echo $u;?>?page=<?= $p ?><?php if ($showShared) {echo '&show-shared=yes';}?>" class="<?= ($p == $page) ? 'active' : '' ?>"><?= $p ?></a>

        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
        <a href="<?php echo $u;?>?page=<?= $page + 1 ?><?php if ($showShared) {echo '&show-shared=yes';}?>">Next</a>

        <a href="<?php echo $u;?>?page=<?= $totalPages ?><?php if ($showShared) {echo '&show-shared=yes';}?>">Last »</a>

        <?php endif; ?>
        <?php } ?>



        



        <?php if ($pageName=='Search') {?>

            <?php if ($page > 1): ?>
        <a href="<?php echo '?query='.urlencode($query).'&query-in='.$queryIn.'&page=1'?>">« First</a>

        <a href="<?php echo '?query='.urlencode($query).'&query-in='.$queryIn.'&page='.$page - 1 ?>">Prev</a>

        <?php endif; ?>
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="<?php echo '?query='.urlencode($query).'&query-in='.$queryIn.'&page='.$p ?>" class="<?= ($p == $page) ? 'active' : '' ?>"><?= $p ?></a>

        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
        <a href="<?php echo '?query='.urlencode($query).'&query-in='.$queryIn.'&page='.$page + 1 ?>">Next</a>

        <a href="<?php echo '?query='.urlencode($query).'&query-in='.$queryIn.'&page='.$totalPages ?>">Last »</a>

        <?php endif; ?>

        <?php } ?>




    </div>

 