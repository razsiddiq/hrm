<div class="panel panel-default">
	<div id="sh_message"></div>
	<div class="panel-heading">
		<h5 class="panel-title">
			<strong>Passport request</strong>
		</h5>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12">
				<form class="add form-hrm" method="post" name="request_passport" id="request_passport" action="<?php echo site_url();?>employees/passport_request_document">

					<div class="form-group">
						<label for="from_date">From Date:</label>
				  		<input type="text" style="width: auto;" required name="from_date" class="form_date_curr form-control" id="from_date" data-date-format="dd MM yyyy" >
					</div>

					<div class="form-group">
						<label for="return_date">Return Date:</label>
				  		<input type="text" style="width: auto;" required name="return_date" class="form_date_curr form-control" id="return_date" data-date-format="dd MM yyyy" >
					</div>

					<div class="form-group">
						<label for="purpose">Purpose:</label>
				  		<textarea class="form-control" placeholder="Purpose of" rows="5" id="purpose" name="purpose"></textarea>
					</div>

	                <div class="col-md-12">
	                  <div class="form-group">
	                    <div class="footer-elements">
	                      <button type="submit" class="btn bg-teal-400 save">Send Request<i class="icon-spinner3 spinner position-left"></i></button>
	                    </div>
	                  </div>
	                </div>
				</form>
			</div>
		</div>
	</div>

</div>

<div class="row m-b-1">
  <div class="col-md-12">
    <div class="box box-block bg-white">
      <div  data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
          <thead>
            <tr>
            	<th>Sl.no</th>
          		<th>Employee name</th>
          		<th>Status</th>
          		<th>Requested date</th>
          		<th>Return date</th>
          		<th>Purpose</th>
          		<?php if(rp_manager_access() || $role_user[0]->role_name == AD_ROLE || (in_array('request-passport-delete',role_resource_ids()) && in_array('request-passport-delete',role_resource_ids())) ){ ?>
          		<th>Action</th>
          		<?php }?>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	var site_url = '<?php echo base_url()?>';
	load_passport_request();
	function load_passport_request(){

		$.extend( $.fn.dataTable.defaults, {
			autoWidth: false,
			dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
			language: {
				search: '<span>Filter:</span> _INPUT_',
				lengthMenu: '<span>Show:</span> _MENU_',
				paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
			}
		});

		var xin_table = $('#xin_table').DataTable({
			"bDestroy": true,
			"iDisplayLength": 100,
			"ajax": {
				url : site_url+"employees/passport_request_list",
				type : 'GET'
			},"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();
			},
			"order": [[ 0, "asc" ]],
			"columnDefs": [
				{
					"targets": [ ],
					"visible": false,
					"searchable": false,

				}
			],
			buttons: [
				{
					extend: 'copyHtml5',
					className: 'btn btn-default',
					exportOptions: {
						columns: [ 0, 1, 3, 4, 5, 6, 7]
					}
				},
				{
					extend: 'excelHtml5',
					className: 'btn btn-default',
					exportOptions: {
						//columns: ':visible'
						columns: [ 0, 1, 3, 4, 5, 6, 7]
					}
				},
				{
					extend: 'pdfHtml5',
					className: 'btn btn-default',
					exportOptions: {
						columns: [ 0, 1, 3, 4, 5, 6, 7]
					}
				},
				{
					extend: 'print',
					text: '<i class="icon-printer position-left"></i> Print table',
					className: 'btn btn-default',
					exportOptions: {
						columns: [ 0, 1, 3, 4, 5, 6, 7]
					}
				},
				/*{
	                extend: 'colvis',
	                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
	                className: 'btn bg-teal-400 btn-icon'
	            }*/
			]

		});

		// Add placeholder to the datatable filter option
		$('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

		// Enable Select2 select for the length option
		$('.dataTables_length select').select2({
			minimumResultsForSearch: Infinity,
			width: 'auto'
		});
	}

	$("#request_passport").submit(function(e){
	    var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("add_type", 'passport_request');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function (JSON) {
				if (JSON.error != '') {
					load_passport_request();
					toastr.error(JSON.message);
					$('.save').prop('disabled', false);
				} else {
					load_passport_request();
					toastr.success(JSON.message);
					$('#request_passport')[0].reset(); 
					$('.save').prop('disabled', false);
				}
			}
		});
	});

	$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	var req_type = $(this).data('type-id');

	$('.delete_record').attr('action',site_url+'employees/delete_passport_request/'+$(this).data('record-id'));

	if(req_type == 'Delete'){
		var text = "Record deleted can't be restored!";
		var confirmButtonText = "Yes, delete it!";
		var type = 'warning';

	}else{

		if(req_type == 'Reject'){

			var text = "Do you really want to change the status.";
		 	var confirmButtonText = "Yes, change it!";
		 	var type = 'input';

		}else{

			var text = "Do you really want to change the status.";
		 	var confirmButtonText = "Yes, change it!";
		 	var type = 'warning';
		 }
	}

	swal({
			title: "Are you sure?",
			html: true,
			text: text,
			type: type,
			showCancelButton: true,
			confirmButtonColor: "#FF7043",
			confirmButtonText: confirmButtonText,
			inputPlaceholder: "Reason for rejection",

		},
		function(isConfirm){

			if (isConfirm) {
				var obj = $('.delete_record'), action = obj.attr('name');
				$.ajax({
					type: "POST",
					url: $('.delete_record').attr('action'),
					data: obj.serialize()+"&is_ajax=2&form="+action+"&type="+req_type+"&comment="+isConfirm,
					cache: false,
					success: function (JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
						} else {
							load_passport_request();
							toastr.success(JSON.result);
						}
					}
				});
			}else{
		        toastr.error("You need to write reason for the rejection.");     
		        return false   
			}

		});
	});

});

</script>