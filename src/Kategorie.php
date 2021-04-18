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
     * Pobiera dane kategorii o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierz(int $id): ?array
    {
        return $this->db->pobierz('kategorie', $id);
    }

}
