function removeShowList(idShow, idList) {
    if (confirm("Voulez vous vraiment supprimer cette série de votre liste ?")) {

        const element = document.getElementById(idShow);
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                if (request.status === 200) {
                    element.remove();
                }
            }
        };
        request.open('GET', '/show/removeShowList?list=' + idList + "&show=" + idShow);
        request.send();
    }
}

function searchShow() {
    const input = document.getElementById("searchShow").value.toUpperCase();
    console.log(input);
    const list = document.getElementById("list").getElementsByTagName("tr");
    for (let i = 0; i < list.length; i++) {
        if (list[i].getElementsByTagName("span")[0].innerHTML.toUpperCase().indexOf(input) > -1) {
            list[i].style.display = "";
        } else {
            list[i].style.display = "none";
        }
    }
}
