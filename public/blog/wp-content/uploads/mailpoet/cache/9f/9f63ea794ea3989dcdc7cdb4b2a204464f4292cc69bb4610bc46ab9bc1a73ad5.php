<?php

/* settings/mta.html */
class __TwigTemplate_186e89b0622592f58eb47f5b0869c33855ab781afd5a2edd267a39ca4fcfa040 extends Twig_Template
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
        $context["intervals"] = array(0 => 1, 1 => 2, 2 => 5, 3 => 10, 4 => 15, 5 => 30);
        // line 2
        $context["default_frequency"] = array("website" => array("emails" => 25, "interval" => 5), "smtp" => array("emails" => 100, "interval" => 5));
        // line 12
        echo "
<!-- mta: group -->
<input
  type=\"hidden\"
  id=\"mta_group\"
  name=\"mta_group\"
  value=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute(($context["settings"] ?? null), "mta_group", array()), "html", null, true);
        echo "\"
/>
<input
  type=\"hidden\"
  id=\"mailpoet_smtp_provider\"
  name=\"mailpoet_smtp_provider\"
  value=\"";
        // line 24
        echo twig_escape_filter($this->env, $this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()), "html", null, true);
        echo "\"
/>
<!-- mta: method -->
<input
  type=\"hidden\"
  id=\"mta_method\"
  name=\"mta[method]\"
  value=\"";
        // line 31
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "method", array()), "html", null, true);
        echo "\"
/>

<!-- mta: sending frequency -->
<input
  type=\"hidden\"
  id=\"mta_frequency_emails\"
  name=\"mta[frequency][emails]\"
  value=\"";
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "frequency", array()), "emails", array()), "html", null, true);
        echo "\"
/>
<input
  type=\"hidden\"
  id=\"mta_frequency_interval\"
  name=\"mta[frequency][interval]\"
  value=\"";
        // line 45
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "frequency", array()), "interval", array()), "html", null, true);
        echo "\"
/>

<!-- mta: mailpoet sending service key -->
<input
  type=\"hidden\"
  id=\"mailpoet_api_key\"
  name=\"mta[mailpoet_api_key]\"
  value=\"";
        // line 53
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "mailpoet_api_key", array()), "html", null, true);
        echo "\"
/>

<!-- smtp: available sending methods -->
<ul class=\"mailpoet_sending_methods\">
  <li
    data-group=\"mailpoet\"
    ";
        // line 60
        if (($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "mailpoet")) {
            echo "class=\"mailpoet_active\"";
        }
        // line 61
        echo "  >
    <div class=\"mailpoet_sending_method_description\">
      <h3>
        <img
          src=\"";
        // line 65
        echo twig_escape_filter($this->env, ($context["assets_url"] ?? null), "html", null, true);
        echo "/img/mailpoet_logo.png\"
          alt=\"MailPoet\"
          width=\"137\"
          height=\"54\"
        />
      </h3>

      <p
        class=\"mailpoet_description";
        // line 73
        if (($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "mailpoet")) {
            echo " mailpoet_hidden";
        }
        echo "\"
        id=\"mailpoet_sending_method_active_text\"
      >
        <strong>";
        // line 76
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("You're now sending with MailPoet!");
        echo "</strong>
        <br />
        ";
        // line 78
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Great, you're all set up. Your emails will now be sent quickly and reliably!");
        echo "
      </p>

      <div
        class=\"mailpoet_description";
        // line 82
        if (($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "mailpoet")) {
            echo " mailpoet_hidden";
        }
        echo "\"
        id=\"mailpoet_sending_method_inactive_text\"
      >
        <strong>";
        // line 85
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Solve all of your sending problems!");
        echo "</strong>
        <br />
        ";
        // line 87
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Let MailPoet send your emails and get the Premium features for as little as 10 dollars or euros per month.");
        echo "
        <ul class=\"sending-method-benefits mailpoet_success\">
          <li class=\"mailpoet_success_item\">";
        // line 89
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Reach the inbox, not the spam box.");
        echo "
          <li class=\"mailpoet_success_item\">";
        // line 90
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Send emails up to 20 times faster than other sending methods.");
        echo "
          <li class=\"mailpoet_success_item\">";
        // line 91
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("SPF & DKIM signatures are already set up! No configuration required.");
        echo "
          <li class=\"mailpoet_success_item\">";
        // line 92
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Automatically remove invalid and bounced addresses (bounce handling) to keep your lists clean.");
        echo "
        </ul>
        <a
          href=\"";
        // line 95
        echo admin_url("admin.php?page=mailpoet-premium");
        echo "\"
          class=\"button button-primary\"
          target=\"_blank\"
        >";
        // line 98
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Find out more");
        echo "</a>
      </div>
    </div>
    <div class=\"mailpoet_status\">
      <span>";
        // line 102
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Activated");
        echo "</span>

      <div class=\"mailpoet_actions\">
        <button
          type=\"button\"
          class=\"mailpoet_sending_service_activate button-secondary\"
        ";
        // line 108
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "mailpoet") ||  !($context["mss_key_valid"] ?? null))) {
            echo " disabled=\"disabled\"";
        }
        // line 109
        echo "        >";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Activate");
        echo "</button>
      </div>
    </div>

  </li>
  <li
    data-group=\"other\"
    ";
        // line 116
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "smtp") || ($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "website"))) {
            echo "class=\"mailpoet_active\"";
        }
        // line 117
        echo "  >
    <div class=\"mailpoet_sending_method_description\">
      <h3>";
        // line 119
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Other");
        echo "</h3>
      <div class=\"mailpoet_description\">
        <strong>";
        // line 121
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Send with your website or third party");
        echo "</strong>
        <br />
        ";
        // line 123
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Choose to send emails through your website (not recommended) or a third party sender.");
        echo "
        <ul class=\"sending-method-benefits mailpoet_error\">
          <li class=\"mailpoet_error_item\">";
        // line 125
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("You'll probably end up in spam.");
        echo "
          <li class=\"mailpoet_error_item\">";
        // line 126
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Sending speed is limited by your web host.");
        echo "
          <li class=\"mailpoet_error_item\">";
        // line 127
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Manual configuration of SPF and DKIM required.");
        echo "
          <li class=\"mailpoet_error_item\">";
        // line 128
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Bounce handling is available, but only with an extra add-on.");
        echo "
        </ul>
      </div>
    </div>
    <div class=\"mailpoet_status\">
      <span>";
        // line 133
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Activated");
        echo "</span>
      <div class=\"mailpoet_actions\">
        <a
          class=\"button-secondary\"
          href=\"#mta/other\">";
        // line 137
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Configure");
        echo "</a>
      </div>
    </div>
  </li>
