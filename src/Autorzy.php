<?php

namespace Ibd;

class Autorzy
{
	private Db $db;

	public function __construct()
	{
		$this->db = new Db();
	}

	/**
	 * Pobiera zapytanie SELECT z autorami.
	 *
	 * @return string
     */
	public function pobierzSelect(): string
    {
        return "SELECT * FROM autorzy WHERE 1=1 ";
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
	 * Pobiera dane autora o podanym id.
	 *
	 * @param int $id
	 * @return array
	 */
	public function pobierz(int $id): array
    {
		return $this->db->pobierz('autorzy', $id);
	}

    /**
     * Pobiera dane o ilości książek danego autora.
     *
     * @param int $id_autora
     * @return int
     */
    public function policzKsiazki(int $id_autora): int
    {
        $sql = "SELECT * FROM ksiazki WHERE id_autora = :id_autora";

        return $this->db->policzRekordy($sql,['id_autora' => $id_autora]);
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
        $sql = "SELECT a.*, concat(a.imie,' ',a.nazwisko) as autor 
                FROM autorzy a
                WHERE 1=1 ";

        // dodawanie warunków do zapytanie
        if (!empty($params['fraza'])) {
            $sql .= "AND (concat(a.imie,' ',a.nazwisko) LIKE :fraza ) ";
            $parametry['fraza'] = "%$params[fraza]%";
        }

        if (!empty($params['id'])) {
            $sql .= "AND k.id = :id ";
            $parametry['id'] = $params['id'];
        }

        // dodawanie sortowania
        if (!empty($params['sortowanie'])) {
            $kolumny = ['a.nazwisko'];
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
	 * Dodaje autora.
	 *
	 * @param array $dane
	 * @return int
	 */
	public function dodaj(array $dane): int
    {
		return $this->db->dodaj('autorzy', [
			'imie' => $dane['imie'],
			'nazwisko' => $dane['nazwisko']
		]);
	}

	/**
	 * Usuwa autora.
	 * 
	 * @param int $id
	 * @return bool
	 */
	public function usun(int $id): bool
    {
		return $this->db->usun('autorzy', $id);
	}

	/**
	 * Zmienia dane autora.
	 * 
	 * @param array $dane
	 * @param int   $id
	 * @return bool
	 */
	public function edytuj(array $dane, int $id): bool
    {
		$update = [
			'imie' => $dane['imie'],
			'nazwisko' => $dane['nazwisko']
		];
		
		return $this->db->aktualizuj('autorzy', $update, $id);
	}

}
