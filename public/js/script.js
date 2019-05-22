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

let items = document.getElementsByClassName(".dropdown-check-list");
for (let item of items){
    dropdown(item.id);
}
function dropdown(id){
    let checkList = document.getElementById(id);
    checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
        if (checkList.classList.contains('visible')) {
            checkList.classList.remove('visible');
        } else {
            checkList.classList.add('visible');
        }
    }
    checkList.onblur = function(evt) {
        checkList.classList.remove('visible');
    }
}

let createActorList = function() {
    ajax_result = document.getElementById('ajax_result');
    ajax_result.innerHTML = "";
    let ul = document.createElement('ul');
    for (let i = 0; i < ajax.length && i < 5; i++){
        let li = document.createElement('li');
        let a =  document.createElement('a');
        //a.innerHTML = ajax[]['name_actor'];
        ul.appendChild(li);
    }
    parent.appendChild(ul);
}

/*search.addEventListener('focusout', function(){
    document.getElementById('result').innerHTML = "";
});*/

function makeRequest(str, url, func) {
    const httpRequest = new XMLHttpRequest();
    if (!httpRequest) {
        alert('Abandon :( Impossible de créer une connexion avec le serveur');
        return false;
    }
    httpRequest.open('POST', url, true);
    httpRequest.onreadystatechange = function() {
        if(this.readyState === 4) {
            if (httpRequest.status == 200){
                let ajax = JSON.parse(httpRequest.responseText);
                console.log(ajax);
                func(ajax);
            }
        }
    }
    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send('q=' + encodeURIComponent(str));
}

const createList = function(ajax){
    const parent = document.getElementById('result');
    parent.innerHTML = "";
    const ul = document.createElement('ul');
    for (let i = 0; i < ajax.length && i < 5; i++){
        const li = document.createElement('li');
        if (ajax[i].hasOwnProperty("id_show")){
            const a =  document.createElement('a');
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


search_actor = document.getElementById("search_actor");
search_actor.addEventListener('keyup',makeRequest(search_actor.value, "https://flixadvisor.fr/actor/search", createActorList));


/* ------------------------------------------- */

const search = document.getElementById('search');
search.addEventListener('keyup', makeRequest(search.value, "https://flixadvisor.fr/recherche", createList));
