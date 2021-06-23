<?php
require_once 'vendor/autoload.php';
session_start();

use Ibd\Zamowienia, Ibd\Ksiazki;

// jesli nie podano parametru id, przekieruj do listy zamówień
if (empty($_GET['id'])) {
    header("Location: moje.zamowienia.php");
    exit();
}

if (empty($_SESSION['id_uzytkownika'])) {
	header("Location: index.php");
	exit();
}

$zamowienia = new Zamowienia();
$ksiazki= new Ksiazki();

$idZamowienia = (int)$_GET['id'];
$zamowienie = $zamowienia->pobierzZamowienie($idZamowienia);
$listaProduktow = $zamowienia->pobierzProdukty($idZamowienia);

// jesli użytkownik nie ma dostępu do rekordu o podanym id, przekieruj do listy zamówień
if ($_SESSION['id_uzytkownika']!=$zamowienie[0]['id_uzytkownika']) {
    header("Location: moje.zamowienia.php");
    exit();
}

setlocale(LC_ALL, 'pl', 'pl_PL', 'pl_PL.ISO8859-2', 'plk', 'polish', 'Polish');

include 'header.php';
?>

<p>
    <a href="moje.zamowienia.php"><i class="fas fa-chevron-left"></i> Powrót</a>
</p>

    <h1>Zamówienie numer: <?= $idZamowienia?></h1>
    Data złożenia zamówienia: <?=strftime("%d %B %Y",strtotime($zamowienie[0]['data_dodania']))?><br>
    Status: <?= ucfirst($zamowienie[0]['status']) ?>
    <br><br>
    <h4>Zamówione produkty</h4>

    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Tytuł</th>
            <th>Autor</th>
            <th>Kategoria</th>
            <th>Cena</th>
            <th>Ilość</th>
            <th>Cena razem</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($listaProduktow as $ks): ?>
            <tr>
                <td style="width: 100px">
                    <?php if (!empty($ks['zdjecie'])): ?>
                        <img src="zdjecia/<?=$ks['zdjecie']?>" alt="<?=$ks['tytul']?>" class="img-thumbnail" />
                    <?php else: ?>
                        brak zdjęcia
                    <?php endif; ?>
                </td>
                <td><?=$ks['tytul']?></td>
                <td><?=$ksiazki->pobierzAutora($ks['id'])?></td>
                <td><?=$ksiazki->pobierzKategorie($ks['id'])?></td>
                <td><?=$ks['cena']?> zł</td>
                <td><?=$ks['liczba_sztuk']?></td>
                <td><?=$ks['cena']*$ks['liczba_sztuk']?> zł</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php include 'footer.php'; ?>