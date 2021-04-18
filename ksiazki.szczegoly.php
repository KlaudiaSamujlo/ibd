<?php

// jesli nie podano parametru id, przekieruj do listy książek
if (empty($_GET['id'])) {
    header("Location: ksiazki.lista.php");
    exit();
}

$id = (int)$_GET['id'];

include 'header.php';

use Ibd\Ksiazki;

$ksiazki = new Ksiazki();

$ksiazka = $ksiazki->pobierz($id);
$autor = $ksiazki->pobierzAutora($ksiazka['id']);
$kategoria = $ksiazki->pobierzKategorie($ksiazka['id']);
?>

    <h2><?= $ksiazka['tytul'] ?></h2>

    <p>
        <a href="ksiazki.lista.php"><i class="fas fa-chevron-left"></i> Powrót</a>
    </p>

    <b>Autor:</b> <?= $autor ?> <br>
    <b>Kategoria:</b> <?= $kategoria ?> <br>
    <b>ISBN:</b> <?= $ksiazka['isbn'] ?> <br>
    <b>Liczba stron:</b> <?= $ksiazka['liczba_stron'] ?> <br>
    <b>Cena:</b> <?= $ksiazka['cena'] ?>  zł <br>
    <b>Opis:</b> <br> <?= $ksiazka['opis'] ?> <br>
    <b>Okładka:</b> <br>
        <?php if (!empty($ksiazka['zdjecie'])): ?>
            <img src="zdjecia/<?= $ksiazka['zdjecie'] ?>" alt="<?= $ksiazka['tytul'] ?>" class="img-thumbnail"/>
        <?php else: ?>
            brak zdjęcia
        <?php endif; ?>


<?php include 'footer.php'; ?>