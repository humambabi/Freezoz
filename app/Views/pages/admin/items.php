<style type='text/css'>
.itemtable-container {
	border: 1px solid red;
	width: 100%;
	min-height: 100px;
	max-height: 1000px;
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
}
</style>



<div class="main-container" style="position: relative;">
	<?php insert_hspace("4.3rem"); ?>

	<div id="dom-container">


	</div>

	
	<!--
	<?php //insert_hspace("5rem"); ?>
	<h5>All items in the system:</h5>
	<div class="itemtable-container">

		<div class="card" style="width: 18rem;">
			<img src="..." class="card-img-top" alt="...">
			<div class="card-body">
				<h5 class="card-title">Card title</h5>
				<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
				<a href="#" class="btn btn-primary">Go somewhere</a>
			</div>
		</div>

	</div>

	<button id="remove" class="btn btn-danger" disabled>
		<i class="fa fa-trash"></i> Delete
	</button>
	-->

	<div style="color:rgba(0,0,0,.01);">.</div>
</div>



<script type="text/javascript">
///////////////////////////////////////////////////////////////////////////////////////////////////
function itemsGetList() {
	$.ajax({
		url: BASE_URI + "/requests/items_getlist",
		type: 'post',
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		datatype: 'json',
		success: function(response) {
//			if (!isJson(response) || (isJson(response) && (response.retcode != STATUS_SUCCESS || response.retdata.itemlist.length < 1))) {
				console.info(response);

				// Show that there are no more items!
//				$('#items-container').html(
//					"<div style='display:flex;flex-direction:column;flex-grow:1;text-align:center;align-items:center;'>" +
//					"   Sorry! there is no item to show!" +
//					"   <div style='margin-top:.5rem;color:#01baef;font-size:1.2rem;'><i class='far fa-sad-tear'></i></div>" +
//					"</div>"
//				);

//				gMaxItemCountPerCol = 0;
				gItemsList = [];
				gPageCur = 0;

				// Hide the pagination
//				setPagination(0, 0);
				return;
//			}

			// We are confident that response is a json object (returned gracefully from server), and there is at least one item
			//console.log(response);

//			gMaxItemCountPerCol = response.retdata.maxitemcountpercol;
			gItemsList = response.retdata.itemlist;
//			gPageCur = sessionStorage.getItem(SS_PAGENUM) || 1; // First page if not saved yet //////no, use just global js var

//			setThisPageItems(true);
		} // Received response
	}); // JQ.Ajax()
}


// General Section ////////////////////////////////////////////////////////////////////////////////
itemsGetList(); // Includes a call to setThisPageItems()
</script>