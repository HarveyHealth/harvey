<?php

/* welcome.html */
class __TwigTemplate_7025034f8c121568a98efe5fe5095766d91f83e1f4390d35e85be133dd525518 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html", "welcome.html", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
<div class=\"wrap mailpoet-about-wrap\">
  <h1>";
        // line 6
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Greetings, humans.");
        echo "</h1>

  <p class=\"about-text\">";
        // line 8
        echo MailPoet\Util\Helpers::replaceLinkTags($this->env->getExtension('MailPoet\Twig\I18n')->translate("Thanks for using MailPoet! We really appreciate all of your love, affection, [link]and (good) plugin reviews.[/link]"), "https://wordpress.org/support/plugin/wysija-newsletters/reviews/", array("target" => "_blank"));
        // line 11
        echo "
  </p>
  <div class=\"mailpoet-logo\"><img src=\"";
        // line 13
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateImageUrl("welcome_template/mailpoet-logo.png");
        echo "\" alt=\"";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("MailPoet Logo");
        echo "\" /></div>

  <h2 class=\"nav-tab-wrapper wp-clearfix\">
    <a href=\"admin.php?page=mailpoet-welcome\" class=\"nav-tab nav-tab-active\">";
        // line 16
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Welcome");
        echo "</a>
    <a href=\"admin.php?page=mailpoet-update\" class=\"nav-tab\">";
        // line 17
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("What's new");
        echo "</a>
  </h2>

  <div class=\"headline-feature feature-video\">
    <div class=\"videoWrapper\">
      <iframe width=\"1050\" height=\"591\" src=\"https://player.vimeo.com/video/184501111?title=0&byline=0&portrait=0\" frameborder=\"0\" webkitallowfullscreen=\"\" mozallowfullscreen=\"\" allowfullscreen=\"\"></iframe>
    </div>
  </div>

  <hr>

  <div class=\"feature-section two-col\">
    <h2>";
        // line 29
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Want to Make MailPoet Even Better?");
        echo "</h2>
    <div class=\"col\">
      <h3>";
        // line 31
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("We Need Your Feedback!");
        echo "</h3>
      <p>";
        // line 32
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("As a beta tester, you have a very important job: to tell us what you think about this new version. If you love it, tell us! If you hate it, let us know! Any and all feedback is useful.");
        echo "</p>
      <p>";
        // line 33
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("To get in touch with us, simply click on the blue circle in the bottom right corner of your screen. This button is visible on all MailPoet pages on your WordPress dashboard.");
        echo " <img width=\"30\" style=\"margin:0\" src=\"";
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateImageUrl("welcome_template/beacon.png");
        echo "\" alt=\"Beacon\" /></p>
    </div>
    <div class=\"col\">
      <h3>";
        // line 36
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Sharing is Caring");
        echo "</h3>
      <p>";
        // line 37
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("By sharing your data <i>anonymously</i> with us, you can help us understand <i>how people use MailPoet</i> and <i>what sort of features they like and don't like</i>.");
        echo " <a href=\"http://beta.docs.mailpoet.com/article/130-sharing-your-data-with-us\" target=\"_blank\">";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Find out more");
        echo " &rarr;</a>
      <br><br>
      <label>
        <input type=\"checkbox\" id=\"mailpoet_analytics_enabled\" value=\"1\"
        ";
        // line 41
        if ($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "analytics", array()), "enabled", array())) {
            echo "checked=\"checked\"";
        }
        // line 42
        echo "        />&nbsp;";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yes, I want to help!");
        echo "
      </label>
      </p>
    </div>
  </div>

  <hr>

  <div class=\"feature-section two-col\">
    <div class=\"col\">
      <h3>";
        // line 52
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Subscribe To Our Newsletter");
        echo "</h3>
      <p>";
        // line 53
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("About once a month, we send out a pretty cool newsletter ourselves.");
        echo "</p>
      <p>";
        // line 54
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Sign up to get a curated selection of awesome links, tips and tricks for using MailPoet, special offers, and important plugin updates!");
        echo "</p>
      <p>
      <iframe width=\"380\" scrolling=\"no\" frameborder=\"0\" src=\"http://www.mailpoet.com/?wysija-page=1&controller=subscribers&action=wysija_outter&wysija_form=5&external_site=1&wysijap=subscriptions-3\" class=\"iframe-wysija\" vspace=\"0\" tabindex=\"0\" style=\"border-style: none; display:block; visibility: visible; background-color: #f1f1f1!important;\" marginwidth=\"0\" marginheight=\"0\" hspace=\"0\" allowtransparency=\"true\" title=\"";
        // line 56
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Subscribe To Our Newsletter");
        echo "\"></iframe>
      </p>
    </div>
    <div class=\"col\">
      <h3>";
        // line 60
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Learn the Ropes");
        echo "</h3>
      <p>";
        // line 61
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("New to MailPoet? Check out our brand new email course. Over the span of 3 weeks, we'll teach you how to create and send your first MailPoet email newsletter. Sign up below!");
        echo "</p>
      <p>
      <iframe width=\"100%\" height=\"100%\" scrolling=\"no\" frameborder=\"0\" src=\"http://newsletters.mailpoet.com?mailpoet_form_iframe=4\" class=\"mailpoet_form_iframe\" vspace=\"0\" tabindex=\"0\" onload=\"if(window['MailPoet']) MailPoet.Iframe.autoSize(this);\" marginwidth=\"0\" marginheight=\"0\" hspace=\"0\" allowtransparency=\"true\"></iframe>
      </p>
    </div>
  </div>

  <hr>

  <div class=\"feature-section one-col mailpoet_centered\">
    <a class=\"button button-primary go-to-plugin\" href=\"admin.php?page=mailpoet-newsletters\">";
        // line 71
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Awesome! Now, take me to MailPoet");
        echo " &rarr;</a>
  </div>

