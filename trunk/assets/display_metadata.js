/**
 * Show/Hide metabox when click in
 */
document.addEventListener('DOMContentLoaded', function() {
    var container =  document.getElementById('trasweb_metadata_metabox__container');

    if( null != container && 'undefined' != container ) {
        container.getElementsByTagName('h2')[0].addEventListener('click', function(event) {
            this.classList.toggle('closed');
        }.bind(container));
    }

    var top_container =  document.getElementById('trasweb-metadata-metabox');
    var max_td_width = top_container.clientWidth / 3;
    var td_list = top_container.querySelectorAll("#trasweb-metadata-metabox td.meta_value");

    for (let i = 0; i < td_list.length; i++) {
        td_list[i].style.maxWidth = max_td_width + 'px';
    }
});