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

/* display/results/comment_for_row.twig */
class __TwigTemplate_a90ac975cb59dc0c574edd7f4917e275ed292de854bf670e7fc7b0885d0fa3da extends \Twig\Template
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
        if (($this->getAttribute(($context["comments_map"] ?? null), $this->getAttribute(($context["fields_meta"] ?? null), "table", []), [], "array", true, true) && $this->getAttribute($this->getAttribute(        // line 2
($context["comments_map"] ?? null), $this->getAttribute(($context["fields_meta"] ?? null), "table", []), [], "array", false, true), $this->getAttribute(($context["fields_meta"] ?? null), "name", []), [], "array", true, true))) {
            // line 3
            echo "    <span class=\"tblcomment\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["comments_map"] ?? null), $this->getAttribute(($context["fields_meta"] ?? null), "table", []), [], "array"), $this->getAttribute(($context["fields_meta"] ?? null), "name", []), [], "array"), "html", null, true);
            echo "\">
        ";
            // line 4
            if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["comments_map"] ?? null), $this->getAttribute(($context["fields_meta"] ?? null), "table", []), [], "array"), $this->getAttribute(($context["fields_meta"] ?? null), "name", []), [], "array")) > ($context["limit_chars"] ?? null))) {
                // line 5
                echo "            ";
                echo twig_escape_filter($this->env, twig_slice($this->env, $this->getAttribute($this->getAttribute(($context["comments_map"] ?? null), $this->getAttribute(($context["fields_meta"] ?? null), "table", []), [], "array"), $this->getAttribute(($context["fields_meta"] ?? null), "name", []), [], "array"), 0, ($context["limit_chars"] ?? null)), "html", null, true);
                echo "…
        ";
            } else {
                // line 7
                echo "            ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["comments_map"] ?? null), $this->getAttribute(($context["fields_meta"] ?? null), "table", []), [], "array"), $this->getAttribute(($context["fields_meta"] ?? null), "name", []), [], "array"), "html", null, true);
                echo "
        ";
            }
            // line 9
            echo "    </span>
";
        }
    }

    public function getTemplateName()
    {
        return "display/results/comment_for_row.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  52 => 9,  46 => 7,  40 => 5,  38 => 4,  33 => 3,  31 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "display/results/comment_for_row.twig", "/var/www/html/templates/display/results/comment_for_row.twig");
    }
}
