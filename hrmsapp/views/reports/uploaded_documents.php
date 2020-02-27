<?php
/* Employees view
*/
?>
<?php $session = $this->session->userdata('username');?>

<?php if(in_array('60b',role_resource_ids())) {?>

	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">

				<strong>Reports of Uploaded Documents</strong>

			</h5>

		</div>



		<div data-pattern="priority-columns">

			<table class="table" id="xin_table">

				<thead>
				<tr>

					<th>Visa</th>
					<th>#OF EMP FOR  UPLOADING</th>
					<th>PASSPORT</th>
					<th>VISA PAGE</th>
					<th>PHOTO</th>
					<th>LABOR CARD</th>
					<th>CONTRACT</th>
					<th>LICENSE</th>
					<th>EID</th>
					<th>SIGNED OFFER LTR</th>
				</tr>
				</thead>

				<tbody>

				</tbody>
			</table>

		</div>
	</div>

	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">
				<strong>Reports of Expired Mandatory Documents</strong>
			</h5>
			<div class="col-md-4 no-margin no-padding">
				<select name="Filter expired documents" class="form-control filter_documents">
					<option value="all">All</option>
					<option value="expired">Already Expired</option>
					<option value="current">Current Month Expiry</option>
					<option value="next">Next 3 Months Expiry</option>
				</select>
			</div>
		</div>


		<div data-pattern="priority-columns">

			<table class="table" id="xin_table_expiry">

				<thead>
				<tr>
					<th>Emp Name</th>
					<th>Visa</th>
					<!--					<th>Visa Issue date</th>-->
					<th>Visa expiry date</th>
					<!--					<th>Driving Issue date</th>-->
					<th>Driving expiry date</th>
					<!--					<th>Passport Issue date</th>-->
					<th>Passport expiry date</th>
					<th>EID expiry date</th>
					<!--					<th>Labour Issue date</th>-->
					<th>Labour expiry date</th>
					<!--					<th>Contract Issue date</th>-->
					<th>Contract expiry date</th>


				</tr>

				</thead>

				<tbody>

				</tbody>
			</table>

		</div>
	</div>



<?php } else { ?>
	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title text-danger">
				<?php echo $this->lang->line('xin_permission');?>
			</h5>
		</div></div>

<?php } ?>

<script>
	$(document).ready(function() {
		$('.filter_documents').change(function () {
			xin_table_expiry($(this).val());
		})
		xin_forms();

		xin_table_expiry();
	});
</script>

<style>
	.popover{
		max-width: 500px !important;
	}
</style>
