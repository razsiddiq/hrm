$(document).ready(function() {

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
$('[data-plugin="select_hrm"]').select2({ width:'100%' });
/*$('#remarks').summernote({
  height: 120,
  minHeight: null,
  maxHeight: null,
  focus: false
});
$('.note-children-container').hide();*/

/* Add data */ /*Form Submit*/
$("#update_status").submit(function(e){
	e.preventDefault();
	let baseUrl = $('#base_url').val();
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);
	//var remarks = $("#remarks").code();
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&update_type=leave&form="+action,//+"&remarks="+remarks,
		cache: false,
		success: function (JSON) {
			$('#json_message').html(JSON.message);
			if (JSON.error != '') {
				toastr.error(JSON.error);
			} else {
				toastr.success(JSON.result);
				window.location = baseUrl+"/timesheet/leave/";

			}
			$('.save').prop('disabled', false);
		}
	});
});

 $('[data-popup="lightbox"]').fancybox({
    autoCenter: true,
    afterShow: function(){
    var win=null;

    var content = $('.fancybox-inner');
	$('.fancybox-wrap')
    // append print button
    .append('<div title="Download" id="fancy_download" style="position: absolute;right: -5em;top: 4em;z-index:9999;background: #249e92;padding: 10px;border: 1px solid #ffff;"><a href='+site_url+'download?type=leavedocument&filename='+extract_name($(this).attr('href'))+'><i class="icon-download" style="font-size: 1.5em;color: white;cursor: pointer;"></i></a></div>');
    $('.fancybox-wrap')
    // append print button
    .append('<div title="Print" id="fancy_print" style="position: absolute;right: -5em;top: 0em;z-index:9999;background: #249e92;padding: 10px;border: 1px solid #ffff;"><i class="icon-printer" style="font-size: 1.5em;color: white;cursor: pointer;"></i></div>')
    // use .on() in its delegated form to bind a click to the print button event for future elements
    .on("click", "#fancy_print", function(){
      win = window.open("width=200,height=200");
      self.focus();
      win.document.open();
      win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
      win.document.write('body, td { font-family: Verdana; font-size: 10pt;}');
      win.document.write('<'+'/'+'style'+'><'+'/'+'head'+'><'+'body'+'>');
      win.document.write(content.html());
      win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');
      win.document.close();
      win.print();
      win.close();
    }); // on



  } //  afterShow
  ,

 }); // fancybox

    // Initialize multiple switches
    if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    }
    else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }

 $(".switch").bootstrapSwitch();

});

function extract_name(name){
var lastIndex = name.lastIndexOf("/");
return  name.substring(lastIndex + 1);
}