</ul>

<p class=\"mailpoet_sending_methods_help help\">
  ";
        // line 144
        echo MailPoet\Util\Helpers::replaceLinkTags($this->env->getExtension('MailPoet\Twig\I18n')->translate("Need help to pick? [link]Check out the comparison table of sending methods[/link]."), "http://beta.docs.mailpoet.com/article/181-comparison-table-of-sending-methods", array("target" => "_blank"));
        // line 147
        echo "
</p>

<div id=\"mailpoet_sending_method_setup\">

  <!-- Sending Method: Other -->
  <div class=\"mailpoet_sending_method\" data-group=\"other\" style=\"display:none;\">
    <table class=\"form-table\">
      <tr>
        <th scope=\"row\">
          <label for=\"mailpoet_smtp_method\">
            ";
        // line 158
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Method");
        echo "
          </label>
        </th>
        <td>
          <!-- smtp provider -->
          <select
            id=\"mailpoet_smtp_method\"
            name=\"smtp_provider\"
          >
            <option data-interval=\"5\" data-emails=\"25\" value=\"server\">
              ";
        // line 168
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Your web host / web server");
        echo "
            </option>
            <option data-interval=\"5\" data-emails=\"100\" value=\"manual\"
              ";
        // line 172
        if (($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "smtp")) {
            // line 174
            echo "              selected=\"selected\"
              ";
        }
        // line 176
        echo "            >
              ";
        // line 177
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("SMTP");
        echo "
            </option>
            <!-- providers -->
            <optgroup label=\"";
        // line 180
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select your provider");
        echo "\">
              ";
        // line 181
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["hosts"] ?? null), "smtp", array()));
        foreach ($context['_seq'] as $context["host_key"] => $context["host"]) {
            // line 182
            echo "              <option
                value=\"";
            // line 183
            echo twig_escape_filter($this->env, $context["host_key"], "html", null, true);
            echo "\"
                data-emails=\"";
            // line 184
            echo twig_escape_filter($this->env, $this->getAttribute($context["host"], "emails", array()), "html", null, true);
            echo "\"
                data-interval=\"";
            // line 185
            echo twig_escape_filter($this->env, $this->getAttribute($context["host"], "interval", array()), "html", null, true);
            echo "\"
                data-fields=\"";
            // line 186
            echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($context["host"], "fields", array()), ","), "html", null, true);
            echo "\"
              ";
            // line 187
            if (($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) == $context["host_key"])) {
                // line 188
                echo "              selected=\"selected\"
              ";
            }
            // line 190
            echo "              >";
            echo twig_escape_filter($this->env, $this->getAttribute($context["host"], "name", array()), "html", null, true);
            echo "</option>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['host_key'], $context['host'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 192
        echo "            </optgroup>
          </select>
        </td>
      </tr>
      <tr id=\"mailpoet_sending_method_host\"
        ";
        // line 198
        if (($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "smtp")) {
            // line 200
            echo "        style=\"display:none;\"
        ";
        }
        // line 202
        echo "      >
        <th scope=\"row\">
          <label for=\"mailpoet_web_host\">
            ";
        // line 205
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Your web host");
        echo "
          </label>
        </th>
        <td>
          <p>
            <!-- sending frequency -->
            <select
              id=\"mailpoet_web_host\"
              name=\"web_host\"
            >

              <!-- web hosts -->
              <option
                value=\"manual\"
                data-emails=\"25\"
                data-interval=\"5\"
                label=\"";
        // line 221
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Not listed (default)");
        echo "\"
              >
              ";
        // line 223
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["hosts"] ?? null), "web", array()));
        foreach ($context['_seq'] as $context["host_key"] => $context["host"]) {
            // line 224
            echo "              <option
                value=\"";
            // line 225
            echo twig_escape_filter($this->env, $context["host_key"], "html", null, true);
            echo "\"
                data-emails=\"";
            // line 226
            echo twig_escape_filter($this->env, $this->getAttribute($context["host"], "emails", array()), "html", null, true);
            echo "\"
                data-interval=\"";
            // line 227
            echo twig_escape_filter($this->env, $this->getAttribute($context["host"], "interval", array()), "html", null, true);
            echo "\"
              ";
            // line 228
            if (($this->getAttribute(($context["settings"] ?? null), "web_host", array()) == $context["host_key"])) {
                // line 229
                echo "              selected=\"selected\"
              ";
            }
            // line 231
            echo "              >";
            echo twig_escape_filter($this->env, $this->getAttribute($context["host"], "name", array()), "html", null, true);
            echo "</option>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['host_key'], $context['host'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 233
        echo "            </select>
          </p>

        </td>
      </tr>
      <tr>
        <th scope=\"row\">
          <label for=\"mailpoet_web_host\">
            ";
        // line 241
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Sending frequency");
        echo "
          </label>
        </th>
        <td>
          <p>
            <!-- sending frequency -->
            <select
              id=\"mailpoet_sending_frequency\"
              name=\"mailpoet_sending_frequency\"
            >
              <option value=\"auto\">
                ";
        // line 252
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Recommended");
        echo "
              </option>
              <option value=\"manual\"
                ";
        // line 256
        if ( !($this->getAttribute(($context["settings"] ?? null), "mailpoet_sending_frequency", array()) == "auto")) {
            // line 258
            echo "                selected=\"selected\"
                ";
        }
        // line 260
        echo "              >
                ";
        // line 261
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("I'll set my own frequency");
        echo "
              </option>
            </select>
            <span id=\"mailpoet_recommended_daily_emails\"></span>
          </p>
          <div id=\"mailpoet_sending_frequency_manual\"
            ";
        // line 268
        if (($this->getAttribute(($context["settings"] ?? null), "mailpoet_sending_frequency", array()) == "auto")) {
            // line 270
            echo "              style=\"display: none\"
            ";
        }
        // line 272
        echo "          >
            <p>
              <input
                id=\"other_frequency_emails\"
                type=\"number\"
                min=\"1\"
                max=\"1000\"
              ";
        // line 279
        if ($this->getAttribute($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "frequency", array()), "emails", array())) {
            // line 280
            echo "                value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "frequency", array()), "emails", array()), "html", null, true);
            echo "\"
              ";
        } else {
            // line 282
            echo "                value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["default_frequency"] ?? null), "website", array()), "emails", array()), "html", null, true);
            echo "\"
              ";
        }
        // line 284
        echo "              />
              ";
        // line 285
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("emails");
        echo "
              <select id=\"other_frequency_interval\">
                ";
        // line 287
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["intervals"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["interval"]) {
            // line 288
            echo "                <option
                  value=\"";
            // line 289
            echo twig_escape_filter($this->env, $context["interval"], "html", null, true);
            echo "\"
                ";
            // line 291
            if (( !$this->getAttribute($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "frequency", array()), "interval", array()) && (            // line 292
$context["interval"] == $this->getAttribute($this->getAttribute(($context["default_frequency"] ?? null), "website", array()), "interval", array())))) {
                // line 294
                echo "                selected=\"selected\"
                ";
            }
            // line 296
            echo "                ";
            if (($this->getAttribute($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "frequency", array()), "interval", array()) == $context["interval"])) {
                // line 297
                echo "                selected=\"selected\"
                ";
            }
            // line 299
            echo "                >
                ";
            // line 300
            echo $this->env->getExtension('MailPoet\Twig\Functions')->getSendingFrequency($context["interval"]);
            echo "
                ";
            // line 301
            if (($context["interval"] == $this->getAttribute($this->getAttribute(($context["default_frequency"] ?? null), "website", array()), "interval", array()))) {
                // line 302
                echo "                (";
                echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("recommended");
                echo ")
                ";
            }
            // line 304
            echo "                </option>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interval'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 306
        echo "              </select>
              <span id=\"mailpoet_other_daily_emails\"></span>
            </p>
            <br />
            <p>
              ";
        // line 311
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("<strong>Warning!</strong> You may break the terms of your web host or provider by sending more than the recommended emails per day. Contact your host if you want to send more.");
        echo "
            </p>
          </div>
        </td>
      </tr>
      <tr class=\"mailpoet_smtp_field\" data-field=\"host\"
        ";
        // line 318
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "manual"))) {
            // line 320
            echo "        style=\"display:none;\"
        ";
        }
        // line 322
        echo "      >
        <th scope=\"row\">
          <label for=\"settings[mta_host]\">
            ";
        // line 325
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("SMTP Hostname");
        echo "
          </label>
          <p class=\"description\">
            ";
        // line 328
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("e.g.: smtp.mydomain.com");
        echo "
          </p>
        </th>
        <td>
          <input
            type=\"text\"
            class=\"regular-text\"
            id=\"settings[mta_host]\"
            name=\"mta[host]\"
            value=\"";
        // line 337
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "host", array()), "html", null, true);
        echo "\" />
        </td>
      </tr>
      <!-- smtp: port -->
      <tr class=\"mailpoet_smtp_field\" data-field=\"port\"
        ";
        // line 343
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "manual"))) {
            // line 345
            echo "        style=\"display:none;\"
        ";
        }
        // line 347
        echo "      >
        <th scope=\"row\">
          <label for=\"settings[mta_port]\">
            ";
        // line 350
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("SMTP Port");
        echo "
          </label>
        </th>
        <td>
          <input
            type=\"number\"
            max=\"65535\"
            min=\"1\"
            maxlength=\"5\"
            style=\"width:5em;\"
            id=\"settings[mta_port]\"
            name=\"mta[port]\"
            value=\"";
        // line 362
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "port", array()), "html", null, true);
        echo "\"
          />
        </td>
      </tr>

      <!-- smtp: amazon region -->
      <tr class=\"mailpoet_aws_field\" data-field=\"region\"
        ";
        // line 370
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "AmazonSES"))) {
            // line 372
            echo "        style=\"display:none;\"
        ";
        }
        // line 374
        echo "      >
        <th scope=\"row\">
          <label for=\"settings[mta_region]\">
            ";
        // line 377
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Region");
        echo "
          </label>
        </th>
        <td>
          <select
            id=\"settings[mta_region]\"
            name=\"mta[region]\"
            value=\"";
        // line 384
        if (($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "smtp")) {
            // line 385
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "region", array()), "html", null, true);
        }
        // line 386
        echo "\"
          >
            ";
        // line 388
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute(($context["hosts"] ?? null), "smtp", array()), "AmazonSES", array()), "regions", array()));
        foreach ($context['_seq'] as $context["region"] => $context["server"]) {
            // line 389
            echo "            <option
              value=\"";
            // line 390
            echo twig_escape_filter($this->env, $context["server"], "html", null, true);
            echo "\"
            ";
            // line 391
            if (($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "region", array()) == $context["server"])) {
                // line 392
                echo "            selected=\"selected\"
            ";
            }
            // line 394
            echo "            >
            ";
            // line 395
            echo twig_escape_filter($this->env, $context["region"], "html", null, true);
            echo "
            </option>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['region'], $context['server'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 398
        echo "          </select>
        </td>
      </tr>

      <!-- smtp: amazon access_key -->
      <tr class=\"mailpoet_aws_field\" data-field=\"access_key\"
        ";
        // line 405
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "AmazonSES"))) {
            // line 407
            echo "        style=\"display:none;\"
        ";
        }
        // line 409
        echo "      >
        <th scope=\"row\">
          <label for=\"settings[mta_access_key]\">
            ";
        // line 412
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Access Key");
        echo "
          </label>
        </th>
        <td>
          <input
            type=\"text\"
            class=\"regular-text\"
            id=\"settings[mta_access_key]\"

            name=\"mta[access_key]\"
            value=\"";
        // line 422
        if (($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "smtp")) {
            // line 423
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "access_key", array()), "html", null, true);
        }
        // line 424
        echo "\"
          />
        </td>
      </tr>

      <!-- smtp: amazon secret_key -->
      <tr class=\"mailpoet_aws_field\" data-field=\"secret_key\"
        ";
        // line 432
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "AmazonSES"))) {
            // line 434
            echo "        style=\"display:none;\"
        ";
        }
        // line 436
        echo "      >
        <th scope=\"row\">
          <label for=\"settings[mta_secret_key]\">
            ";
        // line 439
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Secret Key");
        echo "
          </label>
        </th>
        <td>
          <input
            type=\"text\"
            class=\"regular-text\"
            id=\"settings[mta_secret_key]\"

            name=\"mta[secret_key]\"
            value=\"";
        // line 449
        if (($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) == "smtp")) {
            // line 450
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "secret_key", array()), "html", null, true);
        }
        // line 451
        echo "\"
          />
        </td>
      </tr>

      <!-- smtp: api key -->
      <tr class=\"mailpoet_sendgrid_field\" data-field=\"api_key\"
        ";
        // line 459
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "SendGrid"))) {
            // line 461
            echo "        style=\"display:none;\"
        ";
        }
        // line 463
        echo "      >
        <th scope=\"row\">
          <label for=\"settings[mta_api_key]\">
            ";
        // line 466
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("API Key");
        echo "
          </label>
        </th>
        <td>
          <input
            type=\"text\"
            class=\"regular-text\"
            id=\"settings[mta_api_key]\"
            name=\"mta[api_key]\"
            value=\"";
        // line 475
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "api_key", array()), "html", null, true);
        echo "\"
          />
        </td>
      </tr>

      <!-- smtp: login -->
      <tr id=\"mta_login\" class=\"mailpoet_smtp_field\" data-field=\"login\"
        ";
        // line 483
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "manual"))) {
            // line 485
            echo "        style=\"display:none;\"
        ";
        }
        // line 487
        echo "      >
        <th scope=\"row\">
          <label for=\"settings[mta_login]\">
            ";
        // line 490
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Login");
        echo "
          </label>
        </th>
        <td>
          <input
            type=\"text\"
            class=\"regular-text\"
            id=\"settings[mta_login]\"
            name=\"mta[login]\"
            value=\"";
        // line 499
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "login", array()), "html", null, true);
        echo "\"
          />
        </td>
      </tr>
      <!-- smtp: password -->
      <tr id=\"mta_password\" class=\"mailpoet_smtp_field\" data-field=\"password\"
        ";
        // line 506
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "manual"))) {
            // line 508
            echo "        style=\"display:none;\"
        ";
        }
        // line 510
        echo "      >
        <th scope=\"row\">
          <label for=\"settings[mta_password]\">
            ";
        // line 513
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Password");
        echo "
          </label>
        </th>
        <td>
          <input
            type=\"password\"
            class=\"regular-text\"
            id=\"settings[mta_password]\"
            name=\"mta[password]\"
            value=\"";
        // line 522
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "password", array()), "html", null, true);
        echo "\"
          />
        </td>
      </tr>
      <!-- smtp: security protocol -->
      <tr class=\"mailpoet_smtp_field\" data-field=\"encryption\"
        ";
        // line 529
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "manual"))) {
            // line 531
            echo "        style=\"display:none;\"
        ";
        }
        // line 533
        echo "      >
        <th scope=\"row\">
          <label for=\"settings[mta_encryption]\">
            ";
        // line 536
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Secure Connection");
        echo "
          </label>
        </th>
        <td>
          <select id=\"settings[mta_encryption]\" name=\"mta[encryption]\">
            <option value=\"\">";
        // line 541
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "</option>
            <option
              value=\"ssl\"
            ";
        // line 544
        if (($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "encryption", array()) == "ssl")) {
            // line 545
            echo "            selected=\"selected\"
            ";
        }
        // line 547
        echo "            >SSL</option>
            <option
              value=\"tls\"
            ";
        // line 550
        if (($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "encryption", array()) == "tls")) {
            // line 551
            echo "            selected=\"selected\"
            ";
        }
        // line 553
        echo "            >TLS</option>
          </select>
        </td>
      </tr>
      <!-- smtp: authentication -->
      <tr class=\"mailpoet_smtp_field\" data-field=\"authentication\"
        ";
        // line 560
        if ((($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "smtp") || ($this->getAttribute(($context["settings"] ?? null), "smtp_provider", array()) != "manual"))) {
            // line 562
            echo "        style=\"display:none;\"
        ";
        }
        // line 564
        echo "      >
        <th scope=\"row\">
          <label>
            ";
        // line 567
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Authentication");
        echo "
          </label>
          <p class=\"description\">
            ";
        // line 570
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Leave this option set to Yes. Only a tiny portion of SMTP services prefer Authentication to be turned off.");
        echo "
          </p>
        </th>
        <td>
          <label>
            <input
              type=\"radio\"
              value=\"1\"
              name=\"mta[authentication]\"
            ";
        // line 580
        if (( !$this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "authentication", array()) || ($this->getAttribute($this->getAttribute(        // line 581
($context["settings"] ?? null), "mta", array()), "authentication", array()) == "1"))) {
            // line 582
            echo "            checked=\"checked\"
            ";
        }
        // line 584
        echo "            />";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yes");
        echo "
          </label>
          &nbsp;
          <label>
            <input
              type=\"radio\"
              value=\"-1\"
              name=\"mta[authentication]\"
            ";
        // line 592
        if (($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "mta", array()), "authentication", array()) == "-1")) {
            // line 593
            echo "            checked=\"checked\"
            ";
        }
        // line 595
        echo "            />";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "
          </label>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

  <table class=\"form-table\">
    <tbody>
      <!-- SPF -->
      <tr id=\"mailpoet_mta_spf\">
        <th scope=\"row\">
          <label>
            ";
        // line 609
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("SPF Signature (Highly recommended!)");
        echo "
          </label>
          <p class=\"description\">
            ";
        // line 612
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("This improves your delivery rate by verifying that you're allowed to send emails from your domain.");
        echo "
          </p>
        </th>
        <td>
          <p>
            ";
        // line 617
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("SPF is set up in your DNS. Read your host's support documentation for more information.");
        echo "
          </p>
        </td>
      </tr>
      <!-- test method -->
      <tr>
        <th scope=\"row\">
          <label for=\"mailpoet_mta_test_email\">
            ";
        // line 625
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Test the sending method");
        echo "
          </label>
        </th>
        <td>
          <p>
            <input
              type=\"text\"
              id=\"mailpoet_mta_test_email\"
              class=\"regular-text\"
              value=\"";
        // line 634
        echo twig_escape_filter($this->env, $this->getAttribute(($context["current_user"] ?? null), "user_email", array()), "html", null, true);
        echo "\"
            />
            <a
              id=\"mailpoet_mta_test\"
              class=\"button-secondary\"
            >";
        // line 639
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Send a test email");
        echo "</a>

            <span id=\"tooltip-test\"></span>
          </p>
        </td>
      </tr>
      <!-- activate or cancel -->
      <tr>
        <th scope=\"row\">
          <p>
            <a
              href=\"javascript:;\"
              class=\"mailpoet_mta_setup_save button button-primary\"
            >";
        // line 652
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Activate");
        echo "</a>
            &nbsp;
            <a
              href=\"javascript:;\"
              class=\"mailpoet_mta_setup_cancel\"
            >";
        // line 657
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("or Cancel");
        echo "</a>
          </p>
        </th>
        <td></td>
      </tr>
    </tbody>
  </table>
