/* ----------------------------------------------
Items page
---------------------------------------------- */
const ITEM_WIDTH = 100, ITEM_HEIGHT = 150;
var gItemsList;


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
					var imgSrc = resUrl + "&typ=1&res=" + encodeURIComponent(response.retdata.thumbnail), itemTitle = response.retdata.title;

					// Clear the inner html, and create new elements
					elItem.html(
						"<img width='213' height='120' alt='" + itemTitle + "' src='" + imgSrc + "'/>" +
						"<div class='card-body'>" +
						"   <div class='form-check'>" +
						"      <input class='form-check-input' type='checkbox' value='' id='chk-" + elItem.attr('data-internal') + "'>" +
						"      <label class='form-check-label' for='chk-" + elItem.attr('data-internal') + "' title='" + itemTitle + "'>" + itemTitle + "</label>" +
						"   </div>" +
						"</div>"
					);
				}

				resolve(); // Allow the Promise object to allow next object to execute
			} // Received response
		}); // JQ.Ajax()
	}); // Promise
}


///////////////////////////////////////////////////////////////////////////////////////////////////
async function loadItems() {
	for (var i = 0; i < gItemsList.length; i++) {
		var elItem =  $("div.card[data-index='" + i + "']");

		if (elItem.attr('data-loaded') == "true") continue;
		await getOneItemData(elItem);
	}

	// After (creating) items
	$(".card-body input.form-check-input").click(function() {
		if ($(this).is(':checked')) {
			var rowid = $(this).attr("id").substr(4);

			console.log(rowid);

			$(".card-body input.form-check-input").each(function() {
				if (rowid != $(this).attr("id").substr(4)) $(this).prop('checked', false);
			});

			// Enable control buttons
			$('#itemDelete').prop('disabled', false);
			$('#itemEdit').prop('disabled', false);
		} else {
			// Disable control buttons
			$('#itemDelete').prop('disabled', true);
			$('#itemEdit').prop('disabled', true);
		}
	});
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function viewItems() {
	var elCont = $('#itemtable-container');

	// Create a local function for creating new items
	function createOneItem(idx) {
		var itemHTML = "";

		itemHTML += "<div class='card' data-index='" + idx + "' data-internal='" + gItemsList[idx].rowid + "' data-loaded='false'>";
		itemHTML += "   <i class='fas fa-3x fa-fan fa-spin'></i><div>Loading...</div>";
		itemHTML += "</div>";

		$(itemHTML).appendTo(elCont);
	}

	// Create items
	for (var i = 0; i < gItemsList.length; i++) {
		createOneItem(i);
	}

	loadItems();
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function itemsGetList() {
	$.ajax({
		url: BASE_URI + "/requests/items_getlist",
		type: 'post',
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		datatype: 'json',
		success: function(response) {
			if (!isJson(response) || (isJson(response) && (response.retcode != STATUS_SUCCESS || response.retdata.itemlist.length < 1))) {
				console.info(response);

				// Show that there are no more items!
				$('#itemtable-container').addClass("empty");
				$('#itemtable-container').html(
					"<div style='display:flex;flex-direction:column;flex-grow:1;text-align:center;align-items:center;'>" +
					"   Sorry! there is no item to show!" +
					"</div>"
				);

				gItemsList = [];
				return;
			}

			// We are confident that response is a json object (returned gracefully from server), and there is at least one item
			//console.log(response);

			$('#itemtable-container').removeClass("empty");
			$('#itemtable-container').html("");
			gItemsList = response.retdata.itemlist;
			viewItems();
		} // Received response
	}); // JQ.Ajax()
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function readImgURL(input, imgId) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {
			var objImg = new Image();

			$("#" + imgId).attr('src', e.target.result);
			
			objImg.onload = function() {
				//console.log(this.naturalWidth + "x" + this.naturalHeight);
				// Save this value. on hitting "save" button, validate the saved data to be of desired dimentions
				var imgWidth = this.naturalWidth, imgHeight = this.naturalHeight;

				$("#" + imgId)
					.attr('data-width', imgWidth)
					.attr('data-height', imgHeight)
				;
				$("#" + imgId).siblings("div.imgdims")
					.css("display", "block")
					.text(imgWidth + "px x " + imgHeight + "px")
				;
			}
			objImg.src = e.target.result;
		}

		reader.readAsDataURL(input.files[0]); // convert to base64 string
	} else {
		$("#" + imgId)
			.attr('src', BASE_URI + "/img/image-small-none.jpg")
			.attr('data-width', 0)
			.attr('data-height', 0)
		;
		$("#" + imgId).siblings("div.imgdims")
			.css("display", "none")
			.text("")
		;
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function readVidURL(input, vidId) {
	if (input.files && input.files[0]) {
		$("#" + vidId + " source")[0].src = URL.createObjectURL(input.files[0]);

		$("#" + vidId)[0].onloadeddata = function() {
			//console.log(this.videoWidth + "x" + this.videoHeight);
			// Save this value. on hitting "save" button, validate the saved data to be of desired dimentions
			var vidWidth = this.videoWidth, vidHeight = this.videoHeight;

			$("#" + vidId)
				.attr('data-width', vidWidth)
				.attr('data-height', vidHeight)
			;
			$("#" + vidId).siblings("div.viddims")
				.css("display", "block")
				.text(vidWidth + "px x " + vidHeight + "px")
			;
		};

		$("#" + vidId)[0].load();
		$("#" + vidId)[0].play();
	} else {
		$("#" + vidId)[0].pause();
		$("#" + vidId)[0].currentTime = 0;

		// Unload the video (free memory)
		URL.revokeObjectURL($("#" + vidId + " source")[0].src);

		// Remove the src attribute
		$("#" + vidId + " source").removeAttr('src');

		// Reset the load status (to show the video.poster image)
		$("#" + vidId)[0].load();

		$("#" + vidId)
			.attr('data-width', 0)
			.attr('data-height', 0)
		;
		$("#" + vidId).siblings("div.viddims")
			.css("display", "none")
			.text("")
		;
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function getFormattedTodayDate() {
	var today = new Date(), month, day;

	month = (today.getMonth() + 1).toString();
	if (month.length < 2) month = "0" + month;
	day = today.getDate().toString();
	if (day.length < 2) day = "0" + day;

	return today.getFullYear() + "-" + month + "-" + day;
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function tagAdd() {
	var strText = $("#textItemTags").val().trim().toLowerCase();
	var elCurTags = $("#curTags");


	if (strText.length) {
		// Clean the string
		strText = strText.replace(" ", '_'); // Converts spaces to underscores
		strText = strText.replace(/[^a-z0-9._]/ig, '');
		
		// Append the tag element
		elCurTags.append(
			"<span class='badge badge-primary' type='tag'>" +
				strText +
				"<button type='button' class='close' aria-label='Close' onclick='tagRemove($(this))'><span aria-hidden='true'>&times;</span></button>" +
			"</span>"
		);

		// Remove the "none" element (if exists)
		$("span.badge[type=none]").remove();
	}

	// Always
	$("#textItemTags").val("");
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function tagRemove(sender) {
	sender.parent().remove();

	// If there are no more tags
	if (!$("span.badge[type=tag]").length) $("#curTags").append("<span class='badge badge-secondary' type='none'>None</span>");
}


// General Section ////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
	bsCustomFileInput.init();
	$('#textItemDesc').summernote({
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'italic', 'underline', 'clear']],
			['fontname', ['fontname', 'fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'hr']],
			['view', ['fullscreen', 'codeview']],
		],
		placeholder: "Type the item's description (e.g. features, properties, requirements, version, etc.)"
	});
});

// $('#summernote').summernote('isEmpty')

$("#filePrevImgSmall").change(function() { readImgURL(this, 'imgPrevImgSmall'); });
$("#filePrevImgFull").change(function() { readImgURL(this, 'imgPrevImgFull'); });
$("#dateAddition").val(getFormattedTodayDate());
$("#chkToday").click(function() {
	$("#dateAddition").attr('disabled', this.checked);
	if (this.checked) $("#dateAddition").val(getFormattedTodayDate());
});
$("#btnTagAdd").click(function() { tagAdd(); });
$("#filePrevVidSmall").change(function() { readVidURL(this, 'vidPrevVidSmall'); });
$("#filePrevVidFull").change(function() { readVidURL(this, 'vidPrevVidFull'); });

itemsGetList(); // Includes a call to setThisPageItems()


/*
on 'cancel', clear the form's user input
on 'cancel' or 'save', free the videos URL data object
*/