function confdel(url){
    if(window.confirm("voulez vous vraiment supprimé definitivement cette élément")){
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
