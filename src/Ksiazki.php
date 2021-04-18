<?php

namespace Ibd;
use Ibd\Autorzy;

class Ksiazki
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
     * Pobiera wszystkie książki.
     *
     * @return array
     */
    public function pobierzWszystkie(): ?array
    {
        $sql = "SELECT k.* FROM ksiazki k  ";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera dane książki o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierz(int $id): ?array
    {
        return $this->db->pobierz('ksiazki', $id);
    }

    /**
     * Pobiera autora książki o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierzAutora(int $id): ?string
    {
        $autorzy = new Autorzy();
        $ksiazka = $this->db->pobierz('ksiazki', $id);
        $autor = $autorzy->pobierz($ksiazka['id_autora']);

        return $autor['imie'] . " " . $autor['nazwisko'];
    }

    /**
     * Pobiera kategorię książki o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierzKategorie(int $id): ?string
    {
        $kategorie = new Kategorie();
        $ksiazka = $this->db->pobierz('ksiazki', $id);
        $kategoria = $kategorie->pobierz($ksiazka['id_kategorii']);

        return $kategoria['nazwa'];
    }

    /**
     * Pobiera najlepiej sprzedające się książki.
     *
     */
    public function pobierzBestsellery()
    {
        $sql = "SELECT * FROM ksiazki ORDER BY RAND() LIMIT 5";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera zapytanie SELECT oraz jego parametry;
     *
     * @param array $params
     * @return array
     */
    public function pobierzZapytanie(array $params = []): array
    {
        $parametry = [];
        $sql = "SELECT k.*, concat(a.imie,' ',a.nazwisko) as autor, kat.nazwa as kategoria  
                FROM ksiazki k 
                    join autorzy a on k.id_autora = a.id
                    join kategorie kat on k.id_kategorii = kat.id
                WHERE 1=1 ";

        // dodawanie warunków do zapytanie
        if (!empty($params['fraza'])) {
            $sql .= "AND k.tytul LIKE :fraza ";
            $parametry['fraza'] = "%$params[fraza]%";
        }
        if (!empty($params['id_kategorii'])) {
            $sql .= "AND k.id_kategorii = :id_kategorii ";
            $parametry['id_kategorii'] = $params['id_kategorii'];
        }
        if (!empty($params['id'])) {
            $sql .= "AND k.id = :id ";
            $parametry['id'] = $params['id'];
        }

        // dodawanie sortowania
        if (!empty($params['sortowanie'])) {
            $kolumny = ['k.tytul', 'k.cena'];
            $kierunki = ['ASC', 'DESC'];
            [$kolumna, $kierunek] = explode(' ', $params['sortowanie']);

            if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
                $sql .= " ORDER BY " . $params['sortowanie'];
            }
        }

        return ['sql' => $sql, 'parametry' => $parametry];
    }

    /**
     * Pobiera stronę z danymi książek.
     *
     * @param string $select
     * @param array $params
     * @return array
     */
    public function pobierzStrone(string $select, array $params = []): array
    {
        return $this->db->pobierzWszystko($select, $params);
    }
}
