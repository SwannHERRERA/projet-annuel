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

let items = document.getElementsByClassName("dropdown-check-list");
for (let item of items){
    dropdown(item.id);
}
function dropdown(id){
    const checkList = document.getElementById(id);
    checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
        if (checkList.classList.contains('visible')) {
            checkList.classList.remove('visible');
        } else {
            checkList.classList.add('visible');
        }
    }
}

const createActorList = function(ajax) {
    const ajax_result = document.getElementById('ajax_result');
    const valid_result = document.getElementById('valid_result');
    ajax_result.innerHTML = "";
    const ul = document.createElement('ul');
    for (let i = 0; i < ajax.length && i < 5; i++){
        let li = document.createElement('li');
        li.innerHTML = ajax[i]['name_actor'];
        li.addEventListener('click', function() {
            const valid_result_inputs = valid_result.querySelectorAll('input');
            let exist = false;
            for (valid_result_input of valid_result_inputs) {
                if (valid_result_input.value == ajax[i]['id_actor']) {
                    exist = true;
                    break;
                }
            }
            if (exist === false) {
                const input = document.createElement('input');
                input.setAttribute('type', 'hidden');
                input.setAttribute('value', ajax[i]['id_actor']);
                input.setAttribute('name', 'actor[]');
                const div =  document.createElement('div');
                div.innerHTML = ajax[i]['name_actor'];
                valid_result.appendChild(input);
                valid_result.appendChild(div);
                valid_result.style.display = "block";
            }
        });
        ul.appendChild(li);
    }
    ajax_result.appendChild(ul);
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
                func(ajax);
            }
        }
    }
    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send('q=' + encodeURIComponent(str));
}

const search_actor = document.getElementById("search_actor");
if (search_actor != undefined){
    search_actor.addEventListener('keyup', function() {
        if (search_actor.value.length > 2) {
            makeRequest(search_actor.value,"http://localhost/actor/search"/* "https://flixadvisor.fr/actor/search"*/, createActorList);
        } else {
            document.getElementById('ajax_result').innerHTML = "";
        }
    });
}

/* ------------------------------------------- */

const search = document.getElementById('search');
if (search != undefined) {
    search.addEventListener('keyup', function() {
        if (search.value.length > 2) {
            makeRequest(search.value, "http://localhost/recherche"/*"https://flixadvisor.fr/recherche"*/, createList);
        } else {
            document.getElementById('result').innerHTML = "";
        }
    });
}


/* ------ ------------ ---------- */


const previewImage = document.getElementById("preview");
const uploadingText = document.getElementById("uploading-text");

function submitForm(event) {
    // prevent default form submission
    event.preventDefault();
    uploadImage();
}
function uploadImage() {
    const imageSelecter = document.getElementById("image-selecter"),
        file = imageSelecter.files[0];

    // clear the previous image
    previewImage.removeAttribute("src");
    // show uploading text
    uploadingText.style.display = "block";

    // create form data and append the file
    const formData = new FormData();
    formData.append("image", file);

    // do the ajax part
    const ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            const json = JSON.parse(this.responseText);
            if (!json || json.status !== true)
                return uploadError(json.error);

            showImage(json.url);
        }
    }
    ajax.open("POST", "https://flixadvisor.fr/upload.php", true);
    ajax.send(formData); // send the form data

}
function uploadError(error) {
    // called on error
    alert(error || 'Une erreur a eu lieu.');
}

function showImage(url) {
    previewImage.src = url;
    uploadingText.style.display = "none";
}
