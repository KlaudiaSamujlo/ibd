<?php

namespace Ibd;

class Kategorie
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
     * Pobiera wszystkie kategorie.
     *
     * @return array
     */
    public function pobierzWszystkie(): array
    {
        $sql = "SELECT * FROM kategorie";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera zapytanie SELECT z kategoriami.
     *
     * @return string
     */
    public function pobierzSelect(): string
    {
        return "SELECT * FROM kategorie WHERE 1=1 ";
    }

    /**
     * Wykonuje podane w parametrze zapytanie SELECT.
     *
     * @param string $select
     * @return array
     */
    public function pobierzWszystko(string $select): array
    {
        return $this->db->pobierzWszystko($select);
    }

    /**
     * Pobiera dane kategorii o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierz(int $id): ?array
    {
        return $this->db->pobierz('kategorie', $id);
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
        $sql = "SELECT * 
                FROM kategorie
                WHERE 1=1 ";

        // dodawanie warunków do zapytanie
        if (!empty($params['nazwa'])) {
            $sql .= "AND nazwa LIKE :nazwa ";
            $parametry['nazwa'] = "%$params[nazwa]%";
        }

        if (!empty($params['id'])) {
            $sql .= "AND k.id = :id ";
            $parametry['id'] = $params['id'];
        }

        // dodawanie sortowania
        if (!empty($params['sortowanie'])) {
            $kolumny = ['nazwa'];
            $kierunki = ['ASC', 'DESC'];
            [$kolumna, $kierunek] = explode(' ', $params['sortowanie']);

            if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
                $sql .= " ORDER BY " . $params['sortowanie'];
            }
        }

        return ['sql' => $sql, 'parametry' => $parametry];
    }

    /**
     * Pobiera stronę z danymi autorów.
     *
     * @param string $select
     * @param array $params
     * @return array
     */
    public function pobierzStrone(string $select, array $params = []): array
    {
        return $this->db->pobierzWszystko($select, $params);
    }

    /**
     * Dodaje kategorię.
     *
     * @param array $dane
     * @return int
     */
    public function dodaj(array $dane): int
    {
        return $this->db->dodaj('kategorie', [
            'nazwa' => $dane['nazwa']
        ]);
    }

    /**
     * Usuwa kategorię.
     *
     * @param int $id
     * @return bool
     */
    public function usun(int $id): bool
    {
        return $this->db->usun('kategorie', $id);
    }

    /**
     * Zmienia dane kategorii.
     *
     * @param array $dane
     * @param int   $id
     * @return bool
     */
    public function edytuj(array $dane, int $id): bool
    {
        $update = [
            'nazwa' => $dane['nazwa']
        ];

        return $this->db->aktualizuj('kategorie', $update, $id);
    }
}