</div>

<script type=\"text/javascript\">
  jQuery(function(\$) {
    var tooltip = '";
        // line 668
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, MailPoet\Util\Helpers::replaceLinkTags($this->env->getExtension('MailPoet\Twig\I18n')->translate("Didn't receive the test email? Read our [link]quick guide[/link] to sending issues."), "http://beta.docs.mailpoet.com/article/146-my-newsletters-are-not-being-received", array("target" => "_blank")), "js"), "html", null, true);
        // line 670
        echo "'

    MailPoet.helpTooltip.show(document.getElementById(\"tooltip-test\"), {
      tooltipId: \"tooltip-settings-test\",
      tooltip: tooltip,
    });

    var sending_frequency_template =
      Handlebars.compile(\$('#mailpoet_sending_frequency_template').html());

    // om dom loaded
    \$(function() {
      // constrain number type inputs
      \$('input[type=\"number\"]').on('keyup', function() {
        var currentValue = \$(this).val();
        if(!currentValue) return;

        var minValue = \$(this).attr('min');
        var maxValue = \$(this).attr('max');
        \$(this).val(Math.min(Math.max(minValue, currentValue), maxValue));
      });

      // testing sending method
      \$('#mailpoet_mta_test').on('click', function() {
        // get test email and include it in data
        var recipient = \$('#mailpoet_mta_test_email').val();

        var settings = jQuery('#mailpoet_settings_form').serializeObject();
        var mailer = settings.mta;

        mailer.method = getMethodFromGroup(\$('#mta_group').val());

        // check that we have a from address
        if(settings.sender.address.length === 0) {
          // validation
          return MailPoet.Notice.error(
            '";
        // line 706
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("The email could not be sent. Make sure the option \"Email notifications\" has a FROM email address in the Basics tab."), "js"), "html", null, true);
        echo "',
            { scroll: true, static: true }
          );
        }

        MailPoet.Modal.loading(true);
        MailPoet.Ajax.post({
          api_version: window.mailpoet_api_version,
          endpoint: 'mailer',
          action: 'send',
          data: {
            mailer: mailer,
            newsletter: {
              subject: \"";
        // line 719
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("This is a Sending Method Test");
        echo "\",
              body: {
                html: \"<p>";
        // line 721
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yup, it works! You can start blasting away emails to the moon.");
        echo "</p>\",
                text: \"";
        // line 722
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yup, it works! You can start blasting away emails to the moon.");
        echo "\"
              }
            },
            subscriber: recipient
          }
        }).always(function() {
          MailPoet.Modal.loading(false);
        }).done(function(response) {
          MailPoet.Notice.success(
            \"";
        // line 731
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("The email has been sent! Check your inbox."), "js"), "html", null, true);
        echo "\",
            { scroll: true }
          );
          trackTestEmailSent(true);
        }).fail(function(response) {
          if (response.errors.length > 0) {
            MailPoet.Notice.error(
              response.errors.map(function(error) { return _.escape(error.message); }),
              { scroll: true }
            );
          }
          trackTestEmailSent(false);
        });
      });

      // sending frequency update based on selected provider
      \$('#mailpoet_web_host').on('change keyup', renderHostSendingFrequency);

      // update manual sending frequency when values are changed
      \$('#other_frequency_emails').on('change keyup', function() {
        updateSendingFrequency('other');
      });
      \$('#other_frequency_interval').on('change keyup', function() {
        updateSendingFrequency('other');
      });

      // save configuration of a sending method
      \$('.mailpoet_sending_service_activate').on('click', function() {
        \$('#mta_group').val('mailpoet');
        saveSendingMethodConfiguration('mailpoet');
      });
      \$('.mailpoet_mta_setup_save').on('click', function() {
        \$('#mailpoet_smtp_method').trigger(\"change\");
        \$('#other_frequency_emails').trigger(\"change\");
        // get selected method
        var group = \$('.mailpoet_sending_method:visible').data('group');
        saveSendingMethodConfiguration(group);
      });
      \$('#mailpoet_smtp_method').on('change keyup', renderHostsSelect);
      \$('#mailpoet_sending_frequency').on('change keyup', sendingFrequencyMethodUpdated);

      ";
        // line 772
        if (($this->getAttribute(($context["settings"] ?? null), "mta_group", array()) != "mailpoet")) {
            // line 773
            echo "        \$('#mailpoet_smtp_method').trigger(\"change\");
        \$('#other_frequency_emails').trigger(\"change\");
      ";
        }
        // line 776
        echo "
      function saveSendingMethodConfiguration(group) {

        // set sending method
        if(group === undefined) {
          MailPoet.Notice.error(
            \"";
        // line 782
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("You have selected an invalid sending method."), "js"), "html", null, true);
        echo "\"
          );
        } else {
          // set new sending method active
          setSendingMethodGroup(group);

          // enforce signup confirmation depending on selected group
          setSignupConfirmation(group);

          // back to selection of sending methods
          MailPoet.Router.navigate('mta', { trigger: true });

          // save settings
          \$('.mailpoet_settings_submit > input').trigger('click');
        }
      }

      function setSignupConfirmation(group) {
        if (group === 'mailpoet') {
          // force signup confirmation (select \"Yes\")
          \$('.mailpoet_signup_confirmation[value=\"1\"]').attr('checked', true);
          \$('.mailpoet_signup_confirmation[value=\"\"]').attr('checked', false);

          // hide radio inputs
          \$('#mailpoet_signup_confirmation_input').hide();

          // show mailpoet specific notice
          \$('#mailpoet_signup_confirmation_notice').show();

          // show signup confirmation options
          \$('#mailpoet_signup_options').show();
        } else {
          // show radio inputs
          \$('#mailpoet_signup_confirmation_input').show();

          // hide mailpoet specific notice
          \$('#mailpoet_signup_confirmation_notice').hide();
        }
      }

      function setSendingMethodGroup(group) {
        // deactivate other sending methods
        \$('.mailpoet_sending_methods .mailpoet_active')
          .removeClass('mailpoet_active');

        // set active sending method
        \$('.mailpoet_sending_methods li[data-group=\"'+group+'\"]')
          .addClass('mailpoet_active');

        var method = getMethodFromGroup(\$('#mta_group').val());

        \$('#mta_method').val(method);

        // set MailPoet method description
        \$('#mailpoet_sending_method_active_text')
          .toggleClass('mailpoet_hidden', group !== 'mailpoet');
        \$('#mailpoet_sending_method_inactive_text')
          .toggleClass('mailpoet_hidden', group === 'mailpoet');

        // Hide server error notices
        \$('.mailpoet_notice_server').hide();

        updateMSSActivationUI();
      }

      function getMethodFromGroup(group) {
        var group = group || 'website';
        switch(group) {
          case 'mailpoet':
            return 'MailPoet';
          break;
          case 'website':
            return 'PHPMail';
          break;
          case 'smtp':
            var method = \$('#mailpoet_smtp_provider').val();
            if(method === 'manual') {
              return 'SMTP';
            }
            return method;
          break;
        }
      }

      // cancel configuration of a sending method
      \$('.mailpoet_mta_setup_cancel').on('click', function() {
        // back to selection of sending methods
        MailPoet.Router.navigate('mta', { trigger: true });
      });

      // render sending frequency form
      \$('#mailpoet_smtp_provider').trigger('change');
      \$('#mailpoet_web_host').trigger('change');

      function trackTestEmailSent(success) {
        MailPoet.trackEvent(
          'User has sent a test email from Settings',
          {
            'Sending was successful': !!success,
            'Sending method type': mailer.method,
            'MailPoet Free version': window.mailpoet_version
          }
        );
      }

      \$('.mailpoet_sending_methods_help a').on('click', function() {
        MailPoet.trackEvent(
          'User has clicked to view the sending comparison table',
          {'MailPoet Free version': window.mailpoet_version}
        );
      });
    });

    function setProviderForm() {
      // check provider
      var provider = \$(this).find('option:selected').first();
      var fields = provider.data('fields');

      if(fields === undefined) {
        fields = [
          'host',
          'port',
          'login',
          'password',
          'authentication',
          'encryption'
        ];
      } else {
        fields = fields.split(',');
      }

      \$('.mailpoet_smtp_field').hide();

      fields.map(function(field) {
        \$('.mailpoet_smtp_field[data-field=\"'+field+'\"]').show();
      });

      // update sending frequency
      renderSMTPSendingFrequency(provider);
    }

    function renderSMTPSendingFrequency() {
      // set sending frequency
      var emails = \$('#smtp_frequency_emails').val();
      var interval = \$('#smtp_frequency_interval').val();
      setSendingFrequency({
        output: '#mailpoet_smtp_daily_emails',
        only_daily: true,
        emails: emails,
        interval: interval
      });
      \$('#mta_frequency_emails').val(emails);
      \$('#mta_frequency_interval').val(interval);
    }

    function sendingFrequencyMethodUpdated() {
      var method = \$(this).find('option:selected').first();
      var sendingMethod = \$('#mailpoet_smtp_method').find('option:selected').first().val();
      if(method.val() === \"manual\") {
        \$('#mailpoet_sending_frequency_manual').show();
        \$('#mailpoet_recommended_daily_emails').hide();
        \$('#other_frequency_emails').trigger(\"change\");
      } else {
        \$('#mailpoet_sending_frequency_manual').hide();
        if(sendingMethod !== \"server\") {
          \$('#mailpoet_recommended_daily_emails').show();
        }
        \$('#mailpoet_smtp_method').trigger(\"change\");
      }
    }

    function renderHostsSelect() {
      var method = \$(this).find('option:selected').first();
      var val = method.val();

      if(val === \"server\") {
        \$('#mailpoet_sending_method_host').show();
        \$('#mta_group').val('website');
      } else {
        \$('#mailpoet_sending_method_host').hide();
      }
      if(val === \"manual\") {
        \$('.mailpoet_smtp_field').show();
        \$('#mta_group').val('smtp');
        \$('#mailpoet_smtp_provider').val('manual');
      } else {
        \$('.mailpoet_smtp_field').hide();
      }
      if(val === \"AmazonSES\") {
        \$('.mailpoet_aws_field').show();
        \$('#mta_group').val('smtp');
        \$('#mailpoet_smtp_provider').val('AmazonSES');
      } else {
        \$('.mailpoet_aws_field').hide();
      }
      if(val === \"SendGrid\") {
        \$('.mailpoet_sendgrid_field').show();
        \$('#mta_group').val('smtp');
        \$('#mailpoet_smtp_provider').val('SendGrid');
      } else {
        \$('.mailpoet_sendgrid_field').hide();
      }
      var emails = method.data('emails');
      var interval = method.data('interval');
      if(val === \"server\") {
        emails = \$('#mailpoet_web_host').find('option:selected').first().data('emails');
        interval = \$('#mailpoet_web_host').find('option:selected').first().data('interval');
      }
      const frequencyMethod = \$('#mailpoet_sending_frequency').find('option:selected').first().val();
      if(frequencyMethod === \"manual\") {
        \$('#mailpoet_recommended_daily_emails').hide();
        emails = \$('#other_frequency_emails').val();
        interval = \$('#other_frequency_interval').val();
      } else {
        \$('#mailpoet_recommended_daily_emails').show();
      }
      setSendingFrequency({
        output: '#mailpoet_recommended_daily_emails',
        only_daily: false,
        emails: emails,
        interval: interval
      });
      \$('#mta_frequency_emails').val(emails);
      \$('#mta_frequency_interval').val(interval);
    }

    function renderHostSendingFrequency() {

      var host = \$(this).find('option:selected').first();
      var frequencyType = \$(\"#mailpoet_sending_frequency\").find('option:selected').first().val();

      var emails =
        host.data('emails') || ";
        // line 1014
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["default_frequency"] ?? null), "website", array()), "emails", array()), "html", null, true);
        echo ";
      var interval =
        host.data('interval') || ";
        // line 1016
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["default_frequency"] ?? null), "website", array()), "interval", array()), "html", null, true);
        echo ";
      var fields =
        host.data('fields') || '';

      if (frequencyType === \"manual\") {
        return;
      } else {
        setSendingFrequency({
          output: '#mailpoet_recommended_daily_emails',
          only_daily: false,
          emails: emails,
          interval: interval
        });
      }

      \$('#mta_frequency_emails').val(emails);
      \$('#mta_frequency_interval').val(interval);
    }

    function updateSendingFrequency(method) {
      // get emails
      var options = {
        only_daily: true,
        emails: \$('#'+method+'_frequency_emails').val(),
        interval: \$('#'+method+'_frequency_interval').val()
      };

      var MINUTES_PER_DAY = 1440;
      var SECONDS_PER_DAY = 86400;

      options.daily_emails = ~~(
        (MINUTES_PER_DAY / options.interval) * options.emails
      );

      options.emails_per_second = (~~(
        ((options.daily_emails) / 86400) * 10)
      ) / 10;


      // format daily emails number according to locale
      options.daily_emails = options.daily_emails.toLocaleString();

      \$('#mailpoet_'+method+'_daily_emails').html(
        sending_frequency_template(options)
      );

      // update actual sending frequency values
      \$('#mta_frequency_emails').val(options.emails);
      \$('#mta_frequency_interval').val(options.interval);
    }

    function setSendingFrequency(options) {
      options.daily_emails = ~~((1440 / options.interval) * options.emails);

      // format daily emails number according to locale
      options.daily_emails = options.daily_emails.toLocaleString();

      \$(options.output).html(
        sending_frequency_template(options)
      );
    }

    Handlebars.registerHelper('format_time', function(value, block) {
      var label = null;
      var labels = {
        minute: \"";
        // line 1081
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("every minute");
        echo "\",
        minutes: \"";
        // line 1082
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("every %1\$d minutes");
        echo "\",
        hour: \"";
        // line 1083
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("every hour");
        echo "\",
        hours: \"";
        // line 1084
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("every %1\$d hours");
        echo "\"
      };

      // cast time as int
      value = ~~(value);

      // format time depending on the value
      if(value >= 60) {
        // we're dealing with hours
        if(value === 60) {
          label = labels.hour;
        } else {
          label = labels.hours;
        }
        value /= 60;
      } else {
        // we're dealing with minutes
        if(value === 1) {
          label = labels.minute;
        } else {
          label = labels.minutes;
        }
      }

      if(label !== null) {
        return label.replace('%1\$d', value);
      } else {
        return value;
      }
    });
  });

  // enable/disable MSS method activate button and notice
  function updateMSSActivationUI() {
    var \$ = jQuery;
    var group = \$('.mailpoet_sending_methods .mailpoet_active').data('group');
    var key_valid = !\$('.mailpoet_mss_key_valid').hasClass('mailpoet_hidden');
    var activation_possible = group !== 'mailpoet' && key_valid;
    \$('.mailpoet_sending_service_activate').prop('disabled', !activation_possible);
    \$('.mailpoet_mss_activate_notice').toggle(activation_possible);
  }
