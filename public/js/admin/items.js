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

			//console.log(rowid);

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
				//console.info(response);

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
				"<div class='tagtext'>" + strText + "</div>" +
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
function getTagList() {
	var tList = [];

	$("span.badge[type=tag]").each(function() {
		tList.push($(this).children("div.tagtext").text());
	});

	return tList;
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function tagRemove(sender) {
	sender.parent().remove();

	// If there are no more tags
	if (!$("span.badge[type=tag]").length) $("#curTags").append("<span class='badge badge-secondary' type='none'>None</span>");
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function modalItemAdd_Clear() {
	const INPUT_EMPTY_LABEL = "Choose file";
	
	$("#staticModalItemAdd").html('Add a New Item');
	$("#staticModalItemAdd").siblings('button').removeClass('d-none');
	$("#formItemAdd").removeClass("d-none");
	$("#formItemUpload").addClass("d-none");
	$("#formItemNewFooter").removeClass("d-none");
	
	$("#chkToday").prop('checked', true);
	$("#dateAddition")
		.attr('disabled', true)
		.val(getFormattedTodayDate())
	;

	$("#textItemTitle")
		.val('')
		.removeClass("is-invalid")
	;

	$('#textItemDesc').summernote('reset');

	$("#fileItemFile").val('');
	$("#fileItemFile").siblings("label").text(INPUT_EMPTY_LABEL);

	$("span.badge[type=tag]").remove();
	$("#curTags").html("<small>Current tags:</small>&nbsp;<span class='badge badge-secondary' type='none'>None</span>");

	$("#filePrevImgSmall").val('');
	$("#filePrevImgSmall").siblings("label").text(INPUT_EMPTY_LABEL);
	readImgURL($("#filePrevImgSmall")[0], 'imgPrevImgSmall');

	$("#filePrevImgFull").val('');
	$("#filePrevImgFull").siblings("label").text(INPUT_EMPTY_LABEL);
	readImgURL($("#filePrevImgFull")[0], 'imgPrevImgFull');

	$("#filePrevVidSmall").val('');
	$("#filePrevVidSmall").siblings("label").text(INPUT_EMPTY_LABEL);
	readVidURL($("#filePrevVidSmall")[0], 'vidPrevVidSmall'); // Includes freeing URL data object

	$("#filePrevVidFull").val('');
	$("#filePrevVidFull").siblings("label").text(INPUT_EMPTY_LABEL);
	readVidURL($("#filePrevVidFull")[0], 'vidPrevVidFull'); // Includes freeing URL data object

	$("#fileAddImages").val('');
	$("#fileAddImages").siblings("label").text(INPUT_EMPTY_LABEL);
	
	$("#fileAddVideos").val('');
	$("#fileAddVideos").siblings("label").text(INPUT_EMPTY_LABEL);

	$("#fileAddAudios").val('');
	$("#fileAddAudios").siblings("label").text(INPUT_EMPTY_LABEL);

	$("#textItemPrice").val('');

	$("#selectPrice").val('0');

	$("#selectLicense").val(0)

	// Progress form
	$("#progressItemUpload").attr('aria-valuenow', 0);
	$("#progressItemUpload").css('width', '0');
	$("#labelItemUpload").html("Uploaded: <strong>0%</strong>");
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function modalItemAdd_Validate() {
	var invalid = null;

	// Date (no validation)

	// Title (required)
	if ($("#textItemTitle").val().trim().length < 1) {
		$("#textItemTitle").addClass("is-invalid");
		$("#textItemTitle").on("input", function() { $("#textItemTitle").removeClass("is-invalid"); });
		invalid = "#textItemTitle";
	}

	// Description (required)
	if ($('#textItemDesc').summernote('isEmpty')) {
		$("#desc-invalidlabel").removeClass("d-none"); // set as invalid
		$('#textItemDesc').on("summernote.change", function (e) {
			$("#desc-invalidlabel").addClass("d-none"); // remove invalid flag
		});
		if (!invalid) invalid = ".note-btn";
	}

	// Item's compressed file (required)
	if (!$("#fileItemFile").val()) {
		$("#fileItemFile").addClass("is-invalid");
		$("#fileItemFile").on("input", function() { $("#fileItemFile").removeClass("is-invalid"); });
		if (!invalid) invalid = "#fileItemFile";
	}

	// Tags (at least one)
	if ($("span.badge[type=tag]").length < 1) {
		$("#textItemTags").addClass("is-invalid");
		$("#textItemTags").on("input", function() { $("#textItemTags").removeClass("is-invalid"); });
		$("#btnTagAdd").on("click", function() { $("#textItemTags").removeClass("is-invalid"); });
		if (!invalid) invalid = "#textItemTags";
	}

	// Image-small
	if (!$("#filePrevImgSmall").val() ||
			$("#imgPrevImgSmall").attr("data-width") != ITEMS_IMAGE_SMALL_DIMENSIONS.width ||
			$("#imgPrevImgSmall").attr("data-height") != ITEMS_IMAGE_SMALL_DIMENSIONS.height) {
		$("#filePrevImgSmall").addClass("is-invalid");
		$("#filePrevImgSmall").on("input", function() { $("#filePrevImgSmall").removeClass("is-invalid"); });
		if (!invalid) invalid = "#filePrevImgSmall";
	}

	// Image-full
	if (!$("#filePrevImgFull").val() ||
			$("#imgPrevImgFull").attr("data-width") != ITEMS_IMAGE_FULL_DIMENSIONS.width ||
			$("#imgPrevImgFull").attr("data-height") != ITEMS_IMAGE_FULL_DIMENSIONS.height) {
		$("#filePrevImgFull").addClass("is-invalid");
		$("#filePrevImgFull").on("input", function() { $("#filePrevImgFull").removeClass("is-invalid"); });
		if (!invalid) invalid = "#filePrevImgFull";
	}

	// Video-small
	if (!$("#filePrevVidSmall").val() ||
			$("#vidPrevVidSmall").attr("data-width") != ITEMS_VIDEO_SMALL_DIMENSIONS.width ||
			$("#vidPrevVidSmall").attr("data-height") != ITEMS_VIDEO_SMALL_DIMENSIONS.height) {
		$("#filePrevVidSmall").addClass("is-invalid");
		$("#filePrevVidSmall").on("input", function() { $("#filePrevVidSmall").removeClass("is-invalid"); });
		if (!invalid) invalid = "#filePrevVidSmall";
	}

	// Video-full
	if (!$("#filePrevVidFull").val() ||
			$("#vidPrevVidFull").attr("data-width") != ITEMS_VIDEO_FULL_DIMENSIONS.width ||
			$("#vidPrevVidFull").attr("data-height") != ITEMS_VIDEO_FULL_DIMENSIONS.height) {
		$("#filePrevVidFull").addClass("is-invalid");
		$("#filePrevVidFull").on("input", function() { $("#filePrevVidFull").removeClass("is-invalid"); });
		if (!invalid) invalid = "#filePrevVidFull";
	}

	// Additional images (no validation)
	// Additional videos (no validation)
	// Additional audios (no validation)

	// Price (must be typed, even if '0')
	if ($("#textItemPrice").val().trim().length < 1) {
		$("#textItemPrice").addClass("is-invalid");
		$("#textItemPrice").on("input", function() { $("#textItemPrice").removeClass("is-invalid"); });
		if (!invalid) invalid = "#textItemPrice";
	}

	// License (must be selected, anything except "Select a license...")
	if ($("#selectLicense option:selected").val() < 1) {
		$("#selectLicense").addClass("is-invalid");
		$("#selectLicense").on("input", function() { $("#selectLicense").removeClass("is-invalid"); });
		if (!invalid) invalid = "#selectLicense";
	}

	if (invalid) $(invalid).focus();
	return (invalid ? false : true);
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function deleteCheckedItem() {
	var elmChecked = $("#itemtable-container").find("input:checked");
	var elmTitle = elmChecked.siblings("label"), elmCard = elmChecked.parents(".card");
	var itemRowID = elmCard.attr("data-internal"), itemTitle = elmTitle.html();

	// Check
	if ((itemRowID < 1) || (itemTitle.length < 1)) return;

	// Consent
	swal({
		title: "Are you sure?",
		text: "All files associated with the template item will be deleted permanently, and cannot be recovered!\r\n\r\nItem title:\r\n" + itemTitle,
		icon: "warning",
		buttons: true,
		dangerMode: true
	})
	.then((confirmDelete) => {
		if (confirmDelete) {
			$.post(BASE_URI + "/requests/admin_items_delete", {rowid: itemRowID}, function(response) {
				if (!isJson(response)) {
					console.log(response);
					swal({text: "Sorry, an error occurred!", icon: "warning"}).then(function() {
						itemsGetList();
						return;
					});
				}

				// Deleted
				swal({
					text: response.retdata,
					icon: (response.retcode == STATUS_SUCCESS) ? "success" : "warning"
				}).then(function() {
					itemsGetList();
					return;
				});
			}); // Ajax: Delete item
		} else {
			return; // User cancelled the deletion
		}
	});
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
		placeholder: "Type the item's description (e.g. features, properties, requirements, version, etc.)",
		height: 150
	});
});

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
$("#itemDelete").click(function() { deleteCheckedItem(); });

// "AddItem" modal's events
$("#modalItemAdd").click(function(e) {
	if ($(e.target).parent().attr("data-dismiss")) {
		modalItemAdd_Clear(); // Frees the videos URL data objects
	}
	else if ($(e.target).hasClass("btn-secondary")) {
		modalItemAdd_Clear(); // Frees the videos URL data objects
	}
});


/************************************************
* Save
************************************************/
$("#btnSave").click(function() {
	if (modalItemAdd_Validate()) {
		// Just one very important check before uploading: check that the item title is unique (will become the folder name)
		$.post(BASE_URI + "/requests/admin_items_checktitle", {itemtitle: $("#textItemTitle").val().trim()}, function(response) {
			if (!isJson(response)) {
				console.log(response);
				return;
			}
			if (response.retcode != STATUS_SUCCESS) {
				swal({text: response.retdata, icon: "warning"}).then(function() {
					$("#textItemTitle").addClass("is-invalid");
					$("#textItemTitle").on("input", function() { $("#textItemTitle").removeClass("is-invalid"); });
					$("#textItemTitle").focus();
				});
				return;
			}
			
			// If control reached here, this means that the title is good to go.
			// Continue uploading the item data after receiving the responce.

			// Set UI mode to ItemUpload
			$("#staticModalItemAdd").html('Uploading item components...');
			$("#staticModalItemAdd").siblings('button').addClass('d-none');
			$("#formItemAdd").addClass('d-none');
			$("#formItemUpload").removeClass("d-none");
			$("#formItemNewFooter").addClass("d-none");

			// Upload the components
			var iC, itemData = new FormData();

			itemData.set('date', $("#dateAddition").val());
			itemData.set('title', $("#textItemTitle").val().trim());
			itemData.set('description', $('#textItemDesc').summernote('code'));
			itemData.set('fileItem', $("#fileItemFile")[0].files[0]); // Only one file
			itemData.set('tags', getTagList()); // Will be converted to a string (items separated by commas)
			itemData.set('imgSmall', $("#filePrevImgSmall")[0].files[0]); // Only one file
			itemData.set('imgFull', $("#filePrevImgFull")[0].files[0]); // Only one file
			itemData.set('vidSmall', $("#filePrevVidSmall")[0].files[0]); // Only one file
			itemData.set('vidFull', $("#filePrevVidFull")[0].files[0]); // Only one file
			itemData.set('addImagesCount', $("#fileAddImages")[0].files.length);
			for (iC = 0; iC < $("#fileAddImages")[0].files.length; iC++) {
				itemData.append("addImages-" + iC, $("#fileAddImages")[0].files[iC]);
			}
			itemData.set('addVideosCount', $("#fileAddVideos")[0].files.length);
			for (iC = 0; iC < $("#fileAddVideos")[0].files.length; iC++) {
				itemData.append("addVideos-" + iC, $("#fileAddVideos")[0].files[iC]);
			}
			itemData.set('addAudiosCount', $("#fileAddAudios")[0].files.length);
			for (iC = 0; iC < $("#fileAddAudios")[0].files.length; iC++) {
				itemData.append("addAudios-" + iC, $("#fileAddAudios")[0].files[iC]);
			}
			itemData.set('price', $("#textItemPrice").val());
			itemData.set('license', $("#selectLicense option:selected").val());

			$.ajax({
				url: BASE_URI + "/requests/admin_items_add",
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var fPercentComplete = Number(((evt.loaded / evt.total) * 100).toFixed(1)), iPercentComplete = Math.ceil(fPercentComplete);
							$("#progressItemUpload").attr('aria-valuenow', iPercentComplete);
							$("#progressItemUpload").css('width', iPercentComplete + '%');
							$("#labelItemUpload").html("Uploaded: <strong>" + fPercentComplete + '%</strong>');
						}
					}, false);
					return xhr;
				},
				type: 'post',
				headers: {'X-Requested-With': 'XMLHttpRequest'},
				data: itemData,
				datatype: 'json',
				contentType: false,
				processData: false,
				success: function(response) {
					if (!isJson(response)) {
						console.log(response);

						$('#modalItemAdd').modal("hide");
						modalItemAdd_Clear(); // Frees the videos URL data objects
						
						swal({text: "Some error occurred!", icon: "warning"});
						return;
					}

					// If code reached here, the addition MUST have been succeeded
					//console.log(response);

					setTimeout(function() {
						$('#modalItemAdd').modal("hide");
						modalItemAdd_Clear(); // Frees the videos URL data objects

						swal({text: "Item was added successfully!", icon: "success"}).then(function() {
							itemsGetList();
						});
					}, 1000);
				} // Received response
			}); // JQ.Ajax() (sending all the item's data)
		}); // JQ.Post() (Checking the item's title)
	} // Form validated
});


itemsGetList();
