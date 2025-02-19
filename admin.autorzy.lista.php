<?php

require_once 'vendor/autoload.php';

use Ibd\Autorzy;
use Ibd\Stronicowanie;

$autorzy = new Autorzy();
$select = $autorzy->pobierzSelect();
$lista = $autorzy->pobierzWszystko($select);
$zapytanie = $autorzy->pobierzZapytanie($_GET);

include 'admin.header.php';

// dodawanie warunków stronicowania i generowanie linków do stron
$stronicowanie = new Stronicowanie($_GET, $zapytanie['parametry']);
$linki = $stronicowanie->pobierzLinki($zapytanie['sql'], 'admin.autorzy.lista.php');
$wybrane = $stronicowanie->wybraneRekordy($zapytanie['sql']);
$select = $stronicowanie->dodajLimit($zapytanie['sql']);
$lista = $autorzy->pobierzStrone($select, $zapytanie['parametry']);
?>

<h2>
    Autorzy
    <small><a href="admin.autorzy.dodaj.php">dodaj</a></small>
</h2>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
    <p class="alert alert-success">Autor został dodany.</p>
<?php endif; ?>

    <form method="get" action="" class="form-inline mb-4">
        <input type="text" name="fraza" placeholder="szukaj" class="form-control form-control-sm mr-2"
               value="<?= $_GET['fraza'] ?? '' ?>"/>

        <select name="sortowanie" id="sortowanie" class="form-control form-control-sm mr-2">
            <option value="">sortowanie</option>
            <option value="a.nazwisko ASC"
                <?= ($_GET['sortowanie'] ?? '') == "a.nazwisko ASC" ? 'selected' : '' ?>
            >nazwisko autora rosnąco
            </option>
            <option value="a.nazwisko DESC"
                <?= ($_GET['sortowanie'] ?? '') == "a.nazwisko DESC" ? 'selected' : '' ?>
            >nazwisko autora malejąco
            </option>
        </select>

        <button class="btn btn-sm btn-primary" type="submit">Szukaj</button>
    </form>

<table class="table table-striped" id="autorzy" >
    <thead>
        <tr>
            <th>Id</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Liczba książek</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= $a['imie'] ?></td>
                <td><?= $a['nazwisko'] ?></td>
                <td><?= $autorzy->policzKsiazki($a['id']) ?></td>
                <td class="text-right">
                    <a href="admin.autorzy.edycja.php?id=<?= $a['id'] ?>" title="edycja" class="aEdytujAutora">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a href="admin.autorzy.usun.php?id=<?= $a['id'] ?>" title="usuń" class="aUsunAutora">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    <table style="width:100%">
        <tr>
            <td>
                <nav class="text-center">
                    <?= $linki ?>
                </nav>
            </td>
            <td style="text-align: right; vertical-align:top">
                <?= $wybrane ?>
            </td>
        </tr>
    </table>


<?php include 'admin.footer.php'; ?>