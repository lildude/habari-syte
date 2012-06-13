<div class="mobile-nav">
  <span class="nav-btn" id="mobile-nav-btn">
    <span class="nav-btn-bar"></span>
    <span class="nav-btn-bar"></span>
    <span class="nav-btn-bar"></span>
  </span>
  <h3><a href="/">rigoneri.com</a></h3>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
/*<![CDATA[*/
var twitter_integration_enabled = {% if TWITTER_INTEGRATION_ENABLED %}true{% else %}false{% endif %},
    github_integration_enabled = {% if GITHUB_INTEGRATION_ENABLED %}true{% else %}false{% endif %},
    dribbble_integration_enabled = {% if DRIBBBLE_INTEGRATION_ENABLED %}true{% else %}false{% endif %},
    instagram_integration_enabled = {% if INSTAGRAM_INTEGRATION_ENABLED %}true{% else %}false{% endif %};
/*]]>*/
</script>

{% if DEV_DEPLOYMENT_MODE %}
<script src="{{ MEDIA_URL }}js/libs/jquery.url.js"></script>
<script src="{{ MEDIA_URL }}js/libs/require.js"></script>
<script src="{{ MEDIA_URL }}js/libs/handlebars.js"></script>
<script src="{{ MEDIA_URL }}js/libs/moment.min.js"></script>
<script src="{{ MEDIA_URL }}js/libs/bootstrap-modal.js"></script>
<script src="{{ MEDIA_URL }}js/libs/spin.min.js"></script>
<script src="{{ MEDIA_URL }}js/libs/prettify.js"></script>

<script src="{{ MEDIA_URL }}js/components/base.js"></script>
<script src="{{ MEDIA_URL }}js/components/mobile.js"></script>
<script src="{{ MEDIA_URL }}js/components/blog-posts.js"></script>
<script src="{{ MEDIA_URL }}js/components/links.js"></script>

{% if TWITTER_INTEGRATION_ENABLED %}<script src="{{ MEDIA_URL }}js/components/twitter.js"></script>{% endif %}
{% if GITHUB_INTEGRATION_ENABLED %}<script src="{{ MEDIA_URL }}js/components/github.js"></script>{% endif %}
{% if DRIBBBLE_INTEGRATION_ENABLED %}<script src="{{ MEDIA_URL }}js/components/dribbble.js"></script>{% endif %}
{% if INSTAGRAM_INTEGRATION_ENABLED %}<script src="{{ MEDIA_URL }}js/components/instagram.js"></script>{% endif %}

{% else %}
<script src="{{ MEDIA_URL }}js/min/scripts-{{ COMPRESS_REVISION_NUMBER }}.min.js"></script>
{% endif %}

{% block extra_js %}{% endblock %}
<script type="text/javascript">
/*<![CDATA[*/
{% block extra_inline_js %}{% endblock %}
/*]]>*/
</script>
</body>