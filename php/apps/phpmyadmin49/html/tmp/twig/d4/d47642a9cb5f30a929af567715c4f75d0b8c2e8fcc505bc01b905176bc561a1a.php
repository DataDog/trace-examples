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

/* server/plugins/section.twig */
class __TwigTemplate_5587a2f996e64d2ed89b6730e6f06df6a0850bdf74e5a5c0dd0a764cab7b60c5 extends \Twig\Template
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
        echo "<div class=\"responsivetable\">
    <table class=\"data_full_width\" id=\"plugins-";
        // line 3
        echo twig_escape_filter($this->env, preg_replace("/[^a-z]/", "", twig_lower_filter($this->env, ($context["plugin_type"] ?? null))), "html", null, true);
        echo "\">
        <caption class=\"tblHeaders\">
            ";
        // line 5
        echo twig_escape_filter($this->env, ($context["plugin_type"] ?? null), "html", null, true);
        echo "
        </caption>
        <thead>
            <tr>
                <th>";
        // line 9
        echo _gettext("Plugin");
        echo "</th>
                <th>";
        // line 10
        echo _gettext("Description");
        echo "</th>
                <th>";
        // line 11
        echo _gettext("Version");
        echo "</th>
                <th>";
        // line 12
        echo _gettext("Author");
        echo "</th>
                <th>";
        // line 13
        echo _gettext("License");
        echo "</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 17
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["plugin_list"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["plugin"]) {
            // line 18
            echo "                <tr class=\"noclick\">
                    <th>
                        ";
            // line 20
            echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "plugin_name", [], "array"), "html", null, true);
            echo "
                        ";
            // line 21
            if ( !$this->getAttribute($context["plugin"], "is_active", [], "array")) {
                // line 22
                echo "                            <small class=\"attention\">
                                ";
                // line 23
                echo _gettext("disabled");
                // line 24
                echo "                            </small>
                        ";
            }
            // line 26
            echo "                    </th>
                    <td>";
            // line 27
            echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "plugin_description", [], "array"), "html", null, true);
            echo "</td>
                    <td>";
            // line 28
            echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "plugin_type_version", [], "array"), "html", null, true);
            echo "</td>
                    <td>";
            // line 29
            echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "plugin_author", [], "array"), "html", null, true);
            echo "</td>
                    <td>";
            // line 30
            echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "plugin_license", [], "array"), "html", null, true);
            echo "</td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['plugin'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 33
        echo "        </tbody>
    </table>
</div>
";
    }

    public function getTemplateName()
    {
        return "server/plugins/section.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  115 => 33,  106 => 30,  102 => 29,  98 => 28,  94 => 27,  91 => 26,  87 => 24,  85 => 23,  82 => 22,  80 => 21,  76 => 20,  72 => 18,  68 => 17,  61 => 13,  57 => 12,  53 => 11,  49 => 10,  45 => 9,  38 => 5,  33 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "server/plugins/section.twig", "/var/www/html/templates/server/plugins/section.twig");
    }
}
