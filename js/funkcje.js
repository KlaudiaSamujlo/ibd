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
            //const suma = document.querySelector('#suma')
            //suma.innerHTML = '1000'
        } else {
            alert('Wystąpił błąd: ' + txt);
        }
    }
}

/**
 * Usuwa rekord.
 *
 */
async function usunRekord(e) {
    const b = e.target.closest('a.aUsunZKoszyka')

    if(b){
        e.preventDefault()
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

document.body.onload = () => {
    const ksiazki = document.querySelector('#ksiazki')
    if (ksiazki) {
        ksiazki.addEventListener('click', dodajDoKoszyka)
    }

    const koszyk = document.querySelector('#koszyk')
    if (koszyk) {
        koszyk.addEventListener('click', usunRekord)
    }

    // autorzy
    document.querySelectorAll('.aUsunAutora').forEach(a => a.addEventListener('click', usunRekord))

    // użytkownicy
    document.querySelectorAll('.aUsunUzytkownika').forEach(a => a.addEventListener('click', usunRekord))

    // książki
    document.querySelectorAll('.aUsunKsiazke').forEach(a => a.addEventListener('click', usunRekord))
}





