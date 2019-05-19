$(document).ready(function () {
  let url = document.location.href
  let tab = url.split('#')
  let id = tab[1];
  if (id == 'modal'){
    $('#modal').modal('show')
  }if (id == 'valid_email'){
    $('#valid_email').modal('show')
  }
})

let search = document.getElementById('search');
search.addEventListener('keyup', ajaxSearch);
/*search.addEventListener('focusout', function(){
    document.getElementById('result').innerHTML = "";
});*/
function ajaxSearch() {
    makeRequest(search.value);
}


function makeRequest(str) {
    let httpRequest = false;
    httpRequest = new XMLHttpRequest();
    if (!httpRequest) {
        alert('Abandon :( Impossible de créer une connexion avec le serveur');
        return false;
    }
    httpRequest.open('POST', "https://flixadvisor.fr/recherche", true);
    httpRequest.onreadystatechange = function() {
        if(this.readyState === 4) {
            let ajax = JSON.parse(httpRequest.responseText);
            console.log(ajax);
            createListe(ajax);
        }
    }
    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send('q=' + encodeURIComponent(str));
}

function createListe(ajax){
    let parent = document.getElementById('result');
    parent.innerHTML = "";
    let ul = document.createElement('ul');
    for (let i = 0; i < ajax.length && i < 5; i++){
        let li = document.createElement('li');
        if (ajax[i].hasOwnProperty("id_show")){
            let a =  document.createElement('a');
            a.setAttribute("href","https://flixadvisor.fr/show?show=" +  ajax[i]['id_show']);
            a.innerHTML = ajax[i]['name_show'];
            li.appendChild(a);
        }else if (ajax[i].hasOwnProperty("id_show")){
            li.innerHTML = ajax[i]["name_actor"];
        }else {
            li.innerHTML = ajax[i][1];
        }
        ul.appendChild(li);
    }
    parent.appendChild(ul);
}
