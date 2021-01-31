<style type='text/css'>
h1 {
	text-align: center;
	color: #757575;
}

#items-container {
	display: flex;
	flex-direction: row;
	position: relative;
}

.items-column {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	position: relative;
}
.items-column:nth-child(1) { align-items: flex-start; }
.items-column:nth-child(2) { align-items: center; }
.items-column:nth-child(3) { align-items: flex-end; }

.item-container {
	max-width: 590px;
	max-height: 332px;
	box-shadow: 0 10px 15px -3px rgba(0,0,0,0.07);
	border-radius: 5px;
	background-color: #fff;
	transition: all .5s ease;
	overflow: hidden;
	cursor: pointer;

	display: flex;
	justify-content: center;
	align-items: center;
	font-size: 5rem;
	color: #ccc;
}
.item-container img {
	width: 100%;
	height: 100%;
	transition: all .333s ease;
}

.item-container:hover {
	color: #000;
	box-shadow: 0 10px 15px -3px rgba(0,0,0,0.25), 0 4px 6px -2px rgba(0,0,0,0.05), 0 0 5px rgba(0,0,0,0.1);
}

.item-container:hover img {
	opacity: .5;
}


.item-container { margin-bottom: 15px; }
.items-column:nth-child(2) { margin: 0 15px; }
@media all and (max-width: 1280px) {
	.item-container { margin-bottom: 10px; }
	.items-column:nth-child(2) { margin: 0 10px; }
}
@media all and (max-width: 1024px) {
	.item-container { margin-bottom: 5px; }
	.items-column:nth-child(2) { margin: 0 5px; }
}
@media all and (max-width: 800px) {
	.item-container { margin-bottom: 10px; }
	.items-column:nth-child(2) { margin: 0 0 0 10px; }
}


#pagination {
	display: flex;
	align-items: center;
	justify-content: center;
}

#pg-wrapper {
	display: flex;
	flex-direction: column;
	background: -webkit-linear-gradient(left, rgba(255, 255, 255, 0) 0%, white 17%, white 83%, rgba(255, 255, 255, 0) 100%);
	background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, white 17%, white 83%, rgba(255, 255, 255, 0) 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00ffffff', endColorstr='#00ffffff', GradientType=1);

}

#pg-wrapper:before, #pg-wrapper:after {
	background: -webkit-linear-gradient(left, transparent 0%, rgba(0, 0, 0, 0.1) 17%, rgba(0, 0, 0, 0.1) 83%, transparent 100%);
	background: linear-gradient(to right, transparent 0%, rgba(0, 0, 0, 0.1) 17%, rgba(0, 0, 0, 0.1) 83%, transparent 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#00000000',GradientType=1 );
	content: "";
	height: 1px;
	width: 100%;
}

#pg-inner {
	display: flex;
	flex-direction: row;
	padding: 0 5rem;
}

.pg-btn {
	width: 2rem;
	height: 2rem;
	line-height: 2rem;
	display: flex;
	align-items: center;
	justify-content: center;
	color: rgba(0, 0, 0, .55);
	margin: .5rem;
	border-radius: 50%;
}
.pg-btn:not(.inactive) {
	cursor: pointer;
	-webkit-transition: all 170ms linear;
	transition: all 170ms linear;
}
.pg-btn.current {
	color: black;
	background-color: rgba(0, 0, 0, .2);
}
.pg-btn.inactive {
	cursor: default;
}
.pg-btn:not(.inactive):hover {
	color: black;
	background-color: rgba(0, 0, 0, .1);
}

.ellipsis {
	padding-bottom: .5rem;
	letter-spacing: .15rem;
}

</style>



<div class="main-container" style="position: relative;">
	<?php insert_hspace("4.3rem"); ?>

	<?php insert_hspace("3rem"); ?>
	<h1><i>Enjoy free templates!</i></h1>
	<?php insert_hspace("5rem"); ?>
	
	<div id="items-container">
		<div class="items-column"></div>
		<div class="items-column"></div>
		<div class="items-column"></div>
	</div>

	<div id="pagination"></div>
	<?php insert_hspace("7rem"); ?>

	<!-- Put the body-overlay at the end of the contents -->
	<div class="overlay hidden" id="overlay-body"></div>
