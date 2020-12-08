/* ----------------------------------------------
Main JavaScript codes - Freezoz
---------------------------------------------- */

// The 'spinner' icon to be shown centered in the overlay
const ELM_SPINNER = '<i style="color:#fff" class="fas fa-5x fa-fan fa-spin"></i>';

// Global variables to enable/disable page scroll
var g_bStopScroll = false;
var g_iStopScrollPos = 0;

// Globals related to the user-menu
const UMTOP_BEFORE = 25; // Pixels
var g_iUserMenu_top = 0;


///////////////////////////////////////////////////////////////////////////////////////////////////
function updateUserMenuPosition() {
	var elmBtn = document.getElementById('navbar-userbtn'), elmMenu = document.getElementById('usermenu-container');
	var addTop = 15, addLeft = 9, mLeft;

	if ($(window).innerWidth() <= 768) {
		mLeft = parseInt(elmBtn.offsetLeft) - (parseInt(getComputedStyle(elmMenu).width) / 2.0) + (parseInt(getComputedStyle(elmBtn).width) / 2.0);
	} else {
		mLeft = parseInt(elmBtn.offsetLeft) - parseInt(getComputedStyle(elmMenu).width) + parseInt(getComputedStyle(elmBtn).width) + addLeft;
	}

	g_iUserMenu_top = parseInt(elmBtn.offsetTop) + parseInt(getComputedStyle(elmBtn).height) + addTop + parseInt($(window).scrollTop());

	$('#usermenu-container').css('left', mLeft.toString() + 'px');
	if ($('#usermenu-container').css('display') == "none") {
		$('#usermenu-container').css('top', (g_iUserMenu_top - UMTOP_BEFORE).toString() + 'px');
	} else {
		$('#usermenu-container').css('top', g_iUserMenu_top.toString() + 'px');
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function isEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function isJson(response) {
	return (typeof(response) === "object") ? true : false;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function isInViewport(elmid) {
	var bounding = document.getElementById(elmid).getBoundingClientRect();
	return (
		bounding.top >= 69 && // NavBar's height is 69px
		bounding.left >= 0 &&
		bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
		bounding.right <= (window.innerWidth || document.documentElement.clientWidth)
	);
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function scroll_page(elmid) {
	var bodyRect = document.body.getBoundingClientRect(),
		elemRect = document.getElementById(elmid).getBoundingClientRect(),
		offset = elemRect.top - bodyRect.top;

	window.scrollBy({
		top:			(offset - window.scrollY - 79),
		left:			0,
		behavior:	'smooth'
	});
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function adjust_footerPos() {
	if (parseInt(getComputedStyle($("body")[0]).height) > window.innerHeight) {
		$("footer").css("position", "relative");
	} else {
		$("footer").css("position", "fixed");
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function categories_open() {
	// Go to the top
	window.scrollBy({ top: -window.scrollY, left: 0, behavior: 'smooth' });

	// If the burger menu is opened, close it
	if ($('#btn-burger').hasClass('is-active')) $('#btn-burger').click();

	// Activate the body overlay
	if ($("#overlay-body").hasClass("hidden")) {
		$("#overlay-body").html(ELM_SPINNER);
		$("#overlay-body").removeClass("hidden");

		$.get(BASE_URI + "/assets/categories_form", function(response) { /* The asset must be contained within an element with id="categoriesform" */
			$("#overlay-body").html(response);

			// Set the close event's function
			$("#categoriesform").on("close_event", function() {
				$("#categoriesform").removeClass("slideL-show");
				$("#categoriesform").addClass("slideL-hide");
			});

			// Show the navbar's shadow
			if (!$('#navbar').hasClass('navbar-shadow')) $('#navbar').addClass('navbar-shadow');

			// Save the form's id in the overlay's data
			$("#overlay-body").data("child-DOM-id", "categoriesform");

			// Allow close on click
			$("#overlay-body").data("can-close-on-click", true);

			$("#keyword-search").focus();
		});
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function signinform_open() {
	g_bStopScroll = true;
	g_iStopScrollPos = $(window).scrollTop();

	// If the burger menu is opened, close it
	if ($('#btn-burger').hasClass('is-active')) $('#btn-burger').click();

	// Activate the full-screen overlay
	if ($("#overlay-full").hasClass("hidden")) {
		$("#overlay-full").html(ELM_SPINNER);
		$("#overlay-full").removeClass("hidden");

		$.get(BASE_URI + "/assets/signin_form", function(response) { /* The asset must be contained within an element with id="signinform" */
			$("#overlay-full").html(response);

			// Set the close event's function
			$("#signinform").on("close_event", function() {
				$("#signinform").removeClass("popup-show");
				$("#signinform").addClass("popup-hide");
			});

			// Save the form's id in the overlay's data
			$("#overlay-full").data("child-DOM-id", "signinform");

			// Allow close on click
			$("#overlay-full").data("can-close-on-click", true);
		});
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function usermenu_open() {
	g_bStopScroll = true;
	g_iStopScrollPos = $(window).scrollTop();
	updateUserMenuPosition();

	// Activate the full-screen overlay
	if ($("#overlay-full").hasClass("hidden")) {
		$("#overlay-full").removeClass("hidden");

		$('#usermenu-container').css('display', "flex");
		setTimeout(function() {
			$('#usermenu-container').css('opacity', 1);
			$('#usermenu-container').css('top', g_iUserMenu_top);
		}, 50); // Just a while after 'displaying' it transparently

		// Set the close event's function
		$("#usermenu-container").on("close_event", function() {
			$("#usermenu-container").css('opacity', 0);
			$('#usermenu-container').css('top', (g_iUserMenu_top - UMTOP_BEFORE).toString() + 'px');
			setTimeout(function() {
				$('#usermenu-container').css('display', "none");

				// Mark the burger button as if  the burger menu was closed (sync with #btn-burger.click)
				$('#btn-burger').removeClass('is-active');
				if ($('#navbar-container').hasClass('navbar-menuopen')) $('#navbar-container').removeClass('navbar-menuopen');
				if ($(window)[0].scrollY <= 10) if ($('#navbar').hasClass('navbar-shadow')) $('#navbar').removeClass('navbar-shadow');
			}, 500); // A little more than the transition (opacity) time interval -- not too important!
		});

		// Save the form's id in the overlay's data
		$("#overlay-full").data("child-DOM-id", "usermenu-container");

		// Allow close on click
		$("#overlay-full").data("can-close-on-click", true);

		// Mark the burger button as if  the burger menu was opened (sync with #btn-burger.click)
		$('#btn-burger').addClass('is-active');
		if (!$('#navbar-container').hasClass('navbar-menuopen')) $('#navbar-container').addClass('navbar-menuopen');
		if (($(window).innerWidth() <= 768) && !$('#navbar').hasClass('navbar-shadow')) $('#navbar').addClass('navbar-shadow');
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function user_signout() {
	// Close the user menu (without closing the overlay)
	$("#usermenu-container").trigger("close_event");

	// Set the overlay to not close on click
	$("#overlay-full").data("can-close-on-click", false);

	// Show the spinner in the overlay
	$("#overlay-full").html(ELM_SPINNER);

	// Send the request
	$.post(BASE_URI + "/requests/sign_out", function(response) {
		if (!isJson(response) || response.retcode != STATUS_SUCCESS) {
			console.log(response);
			return;
		}
		
		window.location.href = BASE_URI;
	});
}


/*
General section
*/

// When the window is scrolled
$(window).scroll(function(e) {
	e.preventDefault();
	if (g_bStopScroll) $(window).scrollTop(g_iStopScrollPos);

	if (
		($(window)[0].scrollY > 10) || // After scrolling more than 10px vertically
		(($('#navbar-container').hasClass('navbar-menuopen')) && ($(window).innerWidth() <= 768)) || // BurgerMenu is opened AND we are on a mobile phone
		($("#overlay-body").length && !$("#overlay-body").hasClass("hidden")) // BodyOverlay is shown
	) {
		if (!$('#navbar').hasClass('navbar-shadow')) $('#navbar').addClass('navbar-shadow');
	} else {
		if ($('#navbar').hasClass('navbar-shadow')) $('#navbar').removeClass('navbar-shadow');
	}
});

// When the window is resized  (rare, but nicer to have)
$(window).resize(function() {
	if (($(window).innerWidth() > 768) && ($(window)[0].scrollY <= 10)) {
		if ($('#navbar').hasClass('navbar-shadow')) $('#navbar').removeClass('navbar-shadow');
	}

	adjust_footerPos();
	updateUserMenuPosition();
});

// When the page is loaded into the browser
$(window).on("load", function() {
	adjust_footerPos();
});

// A click event on the 'burger' button (only for screens <= 768px in width)
$('#btn-burger').click(function() {
	if ($('#btn-burger').hasClass('is-active')) {
		// Close the burger menu
		$('#btn-burger').removeClass('is-active');
		if ($('#navbar-container').hasClass('navbar-menuopen')) $('#navbar-container').removeClass('navbar-menuopen');
		if ($(window)[0].scrollY <= 10) if ($('#navbar').hasClass('navbar-shadow')) $('#navbar').removeClass('navbar-shadow');
	} else {
		// Open the burger menu
		$('#btn-burger').addClass('is-active');
		if (!$('#navbar-container').hasClass('navbar-menuopen')) $('#navbar-container').addClass('navbar-menuopen');
		if (!$('#navbar').hasClass('navbar-shadow')) $('#navbar').addClass('navbar-shadow');
	}
});


// When the user clicks the "go to top" button (footer)
$(".foot-gototop").click(function() {
	window.scrollBy({
		top:			-window.scrollY,
		left:			0,
		behavior:	'smooth'
	});
});


// When the overlay (overlay-full) is clicked
$("#overlay-full").click(function(ev) {
	// Consider it a "click" only if clicked on the overlay or the spinner (maybe "svg" or "path" tags) (think of event propagation too)
	if ((ev.target == this) ||
			((ev.target.nodeName.toUpperCase() == "PATH") && (ev.target.parentNode.nodeName.toUpperCase() == "SVG") && (ev.target.parentNode.parentNode == this)) ||
			((ev.target.nodeName.toUpperCase() == "SVG") && (ev.target.parentNode == this))) {
		// Also prevent Click event when not allowed to close on click
		if ($(this).data("can-close-on-click")) {
			// Call the function needed to close the opened form
			var childDomId = $("#overlay-full").data("child-DOM-id");
			if (childDomId) $("#" + childDomId).trigger("close_event");

			// De-activate the full-screen overlay
			if (!$("#overlay-full").hasClass("hidden")) $("#overlay-full").addClass("hidden");

			// Reset the can-close-on-click data value
			$("#overlay-full").data("can-close-on-click", false);

			// Re-allow scrolls
			g_bStopScroll = false;
		}
	}
});


// When the overlay (overlay-body) is clicked
$("#overlay-body").click(function(ev) {
	// Consider it a "click" only if clicked on the overlay or the spinner (maybe "svg" or "path" tags) (think of event propagation too)
	if ((ev.target == this) ||
			((ev.target.nodeName.toUpperCase() == "PATH") && (ev.target.parentNode.nodeName.toUpperCase() == "SVG") && (ev.target.parentNode.parentNode == this)) ||
			((ev.target.nodeName.toUpperCase() == "SVG") && (ev.target.parentNode == this))) {
		// Also prevent Click event when not allowed to close on click
		if ($(this).data("can-close-on-click")) {
			// Call the function needed to close the opened form
			var childDomId = $("#overlay-body").data("child-DOM-id");
			if (childDomId) $("#" + childDomId).trigger("close_event");

			// De-activate the body overlay
			if (!$("#overlay-body").hasClass("hidden")) $("#overlay-body").addClass("hidden");

			// Remove the navbar's shadow (if needed)
			if ($(window)[0].scrollY <= 10) if ($('#navbar').hasClass('navbar-shadow')) $('#navbar').removeClass('navbar-shadow');

			// Re-allow scrolls
			g_bStopScroll = false;
		}
	}
});


// Define click events handlers
$("#navbar-categories").click(function() { categories_open(); }); // Categories (on the navbar)
$("#footer-categories").click(function() { categories_open(); }); // Categories (on the footer)
$("#navbar-signinbtn").click(function() { signinform_open(); }); // Sign-in button (on the navbar)
$("#navbar-userbtn").click(function() { usermenu_open(); }); // User button (on the navbar)
$("#usermenu_signout").click(function() { user_signout(); }); // SignOut (on the user menu)