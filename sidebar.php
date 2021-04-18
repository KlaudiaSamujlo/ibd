<?php

use Ibd\Ksiazki;

$ksiazki = new Ksiazki();

// pobieranie bestsellerow
$lista = $ksiazki->pobierzBestsellery();

?>

<div class="col-md-2">
    <h1>Bestsellery</h1>

    <ul>
        <?php foreach ($lista as $ks): ?>
            <li>
                <a href="ksiazki.szczegoly.php?id=<?= $ks['id'] ?>" title="szczegóły"> "<?= $ks['tytul'] ?>" </a>
                <br>
                <?= $ksiazki->pobierzAutora((int)$ks['id']) ?>

                <?php if (!empty($ks['zdjecie'])): ?>
                    <a href="ksiazki.szczegoly.php?id=<?= $ks['id'] ?>" title="szczegóły">
                        <img src="zdjecia/<?= $ks['zdjecie'] ?>" alt="<?= $ks['tytul'] ?>" class="img-thumbnail"/>
                    </a>
                <?php else: ?>
                    <i>brak zdjęcia</i>
                <?php endif; ?>

            </li>
            <br>
        <?php endforeach; ?>
    </ul>
</div>