
function setupInstagram(el) {
	var href = el.href;

	if($('#instagram-profile').length > 0) {
		window.location = href;
		return;
	}

	var spinner = new Spinner(spin_opts).spin();
	$('#instagram-link').append(spinner.el);

	var href = site_path+"/instagram/"
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
