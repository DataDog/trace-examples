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

/* radio_fields.twig */
class __TwigTemplate_30af0e66e0dd43376228d17c11de54b39670f76007216b237b3ab8d547a83292 extends \Twig\Template
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
        if ( !twig_test_empty(($context["class"] ?? null))) {
            // line 2
            echo "<div class=\"";
            echo twig_escape_filter($this->env, ($context["class"] ?? null), "html", null, true);
            echo "\">
";
        }
        // line 4
        echo "<input type=\"radio\" name=\"";
        echo twig_escape_filter($this->env, ($context["html_field_name"] ?? null), "html", null, true);
        echo "\" id=\"";
        echo ($context["html_field_id"] ?? null);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, ($context["choice_value"] ?? null), "html", null, true);
        echo "\"";
        echo ((($context["checked"] ?? null)) ? (" checked=\"checked\"") : (""));
        echo " />
<label for=\"";
        // line 5
        echo ($context["html_field_id"] ?? null);
        echo "\">";
        echo ((($context["escape_label"] ?? null)) ? (twig_escape_filter($this->env, ($context["choice_label"] ?? null))) : (($context["choice_label"] ?? null)));
        echo "</label>
";
        // line 6
        if (($context["is_line_break"] ?? null)) {
            // line 7
            echo "<br />
";
        }
        // line 9
        if ( !twig_test_empty(($context["class"] ?? null))) {
            // line 10
            echo "</div>
";
        }
    }

    public function getTemplateName()
    {
        return "radio_fields.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  63 => 10,  61 => 9,  57 => 7,  55 => 6,  49 => 5,  38 => 4,  32 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "radio_fields.twig", "/var/www/html/templates/radio_fields.twig");
    }
}
