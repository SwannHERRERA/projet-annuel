const btn_modal = document.querySelectorAll(".open-modal");
const email_modal = document.querySelector('#email_modal');
const pseudo_modal = document.querySelector('#pseudo_modal');
const email_hidden = document.querySelector('#email_hidden');

for (let i = 0; i < btn_modal.length; i++) {
    btn_modal[i].addEventListener('click', function (a) {
        a.preventDefault();
        const pseudo = this.id;
        pseudo_modal.innerHTML = pseudo;
        makeRequest(pseudo);
    })
}

function makeRequest(pseudo) {
    var httpRequest = false;
    httpRequest = new XMLHttpRequest();
    if (!httpRequest) {
        alert('Abandon :( Impossible de créer une connexion avec le serveur');
        return false;
    }
    httpRequest.open('POST', "https://flixadvisor.fr/back/member/ajaxban", true);
    httpRequest.onreadystatechange = function () {
        if (this.readyState === 4) {
            let email = JSON.parse(httpRequest.responseText)[0].email;
            email_modal.innerHTML = email;
            email_hidden.value = email;

        }
    }
    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send('pseudo=' + encodeURIComponent(pseudo));

}

function filter() {
    const filter = document.getElementById("filterMember").value.toUpperCase();
    const table = document.getElementsByTagName("table")[0];
    const tr = table.getElementsByTagName("tr");
    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName("td");
        if (td[0].innerHTML.split("<span")[0].toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}
