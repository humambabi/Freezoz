<style type='text/css'>
h1 {
	text-align: center;
	color: #757575;
}

#items-container {
	position: relative;
}

.item-container {
	position: absolute;
	border-radius: 5px;
	overflow: hidden;
	display: flex;
	flex-direction: column; /* Elements will have full width, and stacked on top of each others */
	background-color: white;
	transform: translate3d(0, 0, 0);
	box-shadow: 0 2px 8px rgb(0 0 0 / 5%);
	transition: all .3s ease;
}

/* Item, its data not loaded yet */
.item-container[data-loaded='false'] {
	align-items: center;
	justify-content: center;
}
.item-container[data-loaded='false'] i {
	color:#ddd;
}
.item-container[data-loaded='false'] div {
	margin-top: .75rem;
	color:#ccc;
}

/* Item, after its item is loaded */
.item-container[data-loaded='true']:hover {
	cursor: pointer;
	transform: translate3d(0, -4px, 0);
	box-shadow: 0 14px 30px rgb(0 0 0 / 20%);
}
.item-container .media-container {
	position: relative;
	display: flex;
}

.media-hidden { /* ONLY for img & video inside .media-container */
	opacity: 0;
	position: absolute;
	left: 100%;
}

@media (pointer:coarse) and (hover:none) {
	.media-container video { display: none }
	i#itemctl-mute { opacity: 0 }
}

.item-container[data-loaded='true'] .media-container .imgshadow {
	position: absolute;
	background-image: linear-gradient(to top, rgba(0, 0, 0, .75), rgba(0, 0, 0, 0));
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	opacity: 0;
	transition: all .3s ease;
}
.item-container[data-loaded='true']:hover .media-container .imgshadow {
	opacity: 1;
}

.pricetag {
	position: absolute;
	z-index: 1;
	color: #664e00;
	background-color: #f9bf00;
	font-size: .9rem;
	font-weight: bold;
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	transform: rotate(-45deg);
	transform-origin: 0 0 0;
	-ms-transform: rotate(-45deg); /* IE 9 */
	-ms-transform-origin: 0 0 0; /* IE 9 */
	width: 85px;
	height: 20px;
	left: -15px;
	top: 46px;
}

.shadow-ctlbar {
	position: absolute;
	left: 0;
	width: 100%;
	bottom: 0;
	padding: .5rem;
	display: flex;
	flex-direction: row;
	justify-content: space-between;
}
.shadow-ctlbar i {
	cursor: pointer;
	font-size: 1.75rem;
	color: rgba(255, 255, 255, .5);
	transition: all .3s ease;
}
.shadow-ctlbar i:hover {
	color: rgba(255, 255, 255, .75);
}

.infobar-title {
	font-size: 1.1rem;
	line-height: 1.1rem;
	padding: .5rem .5rem 0 .5rem;
	height: calc(1.1rem + .5rem); /* line-height + padding.vert (top only )*/
	font-weight: bold;
	white-space: nowrap;
	width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
}
.infobar-btmsec {
	position: absolute;
	bottom: 0;
	width: 100%;
	display: flex;
	flex-direction: row;
	justify-content: space-between;
}
.btmsec-rating {
	display: flex;
	flex-direction: row;
}
.rating-stars {
	display: flex;
	flex-direction: row;
	align-items: center;
	padding: .1rem .5rem; /* Horizontal padding must be the same as the title's */
}
.rating-stars i {
	font-size: 1rem;
	color: #f9bf00;
}
.rating-ratescount {
	color: #757575;
	display: flex;
	flex-direction: row;
	align-items: center;
	font-size: .75rem;
}
.rating-downsales {
	color: #a1a1a1;
	display: flex;
	flex-direction: row;
	align-items: center;
	font-size: .9rem;
	font-weight: bold;
	margin-left: .5rem;
}
.btmsec-control {
	display: flex;
	flex-direction: row;
	padding: .5rem .5rem .5rem 0; /* Right and bottom padding must be equal to the title's padding */
}

