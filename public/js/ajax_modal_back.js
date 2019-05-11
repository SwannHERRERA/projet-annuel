const btn_modal = document.querySelectorAll(".open-modal");
const email_modal = document.querySelector('#email_modal');
const pseudo_modal = document.querySelector('#pseudo_modal');
const email_hidden = document.querySelector('#email_hidden');

for (let i = 0; i < btn_modal.length; i++){
    btn_modal[i].addEventListener('click', function(a){
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
    httpRequest.onreadystatechange = function() {
        if(this.readyState === 4) {
            let email = JSON.parse(httpRequest.responseText)[0].email;
            email_modal.innerHTML = email;
            email_hidden.value = email;

        }
    }
    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send('pseudo=' + encodeURIComponent(pseudo));

}
