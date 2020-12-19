trasweb_fit_metabox_width = function(){
    /* Avoid overflow */
    var metadata_container =  document.getElementById('trasweb-metadata-metabox');

    if( 0 === metadata_container.clientWidth ) {
        /* Fix Gutenberg */
        setTimeout('trasweb_fit_metabox_width()', 100);
        return ;
    }

    for( var depth_i = 1; true; depth_i++ ) {
        var main_tr_list = metadata_container.querySelectorAll('.depth_' + depth_i);

        if( main_tr_list.length <= 0 ) {
            break;
        }

        for( var i = 0; i < main_tr_list.length; i++ ) {
            var top_container =  main_tr_list[i].parentElement.parentElement;
            var td_key = main_tr_list[i].children[0];
            var td_value  = main_tr_list[i].children[1];

            if( ( td_value.clientWidth + td_key.clientWidth ) > top_container.clientWidth ) {
                td_value.style.maxWidth = ( top_container.clientWidth - td_key.clientWidth - 15 /* padding */ ) + 'px';
            }
        }
    }
};

/**
 * Show/Hide metabox when click in
 */
document.addEventListener('DOMContentLoaded', function() {
    /* non post container */
    var container =  document.getElementById('trasweb_metadata_metabox__container');

    /* enable open/close */
    if( null != container ) {
        container.getElementsByTagName('h2')[0].addEventListener('click', function(event) {
            this.classList.toggle('closed');
        }.bind(container));
    }

    trasweb_fit_metabox_width();
});