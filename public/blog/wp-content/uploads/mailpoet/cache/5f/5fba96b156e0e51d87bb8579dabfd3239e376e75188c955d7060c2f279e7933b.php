<?php

/* mp2migration.html */
class __TwigTemplate_649c4d6b64e0e89817ae029cd3c3b79a3188a69bc9b7e61282463b43672fbc99 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html", "mp2migration.html", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
            'after_javascript' => array($this, 'block_after_javascript'),
            'translations' => array($this, 'block_translations'),
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
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Welcome to MailPoet version 3!");
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["settings"] ?? null), "version", array()), "html", null, true);
        echo "</h1>

  <p class=\"about-text\"><strong>";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("This new version is quite an upgrade.");
        echo "</strong> ";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Since this new version is completely new, we first need to update your database before we begin.");
        echo "</p>

  <h3>";
        // line 10
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("What will be kept in MailPoet 3");
        echo "</h3>
  <p>";
        // line 11
        echo sprintf($this->env->getExtension('MailPoet\Twig\I18n')->translate("All the data from your previous MailPoet will be preserved and left intact, rest assured. Get in touch with us if you have issues with the upgrade process by using the form %s at the bottom right of all your MailPoet pages."), (("<img alt="" width=\"30\" style=\"margin:0\" src=\"" . $this->env->getExtension('MailPoet\Twig\Assets')->generateImageUrl("welcome_template/beacon.png")) . "\" alt=\"Beacon\" />"));
        echo "</p>
  <ul>
    <li><strong>Subscribers and lists <img alt="" draggable=\"false\" class=\"emoji\" alt=\"✔\" src=\"https://s.w.org/images/core/emoji/2.2.1/svg/2714.svg\"></strong></li>
    <li><strong>Forms <img alt="" draggable=\"false\" class=\"emoji\" alt=\"✔\" src=\"https://s.w.org/images/core/emoji/2.2.1/svg/2714.svg\"></strong></li>
    <li><strong>Settings <img alt="" draggable=\"false\" class=\"emoji\" alt=\"✔\" src=\"https://s.w.org/images/core/emoji/2.2.1/svg/2714.svg\"></strong></li>
    <li>Archive of sent newsletters (will not be migrated)</li>
    <li>Statistics (will not be migrated)</li>
  </ul>
  <div style=\"position: absolute; top: .2em; right: 0;\"></div>
  <hr />

  <div>
    <br />
    <div id=\"import-actions\">
      <input type=\"submit\" name=\"import\" id=\"import\" class=\"button button-primary\" value=\"";
        // line 25
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Start upgrade");
        echo "\"  />
      <input type=\"submit\" name=\"stop-import\" id=\"stop-import\" class=\"button button-secondary\" value=\"";
        // line 26
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Pause");
        echo "\" style=\"display: none\"/>
      <small> <a id=\"skip-import\" href=\"#\">";
        // line 27
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No thanks, I'll skip and start from scratch.");
        echo "</a></small>
      <br />
      <br />
    </div>

    <div id=\"progressbar\" class=\"mailpoet_progress mailpoet_progress_complete\" style=\"display: none\">
      <span id=\"progresslabel\" class=\"mailpoet_progress_label\">0%</span>
    </div>

    <div id=\"upgrade-completed\" style=\"display: none\">
      <h3>";
        // line 37
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Upgrade completed!");
        echo "</h3>
      <p>";
        // line 38
        echo sprintf($this->env->getExtension('MailPoet\Twig\I18n')->translate("Review our <a href=\"%s\" %s>simple checklist</a> to ensure your transition to MailPoet 3 is smooth."), "http://beta.docs.mailpoet.com/article/199-checklist-for-those-migrating-from-mailpoet-version-2?utm_source=plugin&utm_medium=migration&utm_campaign=migrationchecklist", "target=\"_blank\"");
        echo "</p>
      <input type=\"button\" name=\"goto-welcome\" id=\"goto-welcome\" class=\"button button-primary\" value=\"";
        // line 39
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Go to MailPoet");
        echo "\"  />
    </div>

    <br />
    <div id=\"logger-container\" style=\"display: none\">
      <small><strong>";
        // line 44
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Log...");
        echo "</strong></small>
      <div id=\"logger\"></div>
    </div>

  </div>

</div>
";
    }

    // line 53
    public function block_after_javascript($context, array $blocks = array())
    {
        // line 54
        echo "  ";
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateJavascript("mp2migrator.js");
        // line 56
        echo "
  <script type=\"text/javascript\">
    var mailpoet_mp2_migrator = {
      log_file_url: '";
        // line 59
        echo twig_escape_filter($this->env, ($context["log_file_url"] ?? null), "html", null, true);
        echo "',
      progress_url: '";
        // line 60
        echo twig_escape_filter($this->env, ($context["progress_url"] ?? null), "html", null, true);
        echo "'
    };
  </script>
";
    }

    // line 65
    public function block_translations($context, array $blocks = array())
    {
        // line 66
        echo "  ";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(array("import_complete" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("IMPORT COMPLETE"), "importing" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Importing…"), "import_stopped_by_user" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("IMPORT STOPPED BY USER")));
        // line 70
        echo "
";
    }

    public function getTemplateName()
    {
        return "mp2migration.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 70,  147 => 66,  144 => 65,  136 => 60,  132 => 59,  127 => 56,  124 => 54,  121 => 53,  109 => 44,  101 => 39,  97 => 38,  93 => 37,  80 => 27,  76 => 26,  72 => 25,  55 => 11,  51 => 10,  44 => 8,  37 => 6,  33 => 4,  30 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "mp2migration.html", "/home/vagrant/harvey/public/blog/wp-content/plugins/mailpoet/views/mp2migration.html");
    }
}
