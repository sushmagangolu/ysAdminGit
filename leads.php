<?php include 'inc/header.php'; ?>
		<!-- Titlebar -->
		<div id="titlebar">
			<div class="row">
				<div class="col-md-12">
					<h2>Leads</h2>
					<!-- Breadcrumbs -->
					<nav id="breadcrumbs">
						<ul>
							<li><a href="#">Home</a></li>
							<li>Leads</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
					<div class="card-box table-responsive">
						<table id="datatable-buttons" class="table table-striped table-bordered table-colored table-primary datatable-buttons table-condensed client_datatable" style="width:100%"></table>
					</div>
			</div>
		</div>
<?php include 'inc/footer.php'; ?>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="module/ge.js"></script>
<script src="module/leads.js"></script>
