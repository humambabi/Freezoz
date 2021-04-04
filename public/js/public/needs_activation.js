/* ----------------------------------------------
(Account) Needs activation page
---------------------------------------------- */

//$("#return-home").click(function() { window.location.href = BASE_URI; });


$("#send-activationcode").click(function() {
	g_bStopScroll = true;
	g_iStopScrollPos = $(window).scrollTop();

	// If the burger menu is opened, close it
	if ($('#btn-burger').hasClass('is-active')) $('#btn-burger').click();

	// Activate the full-screen overlay
	if ($("#overlay-full").hasClass("hidden")) {
		$("#overlay-full").data("can-close-on-click", false);
		$("#overlay-full").html(ELM_SPINNER);
		$("#overlay-full").removeClass("hidden");

		$.post(BASE_URI + "/requests/send_activationcode", function(response) {
			if (!isJson(response)) {
				console.log(response);
				return;
			}

			//console.log(response);

			$("#overlay-full").html("&nbsp;"); // Remove the spinner

			if (response.retcode != STATUS_SUCCESS) {
				swal({
					title: "Wait!", text: response.retdata, icon: "warning"
				}).then(function() {
					$("#overlay-full").data("can-close-on-click", true); // Allow close on click
					$("#overlay-full").trigger("click");
				});
				return; // (Leaving the form as it is!)
			}

			// Success
			swal({
				title: "Sent!", text: response.retdata, icon: "success"
			}).then(function() {
				$("#overlay-full").data("can-close-on-click", true); // Allow close on click
				$("#overlay-full").trigger("click");

				window.location.href = BASE_URI; // Go to home, after the email has been sent.
			});
		}); // Ajax
	} // Open the full-screen overlay
});