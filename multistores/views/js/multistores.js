$(document).ready(function(){

    $('button[name="confirmDeliveryOption"]').on('click',function(){
        var selected_store_name = $(this).prop("value");
        var selected_store_id = $(this).prop("id");
        _selectedStore(selected_store_name, selected_store_id);
        //alert('ok');
    });


    function _selectedStore(selected_store_name, selected_store_id){
        $.ajax({
            url:'modules/multistores/ajax.php',
            type:'json',
            method:'post',
            data: {
                action: 'selectedStore',
                store: selected_store_name,
                store_id: selected_store_id
            },
            success: function(result) {
                console.log(result);
                if(result.status == 'success') {
                }
        	}
        });
    }
   
});