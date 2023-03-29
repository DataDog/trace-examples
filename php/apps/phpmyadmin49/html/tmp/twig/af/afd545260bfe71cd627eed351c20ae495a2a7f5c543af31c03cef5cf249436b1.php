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

/* server/collations/charsets.twig */
class __TwigTemplate_0286b83dc9164e7f96ef2db90ca81596d18de16e9a9735a733ff7cff2cbe5636 extends \Twig\Template
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
        echo "<div id=\"div_mysql_charset_collations\">
    <table class=\"data noclick\">
        <thead>
            <tr>
                <th id=\"collationHeader\">";
        // line 5
        echo _gettext("Collation");
        echo "</th>
                <th>";
        // line 6
        echo _gettext("Description");
        echo "</th>
            </tr>
        </thead>
        ";
        // line 9
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["mysql_charsets"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["current_charset"]) {
            // line 10
            echo "            <tr>
                <th colspan=\"2\" class=\"right\">
                    ";
            // line 12
            echo twig_escape_filter($this->env, $context["current_charset"], "html", null, true);
            echo "
                    ";
            // line 13
            if ( !twig_test_empty($this->getAttribute(($context["mysql_charsets_desc"] ?? null), $context["current_charset"], [], "array"))) {
                // line 14
                echo "                        (<em>";
                echo twig_escape_filter($this->env, $this->getAttribute(($context["mysql_charsets_desc"] ?? null), $context["current_charset"], [], "array"), "html", null, true);
                echo "</em>)
                    ";
            }
            // line 16
            echo "                </th>
            </tr>
            ";
            // line 18
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["mysql_collations"] ?? null), $context["current_charset"], [], "array"));
            foreach ($context['_seq'] as $context["_key"] => $context["current_collation"]) {
                // line 19
                echo "                <tr class=\"";
                echo ((($this->getAttribute(($context["mysql_dft_collations"] ?? null), $context["current_charset"], [], "array") == $context["current_collation"])) ? (" marked") : (""));
                echo "\">
                    <td>";
                // line 20
                echo twig_escape_filter($this->env, $context["current_collation"], "html", null, true);
                echo "</td>
                    <td>";
                // line 21
                echo twig_escape_filter($this->env, PhpMyAdmin\Charsets::getCollationDescr($context["current_collation"]), "html", null, true);
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['current_collation'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 24
            echo "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['current_charset'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "    </table>
</div>
";
    }

    public function getTemplateName()
    {
        return "server/collations/charsets.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  98 => 25,  92 => 24,  83 => 21,  79 => 20,  74 => 19,  70 => 18,  66 => 16,  60 => 14,  58 => 13,  54 => 12,  50 => 10,  46 => 9,  40 => 6,  36 => 5,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "server/collations/charsets.twig", "/var/www/html/templates/server/collations/charsets.twig");
    }
}
