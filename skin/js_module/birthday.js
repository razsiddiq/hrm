$(document).ready(function(){
	
	
	
	
    var xin_comment_table=$('#xin_comment_table').DataTable({
		"bDestroy": true,
		"paging":   false,
        "ordering": false,
        "info":     false,
		 "filter":     false,
		 "iDisplayLength": 1000,
		"ajax": {
			url : base_url+"/comments_list/"+$('#birthday_id').val()+'/'+$('#birthday_date').val(),
			type : 'GET'
		},
		"columnDefs": [	
				{
					"targets": [ 0 ],
					"visible": false,
					"searchable": false,
					"orderData": [ 0, 1 ]
			 }],
		"order": [[ 0, "desc" ]],  
        "fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	
			
        
    });
	
	
	   
	// Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
	
$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
	 swal({
            title: "Are you sure?",
            text: "Record deleted can't be restored!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, delete it!",
            //closeOnConfirm: false,
            //closeOnCancel: false			
			
        },
        function(isConfirm){
            if (isConfirm) {
				var obj = $('.delete_record'), action = obj.attr('name');
		
				$.ajax({
					type: "POST",
					url: $('.delete_record').attr('action'),
					data: obj.serialize()+"&is_ajax=6&data=ticket_comment&form="+action,
					cache: false,
					success: function (JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
						} else {
							  	/*xin_comment_table.ajax.reload(function(){ 
						
					}, true);*/
					
					toastr.success(JSON.result);
						var xin_comment_table=$('#xin_comment_table').DataTable({
		"bDestroy": true,
		"paging":   false,
        "ordering": false,
        "info":     false,
		 "filter":     false,
		 "iDisplayLength": 1000,
		"ajax": {
			url : base_url+"/comments_list/"+$('#birthday_id').val()+'/'+$('#birthday_date').val(),
			type : 'GET'
		},
		"columnDefs": [	
				{
					"targets": [ 0 ],
					"visible": false,
					"searchable": false,
					"orderData": [ 0, 1 ]
			 }],
		"order": [[ 0, "desc" ]],  
        "fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	
			
        
    });
						
						}
					}
				});
		  
		  
            }
            /*else {
                swal({
                    title: "Cancelled",
                    text: "Your imaginary file is safe :)",
                    confirmButtonColor: "#2196F3",
                    type: "error"
                });
            }*/
        });
});
	/* Edit comment */
	$("#set_comment").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=5&add_type=set_comment&update=1&view=birthday&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					xin_comment_table.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('#xin_comment').val('');
					$('.save').prop('disabled', false);
					
				}
			}
		});
	});
	
	


	
	



});

function reply_comments(id){
$('.show_comments').hide("slow");
$('#show_comments_'+id).slideToggle("slow");
}

function show_comments(id){
	/*Form Submit*/	
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: base_url+"/set_comment/",
			data: "&is_ajax=5&add_type=set_comment&update=1&view=birthday&birthday_id="+$('#birthday_id').val()+"&birthday_date="+$('#birthday_date').val()+"&user_id="+$('#user_id').val()+"&xin_comment="+$('.xin_comments_'+id).val()+"&to_id="+$('.to_id_'+id).val()+"&parent="+id,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {

				$('#xin_comment_table').dataTable({
						"bDestroy": true,
						"ajax": {
							url : base_url+"/comments_list/"+$('#birthday_id').val()+'/'+$('#birthday_date').val(),
							type : 'GET'
						},
						"columnDefs": [
							{
								"targets": [ 0 ],
								"visible": false,
								"searchable": false,
								"orderData": [ 0, 1 ]
						 }],
						"order": [[ 0, "asc" ]],
						"fnDrawCallback": function(settings){
						$('[data-toggle="tooltip"]').tooltip();          
						}
				});


						toastr.success(JSON.result);
					
					$('#xin_comment').val('');
					$('.save').prop('disabled', false);
					
				}
			}
		});
	return false;

}