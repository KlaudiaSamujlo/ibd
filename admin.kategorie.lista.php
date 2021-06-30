<?php

require_once 'vendor/autoload.php';

use Ibd\Kategorie;
use Ibd\Stronicowanie;

$kategorie = new Kategorie();
$select = $kategorie->pobierzSelect();
$lista = $kategorie->pobierzWszystko($select);
$zapytanie = $kategorie->pobierzZapytanie($_GET);

include 'admin.header.php';

// dodawanie warunków stronicowania i generowanie linków do stron
$stronicowanie = new Stronicowanie($_GET, $zapytanie['parametry']);
$linki = $stronicowanie->pobierzLinki($zapytanie['sql'], 'admin.kategorie.lista.php');
$wybrane = $stronicowanie->wybraneRekordy($zapytanie['sql']);
$select = $stronicowanie->dodajLimit($zapytanie['sql']);
$lista = $kategorie->pobierzStrone($select, $zapytanie['parametry']);
?>

<h2>
    Kategorie
    <small><a href="admin.kategorie.dodaj.php">dodaj</a></small>
</h2>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
    <p class="alert alert-success">Kategoria została dodana.</p>
<?php endif; ?>

    <form method="get" action="" class="form-inline mb-4">
        <input type="text" name="nazwa" placeholder="szukaj" class="form-control form-control-sm mr-2"
               value="<?= $_GET['nazwa'] ?? '' ?>"/>

        <select name="sortowanie" id="sortowanie" class="form-control form-control-sm mr-2">
            <option value="">sortowanie</option>
            <option value="nazwa ASC"
                <?= ($_GET['sortowanie'] ?? '') == "nazwa ASC" ? 'selected' : '' ?>
            >kategoria rosnąco
            </option>
            <option value="nazwa DESC"
                <?= ($_GET['sortowanie'] ?? '') == "nazwa DESC" ? 'selected' : '' ?>
            >kategoria malejąco
            </option>
        </select>

        <button class="btn btn-sm btn-primary" type="submit">Szukaj</button>
    </form>

<table class="table table-striped" id="kategorie" >
    <thead>
        <tr>
            <th>Id</th>
            <th>Nazwa</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $k): ?>
            <tr>
                <td><?= $k['id'] ?></td>
                <td><?= $k['nazwa'] ?></td>
                <td class="text-right">
                    <a href="admin.kategorie.edycja.php?id=<?= $k['id'] ?>" title="edycja" class="aEdytujKategorie">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a href="admin.kategorie.usun.php?id=<?= $k['id'] ?>" title="usuń" class="aUsunKategorie">
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