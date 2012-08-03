
function setupGithub(url, el) {
	var href = el.href;

	if ($('#github-profile').length > 0) {
		window.location = href;
		return;
	}

	var params = url.attr('path').split('/').filter(function(w) {
		if (w.length)
			return true;
		return false;
	})

	if (params.length == 1) {
		var username = params[0];

		var spinner = new Spinner(spin_opts).spin();
		$('#github-link').append(spinner.el);

		var href = site_path+"/github/" + username
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
