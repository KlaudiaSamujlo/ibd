<?php
require_once 'vendor/autoload.php';
session_start();

use Ibd\Koszyk;
use Ibd\Ksiazki;

$koszyk = new Koszyk();
$ksiazki= new Ksiazki();

if(isset($_POST['zmien'])) {
	$koszyk->zmienLiczbeSztuk($_POST['ilosci']);
	header("Location: koszyk.lista.php");
}

$listaKsiazek = $koszyk->pobierzWszystkie();
$suma = 0.0;
foreach ($listaKsiazek as $ks) {
    $suma += $ks['cena'] * $ks['liczba_sztuk'];
}

include 'header.php';
?>

<h2>Koszyk</h2>

<form method="post" action="">
	<table class="table table-striped table-condensed" id="koszyk" >
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Tytuł</th>
				<th>Autor</th>
				<th>Kategoria</th>
				<th>Cena PLN</th>
				<th>Liczba sztuk</th>
				<th>Cena razem</th>
				<th>&nbsp;</th>
			</tr>
		</thead>

		<?php if(count($listaKsiazek) > 0): ?>
			<tbody>
				<?php foreach($listaKsiazek as $ks): ?>
					<tr >
                        <td style="width: 100px">
							<?php if(!empty($ks['zdjecie'])): ?>
								<img src="zdjecia/<?= $ks['zdjecie'] ?>" alt="<?= $ks['tytul'] ?>" class="img-thumbnail" />
							<?php else: ?>
								brak zdjęcia
							<?php endif; ?>
						</td>
						<td><?= $ks['tytul'] ?></td>
						<td><?= $ksiazki->pobierzAutora($ks['id']) ?></td>
						<td><?= $ksiazki->pobierzKategorie($ks['id']) ?></td>
						<td><?= round($ks['cena'],2) ?></td>
						<td>
							<div style="width: 50px">
								<input type="text" name="ilosci[<?= $ks['id_koszyka'] ?>]" value="<?= $ks['liczba_sztuk'] ?>" class="form-control" />
							</div>
						</td>
						<td><?= $ks['cena'] * $ks['liczba_sztuk'] ?></td>
						<td style="white-space: nowrap" >
							<a href="koszyk.usun.php?id=<?=$ks['id_koszyka']?>" title="usuń z koszyka" class="aUsunKsiazke">
                                <i class="fas fa-trash" ></i>
							</a>
							<a href="ksiazki.szczegoly.php?id=<?=$ks['id']?>" title="szczegóły">
                                <i class="fas fa-folder-open"></i>
                            </a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
            <tfoot>

                <tr>
                    <td colspan="3" class="text-left" style="font-size: 18px"> Razem do zapłaty: <b> <?= round($suma,2) ?> zł </b></td>
                    <td colspan="5" class="text-right">
                        <input type="submit" class="btn btn-secondary btn-sm" name="zmien" value="Zmień liczbę sztuk" />
                        <?php if (!empty($_SESSION['id_uzytkownika'])): ?>
                            <a href="zamowienie.php" class="btn btn-primary btn-sm">Złóż zamówienie</a>
                        <?php endif; ?>
                    </td>
                </tr>
            </tfoot>
		<?php else: ?>
			<tr><td colspan="8" style="text-align: center">Brak produktów w koszyku.</td></tr>
		<?php endif; ?>
	</table>
</form>

<?php include 'footer.php'; ?>