<?php

/* form/templates/blocks/textarea.hbs */
class __TwigTemplate_0dd1cb298add3f0247e93fcb83a5de8a1c581e673da8be9e5dd1658dd4d4ebd3 extends Twig_Template
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
        echo "<p>
  {{#unless params.label_within}}<label>{{ params.label }}{{#if params.required}} *{{/if}}</label>{{/unless}}
  <textarea placeholder=\"{{#if params.label_within}}{{ params.label }}{{#if params.required}} *{{/if}}{{/if}}\" disabled=\"disabled\" rows=\"{{#if params.lines}}{{ params.lines }}{{else}}1{{/if}}\">{{ params.text }}</textarea>
</p>";
    }

    public function getTemplateName()
    {
        return "form/templates/blocks/textarea.hbs";
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
        return new Twig_Source("", "form/templates/blocks/textarea.hbs", "/home/vagrant/harvey/public/blog/wp-content/plugins/mailpoet/views/form/templates/blocks/textarea.hbs");
    }
}
