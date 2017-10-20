<?php include 'inc/header.php'; ?>

		<!-- Titlebar -->
		<div id="titlebar">
			<div class="row">
				<div class="col-md-12">
					<h2>Listings</h2>
					<!-- Breadcrumbs -->
					<nav id="breadcrumbs">
						<ul>
							<li><a href="#">Home</a></li>
							<li><a href="#">Dashboard</a></li>
							<li>Listings</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>

		<div class="row">
			<!-- Listings -->
			<div class="col-lg-12 col-md-12 card-box">
					<h4>Active Listings</h4>
					<table id="datatable-buttons" class="table table-striped table-bordered table-colored table-primary datatable-buttons table-condensed client_datatable" style="width:100%"></table>
			</div>
		</div>
			<?php include 'inc/footer.php'; ?>
		<script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.7/jq-3.2.1/jq-3.2.1/dt-1.10.16/b-1.4.2/b-colvis-1.4.2/b-print-1.4.2/fh-3.1.3/r-2.2.0/sc-1.4.3/datatables.min.js"></script>
		<script src="module/schools.js"></script>
