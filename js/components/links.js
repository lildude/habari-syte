var $url;
function setupLinks() {

	$('a[data-toggle=modal]').click(function(e) {

		if (e.which == 2)	// Not sure why we do this
			return;

		e.preventDefault();
		e.stopPropagation();

		if (this.href == $url)
			return;

		var url = $.url(this.href.replace('/#!', ''));
		$url = this.href;
		
		// Take the id and determine the profile etc from it
		var module = this.id.split('-')[0];
		
		// If the block is already visible, take the user to the actual URL if they click it again
		if ($('#'+module+'-profile').length > 0) {
			window.location = this.href;
			return;
		}

		// If any other blocks still exist, remove them first
		if ($('div[id$="-profile"]').length > 0) {
			$('div[id$="-profile"]').remove();
			$('.modal-backdrop').remove();
		}
		
		// Determine the username from the position is occurs in the actual site's profile URL
		var params = url.attr('path').split('/').filter(function(w) {
			if (w.length)
				return true;
			return false;
		})

		var username = '';
		switch( params.length ) {
			case 1:		// twitter, github, dribbble
				username = params[0];
				break;	
			case 2:		// last.fm
				username =  params[1];
				break;
			default:	// instagram
				
		}
		
		var href = site_path + "/" + module + "/" + username;
		
		var spinner = new Spinner(spin_opts).spin();
		$('#'+this.id).append(spinner.el);


		$.get(href, function(data) {
			$(data).modal().on('hidden', function () {
					$(this).remove();
					adjustSelection('home-link');
				});
			}).success(function() {
				adjustSelection(module+'-link');
				spinner.stop();
			});
		return;

		// If you get here something unexpected happened :-)
		window.location = href;

		});
}

function adjustSelection(el) {
  $('.main-nav').children('li').removeClass('sel');
  $('#' + el).parent().addClass('sel');

  if (el == 'home-link')
    $url = null;
}

