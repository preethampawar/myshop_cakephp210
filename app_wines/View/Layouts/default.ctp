<?php $cakeDescription = __d('cake_dev', 'WineS :'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>
			<?php echo $cakeDescription ?>:
			<?php echo $title_for_layout; ?>
		</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
		<link rel="icon" type="image/gif" href="<?php echo $this->Html->url('/img/stats.gif', true);?>">
        <link rel="stylesheet" href="<?php echo $this->Html->url('/css/normalize.css', true);?>">
        <link rel="stylesheet" href="<?php echo $this->Html->url('/css/main.css', true);?>">
		
		<!-- bootstrap CSS-->
		<link rel="stylesheet" href="<?php echo $this->Html->url('/bootstrap-3.3.7/dist/css/bootstrap.min.css');?>">
		<link rel="stylesheet" href="<?php echo $this->Html->url('/bootstrap-3.3.7/dist/css/bootstrap-theme.min.css');?>">	
		
		
        <script src="<?php echo $this->Html->url('/js/vendor/modernizr-2.6.2.min.js', true);?>"></script>
		
		<!-- jQuery JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		
		<?php 
			// echo $this->Html->css('cake.generic'); 
			echo $this->Html->css('site'); 
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
		<!-- bootstrap JS-->
		<script type="text/javascript" src="<?php echo $this->Html->url('/bootstrap-3.3.7/dist/js/bootstrap.min.js');?>"></script>
		<!-- select2 CSS -->
		<link rel="stylesheet" href="<?php echo $this->Html->url('/select2/select2.min.css');?>">		
		<!-- select2 JS -->
		<script type="text/javascript" src="<?php echo $this->Html->url('/select2/select2.min.js');?>"></script>
		<script type="text/javascript" src="<?php echo $this->Html->url('/html-table-search/html-table-search.js');?>"></script>
		<!-- html table search JS -->
		<style type="text/css">
		.select2-results ul {
			color:#333;
		}
		.row {
			margin-right: 0px;
			margin-left: 0px;
		}
		.checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"], .radio input[type="radio"], .radio-inline input[type="radio"] {
			margin-left: 0px;
		}
		.btn {
			text-decoration: none;
		}
		</style>
		
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="<?php $this->Html->url('/js/html5shiv.min.js');?>"></script>	
			<script type="text/javascript" src="<?php $this->Html->url('/js/respond.min.js');?>"></script>
		<![endif]-->
		
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<link href="<?php echo $this->Html->url('/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css');?>" rel="stylesheet">
		
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
       
        
		<?php	
		$showHeader = true;
		if(isset($hideHeader) and ($hideHeader)) { $showHeader = false; }
		?>
		
		<?php if($showHeader) { ?>		
		<header id="header">
			<h1 style="float:left;">
				<?php echo ($this->Session->check('Store')) ? strtoupper($this->Session->read('Store.name')) : 'SimpleAccounting'; ?>
			</h1>
			<?php if($this->Session->check('Auth.User')) { ?>
			<div style="float:right;">				
				<?php 
					echo $this->Html->link('My Stores', array('controller'=>'stores', 'action'=>'index'));
                    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                    if ($this->Session->read('manager') == '1') {
                        echo $this->Html->link('Users', array('controller'=>'users', 'action'=>'index'));
                        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                    }

                    if ($this->Session->read('storeAccess.isAdmin')) {
                        echo $this->Html->link('User Access', array('controller' => 'stores', 'action' => 'storeAccess'));
                        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                    }

                echo $this->Html->link('Logout', array('controller'=>'users', 'action'=>'logout'));
				?>
			</div>
			<?php }?>
			<div style="clear:both;"></div>
			<?php if($this->Session->check('Auth.User')) { ?>
				<nav style="font-size:12px;">

					<?php
					if($this->Session->check('Store')) {
					?>
						<?php echo $this->Html->link('Home', array('controller'=>'stores', 'action'=>'home'));?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Products', array('controller'=>'product_categories', 'action'=>'index'));?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Brands', array('controller'=>'brands', 'action'=>'index'));?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Invoices', array('controller'=>'invoices', 'action'=>'index'));?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Closing Stock', array('controller'=>'sales', 'action'=>'viewClosingStock'));?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Breakage Stock', array('controller'=>'breakages', 'action'=>'viewBreakageStock'));?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Purchases', array('controller'=>'purchases', 'action'=>'index'));?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Sales', array('controller'=>'sales', 'action'=>'index'));?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Cashbook', array('controller'=>'cashbook', 'action'=>'index'));?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
                        <?php echo $this->Html->link('Dealers', array('controller'=>'dealers', 'action'=>'index'));?>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <?php echo $this->Html->link('Reports', array('controller'=>'reports', 'action'=>'home'));?>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="/SimpleAccountingApp-v1.0.0.apk">Download Mobile App</a>

                        <?php // echo $this->Html->link('Counter Balance Sheets', array('controller'=>'CounterBalanceSheets', 'action'=>'index'));?>
                        <?php // echo $this->Html->link('Employees', array('controller'=>'employees', 'action'=>'index'));?>
                        <?php // echo $this->Html->link('Bank Book', array('controller'=>'banks', 'action'=>'index'));?>

					<?php
					}
					?>
				</nav>			
			<?php } ?>
		</header>
        <?php } ?>
		
		<div id="content">
			<?php
			$showSideBar = true;
			$class="contentBar";
			if(isset($hideSideBar) and ($hideSideBar == true)) {
				$showSideBar = false;
				$class="properMargin";
			}
			?>
			<div class="row">
				<?php 
				if($showSideBar) { 
				?>
				<div class="col-xs-3 col-sm-3 col-lg-2">
					<div id="leftSideBar">
						<nav>
							<?php
							// reports menu
							if ($this->fetch('reports_menu')):
								echo $this->fetch('reports_menu');
							endif;
							
							// stock report menu
							if ($this->fetch('stock_reports_menu')):
								echo $this->fetch('stock_reports_menu');
							endif;
							
							// sales report menu
							if ($this->fetch('sales_report_menu')):
								echo $this->fetch('sales_report_menu');
							endif;	
							
							// purchases report menu
							if ($this->fetch('purchases_report_menu')):
								echo $this->fetch('purchases_report_menu');
							endif;	
							
							// invoices report menu
							if ($this->fetch('invoices_report_menu')):
								echo $this->fetch('invoices_report_menu');
							endif;	
							
							// employees report menu
							if ($this->fetch('employees_report_menu')):
								echo $this->fetch('employees_report_menu');
							endif;	
							
							// dealers report menu
							if ($this->fetch('dealers_report_menu')):
								echo $this->fetch('dealers_report_menu');
							endif;
							
							// bank report menu
							if ($this->fetch('bank_menu')):
								echo $this->fetch('bank_menu');
							endif;	
							
							?>					
						</nav>	
						<br>
					</div>
				</div>
				<?php 
				} 
				?> 
				<div <?php if($showSideBar) { ?> class="col-xs-9 col-sm-9 col-lg-10" <?php } ?>>
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->fetch('content'); ?>
				</div>	
			</div>
			<?php
			/*
			?>
			<div class="<?php echo $class;?>">
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
			<?php
			*/
			?>
			<div class="clear"></div>
		</div>
		
<!--        <script src="--><?php //echo $this->Html->url('/js/plugins.js', true);?><!--"></script>-->
<!--        <script src="--><?php //echo $this->Html->url('/js/main.js', true);?><!--"></script>-->
		<script>
			// In your Javascript (external .js resource or <script> tag)
			$(document).ready(function() {
			    if($('.autoSuggest').length) {
                    $('.autoSuggest').select2();
                }

                if($('table.search-table').length) {
                    $('table.search-table').tableSearch({
                        searchText: '',
                        searchPlaceHolder: 'Search...',
                        caseSensitive: false
                    });
                }
   
			});
		</script>
       
		<?php echo $this->element('sql_dump'); ?>
    </body>
</html>