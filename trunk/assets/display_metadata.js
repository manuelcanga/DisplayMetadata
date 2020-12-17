/**
 * Show/Hide metabox when click in
 */
document.addEventListener('DOMContentLoaded', function() {
    var container =  document.getElementById('trasweb_metadata_metabox__container');
    container.getElementsByTagName('h2')[0].addEventListener('click', function(event) {
        this.classList.toggle('closed');
    }.bind(container));
});