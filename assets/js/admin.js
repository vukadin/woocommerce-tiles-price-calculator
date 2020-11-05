jQuery(document).ready(function($){
    $('#_wtpc_is_measurable').on('click',function(){
        if( this.checked )
        {
            $('.show_if_measurable').show();
        }
        else {
            $('.show_if_measurable').hide();
        }
    });

    if( !$('#_wtpc_is_measurable').prop('checked') ){
        $('.show_if_measurable').hide();
    }
});