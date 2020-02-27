$(document).ready(function(){
	//myScheduleTrigger();
	/*if(user_resource_role == 'YES' ) {	
		sparkline("#active-employees", "line", 30, 35, "basis", 750, 2000, "#26A69A");
		sparkline("#total-expense", "line", 30, 35, "basis", 750, 2000, "#FF7043");
		sparkline("#total-salary", "line", 30, 35, "basis", 750, 2000, "#5C6BC0");
		sparkline("#total-jobs", "line", 30, 35, "basis", 750, 2000, "#E84033");
	}*/
	var birthHeight=$('.birth_height').height();
	if(birthHeight > 290){
		$('.birth_height').attr('style','overflow-y: scroll; max-height: 290px;margin-bottom: 1em;');
	}
	$('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
});

function kFormatter(num) {
	if(num==0 || num==null){
		return 0;
	}
	else{
    return num > 999 ? (num/1000).toFixed(0) + 'k' : num
	}
}

if(user_resource_role == 'NO' ) {	
	$(function () {
			require.config({
					paths: {
							echarts: 'assets/js/plugins/visualization/echarts'
					}
			});
			require(
					[
							'echarts',
							'echarts/theme/limitless',
							'echarts/chart/bar',
							'echarts/chart/line',
							'echarts/chart/pie'
					],
					// Charts setup
					function (ec, limitless) {
							var task_progress_value=$('#task_progress_value').val();
							$.ajax({
							url: base_url+'/time_attn/',
							method: "json",
							success: function(response) {
									var bgcolor = [];
									var final = [];
									var final2 = [];
									var name = [];
									var punchin = [];
									var punchout = [];
									for(i=0; i < response.d_name; i++) {
											final.push(response.chart_data[i].count);
											name.push(response.chart_data[i].name);
											final2.push(response.chart_data[i].label);
											bgcolor.push(response.chart_data[i].value);
											punchin.push(response.chart_data[i].punch_in);
											punchout.push(response.chart_data[i].punch_out);
									}
									var stacked_lines = ec.init(document.getElementById('stacked_lines'), limitless);
									stacked_lines_options = {
											tooltip: {
													trigger: 'axis'
											},
											legend: {
													data: ['Total Work']//, 'Punch Out'
											},
											color: ['#99C1DC'],
											calculable: true,
											tooltip: {
													trigger: 'axis',
													formatter: function (params) {
															if(punchin[params[0].dataIndex]!=0){
																	var PIN = $.parseJSON('{"date_created":"'+punchin[params[0].dataIndex]+'"}'),
																	PIN_D = new Date(1000*PIN.date_created);
															}
															else{
																	PIN_D ='N/A';
															}
															if(punchout[params[0].dataIndex]!=''){
																	var POUT = $.parseJSON('{"date_created":"'+punchout[params[0].dataIndex]+'"}'),
																	POUT_D = new Date(1000*POUT.date_created);
															}
															else{
																	POUT_D ='N/A';
															}

															var minutes = Math.floor(params[0].data); // 7
															var seconds = Math.floor(((params[0].data - Math.floor(params[0].data))*3600)/60);

															return params[0].name + '<br>'
															+ params[0].seriesName + ' :  ' + minutes + ' h ' + seconds + ' m' + '<br>'
															+ 'Punch In :' + PIN_D.toLocaleString() + '<br>'
															+ 'Punch Out :' + POUT_D.toLocaleString();
													}
											},
											xAxis: [{
													type: 'category',
													boundaryGap: false,
													data: name
											}],
											yAxis: [{
													type: 'value',
													axisLabel: {
															formatter: '{value} h'
													},

											}],
											series: [
													{
															name: 'Total Work',
															type: 'line',
															stack: 'Total',
															data: bgcolor
													},
											]
									};

									stacked_lines.setOption(stacked_lines_options);

									window.onresize = function () {
											setTimeout(function () {
													stacked_lines.resize();
											}, 200);
									}
							}
							});

							var multiple_donuts = ec.init(document.getElementById('multiple_donuts'), limitless);
							var labelTop = {
									normal: {
											label: {
													show: true,
													position: 'center',
													formatter: '{b}\n',
													textStyle: {
															baseline: 'middle',
															fontWeight: 300,
															fontSize: 15
													}
											},
											labelLine: {
													show: false
											}
									}
							};

							var labelFromatter = {
									normal: {
											label: {
													formatter: function (params) {
															return '\n\n' + (100 - params.value) + '%'
													}
											}
									}
							}

							var labelBottom = {
									normal: {
											color: '#eee',
											label: {
													show: true,
													position: 'center',
													textStyle: {
															baseline: 'middle'
													}
											},
											labelLine: {
													show: false
											}
									},
									emphasis: {
											color: 'rgba(0,0,0,0)'
									}
							};

							var radius = [60, 75];

							multiple_donuts_options = {
									series: [
											{
													type: 'pie',
													center: ['50%', '50%'],
													radius: radius,
													itemStyle: labelFromatter,
													data: [
															{name: 'other', value: (100-task_progress_value), itemStyle: labelBottom},
															{name: 'Profile', value: task_progress_value,itemStyle: labelTop}
													]
											}
									]
							};


							multiple_donuts.setOption(multiple_donuts_options);
							window.onresize = function () {
								setTimeout(function () {
										multiple_donuts.resize();
									}, 200);
							}
					}
			);

			$(".colorpicker-basic").spectrum({
				//showInput: true
			});

			$("#anytime-both1,#anytime-both").AnyTime_picker({
					format: "%M %D %Y %H:%i",
			});

			$("#schedule-form").submit(function(e){
					e.preventDefault();
					var obj = $(this), action = obj.attr('name');
					$('.save').prop('disabled', true);
					var st=$('.sp-preview-inner').attr('style');
					st=st.replace('background-color:','');
					st=st.replace(';','');
					$('#schedule_color').val(st);
					$.ajax({
						type: "POST",
						url: e.target.action,
						data: obj.serialize()+"&is_ajax=1&add_type=add_schedule&form="+action,
						cache: false,
						success: function (JSON) {
							if (JSON.error != '') {
								toastr.error(JSON.error);
								$('.save').prop('disabled', false);
							} else {
								toastr.success(JSON.result);
								myScheduleTrigger();
								$('.add-form').fadeOut('slow');
								$('#schedule-form')[0].reset(); // To reset form fields
								$('.save').prop('disabled', false);
								$(".add-new-form").show();
							}
						}
					});
			});
	});
}

function leave_summary_table(id='',start_date='',end_date='',type_id='',status='',emp_id=''){

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
			url : site_url+"dashboard/dashboard_leave_chart/?id="+id+'&start_date='+start_date+'&end_date='+end_date+'&type_id='+type_id+'&status='+status+'&emp_id='+emp_id,
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();
		},
		
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					 exportOptions: {
                        columns: ':visible'
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
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
}

$("#leave_chart_form").submit(function(e){
	e.preventDefault();

	var dep_id = $('#dep_id').val();
	var start_date = $('#start_date').val();
	var end_date = $('#end_date').val();
	var status = $('#status').val();
	var emp_id = $('#emp_id').val();

	$('#leave_type_id').val('');
	$('#emp_id').val('');
	

	design_sal_graph(dep_id,start_date,end_date,status,emp_id);
});

function leave_list_by_type(){
	var dep_id = $('#dep_id').val();
	var start_date = $('#start_date').val();
	var end_date = $('#end_date').val();
	var status = $('#status').val();
	var emp_id = $('#emp_id').val();
	var type_id = $('#leave_type_id').val();
	leave_summary_table(dep_id,start_date,end_date,type_id,status,emp_id);
}

$(document).ready(function(){
	design_sal_graph();
});

function design_sal_graph(id='',start_date='',end_date='',status='',emp_id=''){
		
		var id = $('#dep_id').val();
		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		var pie = document.getElementById("pie");
		var barchart;
		var color1b ='rgba(14,148,140,1)';
		var color2b ='rgba(100, 149, 237,1)';
		
		leave_summary_table(id,start_date,end_date,type_id='',status,emp_id='');

		$.ajax({
				url: base_url+'/dashboard_leave_chart/?id='+id+'&start_date='+start_date+'&end_date='+end_date+'&status='+status+'&emp_id='+emp_id,
				method: "json",
				success: function(response) {

					$('#emp_select').html(response.emp_list);
					$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

					var bgcolor = [];
					var final = [];
					var final2 = [];
					var name = [];
					
					$.each(response.chart_data, function(i, item) {
						final.push(response.chart_data[i].count);
						name.push(response.chart_data[i].name);
						final2.push(response.chart_data[i].label);
						bgcolor.push(response.chart_data[i].color);
					});

					var data_leave = {
						label: 'Leave application count',
						data: final,
						backgroundColor: bgcolor,
						borderWidth: 0,
						yAxisID: "y-axis-no-of-employees"
					};
					
					var planetData = {
						labels: final2,
						names:name,
						datasets: [data_leave]
					};

					var chartOptions = {
							responsive: true,
								scales: {
								xAxes: [{
										 gridLines: {
										drawOnChartArea: false
									},
								}],
								yAxes: [{
									 gridLines: {
										drawOnChartArea: false
									},
									stacked: true,
									position: "left",
									id: "y-axis-no-of-employees",
									ticks: {
										beginAtZero: true,
										callback: function (value) { if (Number.isInteger(value)) { return value; } },
											stepSize: 10,
											fontColor: color1b, // this here
											fontStyle: "bold",
										},
								},{
									stacked: false,
									position: "right",
									id: "y-axis-total-salary",
									 ticks: {
										 beginAtZero: true,
										callback: function (value) { if (Number.isInteger(value)) { return value; } },
										stepSize: 1,
										fontColor: '#ffffff', // this here
										fontStyle: "bold",
										},
								}]
								},"animation": {
											"onComplete": function() {
													var chartInstance = this.chart,
													ctx = chartInstance.ctx;
													ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
													ctx.textAlign = 'center';
													ctx.textBaseline = 'bottom';
													this.data.datasets.forEach(function(dataset, i) {
														var meta = chartInstance.controller.getDatasetMeta(i);
														meta.data.forEach(function(bar, index)
														{
														var data = dataset.data[index];
														if(dataset.label=='No. Of Employees'){
														ctx.fillStyle  = color1b;
														ctx.fillText(kFormatter(data), bar._model.x, bar._model.y - 1);
														}else{
														ctx.fillStyle  = color2b;
														ctx.fillText(kFormatter(data), bar._model.x, bar._model.y - 1);
														}

														});
													});
											}
									  }
									 ,tooltips: {
											mode: 'label',
											backgroundColor :'rgba(0, 0, 0)',
											bodySpacing: 10,
											titleMarginBottom: 10,
									 },
					};

					barChart = new Chart(pie, {
							type: 'bar',
							data: planetData,
							options: chartOptions
					});
				},
				error: function(data) {
					// console.log(data);
				}
		});
}

function change_design_salary(id){
 design_sal_graph(id);
}

function myScheduleTrigger(){
		$('.schedule').fullCalendar('destroy');
		var fullDate = new Date()
		var user_id=$('#user_id').val();
		if(user_id!=undefined){
			$.ajax({
				url: base_url+'/get_my_schedule/'+$('#user_id').val(),
				method: "json",
				success: function(response) {
					var eventsColors=JSON.parse(response);
					$('.schedule').fullCalendar({
							header: {
									left: 'prev,next today',
									center: 'title',
									right: 'prev,next',//month,agendaWeek,agendaDay
							},
							defaultDate: fullDate,
							editable: true,
							events: eventsColors,
							eventRender: function(event, element) {
									element.find(".fc-bg").css("pointer-events","none");
									element.append("<div style='position:absolute;bottom:0px;left:-1px;top: -23px;' ><i  data-toggle='modal' id='btnEditEvent' class='icon-pencil7 mr-5 text-teal-400'></i><i id='btnDeleteEvent' class='icon-trash text-danger'></i></div>" );
									element.find("#btnDeleteEvent").click(function(){
										swal({
												title: "Are you sure?",
												text: "Record deleted can't be restored!",
												type: "warning",
												showCancelButton: true,
												confirmButtonColor: "#FF7043",
												confirmButtonText: "Yes, delete it!",

										},
										function(isConfirm){
									if (isConfirm) {
										$.ajax({
											type: "POST",
											url: base_url+'/delete_my_schedule/'+event.id,
											cache: false,
											success: function (JSON) {
															toastr.success(JSON.result);
															$('.schedule').fullCalendar('removeEvents',event.id);
											}
										});
									}
							});
									});
							element.find("#btnEditEvent").click(function(){
								$(".edit-modal-data").modal();
								var modal = $(this);
								$.ajax({
									url : base_url+"/read/",
									type: "GET",
									data: 'jd=1&is_ajax=1&mode=modal&data=schedule&event_id='+event.id,
									success: function (response) {
										if(response) {
											$("#ajax_modal").html(response);
										}
									}
								});
							});
						},
							eventClick: function(event) {
									if (event.url) {
											window.open(event.url);
											return false;
									}
							}
					});
				}
			});
		}
   //$('.schedule').fullCalendar('refresh');
}

function sparkline(element, chartType, qty, height, interpolation, duration, interval, color) {
        var d3Container = d3.select(element),
            margin = {top: 0, right: 0, bottom: 0, left: 0},
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
            height = height - margin.top - margin.bottom;

        var data = [];
        for (var i=0; i < qty; i++) {
            data.push(Math.floor(Math.random() * qty) + 5)
        }

        var x = d3.scale.linear().range([0, width]);

        var y = d3.scale.linear().range([height - 5, 5]);

        x.domain([1, qty - 3])

        y.domain([0, qty])

        var line = d3.svg.line()
            .interpolate(interpolation)
            .x(function(d, i) { return x(i); })
            .y(function(d, i) { return y(d); });

        var area = d3.svg.area()
            .interpolate(interpolation)
            .x(function(d,i) { 
                return x(i); 
            })
            .y0(height)
            .y1(function(d) { 
                return y(d); 
            });

        var container = d3Container.append('svg');

        var svg = container
            .attr('width', width + margin.left + margin.right)
            .attr('height', height + margin.top + margin.bottom)
            .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var clip = svg.append("defs")
            .append("clipPath")
            .attr('id', function(d, i) { return "load-clip-" + element.substring(1) })

        var clips = clip.append("rect")
            .attr('class', 'load-clip')
            .attr("width", 0)
            .attr("height", height);

        clips
            .transition()
                .duration(1000)
                .ease('linear')
                .attr("width", width);

        var path = svg.append("g")
            .attr("clip-path", function(d, i) { return "url(#load-clip-" + element.substring(1) + ")"})
            .append("path")
                .datum(data)
                .attr("transform", "translate(" + x(0) + ",0)");

        if(chartType == "area") {
            path.attr("d", area).attr('class', 'd3-area').style("fill", color); // area
        }
        else {
            path.attr("d", line).attr("class", "d3-line d3-line-medium").style('stroke', color); // line
        }

        path
            .style('opacity', 0)
            .transition()
                .duration(750)
                .style('opacity', 1);

        setInterval(function() {

            data.push(Math.floor(Math.random() * qty) + 5);

            data.shift();

            update();

        }, interval);

        function update() {

            path
                .attr("transform", null)
                .transition()
                    .duration(duration)
                    .ease("linear")
                    .attr("transform", "translate(" + x(0) + ",0)");

            if(chartType == "area") {
                path.attr("d", area).attr('class', 'd3-area').style("fill", color)
            }
            else {
                path.attr("d", line).attr("class", "d3-line d3-line-medium").style('stroke', color);
            }
        }

        $(window).on('resize', resizeSparklines);

        $(document).on('click', '.sidebar-control', resizeSparklines);

        function resizeSparklines() {
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;

            container.attr("width", width + margin.left + margin.right);

            svg.attr("width", width + margin.left + margin.right);

            x.range([0, width]);

            clips.attr("width", width);

            svg.select(".d3-line").attr("d", line);

            svg.select(".d3-area").attr("d", area);
        }
}

