<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 text-center">
            <br><br>
            <h1>Welcome to <?php echo strtoupper($this->Session->read('Store.name')); ?></h1>
            <br>
            <div class="panel panel-default hidden">
                <div class="panel-heading">
                    <h4>Closing Stock</h4>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="/sales/addClosingStockMobile" class="list-group-item"><i class="fa fa-plus-circle"></i>
                            Add Closing Stock</a>
                        <a href="/sales/viewClosingStock" class="list-group-item"><i class="fa fa-list-alt"></i> Show
                            Closing Stock Report</a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default hidden mt-3">
                <div class="panel-heading">
                    <h4>Invoices</h4>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="/invoices/add" class="list-group-item"><i class="fa fa-plus-circle"></i> Add
                            Invoice</a>
                        <a href="/invoices/" class="list-group-item"><i class="fa fa-list-alt"></i> Invoice List</a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default hidden mt-3">
                <div class="panel-heading">
                    <h4>Stock Reports</h4>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a class="list-group-item" href="/reports/dayWiseStockReport"><i class="fa fa-list-alt"></i>
                            Show Custom Stock Report</a>
                        <a class="list-group-item" href="/reports/completeStockReport"><i class="fa fa-list-alt"></i>
                            Show Complete Stock Report</a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default hidden mt-3">
                <div class="panel-heading">
                    <h4>Visual Reports</h4>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a class="list-group-item" href="/reports/completeStockReportChart/store_performance"><i class="fa fa-list-alt"></i>
                            My Store Performance</a>
                        <a class="list-group-item" href="/reports/completeStockReportChart/top_performing_products"><i class="fa fa-list-alt"></i>
                            Top Performing Products</a>
                        <a class="list-group-item" href="/reports/completeStockReportChart/sales_purchases_profit"><i class="fa fa-list-alt"></i>
                            Sales, Purchases & Profit on sales</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>

