<?php $session = $this->session->userdata('username');?>
<div class="add-form" style="display:none;">
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">
			<strong>Add New</strong> <?php echo $breadcrumbs;?></li>
		</h5>
		<div class="heading-elements">
			<div class="add-record-btn">
			<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
			</div>
		</div>
	</div>	
	<div class="row m-b-1">
	<div class="col-md-12">
		<form action="<?php echo site_url("employee/attendance/add_manual_attendance") ?>" method="post" name="add_manual_attendance" id="xin-form">
			<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
			<div class="col-lg-12">			
				<div class="col-md-4">
					<div class="form-group">
						<label for="name_of_week">Attendance Status</label>
						<select class="form-control" name="attendance_status" data-plugin="select_hrm" data-placeholder="Choose the attendance status...">
							<option value="">&nbsp;</option>
							<option value="Present">Present</option>
							<option value="Absent">Absent</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name_of_week">Reason</label>
						<textarea name="reason" class="form-control"></textarea>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="name">Start Date</label>
						<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
							<input class="form-control" placeholder="Select Date" name="start_date" id="start_date" size="16" type="text"  value="<?php echo date('d F Y');?>" readonly>
							<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
							<label for="name">End Date</label>
							<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
								<input class="form-control" placeholder="Select Date" name="end_date" id="end_date" size="16" type="text"  value="<?php echo date('d F Y');?>" readonly>
								<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
							</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="name">Start Time</label>
						<input class="form-control timepicker clear-1" placeholder="Start Time" readonly name="start_time" type="text" value="">
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="name">End Time</label>
						<input class="form-control timepicker clear-1" placeholder="End Time" readonly name="end_time" type="text" value="">
					</div>
				</div>
				
				<div class="clearfix"></div>
				
			

				<div class="footer-elements">
					<button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
				</div>
			</div>
		</form>
	</div>
	</div>
</div>
</div>

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">
			<strong>Filter</strong>
		</h5>
	</div>
	<div class="panel-body">
		<div class="row">			
			<div class="col-md-3">
				<div class="form-group">
					<div class="input-group date form_month_year" data-date="" data-date-format="yyyy MM"  data-link-format="yyyy MM">
						<input class="form-control" placeholder="Select Month" size="16" id="month_year_1"  type="text" value="<?php echo date('Y F');?>" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					</div>
				</div>             		
			</div>
			<div class="col-md-3">
				<div class="form-group"> &nbsp;
					<button type="button" class="btn bg-teal-400 trigger_filter mr-20">Filter</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">
			<strong>Manual Attendance List</strong>
		</h5>
		<div class="heading-elements">
			<div class="form-group"> &nbsp;				
							
			</div>
		</div>
	</div>

	<div data-pattern="priority-columns">
		<table class="table" id="xin_shift_table" >
			<thead>
				<tr>
				<th>Start/End Date</th>
				<th>Attendace Status</th>
				<th>Approved Status</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

	
<link href="<?php echo base_url();?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
<style>
.select2-search__field{width:100% !important;}
input[type="checkbox"]{ position: absolute; opacity: 0; z-index: -1; }
input[type="checkbox"]+span:before { font: 14pt  FontAwesome; content: '\00f096'; display: inline-block; width: 12pt; padding: 2px 0 0 3px; margin-right: 0.5em; }
input[type="checkbox"]:checked+span:before { content: '\00f046'; }
input[type="checkbox"]:disabled + span::before {color: grey;opacity: 0.3;}
</style>
<script>
$(document).ready(function(){	
	$("#xin-form").submit(function(e){
	e.preventDefault();     	
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&add_type=add_manual_attendance&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} else {
				var xin_shift_table = $('#xin_shift_table').DataTable();
				xin_shift_table.ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);
				$('.add-form').fadeOut('slow');
				$('#xin-form')[0].reset(); // To reset form fields
				$('form#xin-form select').val('').trigger("change");
				$('.save').prop('disabled', false);
				$(".add-new-form").show();		
			}
		}
	});
});

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	$('.clockpicker').clockpicker();
	var input = $('.timepicker').clockpicker({
		placement: 'bottom',
		align: 'left',
		autoclose: true,
		'default': 'now'
	});

	$('.trigger_filter').on('click',function(){
		shift_schedule_list();
	});
	shift_schedule_list();
	
	function shift_schedule_list(){		
		var month_year=$('#month_year_1').val();		
		$.extend( $.fn.dataTable.defaults, {
			autoWidth: false,
			dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
			language: {
				search: '<span>Filter:</span> _INPUT_',
				lengthMenu: '<span>Show:</span> _MENU_',
				paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
			}
    	});
	
		var xin_shift_table = $('#xin_shift_table').DataTable({
		"bDestroy": true,
		"iDisplayLength": 100,
		"ajax": {
			url 	: site_url+"employee/attendance/employee_manual_attendance_lists/?month_year="+month_year,
			type 	: 'GET'
				},
		"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();          
				}, 
				buttons: [	
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
				{
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn btn-default',
                exportOptions: {
                    columns: ':visible'
                }
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-teal-400 btn-icon'
                }
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

	
});
</script>