<?php

namespace Ibd;

class Autorzy
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
     * Pobiera wszystkich autorów.
     *
     * @return array
     */
    public function pobierzWszystkich(): array
    {
        $sql = "SELECT * FROM autorzy";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera dane autora o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierz(int $id): ?array
    {
        return $this->db->pobierz('autorzy', $id);
    }

}
