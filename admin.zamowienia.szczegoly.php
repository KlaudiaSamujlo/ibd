<?php
require_once 'vendor/autoload.php';
include 'admin.header.php';

use Ibd\Zamowienia, Ibd\Ksiazki;

// jesli nie podano parametru id, przekieruj do listy zamówień
if (empty($_GET['id'])) {
    header("Location: admin.zamowienia.lista.php");
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
$statusy = $zamowienia->pobierzStatusy();

if (!empty($_POST['id_statusu'])) {
    if ($zamowienia->edytuj($_POST,$idZamowienia)) {
        header("Location: admin.zamowienia.szczegoly.php?id=" . $idZamowienia . "&msg=1");
    }
}

?>

<p>
    <a href="admin.zamowienia.lista.php"><i class="fas fa-chevron-left"></i> Powrót</a>
</p>

    <h1>Zamówienie numer: <?= $idZamowienia?></h1>
    Zamówienie złożone przez użytkownika o loginie: <b><?= $zamowienie[0]['login'] ?></b><br>
    Data złożenia zamówienia: <b><?=strftime("%d %B %Y",strtotime($zamowienie[0]['data_dodania']))?></b><br>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
        <p class="alert alert-success">Status zamówienia został zmieniony na "<?= $zamowienie[0]['status'] ?>".</p>
    <?php endif; ?>
    <form method="post" action="">
    <table id="zamowienie">
        <tr>
            <td style="width: 60px">
                Status:
            </td>
            <td style="width: 150px">
                <select name="id_statusu" id="id_statusu" class="form-control form-control-sm mr-2" style="font-weight:bold">
                    <option value=""><?= $zamowienie[0]['status'] ?></option>
                    <?php foreach ($statusy as $s): ?>
                        <?php if ($s['nazwa']!=$zamowienie[0]['status']): ?>
                            <option value="<?= $s['id'] ?>"
                            ><?= $s['nazwa'] ?></option>
                        <?php endif ?>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td> </td>
            <td>
                <button type="submit" class="btn btn-primary">Zapisz</button>
            </td>
        </tr>
    </table>
    </form>

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

<?php include 'admin.footer.php'; ?>