<?php

/* form/templates/blocks/checkbox.hbs */
class __TwigTemplate_f8c5ad8a8b35a23df7294608d8c5bedd2590ba3ba356643e2be9b0557dfddb0a extends Twig_Template
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
        echo "{{#if params.label}}
  <p>
    <label>{{ params.label }}{{#if params.required}} *{{/if}}</label>
  </p>
{{/if}}
{{#each params.values}}
  <p>
    <label><input class=\"mailpoet_checkbox\" type=\"checkbox\" value=\"1\" {{#if is_checked}}checked=\"checked\"{{/if}} disabled=\"disabled\" />{{ value }}</label>
  </p>
{{/each}}";
    }

    public function getTemplateName()
    {
        return "form/templates/blocks/checkbox.hbs";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "form/templates/blocks/checkbox.hbs", "/home/vagrant/harvey/public/blog/wp-content/plugins/mailpoet/views/form/templates/blocks/checkbox.hbs");
    }
}