</script>

";
        // line 1127
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "mailpoet_sending_frequency_template", "settings/templates/sending_frequency.hbs");
        // line 1130
        echo "
";
    }

    public function getTemplateName()
    {
        return "settings/mta.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1602 => 1130,  1600 => 1127,  1554 => 1084,  1550 => 1083,  1546 => 1082,  1542 => 1081,  1474 => 1016,  1469 => 1014,  1234 => 782,  1226 => 776,  1221 => 773,  1219 => 772,  1175 => 731,  1163 => 722,  1159 => 721,  1154 => 719,  1138 => 706,  1100 => 670,  1098 => 668,  1084 => 657,  1076 => 652,  1060 => 639,  1052 => 634,  1040 => 625,  1029 => 617,  1021 => 612,  1015 => 609,  997 => 595,  993 => 593,  991 => 592,  979 => 584,  975 => 582,  973 => 581,  972 => 580,  960 => 570,  954 => 567,  949 => 564,  945 => 562,  943 => 560,  935 => 553,  931 => 551,  929 => 550,  924 => 547,  920 => 545,  918 => 544,  912 => 541,  904 => 536,  899 => 533,  895 => 531,  893 => 529,  884 => 522,  872 => 513,  867 => 510,  863 => 508,  861 => 506,  852 => 499,  840 => 490,  835 => 487,  831 => 485,  829 => 483,  819 => 475,  807 => 466,  802 => 463,  798 => 461,  796 => 459,  787 => 451,  784 => 450,  782 => 449,  769 => 439,  764 => 436,  760 => 434,  758 => 432,  749 => 424,  746 => 423,  744 => 422,  731 => 412,  726 => 409,  722 => 407,  720 => 405,  712 => 398,  703 => 395,  700 => 394,  696 => 392,  694 => 391,  690 => 390,  687 => 389,  683 => 388,  679 => 386,  676 => 385,  674 => 384,  664 => 377,  659 => 374,  655 => 372,  653 => 370,  643 => 362,  628 => 350,  623 => 347,  619 => 345,  617 => 343,  609 => 337,  597 => 328,  591 => 325,  586 => 322,  582 => 320,  580 => 318,  571 => 311,  564 => 306,  557 => 304,  551 => 302,  549 => 301,  545 => 300,  542 => 299,  538 => 297,  535 => 296,  531 => 294,  529 => 292,  528 => 291,  524 => 289,  521 => 288,  517 => 287,  512 => 285,  509 => 284,  503 => 282,  497 => 280,  495 => 279,  486 => 272,  482 => 270,  480 => 268,  471 => 261,  468 => 260,  464 => 258,  462 => 256,  456 => 252,  442 => 241,  432 => 233,  423 => 231,  419 => 229,  417 => 228,  413 => 227,  409 => 226,  405 => 225,  402 => 224,  398 => 223,  393 => 221,  374 => 205,  369 => 202,  365 => 200,  363 => 198,  356 => 192,  347 => 190,  343 => 188,  341 => 187,  337 => 186,  333 => 185,  329 => 184,  325 => 183,  322 => 182,  318 => 181,  314 => 180,  308 => 177,  305 => 176,  301 => 174,  299 => 172,  293 => 168,  280 => 158,  267 => 147,  265 => 144,  255 => 137,  248 => 133,  240 => 128,  236 => 127,  232 => 126,  228 => 125,  223 => 123,  218 => 121,  213 => 119,  209 => 117,  205 => 116,  194 => 109,  190 => 108,  181 => 102,  174 => 98,  168 => 95,  162 => 92,  158 => 91,  154 => 90,  150 => 89,  145 => 87,  140 => 85,  132 => 82,  125 => 78,  120 => 76,  112 => 73,  101 => 65,  95 => 61,  91 => 60,  81 => 53,  70 => 45,  61 => 39,  50 => 31,  40 => 24,  31 => 18,  23 => 12,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "settings/mta.html", "/home/vagrant/harvey/public/blog/wp-content/plugins/mailpoet/views/settings/mta.html");
    }
}
