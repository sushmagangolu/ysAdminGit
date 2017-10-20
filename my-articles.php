<?php include 'inc/header.php'; ?>
<!-- Titlebar -->
<div id="titlebar">
    <div class="row">
        <div class="col-md-12">
            <h2>My Articles</h2>
            <!-- Breadcrumbs -->
            <nav id="breadcrumbs">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li>My Articles</li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<div class="card-box">
  <div class="row">
    <div class="col-md-12">
      <a href="add-article.php" class="button">Add Article</a>
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
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-html5-1.3.1/b-print-1.3.1/fh-3.1.2/r-2.1.1/sc-1.4.2/datatables.min.js"></script>
<script src="module/my-articles.js"></script>
