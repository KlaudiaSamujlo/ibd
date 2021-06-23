<?php
require_once 'vendor/autoload.php';
session_start();

use Ibd\Zamowienia;

if (empty($_SESSION['id_uzytkownika'])) {
	header("Location: index.php");
	exit();
}

$zamowienia = new Zamowienia();
$listaZamowien = $zamowienia->pobierzDlaUzytkownika($_SESSION['id_uzytkownika']);
setlocale(LC_ALL, 'pl', 'pl_PL', 'pl_PL.ISO8859-2', 'plk', 'polish', 'Polish');

include 'header.php';
?>

<h1>Moje zamówienia</h1>

<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th>Nr zamówienia</th>
            <th>Status</th>
            <th>Data złożenia</th>
			<th>Liczba produktów</th>
			<th>Wartość</th>
            <th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($listaZamowien as $z): ?>
		<tr>
			<td><?=$z['id']?></td>
            <td><?=ucfirst($z['status'])?></td>
            <td><?=strftime("%d %B %Y",strtotime($z['data_dodania']))?></td>
			<td><?=$z['liczba_produktow']?></td>
			<td><?=$z['suma']?> zł</td>
            <td>
                <a href="moje.zamowienia.szczegoly.php?id=<?= $z['id'] ?>" title="szczegóły">
                    <i class="fas fa-folder-open"></i>
                </a>
            </td>
		</tr>
		<?php endforeach; ?>
	</tbody>

</table>

<?php include 'footer.php'; ?>