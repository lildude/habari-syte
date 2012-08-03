
function fetchBlogPosts(post, tag) {	
	setupLinks();
	adjustBlogHeaders();
	prettyPrint();
	setTimeout(setupBlogHeaderScroll, 1000);
	adjustSelection('home-link');
}

function adjustBlogHeaders() {
  if(isMobileView)
    return;

  $('.blog-section article hgroup').each(function(i, e) {
    $(e).find('h3 a').css({
       'margin-top': '-' + ($(e).height() + 100) + 'px' 
    }).addClass('adjusted');
  });
}

function setupBlogHeaderScroll() {

  if(isMobileView)
    return;

  var previousTarget,
      activeTarget,
      $window = $(window),
      offsets = [],
      targets = [],
      $posts = $('.blog-section article hgroup h3 a').each(function() {
        if (this.hash) {
          targets.push(this.hash);
          offsets.push($(this.hash).offset().top);
        }
      });

  function processScroll(e) {
    var scrollTop = $window.scrollTop(),
        i = offsets.length;

    for (i; i--;) {
      if (activeTarget != targets[i] && scrollTop > offsets[i] && (!offsets[i + 1] || scrollTop < offsets[i + 1])) {

          var hgroup = $(activeTarget).find("hgroup");
          var margintop = '';
          if (hgroup.length) {
            margintop = '-' + ($(hgroup[0]).height() + 100) + 'px';
          }

          //set current target to be absolute
          $("h3 a[href=" + activeTarget + "]").removeClass("active").css({
            position: "absolute",
            top: "auto",
            'margin-top': margintop
          });

          //set new target to be fixed
          activeTarget = targets[i];
          $("h3 a[href=" + activeTarget + "]").attr('style', '').addClass("active");
      }

      if (activeTarget && activeTarget != targets[i] && scrollTop + 50 >= offsets[i] && (!offsets[i + 1] || scrollTop + 50 <= offsets[i + 1])) {

          // if it's close to the new target scroll the current target up
          $("h3 a[href=" + activeTarget + "]")
              .removeClass("active")
              .css({
                  position: "absolute",
                  top: ($(activeTarget).outerHeight(true) + $(activeTarget).offset().top - 50) + "px",
                  bottom: "auto"
              });
      }

      if (activeTarget == targets[i] && scrollTop > offsets[i] - 50  && (!offsets[i + 1] || scrollTop <= offsets[i + 1] - 50)) {
          // if the current target is not fixed make it fixed.
          if (!$("h3 a[href=" + activeTarget + "]").hasClass("active")) {
              $("h3 a[href=" + activeTarget + "]").attr('style', '').addClass("active");
          }
      }
    }
  }

  $posts.click(function(e) {
    if (!this.hash)
      return;
    $('html, body').stop().animate({
        scrollTop: $(this.hash).offset().top
    }, 500, 'linear');

    processScroll();
    e.preventDefault();
  });

  $window.scroll(processScroll).trigger("scroll");
}
