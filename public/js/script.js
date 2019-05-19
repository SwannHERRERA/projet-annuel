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
function ajaxSearch() {
    console.log(search.value);
}
