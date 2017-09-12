<?php

/* settings/advanced.html */
class __TwigTemplate_a0a68d715411c609eca65d782816edd3e0ee4e96cce7f94fe1aa38dce6b04421 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<table class=\"form-table\">
  <tbody>
    <!-- bounce email -->
    <tr>
      <th scope=\"row\">
        <label for=\"settings[bounce_email]\">
          ";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Bounce email address");
        echo "
        </label>
        <p class=\"description\">
          ";
        // line 10
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Your bounced emails will be sent to this address.");
        echo "
          <a href=\"http://beta.docs.mailpoet.com/article/180-how-bounce-management-works-in-mailpoet-3\"
             target=\"_blank\"
          >";
        // line 13
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Read more.", "support article link label");
        echo "</a>
        </p>
      </th>
      <td>
        <p>
          <input type=\"text\"
            id=\"settings[bounce_email]\"
            name=\"bounce[address]\"
            value=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "bounce", array()), "address", array()), "html", null, true);
        echo "\"
            placeholder=\"bounce@mydomain.com\"
          />
        </p>
      </td>
    </tr>
    <!-- task scheduler -->
    <tr>
      <th scope=\"row\">
        <label>
          ";
        // line 31
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Newsletter task scheduler (cron)");
        echo "
        </label>
        <p class=\"description\">
          ";
        // line 34
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select what will activate your newsletter queue.");
        echo "
          <a href=\"http://docs.mailpoet.com/article/129-what-is-the-newsletter-task-scheduler\"
             target=\"_blank\"
          >";
        // line 37
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Read more.", "support article link label");
        echo "</a>
        </p>
      </th>
      <td>
        <p>
          <label>
            <input
              type=\"radio\"
              name=\"cron_trigger[method]\"
              value=\"";
        // line 46
        echo twig_escape_filter($this->env, $this->getAttribute(($context["cron_trigger"] ?? null), "wordpress", array()), "html", null, true);
        echo "\"
              ";
        // line 47
        if (($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "cron_trigger", array()), "method", array()) == $this->getAttribute(($context["cron_trigger"] ?? null), "wordpress", array()))) {
            // line 48
            echo "              checked=\"checked\"
              ";
        }
        // line 50
        echo "            />";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Visitors to your website (recommended)");
        echo "
          </label>
        </p>
        <p>
          <label>
            <input
              type=\"radio\"
              name=\"cron_trigger[method]\"
              value=\"";
        // line 58
        echo twig_escape_filter($this->env, $this->getAttribute(($context["cron_trigger"] ?? null), "mailpoet", array()), "html", null, true);
        echo "\"
              ";
        // line 59
        if (($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "cron_trigger", array()), "method", array()) == $this->getAttribute(($context["cron_trigger"] ?? null), "mailpoet", array()))) {
            // line 60
            echo "                checked=\"checked\"
                ";
        }
        // line 62
        echo "              />";
        echo MailPoet\Util\Helpers::replaceLinkTags($this->env->getExtension('MailPoet\Twig\I18n')->translate("MailPoet's own script. Doesn't work with [link]these hosts[/link]."), "http://docs.mailpoet.com/article/131-hosts-which-mailpoet-task-scheduler-wont-work", array("target" => "_blank"));
        // line 65
        echo "
          </label>
        </p>
      </td>
    </tr>
    <!-- link tracking -->
    <tr>
      <th scope=\"row\">
        <label>
          ";
        // line 74
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Open and click tracking");
        echo "
        </label>
        <p class=\"description\">
          ";
        // line 77
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Enable or disable open and click tracking.");
        echo "
        </p>
      </th>
      <td>
        <p>
          <label>
            <input
              type=\"radio\"
              name=\"tracking[enabled]\"
              value=\"1\"
              ";
        // line 87
        if ($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "tracking", array()), "enabled", array())) {
            // line 88
            echo "              checked=\"checked\"
              ";
        }
        // line 90
        echo "            />";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yes");
        echo "
          </label>
          &nbsp;
          <label>
            <input
              type=\"radio\"
              name=\"tracking[enabled]\"
              value=\"\"
              ";
        // line 98
        if ( !$this->getAttribute($this->getAttribute(($context["settings"] ?? null), "tracking", array()), "enabled", array())) {
            // line 99
            echo "              checked=\"checked\"
              ";
        }
        // line 101
        echo "            />";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "
          </label>
        </p>
      </td>
    </tr>
    <!-- share anonymous data? -->
    <tr>
      <th scope=\"row\">
        <label>
          ";
        // line 110
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Share anonymous data");
        echo "
        </label>
        <p class=\"description\">
          ";
        // line 113
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Share anonymous data and help us improve the plugin. We appreciate your help!");
        echo "
          <a
            href=\"http://docs.mailpoet.com/article/130-sharing-your-data-with-us\"
            target=\"_blank\"
          >";
        // line 117
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Read more.", "support article link label");
        echo "</a>
        </p>
      </th>
      <td>
        <p>
          <label>
            <input
              type=\"radio\"
              name=\"analytics[enabled]\"
              value=\"1\"
              ";
        // line 127
        if ($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "analytics", array()), "enabled", array())) {
            // line 128
            echo "                checked=\"checked\"
              ";
        }
        // line 130
        echo "            />";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yes");
        echo "
          </label>
          &nbsp;
          <label>
            <input
              type=\"radio\"
              name=\"analytics[enabled]\"
              value=\"\"
              ";
        // line 138
        if ( !$this->getAttribute($this->getAttribute(($context["settings"] ?? null), "analytics", array()), "enabled", array())) {
            // line 139
            echo "                checked=\"checked\"
              ";
        }
        // line 141
        echo "            />";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "
          </label>
        </p>
      </td>
    </tr>
    <!-- reinstall -->
    <tr>
      <th scope=\"row\">
        <label>";
        // line 149
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Reinstall from scratch");
        echo "</label>
        <p class=\"description\">
          ";
        // line 151
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Want to start from the beginning? This will completely delete MailPoet and reinstall it from scratch. Remember: you will lose all of your data!");
        echo "
        </p>
      </th>
      <td>
        <p>
          <a
            id=\"mailpoet_reinstall\"
            class=\"button\"
            href=\"javascript:;\">";
        // line 159
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Reinstall now...");
        echo "</a>
        </p>
      </td>
    </tr>
  </tbody>
</table>
";
    }

    public function getTemplateName()
    {
        return "settings/advanced.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  267 => 159,  256 => 151,  251 => 149,  239 => 141,  235 => 139,  233 => 138,  221 => 130,  217 => 128,  215 => 127,  202 => 117,  195 => 113,  189 => 110,  176 => 101,  172 => 99,  170 => 98,  158 => 90,  154 => 88,  152 => 87,  139 => 77,  133 => 74,  122 => 65,  119 => 62,  115 => 60,  113 => 59,  109 => 58,  97 => 50,  93 => 48,  91 => 47,  87 => 46,  75 => 37,  69 => 34,  63 => 31,  50 => 21,  39 => 13,  33 => 10,  27 => 7,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "settings/advanced.html", "/home/vagrant/harvey/public/blog/wp-content/plugins/mailpoet/views/settings/advanced.html");
    }
}