</div>

<script type=\"text/javascript\">
jQuery(function(\$) {
  // Find all videos
  var \$allVideos = \$(\"iframe[src^='//player.vimeo.com'], iframe[src^='//www.youtube.com']\"),
  // The element that is fluid width
  \$fluidEl = \$(\"body\");
  // Figure out and save aspect ratio for each video
  \$allVideos.each(function() {
    \$(this)
      .data('aspectRatio', this.height / this.width)
      // and remove the hard coded width/height
      .removeAttr('height')
      .removeAttr('width');
  });
  // When the window is resized
  \$(window).resize(function() {
  var newWidth = \$fluidEl.width();
  // Resize all videos according to their own aspect ratio
  \$allVideos.each(function() {
    var \$el = \$(this);
    \$el
      .width(newWidth)
      .height(newWidth * \$el.data('aspectRatio'));
    });
  // Kick off one resize to fix all videos on page load
  }).resize();

    \$(function() {
      \$(\"#mailpoet_analytics_enabled\").on(\"click\", function() {
        var is_enabled = \$(this).is(\":checked\") ? true : \"\";
        MailPoet.Ajax.post({
          api_version: window.mailpoet_api_version,
          endpoint: \"settings\",
          action: \"set\",
          data: {
            analytics: { enabled: (is_enabled)}
          }
        }).fail((response) => {
          if (response.errors.length > 0) {
            MailPoet.Notice.error(
              response.errors.map((error) => { return error.message; }),
              { scroll: true }
            );
          }
        });

        if (is_enabled) {
          MailPoet.forceTrackEvent(
            'User has installed MailPoet',
            {'MailPoet Free version': window.mailpoet_version}
          );
        }

      })
    });
});
</script>

";
    }

    public function getTemplateName()
    {
        return "welcome.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  162 => 71,  149 => 61,  145 => 60,  138 => 56,  133 => 54,  129 => 53,  125 => 52,  111 => 42,  107 => 41,  98 => 37,  94 => 36,  86 => 33,  82 => 32,  78 => 31,  73 => 29,  58 => 17,  54 => 16,  46 => 13,  42 => 11,  40 => 8,  35 => 6,  31 => 4,  28 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "welcome.html", "/home/vagrant/harvey/public/blog/wp-content/plugins/mailpoet/views/welcome.html");
    }
}
