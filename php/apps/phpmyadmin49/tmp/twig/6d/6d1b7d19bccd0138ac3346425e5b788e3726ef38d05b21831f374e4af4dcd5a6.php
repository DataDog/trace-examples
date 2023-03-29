<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* javascript/display.twig */
class __TwigTemplate_7d97a5480160abf1f548d54564ddb81ad80588146a8826fdfa41a35caad0a22e extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<script type=\"text/javascript\">
if (typeof configInlineParams === \"undefined\" || !Array.isArray(configInlineParams)) configInlineParams = [];
configInlineParams.push(function() {
";
        // line 4
        echo twig_join_filter(($context["js_array"] ?? null), ";
");
        echo ";
});
if (typeof configScriptLoaded !== \"undefined\" && configInlineParams) loadInlineConfig();
</script>
";
    }

    public function getTemplateName()
    {
        return "javascript/display.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 4,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "javascript/display.twig", "/var/www/public/phpMyAdmin49/templates/javascript/display.twig");
    }
}
