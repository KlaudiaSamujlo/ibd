<?php

use Ibd\Ksiazki;
use Ibd\Koszyk;

$ksiazki = new Ksiazki();
$koszyk = new Koszyk();

// pobieranie bestsellerow
$lista = $ksiazki->pobierzBestsellery();

// Suma w koszyku
$wKoszyku = $koszyk->pobierzWszystkie();
$suma = 0.00;
foreach ($wKoszyku as $ks) {
    $suma += $ks['cena'] * $ks['liczba_sztuk'];
}
$sumaHtml = "<span id='suma'>$suma</span>";
?>

<div class="col-md-3">
    <?php if (empty($_SESSION['id_uzytkownika'])): ?>
        <h1>Logowanie</h1>

        <form method="post" action="logowanie.php">
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" class="form-control input-sm" />
            </div>
            <div class="form-group">
                <label for="haslo">Hasło:</label>
                <input type="password" id="haslo" name="haslo" class="form-control input-sm" />
            </div>
            <div class="form-group">
                <button type="submit" name="zaloguj" id="submit" class="btn btn-primary btn-sm">Zaloguj się</button>
                <a href="rejestracja.php" class="btn btn-link btn-sm">Zarejestruj się</a>
                <input type="hidden" name="powrot" value="<?= basename($_SERVER['SCRIPT_NAME']) ?>" />
            </div>
        </form>
    <?php else: ?>
        <p class="text-right">
            Zalogowany: <strong><?= $_SESSION['login'] ?></strong>
            &nbsp;
            <a href="wyloguj.php" class="btn btn-secondary btn-sm">wyloguj się</a>
        </p>
    <?php endif; ?>

    <h1>Koszyk</h1>
    <p>
        Suma wartości książek w koszyku:
        <strong><?= $sumaHtml ?></strong> PLN
    </p>

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