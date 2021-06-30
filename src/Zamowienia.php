<?php

namespace Ibd;

class Zamowienia
{
    /**
     * Instancja klasy obsługującej połączenie do bazy.
     *
     * @var Db
     */
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    /**
     * Dodaje zamówienie.
     * 
     * @param int $idUzytkownika
     * @return int Id zamówienia
     */
    public function dodaj(int $idUzytkownika): int
    {
        return $this->db->dodaj('zamowienia', [
            'id_uzytkownika' => $idUzytkownika,
            'id_statusu' => 1
        ]);
    }

    /**
     * Dodaje szczegóły zamówienia.
     * 
     * @param int   $idZamowienia
     * @param array $dane Książki do zamówienia
     */
    public function dodajSzczegoly(int $idZamowienia, array $dane): void
    {
        foreach ($dane as $ksiazka) {
            $this->db->dodaj('zamowienia_szczegoly', [
                'id_zamowienia' => $idZamowienia,
                'id_ksiazki' => $ksiazka['id'],
                'cena' => $ksiazka['cena'],
                'liczba_sztuk' => $ksiazka['liczba_sztuk']
            ]);
        }
    }

    /**
     * Pobiera wszystkie zamówienia.
     *
     * @return array
     */
    public function pobierzWszystkie(): array
    {
        $sql = "
			SELECT z.*, u.login, s.nazwa AS status,
			ROUND(SUM(sz.cena*sz.liczba_sztuk), 2) AS suma,
			COUNT(sz.id) AS liczba_produktow,
			SUM(sz.liczba_sztuk) AS liczba_sztuk
			FROM zamowienia z JOIN uzytkownicy u ON z.id_uzytkownika = u.id
			JOIN zamowienia_statusy s ON z.id_statusu = s.id
			JOIN zamowienia_szczegoly sz ON z.id = sz.id_zamowienia
			GROUP BY z.id
	    ";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera statusy.
     *
     * @return array
     */
    public function pobierzStatusy(): array
    {
        $sql = "
			SELECT s.*
			FROM zamowienia_statusy s
	    ";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera zamówienia danego użytkownika.
     *
     * @param int     $idUzytkownika
     * @return array
     */
    public function pobierzDlaUzytkownika(int $idUzytkownika): array
    {
        $sql = "
			SELECT z.*, u.login, s.nazwa AS status,
			ROUND(SUM(sz.cena*sz.liczba_sztuk), 2) AS suma,
			COUNT(sz.id) AS liczba_produktow,
			SUM(sz.liczba_sztuk) AS liczba_sztuk
			FROM zamowienia z JOIN uzytkownicy u ON z.id_uzytkownika = u.id
			JOIN zamowienia_statusy s ON z.id_statusu = s.id
			JOIN zamowienia_szczegoly sz ON z.id = sz.id_zamowienia
			WHERE u.id = :id
			GROUP BY z.id
            ORDER BY z.id DESC
	    ";

        return $this->db->pobierzWszystko($sql, ['id' => $idUzytkownika]);
    }

    /**
     * Pobiera zamówienie o podanym numerze.
     *
     * @param int     $idZamowienia
     * @return array
     */
    public function pobierzZamowienie(int $idZamowienia): array
    {
        $sql = "
			SELECT z.*, u.login, s.nazwa AS status,
			ROUND(SUM(sz.cena*sz.liczba_sztuk), 2) AS suma,
			COUNT(sz.id) AS liczba_produktow,
			SUM(sz.liczba_sztuk) AS liczba_sztuk
			FROM zamowienia z JOIN uzytkownicy u ON z.id_uzytkownika = u.id
			JOIN zamowienia_statusy s ON z.id_statusu = s.id
			JOIN zamowienia_szczegoly sz ON z.id = sz.id_zamowienia
			WHERE z.id = :id
			GROUP BY z.id
	    ";

        return $this->db->pobierzWszystko($sql, ['id' => $idZamowienia]);
    }

    /**
     * Pobiera produkty z zamówienia o podanym numerze.
     *
     * @param int     $idZamowienia
     * @return array
     */
    public function pobierzProdukty(int $idZamowienia): array
    {
        $sql = "
			SELECT sz.id_zamowienia, sz.cena as cena_w_zamowieniu, sz.liczba_sztuk, ks.* 
			FROM zamowienia_szczegoly sz
			JOIN ksiazki ks ON sz.id_ksiazki = ks.id
			WHERE sz.id_zamowienia = :id
	    ";

        return $this->db->pobierzWszystko($sql, ['id' => $idZamowienia]);
    }

    /**
     * Zmienia dane zamówienia.
     *
     * @param array $dane
     * @param int   $id
     * @return bool
     */
    public function edytuj(array $dane, int $id): bool
    {
        $update = [
            'id_statusu' => $dane['id_statusu']
        ];

        return $this->db->aktualizuj('zamowienia', $update, $id);
    }
}
