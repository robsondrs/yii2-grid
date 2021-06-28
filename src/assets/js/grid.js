jQuery("[data-delete]").on("click",  function (event) {
    event.preventDefault();
    pjaxgrid = jQuery(this).data('pjax');
    success = jQuery(this).data('success');
    title = jQuery(this).data('delete');
    url = jQuery(this).attr('href');
    redirect = jQuery(this).data('redirect');
    yes = jQuery(this).data('yes');
    no = jQuery(this).data('no');
    
    swal({
        title: title,
        type: 'warning',
        reverseButtons: true,
        cancelButtonText: no,
        showCancelButton: true,
        confirmButtonText: yes,
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return  jQuery.ajax({url: url});
        }
    })
    .then( result  => {
        if (!result.value) {
            throw null;
        }
        swal({
            title: success,
            type: 'success',
            showConfirmButton: true,
            timer: 1000,
            onClose: () => {
                if (redirect) {
                    window.location.href = redirect;
                }
                if (pjaxgrid) {
                    jQuery.pjax.reload({container: pjaxgrid});
                } else {
                    window.location.reload();
                }
            }
        });
    })
    .catch( error => {
        if (error){
            swal({
                title: error.responseText,
                type: 'error',
            });
        }       
    });
});
