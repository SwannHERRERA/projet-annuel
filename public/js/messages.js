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
    request.open('GET', '/messages/getMessages?pseudo=' + pseudo);
    request.send();
}

function sendMessage() {
    const pseudo = document.getElementsByClassName("list-group-item list-group-item-action active")[0].innerHTML;
    const element = document.getElementById("messages");
    const message = document.getElementById("newMessage").value;
    if (message.length > 0) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                if (request.status === 200) {
                    element.innerHTML += request.responseText;
                    message.value = "";
                }
            }
        };
        request.open('POST', '/messages/sendMessage');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(
            'pseudo=' + pseudo +
            '&message=' + message
        );
    }
}