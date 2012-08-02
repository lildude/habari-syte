function setupLastfm(url, el) {
	var href = el.href;

	if ($('#lastfm-profile').length > 0) {
		$('#lastfm-profile').modal('show');
		return;
	}

	/* New code here */
	// Start the spinner
	var params = url.attr('path').split('/').filter(function(w) {
		if (w.length)
			return true;
		return false;
	})

	if (params.length == 2) {
		var username = params[1];

		var spinner = new Spinner(spin_opts).spin();
		$('#lastfm-link').append(spinner.el);

		var href = site_path+"/lastfm/" + username
		$.get(href, function(data) {
			$(data).modal().on('hidden', function () {
					$(this).remove();
					adjustSelection('home-link');
				});
			}).success(function() {
			spinner.stop();
		});
		return;
	}

	window.location = href;
}
