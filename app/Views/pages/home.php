<style type='text/css'>
h1 {
	text-align: center;
	color: #757575;
}

#items-container {
	/*display: flex;
	flex-direction: row;*/
	position: relative;
}

.item-container {
	position: absolute;
	transition: all .250s ease;


	border: 1px solid blue;
}



/*
.items-column {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	position: relative;
}

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
*/


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
	
	<div id="items-container"></div>

	<?php insert_hspace("3rem"); ?>
	<div id="pagination"></div>
	<?php insert_hspace("7rem"); ?>

	<!-- Put the body-overlay at the end of the contents -->
	<div class="overlay hidden" id="overlay-body"></div>
</div>


<script type="text/javascript">
const SS_PAGENUM = "ss_pgnum";
const ITEM_RATIO = .56271;
var gItemsList, gMaxItemCountPerCol, gPageCur;


///////////////////////////////////////////////////////////////////////////////////////////////////
function itemPos(thisPageItemList, itemIdx, itemWidth, itemHeight, itemMargin, contWidth, colCount) {
	if (thisPageItemList.length == 1) {
		// Always centered, whether there are 1, 2, or 3 columns
		return {
			x: Math.floor((contWidth / 2) - (itemWidth / 2)),
			y: 0
		};
	} else {
		// more than 1 item
		if (colCount == 1) {
			return {
				x: Math.floor((contWidth / 2) - (itemWidth / 2)),
				y: ((itemHeight + itemMargin) * itemIdx)
			};
		} else if (colCount == 2) {
			var rowIdx = Math.floor(itemIdx / 2);
			return {
				x: (itemIdx % 2) ? (contWidth - itemWidth) : 0,
				y: ((itemHeight + itemMargin) * rowIdx)
			};
		} else if (colCount == 3) {
			var rowIdx = Math.floor(itemIdx / 3);
			var colIdx = 2 - ((itemIdx + 1) % 3);
			var plusHeight = ((colIdx == 0) || (colIdx == 2)) ? Math.ceil(itemHeight / 2) : 0;
			return {
				x: (colIdx == 0) ? 0 : ((colIdx == 1) ? Math.floor((contWidth / 2) - (itemWidth / 2)) : (contWidth - itemWidth)),
				y: plusHeight +  ((itemHeight + itemMargin) * rowIdx)
			};
		}
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function setThisPageItems(create) {
	var elCont = $('#items-container'), thisPageItemList;
	var widthContainer = elCont[0].getBoundingClientRect().width;
	var itemWidth, itemHeight, itemMargin, colCount, pageCount;


	// Config column & margins
	if (widthContainer >= 800) {
		colCount = 3;
	} else if (widthContainer >= 480) {
		colCount = 2;
	} else {
		colCount = 1;
	}

	if (widthContainer >= 1280) {
		itemMargin = 15;
	} else if (widthContainer >= 1024) {
		itemMargin = 10;
	} else if (widthContainer >= 800) {
		itemMargin = 5;
	} else {
		itemMargin = 10;
	}

	// Get this page's item list
	var MaxPerPage, StartIdx, EndIdx;

	if (gItemsList.length < 1) {
		pageCount = 0;
		MaxPerPage = 0; StartIdx = 0; EndIdx = 0;
		thisPageItemList = [];
	} else {
		MaxPerPage = gMaxItemCountPerCol * colCount;
		if (gItemsList.length <= MaxPerPage) pageCount = 1; else pageCount = Math.ceil(gItemsList.length / MaxPerPage);
		if (gPageCur > pageCount) gPageCur = pageCount;
		StartIdx = MaxPerPage * (gPageCur - 1);
		EndIdx = StartIdx + MaxPerPage; // Not included, no problem if exceeds the array max idx
		thisPageItemList = gItemsList.slice(StartIdx, EndIdx);
	}

	// Calculate needed variables
	itemWidth = (widthContainer - ((colCount - 1) * itemMargin))  / colCount;
	itemHeight = Math.floor(itemWidth * ITEM_RATIO);
	itemWidth = Math.floor(itemWidth);
	if (itemWidth > widthContainer) {
		itemWidth = widthContainer;
		itemHeight = Math.floor(itemWidth * ITEM_RATIO); // Again
	}

	// Adjust the container's height
	var rowCount = Math.ceil(thisPageItemList.length / colCount), plusHeight = 0;
	
	if ((colCount == 3) && (gItemsList.length > 0)) {
		var lastColIdx = 2 - (((thisPageItemList.length - 1) + 1) % 3);

		if (lastColIdx == 0 || lastColIdx == 2) plusHeight = Math.ceil(itemHeight / 2);
	}
	if (gItemsList.length > 0) {
		$('#items-container').css('height', ((itemHeight * rowCount) + (itemMargin * (rowCount - 1)) + plusHeight) + "px");
	} else {
		$('#items-container').css('height', "0");
	}

	adjust_footerPos(); // From main.js

	if (create) {
		// Create items
		for (var i = 0; i < thisPageItemList.length; i++) {
			var thisItemPos = itemPos(thisPageItemList, i, itemWidth, itemHeight, itemMargin, widthContainer, colCount);

			$("<div class='item-container' data-itemidx='" + i + "'></div>").css({
				"width": itemWidth + "px",
				"height": itemHeight + "px",
				"left": thisItemPos.x + "px",
				"top": thisItemPos.y + "px"
			}).appendTo(elCont);
		}
	} else {
		// Set items, and delete the additionals (if any), and create new ones (if needed)
		var largestCount = thisPageItemList.length > $("div.item-container").length ? thisPageItemList.length : $("div.item-container").length;
		for (var i = 0; i < largestCount; i++) {
			var elItem = $("div.item-container[data-itemidx='" + i + "']");
			var thisItemPos = itemPos(thisPageItemList, i, itemWidth, itemHeight, itemMargin, widthContainer, colCount);

			if (elItem.length) {
				if (i > (thisPageItemList.length - 1)) {
					// Delete
					elItem.remove();
				} else {
					// Set
					elItem.css({
						"width": itemWidth + "px",
						"height": itemHeight + "px",
						"left": thisItemPos.x + "px",
						"top": thisItemPos.y + "px"
					});
				}
			} else {
				// Create
				$("<div class='item-container' data-itemidx='" + i + "'></div>").css({
					"width": itemWidth + "px",
					"height": itemHeight + "px",
					"left": thisItemPos.x + "px",
					"top": thisItemPos.y + "px"
				}).appendTo(elCont);
			}
		}
	}

	// Show/hide the pagination and set its distant from the most bottom item
	setPagination(gPageCur, pageCount);
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function setPagination(curPage, allPageCount) {
	const MAX_SHOWN_PAGEBTNS = 3; // MUST BE AN ODD NUMBER!
	const MAXWIDTH_COMPACT = 600; // px

	//console.log("PageCount: " + allPageCount);
	//console.log("CurPage: " + curPage);

	if (allPageCount < 2) {
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
	if (curPage >= 1 && curPage <= allPageCount) shownPages.push(curPage);
	shownWing = MAX_SHOWN_PAGEBTNS - shownPages.length;
	//console.log("right wing: " + shownWing);
	for (i = curPage + 1; i < (curPage + shownWing + 1); i++) {
		//console.log("*** i: " + i);
		if (i >= 1 && i <= allPageCount) shownPages.push(i);
	}

	//console.log(shownPages);

	showPrevBtn = curPage > 1 ? true : false;
	showFirst = shownPages[0] > 1 ? true : false;
	showPrevEll = (showFirst && (shownPages[0] > 2)) ? true : false;
	showNextBtn = curPage < allPageCount ? true : false;
	showLast = shownPages[shownPages.length - 1] < allPageCount ? true : false;
	showNextEll = (showLast && (shownPages[shownPages.length - 1] < (allPageCount - 1))) ? true : false;
	
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
		html +=	"      <div class='pg-btn' title='Page " + allPageCount + "' onclick='gotoPage(" + allPageCount + ")'>" + allPageCount + "</div>";
	}
	if (showNextBtn) {
		html +=	"      <div class='pg-btn' title='Next Page' onclick='gotoPage(" + parseInt(curPage + 1) + ")'>&#10095;</div>";
	}

	html +=		"   </div>";
	html +=		"</div>";

	$("#pagination").html(html);
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function gotoPage(page, allPageCount) {
	if (page < 1) page = 1;
	if (page > allPageCount) page = allPageCount;

	sessionStorage.setItem(SS_PAGENUM, page);
	window.location.reload();
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
					"   Sorry! there is no item to show!" +
					"   <div style='margin-top:.5rem;color:#01baef;font-size:1.2rem;'><i class='far fa-sad-tear'></i></div>" +
					"</div>"
				);

				gMaxItemCountPerCol = 0;
				gItemsList = [];
				gPageCur = 0;

				// Hide the pagination
				setPagination(0, 0);
				return;
			}

			// We are confident that response is a json object (returned gracefully from server), and there is at least one item
			//console.log(response);

			gMaxItemCountPerCol = response.retdata.maxitemcountpercol;
			gItemsList = response.retdata.itemlist;
			gPageCur = sessionStorage.getItem(SS_PAGENUM) || 1; // First page if not saved yet

			setThisPageItems(true);
		} // Received response
	}); // JQ.Ajax()
}



// General Section ////////////////////////////////////////////////////////////////////////////////
itemsGetList(); // Includes a call to setThisPageItems()

$(window).on('resize', function() {
	setThisPageItems(false);
});
</script>