</div>


<script type="text/javascript">
const SS_PAGENUM = "ss_pgnum";
var gColCount, gMaxItemCountPerCol, gColNth, gItemsList, gPageCount, gPageCur;

///////////////////////////////////////////////////////////////////////////////////////////////////
function setColCount() {
	// Get thresholds from css styles
	if (window.innerWidth >= 800) {
		gColCount = 3;
		gColNth = 2;
		$('.items-column:nth-child(2)').css('display', "flex");
		$('.items-column:nth-child(3)').css('display', "flex");
		$('.items-column').css('max-width', "33%");
	} else if (window.innerWidth >= 480) {
		gColCount = 2;
		gColNth = 1;
		$('.items-column:nth-child(2)').css('display', "flex");
		$('.items-column:nth-child(3)').css('display', "none");
		$('.items-column').css('max-width', "50%");
	} else {
		gColCount = 1;
		gColNth = 1;
		$('.items-column:nth-child(2)').css('display', "none");
		$('.items-column:nth-child(3)').css('display', "none");
		$('.items-column').css('max-width', "100%");
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function adjustColTopOffset() {
	var top = window.innerWidth / 13;
	if (top > 123) top = 123;
	if (gColCount < 3) top = 0; // gColCount MUST have been already updated
	$('.items-column:nth-child(1), .items-column:nth-child(3)').css('top', parseInt(top) + 'px');
}
function setPagination(curPage) {
	const MAX_SHOWN_PAGEBTNS = 3; // MUST BE AN ODD NUMBER!
	const MAXWIDTH_COMPACT = 600; // px

	//console.log("PageCount: " + gPageCount);
	//console.log("CurPage: " + curPage);

	if (gPageCount < 2) {
		$("#pagination").css('display', "none");
		return;
	}

	var i, html = "", shownWing, shownPages = [], showPrevBtn, showFirst, showPrevEll, showNextBtn, showLast, showNextEll;

	$("#pagination").css('display', "flex");
	
	curPage = parseInt(curPage); // IMPORTANT!
	shownWing = (MAX_SHOWN_PAGEBTNS - 1) / 2;
	for (i = (curPage - shownWing); i < curPage; i++) {
		if (i >= 1) shownPages.push(i);
	}
	if (curPage >= 1 && curPage <= gPageCount) shownPages.push(curPage);
	shownWing = MAX_SHOWN_PAGEBTNS - shownPages.length;
	//console.log("right wing: " + shownWing);
	for (i = curPage + 1; i < (curPage + shownWing + 1); i++) {
		//console.log("*** i: " + i);
		if (i >= 1 && i <= gPageCount) shownPages.push(i);
	}

	//console.log(shownPages);

	showPrevBtn = curPage > 1 ? true : false;
	showFirst = shownPages[0] > 1 ? true : false;
	showPrevEll = (showFirst && (shownPages[0] > 2)) ? true : false;
	showNextBtn = curPage < gPageCount ? true : false;
	showLast = shownPages[shownPages.length - 1] < gPageCount ? true : false;
	showNextEll = (showLast && (shownPages[shownPages.length - 1] < (gPageCount - 1))) ? true : false;
	
	html += 		"<div id='pg-wrapper'>";
	html += 		"   <div id='pg-inner'>";

	if (showPrevBtn) {
		html +=	"      <div class='pg-btn' title='Previous Page' onclick='gotoPage(" + parseInt(curPage - 1) + ")'>&#10094;</div>";
	}
	if (showFirst && (window.innerWidth >= MAXWIDTH_COMPACT)) {
		html +=	"      <div class='pg-btn' title='Page 1' onclick='gotoPage(1)'>1</div>";
	}
	if (showPrevEll && (window.innerWidth >= MAXWIDTH_COMPACT)) {
		html +=	"      <div class='pg-btn inactive ellipsis'>...</div>";
	}

	for (i = 0; i < shownPages.length; i++) {
		if ((window.innerWidth < MAXWIDTH_COMPACT) && (shownPages[i] != curPage)) continue;
		html +=	"      <div class='pg-btn" + (shownPages[i] == curPage ? " current" : "") + "' title='Page " + shownPages[i] + "' onclick='gotoPage(" + shownPages[i] + ")'>" + shownPages[i] + "</div>";
	}

	if (showNextEll && (window.innerWidth >= MAXWIDTH_COMPACT)) {
		html +=	"      <div class='pg-btn inactive ellipsis'>...</div>";
	}
	if (showLast && (window.innerWidth >= MAXWIDTH_COMPACT)) {
		html +=	"      <div class='pg-btn' title='Page " + gPageCount + "' onclick='gotoPage(" + gPageCount + ")'>" + gPageCount + "</div>";
	}
	if (showNextBtn) {
		html +=	"      <div class='pg-btn' title='Next Page' onclick='gotoPage(" + parseInt(curPage + 1) + ")'>&#10095;</div>";
	}

	html +=		"   </div>";
	html +=		"</div>";

	$("#pagination").html(html);
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function gotoPage(page) {
	if (page < 1) page = 1;
	if (page > gPageCount) page = gPageCount;

	sessionStorage.setItem(SS_PAGENUM, page);
	window.location.reload();
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function showSequenceActualItem(itemIdx) {
	var itemEl = $('div.item-container[data-idx=' + itemIdx + ']');

	if (itemEl.length < 1) return; // Reached the end of the page's items

	$.ajax({
		url: BASE_URI + "/requests/item_getdata",
		type: 'post',
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		data: {
			rowid: itemEl.attr('data-rid'),
			dtype: "home"
		},
		datatype: 'json',
		success: function(response) {
			if (!isJson(response) || (isJson(response) && (response.retcode != STATUS_SUCCESS))) {
				console.info(response);

				// Leave this item loading
				// Or show an error sign?

				// Continue to the next item sequence
			}

			// We are confident that response is a json object (returned gracefully from server), and there is a data
			//console.log(response);


			// Show item's data
			itemEl.html(response.retdata.imgelThumbnail);

			// Call self with next idx
			setTimeout(function() { showSequenceActualItem(itemIdx + 1); }, 100);
		} // Received response
	}); // JQ.Ajax()
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function showAllPlaceholders(maxperpage) {
	var startIdx = maxperpage * (gPageCur - 1);
	var endIdx = startIdx + maxperpage; // Not included, no problem if exceeds the array max idx
	var thisPageItems = gItemsList.slice(startIdx, endIdx);
	var i;


	for (i = 0; i < thisPageItems.length; i++) {
		$('.items-column:nth-child(' + gColNth + ')').append(
			"<div class='item-container' data-idx='" + i + "' data-rid='" + thisPageItems[i] + "'>" +
			'   <img alt="Loading..." src="' + BASE_URI + '/img/item_empty.png' + '" />' +
			'<div>'
		);

		if (gColCount == 3) {
			// Begins with nth=2
			gColNth--;
			if (gColNth < 1) gColNth = 3;
		} else if (gColCount == 2) {
			// Begins with nth=1
			gColNth++;
			if (gColNth > 2) gColNth = 1;
		}
		// Else: also begins with nth=1 => NO CHANGE
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function resetItemView(pageCur) {
	var maxperpage, lastcol;

	$('#items-container').html("<div class='items-column'></div><div class='items-column'></div><div class='items-column'></div>");
	setColCount();
	adjustColTopOffset();

	maxperpage = gMaxItemCountPerCol * gColCount;
	if (gItemsList.length <= maxperpage) gPageCount = 1; else gPageCount = Math.ceil(gItemsList.length / maxperpage);
	gPageCur = pageCur;
	if (gPageCur > gPageCount) gPageCur = gPageCount;

	// Show the item placeholder
	showAllPlaceholders(maxperpage);

	// From main.js (page contents are filled after page is already loaded!)
	$("footer").css("position", "relative"); 
	setTimeout(function() { adjust_footerPos(); }, 250);

	// Show/hide the pagination and set its distant from the most bottom item
	setPagination(gPageCur);

	lastcol = gColNth + 1;
	if (lastcol > 3) lastcol = 1;
	//console.log(lastcol);
	if ((gColCount == 3) && (lastcol == 1 || lastcol == 3)) {
		$('#items-container').css('margin-bottom', 85 + parseInt($('.items-column:nth-child(1)').css('top')) + 'px');
	} else {
		$('#items-container').css('margin-bottom', 99 + 'px');
	}

	// Set the show item function to be executed later
	
		showSequenceActualItem(0);
		

		
	
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function itemsGetList() {
	$.ajax({
		url: BASE_URI + "/requests/items_getlist",
		type: 'post',
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		data: {
			find_intitle: "Humam"
		},
		datatype: 'json',
		success: function(response) {
			if (!isJson(response) || (isJson(response) && (response.retcode != STATUS_SUCCESS || response.retdata.itemlist.length < 1))) {
				//console.info(response);

				// Show that there are no more items!
				$('#items-container').html(
					"<div style='display:flex;flex-direction:column;flex-grow:1;text-align:center;align-items:center;'>" +
					"   Sorry! there are no more items to show!" +
					"   <div style='margin-top:.5rem;color:#01baef;font-size:1.2rem;'><i class='far fa-sad-tear'></i></div>" +
					"</div>"
				);

				// Hide the pagination
				gPageCount = 0;
				setPagination(0);
				return;
			}

			// We are confident that response is a json object (returned gracefully from server), and there is at least one item
			//console.log(response);

			gMaxItemCountPerCol = response.retdata.maxitemcountpercol;
			gItemsList = response.retdata.itemlist;
			gPageCur = sessionStorage.getItem(SS_PAGENUM) || 1; // First page if not saved yet

			resetItemView(gPageCur);
		} // Received response
	}); // JQ.Ajax()
}









function showItem(idx, actual) {
	if (actual) {
		$('.items-column:nth-child(' + gColNth + ') div.item-container[data-itemid=' + idx + ']').html(
			'<img title="' + idx + '" alt="' + Math.floor(Math.random() * 100) + '" src="' + gRetObj[idx].url + '" />'
		);
	} else {
		$('.items-column:nth-child(' + gColNth + ')').append(
			'<div class="item-container" data-itemid="' + idx + '">' +
			'   <img alt="Loading..." src="' + BASE_URI + '/img/item_empty.png' + '" />' +
			'<div>'
		);
	}

	if (gColCount == 3) {
		// Begins with nth=2
		gColNth--;
		if (gColNth < 1) gColNth = 3;
	} else if (gColCount == 2) {
		// Begins with nth=1
		gColNth++;
		if (gColNth > 2) gColNth = 1;
	}
	// Else: also begins with nth=1 => NO CHANGE
}
function getItemsInfo() {
	gRetObj = []; // Reset the array
	for (var i = 0; i < ASSUMED_ITEM_COUNT; i++) {
		var item = {
			url: 'https://picsum.photos/seed/' + Math.floor(Math.random() * 1000) + '/590/332'
		};
		gRetObj.push(item);
	}

	// Show "empty" image placeholders
	for (i = 0; i < gRetObj.length; i++) {
		showItem(i, false);
	};

	// Create and return a deferred jQuery obj
	var defObj = $.Deferred();
	return defObj.promise();
}
function getItemDataErr() {
	console.log('getItemDataErr!');
};
function doShowItemsSequentially(defObj, idx) {
	if (idx >= gRetObj.length) return; // Stop the chain
	if (idx == 0) {
		// Clear items
//		for (var i = 1; i <= 3; i++) {
//			$('.items-column:nth-child(' + i + ')').html("");
//		}

		// Set the 1st column to be filled
		if (gColCount == 3) gColNth = 2; else gColNth = 1;
	}

	var nextObj = defObj.then(showItem(idx, true), getItemDataErr);
	doShowItemsSequentially(nextObj, idx + 1);
}














// General Section
itemsGetList();


//var defObj = getItemsInfo();
$(window).on('resize', function() {
	// We should clear columns, re-display items again (it only occurrs on changing)
//	var oldColCount = gColCount;

//	setColCount(); // Updates gColCount
//	adjustColTopOffset();
//	if (oldColCount != gColCount) doShowItemsSequentially(defObj, 0);

	resetItemView(1);
});
//setTimeout(function() { doShowItemsSequentially(defObj, 0); }, 1000);

</script>