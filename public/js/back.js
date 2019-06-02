function confdel(url) {
    if (window.confirm("voulez vous vraiment supprimé definitivement cette élément")) {
        window.location = url;
    }
}

function searchShow() {
    const filter = document.getElementById("searchShow").value.toUpperCase();
    const table = document.getElementsByTagName("table")[0];
    const tr = table.getElementsByTagName("tr");
    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName("td");
        if (td[0].innerHTML.split("<span")[0].toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

function getMessages(pseudo) {
    const element = document.getElementById("messages");
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.innerHTML = request.responseText;
            }
        }
    };
    request.open('GET', '/back/ticket/getMessages?pseudo=' + pseudo);
    request.send();
}


function sendMessage() {
    const pseudo = document.getElementsByClassName("list-group-item list-group-item-action active")[0].innerHTML.split("<br>")[0];
    const element = document.getElementById("messages");
    const message = document.getElementById("newMessage").value;
    if (message.length > 0) {
        console.log(pseudo);
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                if (request.status === 200) {
                    element.innerHTML += request.responseText;
                    message.value = "";
                }
            }
        };
        request.open('POST', '/back/ticket/sendMessage');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(
            'pseudo=' + pseudo +
            '&message=' + message
        );
    }
}

function filter() {
    const filter = document.getElementById('filter').value.toUpperCase();
    const elements = document.getElementById('list-tab').getElementsByTagName('a');
    for (let i = 0; i < elements.length; i++) {
        if (elements[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            elements[i].style.display = "";
        } else {
            elements[i].style.display = "none";
        }
    }
}