const dodajDoKoszyka = async (e) => {
    const a = e.target.closest('a.aDodajDoKoszyka')

    if (a) {
        e.preventDefault()
        const href = a.getAttribute('href')
        const response = await fetch(href, {method: 'POST'})
        const txt = await response.text()

        if (txt === 'ok') {
            const wKoszyku = document.querySelector('#wKoszyku')
            wKoszyku.innerHTML = parseInt(wKoszyku.innerHTML) + 1
            a.outerHTML = '<i class="fas fa-check text-success"></i>'
            const sumaWKoszyku = document.querySelector('#sumaWKoszyku')
            sumaWKoszyku.innerHtml = parseInt(sumaWKoszyku.innerHTML) + 1
        } else {
            alert('Wystąpił błąd: ' + txt);
        }
    }
}

document.body.onload = () => {
    const ksiazki = document.querySelector('#ksiazki')
    if (ksiazki) {
        ksiazki.addEventListener('click', dodajDoKoszyka)
    }

    // autorzy
    document.querySelectorAll('.aUsunAutora').forEach(a => a.addEventListener('click', usunRekord))

    // użytkownicy
    document.querySelectorAll('.aUsunUzytkownika').forEach(a => a.addEventListener('click', usunRekord))

    // książki
    document.querySelectorAll('.aUsunKsiazke').forEach(a => a.addEventListener('click', usunRekord))

    // kategorie
    document.querySelectorAll('.aUsunKategorie').forEach(a => a.addEventListener('click', usunRekord))
}

/**
 * Usuwa rekord.
 *
 */
async function usunRekord(e) {

    e.preventDefault()

    if(e.target.closest('a.aUsunAutora') && parseInt(e.target.closest('td').previousElementSibling.innerHTML)>0) {
        alert('Nie można usunąc autora, który ma przypisaną przynajmniej jedną książkę.');
    } else {
        if (confirm('Czy na pewno chcesz usunąć rekord?')) {
            const a = e.target.parentNode
            const resp = await fetch(a.getAttribute('href'), {method: 'POST'})
            const text = await resp.text()

            if (text === 'ok') {
                a.closest('tr').style.textDecoration = 'line-through'
                a.closest('td').innerHTML = ''
            } else {
                alert('Wystąpił błąd przy przetwarzaniu zapytania. Prosimy spróbować ponownie.');
            }
        }
    }
}





