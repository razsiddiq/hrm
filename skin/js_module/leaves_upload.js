$(document).ready(function() {
	var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
        '  <div class="modal-content">\n' +
        '    <div class="modal-header">\n' +
        '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
        '    </div>\n' +
        '    <div class="modal-body">\n' +
        '      <div class="floating-buttons btn-group"></div>\n' +
        '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>\n';

    // Buttons inside zoom modal
    var previewZoomButtonClasses = {
        toggleheader: 'btn btn-default btn-icon btn-xs btn-header-toggle',
        fullscreen: 'btn btn-default btn-icon btn-xs',
        borderless: 'btn btn-default btn-icon btn-xs',
        close: 'btn btn-default btn-icon btn-xs'
    };

    // Icons inside zoom modal classes
    var previewZoomButtonIcons = {
        prev: '<i class="icon-arrow-left32"></i>',
        next: '<i class="icon-arrow-right32"></i>',
        toggleheader: '<i class="icon-menu-open"></i>',
        fullscreen: '<i class="icon-screen-full"></i>',
        borderless: '<i class="icon-alignment-unalign"></i>',
        close: '<i class="icon-cross3"></i>'
    };

    // File actions
    var fileActionSettings = {
        zoomClass: 'btn btn-link btn-xs btn-icon',
        zoomIcon: '<i class="icon-zoomin31"></i>',
        dragClass: 'btn btn-link btn-xs btn-icon',
        dragIcon: '<i class="icon-three-bars"></i>',
        removeClass: 'btn btn-link btn-icon btn-xs',
        removeIcon: '<i class="icon-trash"></i>',
        indicatorNew: '<i class="icon-file-plus1 text-slate"></i>',
        indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
        indicatorError: '<i class="icon-cross2 text-danger"></i>',
        indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
    };
	$('.file-input-no').fileinput({
        browseLabel: 'Browse',
        browseClass: 'btn bg-teal-400',
        removeClass: 'btn btn-default',
        //uploadClass: 'btn bg-success-400',
        browseIcon: '<i class="icon-file-plus"></i>',
        //uploadIcon: '<i class="icon-file-upload2"></i>',
        removeIcon: '<i class="icon-cross3"></i>',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>',
            main1: "{preview}\n" +
                "<div class='input-group {class}'>\n" +
                "   <div class='input-group-btn'>\n" +
                "       {browse}\n" +
                "   </div>\n" +
                "   {caption}\n" +
                "   <div class='input-group-btn'>\n" +
                "       {remove}\n" +
                "   </div>\n" +
                "</div>",
            modal: modalTemplate
        },
        initialCaption: "No file selected",	
        //allowedFileExtensions: ["jpg", "gif", "png", "JPG", "JPEG"],	
		 allowedFileExtensions: ["csv"],
        previewZoomButtonClasses: previewZoomButtonClasses,
        previewZoomButtonIcons: previewZoomButtonIcons,
        fileActionSettings: fileActionSettings,
		
    });
	
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
		"ajax": {
			url : site_url+"timesheet/leave_list/",
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					 exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                },
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
                        columns: ':visible'// [ 1, 2, 3, 4, 5, 6, 7]
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
	
	$( document ).on( "change", ".change_colors", function() {
		var value=$(this).val();
		$(this).parent('td').removeClass('warning');
		$(this).parent('td').removeClass('danger');
		$(this).parent('td').removeClass('success');
		if(value!=0 && value!='' ){
			$(this).parent('td').addClass('success');
		}else{
		    $(this).parent('td').addClass('danger');
		}
	});
	
  	$("#xin-form").submit(function(e){		
	    var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("add_type", 'leaves_upload');
		fd.append("data", 'leaves_upload');
		fd.append("form", action);
		e.preventDefault();		
	    //$("#preview_view").html('');
		$('.save1').prop('disabled', true);
		var preview_type=$('#preview_type').val();
if(preview_type=='preview'){
		$('#preview_view').html('<div class="row text-center mt-20 mb-20"><i class="icon-spinner2 spinner" style="font-size: 3em;color: #26a69a;"></i></div>');
 }

		$.ajax({			
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function (JSON) {
			$('.save1').prop('disabled', false);
				if (JSON.error != '') {
					toastr.error(JSON.error);
				
				} else {
					if (JSON.result != '') {
					toastr.success(JSON.result);
					$('#xin-form')[0].reset();
					$('.fileinput-remove').click();
					$('form#xin-form select').val('').trigger("change");
					$('#leave_format').hide();
					$('#leave_conversion').hide();
					$('#employee_info').hide();
				    }

			        $("#preview_view").html(JSON.message);
					


					$('[data-popup=popover-custom]').popover({
		template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
	});
	
	$('.date').pickadate({	format: "dd mmmm yyyy",
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: 100});
					
				}
			}
		});
	});
	

	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

	
});

function change_slug(slug){
	$('#preview_type').val(slug);
}
function leave_types(slug){
	
	if(slug=='Leave'){
		$('#leave_format').show();
		$('#leave_conversion').hide();
		$('#employee_info').hide();
	}else if(slug=='Leave_Cash_Conversion'){
		$('#leave_format').hide();
		$('#leave_conversion').show();		
		$('#employee_info').hide();
	}else if(slug=='employee_info'){
		$('#leave_format').hide();
		$('#leave_conversion').hide();		
		$('#employee_info').show();
	}else{
		$('#leave_format').hide();
		$('#leave_conversion').hide();		
		$('#employee_info').hide();
	}
	
	
	
	
	$('#preview_view').html('');
	
}


