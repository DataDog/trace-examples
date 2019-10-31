<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* display/results/additional_fields.twig */
class __TwigTemplate_6018ab1d54845156bdd39af73cfe5adab141672711443a213442f28eb6c750d2 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<input type=\"hidden\" name=\"sql_query\" value=\"";
        echo ($context["sql_query"] ?? null);
        echo "\" />
<input type=\"hidden\" name=\"goto\" value=\"";
        // line 2
        echo twig_escape_filter($this->env, ($context["goto"] ?? null), "html", null, true);
        echo "\" />
";
        // line 4
        echo "<input type=\"hidden\" name=\"pos\" size=\"3\" value=\"";
        echo twig_escape_filter($this->env, ($context["pos"] ?? null), "html", null, true);
        echo "\" />
<input type=\"hidden\" name=\"is_browse_distinct\" value=\"";
        // line 5
        echo twig_escape_filter($this->env, ($context["is_browse_distinct"] ?? null), "html", null, true);
        echo "\" />
";
        // line 6
        echo _gettext("Number of rows:");
        // line 7
        echo PhpMyAdmin\Util::getDropdown("session_max_rows",         // line 9
($context["number_of_rows_choices"] ?? null),         // line 10
($context["max_rows"] ?? null), "", "autosubmit",         // line 13
($context["number_of_rows_placeholder"] ?? null));
        // line 14
        echo "
";
    }

    public function getTemplateName()
    {
        return "display/results/additional_fields.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  55 => 14,  53 => 13,  52 => 10,  51 => 9,  50 => 7,  48 => 6,  44 => 5,  39 => 4,  35 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "display/results/additional_fields.twig", "/var/www/html/templates/display/results/additional_fields.twig");
    }
}