/* Adjust InfoBar's contents size -> indirectly height via js */
@media all and (max-width: 1345px) {
	.infobar-title { font-size: 1rem; line-height: 1rem; padding: .4rem .4rem 0 .4rem; height: calc(1rem + .4rem); font-weight: bold; }
	.rating-stars { padding: .1rem .4rem; }
	.rating-stars i { font-size: .9rem; }
	.rating-ratescount { font-size: .65rem; }
	.rating-downsales { font-size: .8rem; margin-left: .4rem; }
	.btmsec-control { padding: .4rem .4rem .4rem 0; }
	.btmsec-control button { padding: .2rem .5rem; font-size: .8rem; }
	.pricetag { font-size: .8rem; height: 18px; }
	.shadow-ctlbar i { font-size: 1.5rem; }
@media all and (max-width: 1130px) {
	.infobar-title { font-size: .8rem; line-height: .8rem; padding: .3rem .3rem 0 .3rem; height: calc(.8rem + .3rem); font-weight: bold; }
	.rating-stars { padding: .1rem .3rem; } /* Horizontal padding must be the same as the title's */
	.rating-stars i { font-size: .7rem; }
	.rating-ratescount { font-size: .55rem; }
	.rating-downsales { font-size: .7rem; margin-left: .3rem; }
	.btmsec-control { padding: .3rem .3rem .3rem 0; } /* Right and bottom padding must be equal to the title's padding */
	.btmsec-control button { padding: .15rem .4rem; font-size: .7rem; }
	.pricetag { font-size: .7rem; height: 15px; }
	.shadow-ctlbar i { font-size: 1.15rem; }
}
@media all and (max-width: 980px) { /* 963 */
	.infobar-title { font-size: 1rem; line-height: 1rem; padding: .4rem .4rem 0 .4rem; height: calc(1rem + .4rem); font-weight: bold; }
	.rating-stars { padding: .1rem .4rem; } /* Horizontal padding must be the same as the title's */
	.rating-stars i { font-size: .9rem; }
	.rating-ratescount { font-size: .65rem; }
	.rating-downsales { font-size: .8rem; margin-left: .4rem; }
	.btmsec-control { padding: .4rem .4rem .4rem 0; } /* Right and bottom padding must be equal to the title's padding */
	.btmsec-control button { padding: .2rem .5rem; font-size: .8rem; }
	.pricetag { font-size: .8rem; height: 18px; }
	.shadow-ctlbar i { font-size: 1.5rem; }
}
@media all and (max-width: 700px) {
	.infobar-title { font-size: .8rem; line-height: .8rem; padding: .3rem .3rem 0 .3rem; height: calc(.8rem + .3rem); font-weight: bold; }
	.rating-stars { padding: .1rem .3rem; } /* Horizontal padding must be the same as the title's */
	.rating-stars i { font-size: .7rem; }
	.rating-ratescount { font-size: .5rem; }
	.rating-downsales { font-size: .6rem; margin-left: .25rem; }
	.btmsec-control { padding: .3rem .3rem .3rem 0; } /* Right and bottom padding must be equal to the title's padding */
	.btmsec-control button { padding: .15rem .4rem; font-size: .6rem; }
	.pricetag { font-size: .7rem; height: 15px; }
	.shadow-ctlbar i { font-size: 1.15rem; }
}
@media all and (max-width: 550px) { /* 533 */
	.infobar-title { font-size: 1.1rem; line-height: 1.1rem; padding: .5rem .5rem 0 .5rem; height: calc(1.1rem + .5rem); font-weight: bold; }
	.rating-stars { padding: .1rem .5rem; } /* Horizontal padding must be the same as the title's */
	.rating-stars i { font-size: 1rem; }
	.rating-ratescount { font-size: .75rem; }
	.rating-downsales { font-size: .9rem; margin-left: .5rem; }
	.btmsec-control { padding: .5rem .5rem .5rem 0; } /* Right and bottom padding must be equal to the title's padding */
	.btmsec-control button { padding: .35rem .7rem; font-size: .9rem; }
	.pricetag { font-size: .9rem; height: 20px; }
	.shadow-ctlbar i { font-size: 1.75rem; }
}
@media all and (max-width: 407px) { /* 400 */
	.infobar-title { font-size: 1rem; line-height: 1rem; padding: .4rem .4rem 0 .4rem; height: calc(1rem + .4rem); font-weight: bold; }
	.rating-stars { padding: .1rem .4rem; } /* Horizontal padding must be the same as the title's */
	.rating-stars i { font-size: .9rem; }
	.rating-ratescount { font-size: .65rem; }
	.rating-downsales { font-size: .8rem; margin-left: .4rem; }
	.btmsec-control { padding: .4rem .4rem .4rem 0; } /* Right and bottom padding must be equal to the title's padding */
	.btmsec-control button { padding: .2rem .5rem; font-size: .8rem; }
	.pricetag { font-size: .8rem; height: 18px; }
	.shadow-ctlbar i { font-size: 1.5rem; }
}
@media all and (max-width: 360px) { /* 350 */
	.infobar-title { font-size: .8rem; line-height: .8rem; padding: .3rem .3rem 0 .3rem; height: calc(.8rem + .3rem); font-weight: bold; }
	.rating-stars { padding: .1rem .3rem; } /* Horizontal padding must be the same as the title's */
	.rating-stars i { font-size: .7rem; }
	.rating-ratescount { font-size: .55rem; }
	.rating-downsales { font-size: .7rem; margin-left: .3rem; }
	.btmsec-control { padding: .3rem .3rem .3rem 0; } /* Right and bottom padding must be equal to the title's padding */
	.btmsec-control button { padding: .15rem .4rem; font-size: .7rem; }
	.pricetag { font-size: .7rem; height: 15px; }
	.shadow-ctlbar i { font-size: 1.15rem; }
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
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00000000', endColorstr='#00000000',GradientType=1);
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
function getInfobarHeight() {
	// Using window.innerWidth here, to easily synchronize it with css
	var winWidth = window.innerWidth;

	if (winWidth > 1345) return 76;
	if (winWidth > 1130) return 63;
	if (winWidth > 980) return 52; // Two columns (gets bigger) // 963
	if (winWidth > 700) return 63;
	if (winWidth > 550) return 50; // One column // 533
	if (winWidth > 407) return 76; // 400
	if (winWidth > 360) return 63; // 350
	return 52;
}


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
function htmlRating(rating) {
	var i, retHtml = "", tmpRating = rating;

	function measure(mx, cur) {
		if (cur >= mx) return 1;
		if ((cur + 1.0) <= mx) return -1;
		return 0;
	}

	for (i = 5; i > 0; i--) {
		var r = measure(i, tmpRating);

		if (r > 0) {
			retHtml = "<i class='fas fa-star'></i>" + retHtml;
		} else if (r < 0) {
			retHtml = "<i class='far fa-star'></i>" + retHtml;
		} else {
			retHtml = "<i class='fas fa-star-half-alt'></i>" + retHtml;
		}
	}

	return retHtml;
}


///////////////////////////////////////////////////////////////////////////////////////////////////
async function getOneItemData(elItem) {
	return new Promise(resolve => {
		$.ajax({
			url: BASE_URI + "/requests/item_getdata",
			type: 'post',
			headers: {'X-Requested-With': 'XMLHttpRequest'},
			data: {
				rid: elItem.attr('data-internal'),
				dtype: "home"
			},
			datatype: 'json',
			success: function(response) {
				//console.log(response);

				// Always set the item as loaded, even if there was an error!
				elItem.attr('data-loaded', "true");
				
				if (isJson(response)) {
					// We are confident that response is a json object (returned gracefully from server)

					var resUrl = BASE_URI + "/resources/get?dir=" + encodeURIComponent(response.retdata.folder);
					var imgSrc = resUrl + "&typ=1&res=" + encodeURIComponent(response.retdata.thumbnail);
					var vidSrc = resUrl + "&typ=2&res=" + encodeURIComponent(response.retdata.prevvid);
					var itemTitle = response.retdata.title, itemFree = parseInt(response.retdata.price) > 0 ? false : true;

					// Clear the inner html, and create new elements
					elItem.html(
						"<div class='pricetag'>" + (itemFree ? "Free" : "<i class='fas fa-crown'></i>") + "</div>" +
						"<div class='media-container'>" +
						"   <img width='100%' height='auto' alt='" + itemTitle + "' src='" + imgSrc + "'/>" +
						"   <video width='100%' height='auto' playsinline='playsinline' muted='muted' loop='loop' poster='" + imgSrc + "' preload='metadata'>" +
						"      <source src='" + vidSrc + "' type='" + response.retdata.prevvidhtmltagtype + "'>" +
						"      Sorry!, your borowser doesn't support the video tag. Consider upgrading." +
						"   </video>" +
						"   <div class='imgshadow'>" +
						"      <div class='shadow-ctlbar'>" +
						"         <i id='itemctl-mute' title='Mute' class='fas fa-volume-mute'></i>" +
						"         <i id='itemctl-like' title='Add to favorites' class='far fa-heart'></i>" +
						"      </div>" +
						"   </div>" +
						"</div>" +
						"<div class='infobar-title'>" + itemTitle + "</div>" +
						"<div class='infobar-btmsec'>" +
						"   <div class='btmsec-rating'>" +
						"      <div class='rating-stars'>" + htmlRating(parseFloat(response.retdata.rating)) + "</div>" +
						"      <div class='rating-ratescount'>(" + response.retdata.ratescount + ")</div>" +
						"      <div class='rating-downsales'>" + (itemFree ? "Downloads" : "Sales") + ": " + response.retdata.downsalecount + "</div>" +
						"   </div>" +
						"   <div class='btmsec-control'>" +
						"      <button type='button' class='solid green medium'>DETAILS</button>" +
						"   </div>" +
						"</div>"
					);

					elItem.mouseenter(function() {
						var elImg = elItem.find(".media-container img"), elVid = elItem.find(".media-container video");
						
						if (elVid.css("display") == "none") {
							elImg.removeClass("media-hidden");
							elVid.addClass("media-hidden");
						} else {
							elImg.addClass("media-hidden");
							elVid.removeClass("media-hidden");
							elVid[0].play().then(function() { elVid.attr("playing", true); });
						}
					});
					elItem.mouseleave(function() {
						var elImg = elItem.find(".media-container img"), elVid = elItem.find(".media-container video");

						elImg.removeClass("media-hidden");
						elVid.addClass("media-hidden");

						if (elVid.css("display") != "none") {
							if (elVid.attr("playing")) {
								elVid[0].pause();
								elVid.attr("playing", false);
							}
						}
					});
					elItem.find("i#itemctl-mute").click(function() {
						var elVid = elItem.find(".media-container video");

						if (elVid[0].muted) {
							elVid[0].muted = false;
							elVid[0].volume = 0.1;
							elItem.find("i#itemctl-mute")[0].className = "fas fa-volume-up";
						} else {
							elVid[0].muted = true;
							elItem.find("i#itemctl-mute")[0].className = "fas fa-volume-mute";
						}
					});
				}

				/*setTimeout(function() {*/ resolve(); /*}, 250);*/ // Allow the Promise object to allow next object to execute
			} // Received response
		}); // JQ.Ajax()
	}); // Promise
}


///////////////////////////////////////////////////////////////////////////////////////////////////
async function loadItems() {
	// This routine will be called always, so it must check for newly added items only and load their data
	// So, it works for both cases: When the current page is just refreshed (all items are new), and when
	// the client's window was resized (in one of three cases, a new items are being created and need to load data)

	for (var i = 0; i < $("div.item-container").length; i++) {
		var elItem =  $("div.item-container[data-index='" + i + "']");

		if (elItem.attr('data-loaded') == "true") continue;
		await getOneItemData(elItem);
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function setThisPageItems(create) {
	var elCont = $('#items-container'), thisPageItemList;
	var widthContainer = elCont[0].getBoundingClientRect().width;
	var itemWidth, imgHeight, infobarHeight, itemHeight, itemMargin, colCount, pageCount;


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
	imgHeight = Math.floor(itemWidth * ITEM_RATIO);
	itemWidth = Math.floor(itemWidth);
	if (itemWidth > widthContainer) {
		itemWidth = widthContainer;
		imgHeight = Math.floor(itemWidth * ITEM_RATIO); // Again
	}
	infobarHeight = getInfobarHeight();
	itemHeight = imgHeight + infobarHeight;

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

	// Create a local function for creating new items
	function createOneItem(idx) {
		var thisItemPos = itemPos(thisPageItemList, idx, itemWidth, itemHeight, itemMargin, widthContainer, colCount), itemHTML = "";

		itemHTML += "<div class='item-container' data-index='" + idx + "' data-internal='" + thisPageItemList[idx].rowid + "' data-loaded='false'>";
		itemHTML += "   <i class='fas fa-3x fa-fan fa-spin'></i><div>Loading...</div>";
		itemHTML += "</div>";

		$(itemHTML).css({
			"width": itemWidth + "px",
			"height": itemHeight + "px",
			"left": thisItemPos.x + "px",
			"top": thisItemPos.y + "px"
		}).appendTo(elCont);
	}

	// Decide whether to create new items, modify existing, delete the additionals...etc
	if (create) {
		// Create items
		for (var i = 0; i < thisPageItemList.length; i++) {
			createOneItem(i);
		}
	} else {
		// Set items, and delete the additionals (if any), and create new ones (if needed)
		var largestCount = thisPageItemList.length > $("div.item-container").length ? thisPageItemList.length : $("div.item-container").length;
		for (var i = 0; i < largestCount; i++) {
			var elItem = $("div.item-container[data-index='" + i + "']");
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
				createOneItem(i);
			}
		}
	}

	// Show/hide the pagination and set its distant from the most bottom item
	setPagination(gPageCur, pageCount);

	// Get item data (checks for new items only: means it works for both a new page load, and a resize with creating a new items)
	loadItems();
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