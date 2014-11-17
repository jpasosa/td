<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- SCRIPTS FOOTER -->

<?php if(isset($dashboard)): ?>
	<!-- smart resize event -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.debouncedresize.min.js"></script>
	<!-- hidden elements width/height -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.actual.min.js"></script>
	<!-- js cookie plugin -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.cookie.min.js"></script>
	<!-- main bootstrap js -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/bootstrap.min.js"></script> -->
	<!-- tooltips -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/qtip2/jquery.qtip.min.js"></script>
	<!-- jBreadcrumbs -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
	<!-- lightbox -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/colorbox/jquery.colorbox.min.js"></script>
	<!-- fix for ios orientation change -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/ios-orientationchange-fix.js"></script>
	<!-- scrollbar -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/antiscroll/antiscroll.js"></script>
	<script src="<?php echo ADMIN_FOLDER;?>/lib/antiscroll/jquery-mousewheel.js"></script>
	<!-- to top -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/UItoTop/jquery.ui.totop.min.js"></script>
	<!-- common functions -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/gebo_common.js"></script>
	<!-- touch events for jquery ui-->
	<script src="<?php echo ADMIN_FOLDER;?>/js/forms/jquery.ui.touch-punch.min.js"></script>
	<!-- multi-column layout -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.imagesloaded.min.js"></script>
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.wookmark.js"></script>
	<!-- responsive table -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.mediaTable.min.js"></script>
	<!-- small charts -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.peity.min.js"></script>
	<!-- charts -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/flot/jquery.flot.min.js"></script>
	<script src="<?php echo ADMIN_FOLDER;?>/lib/flot/jquery.flot.resize.min.js"></script>
	<script src="<?php echo ADMIN_FOLDER;?>/lib/flot/jquery.flot.pie.min.js"></script>
	<!-- calendar -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/fullcalendar/fullcalendar.min.js"></script>
	<!-- sortable/filterable list -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/list_js/list.min.js"></script>
	<script src="<?php echo ADMIN_FOLDER;?>/lib/list_js/plugins/paging/list.paging.min.js"></script>
	<!-- dashboard functions -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/gebo_dashboard.js"></script>
<?php endif;?>

<?php if(isset($datatable)): ?>
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.min.js"></script>
	<!-- smart resize event -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.debouncedresize.min.js"></script>
	<!-- hidden elements width/height -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.actual.min.js"></script>
	<!-- js cookie plugin -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/jquery.cookie.min.js"></script>
	<!-- main bootstrap js -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/bootstrap.min.js"></script>
	<!-- bootstrap plugins -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/bootstrap.plugins.min.js"></script>
	<script src="<?php echo ADMIN_FOLDER;?>lib/datepicker/bootstrap-datepicker.js"></script>

	<!-- tooltips -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/qtip2/jquery.qtip.min.js"></script>
	<!-- jBreadcrumbs -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
	<!-- sticky messages -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/sticky/sticky.min.js"></script>
	<!-- fix for ios orientation change -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/ios-orientationchange-fix.js"></script>
	<!-- scrollbar -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/antiscroll/antiscroll.js"></script>
	<script src="<?php echo ADMIN_FOLDER;?>/lib/antiscroll/jquery-mousewheel.js"></script>
	<!-- common functions -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/gebo_common.js"></script>
	<!-- colorbox -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/colorbox/jquery.colorbox.min.js"></script>
	<!-- datatable -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/datatables/jquery.dataTables.min.js"></script>
	<!-- additional sorting for datatables -->
	<script src="<?php echo ADMIN_FOLDER;?>/lib/datatables/jquery.dataTables.sorting.js"></script>
	<!-- tables functions -->
	<script src="<?php echo ADMIN_FOLDER;?>/js/gebo_tables.js"></script>
<?php endif;?>

<script src="<?php echo ADMIN_FOLDER;?>lib/jquery-ui/jquery-ui-1.8.20.custom.min.js"></script>
<!-- touch events for jquery ui-->
<script src="<?php echo ADMIN_FOLDER;?>js/forms/jquery.ui.touch-punch.min.js"></script>
<!-- masked inputs -->
<script src="<?php echo ADMIN_FOLDER;?>js/forms/jquery.inputmask.min.js"></script>
<!-- autosize textareas -->
<script src="<?php echo ADMIN_FOLDER;?>js/forms/jquery.autosize.min.js"></script>
<!-- textarea limiter/counter -->
<script src="<?php echo ADMIN_FOLDER;?>js/forms/jquery.counter.min.js"></script>
<!-- datepicker -->
<script src="<?php echo ADMIN_FOLDER;?>lib/datepicker/bootstrap-datepicker.js"></script>
<!-- timepicker -->
<script src="<?php echo ADMIN_FOLDER;?>lib/datepicker/bootstrap-timepicker.min.js"></script>
<!-- tag handler -->
<script src="<?php echo ADMIN_FOLDER;?>lib/tag_handler/jquery.taghandler.min.js"></script>
<!-- input spinners -->
<script src="<?php echo ADMIN_FOLDER;?>js/forms/jquery.spinners.min.js"></script>
<!-- styled form elements -->
<script src="<?php echo ADMIN_FOLDER;?>lib/uniform/jquery.uniform.min.js"></script>
<!-- animated progressbars -->
<script src="<?php echo ADMIN_FOLDER;?>js/forms/jquery.progressbar.anim.js"></script>
<!-- multiselect -->
<script src="<?php echo ADMIN_FOLDER;?>lib/multiselect/js/jquery.multi-select.min.js"></script>
<!-- enhanced select (chosen) -->
<script src="<?php echo ADMIN_FOLDER;?>lib/chosen/chosen.jquery.min.js"></script>


<?php
if(isset($scripts)){
	if(is_array($scripts)){
		foreach($scripts as $script){
			echo $script;
		}
	}else{
		echo $scripts;
	}
}
?>

<script>
	$(document).ready(function() {
		//* show all elements & remove preloader
		setTimeout('$("html").removeClass("js")',1000);
		$(".uni_style").uniform();
	});
</script>

</div>
<footer class="pull-right">
<div class="row">

<br><br>
</div>
</footer>
</body>
</html>
