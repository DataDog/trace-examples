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

/* server/variables/variable_row.twig */
class __TwigTemplate_65fc106c39fb8a83ac1560d4d36bb04e8d59b324cc93bb23a9e03ae3600ad611 extends \Twig\Template
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
        echo "<tr class=\"var-row ";
        echo twig_escape_filter($this->env, ($context["row_class"] ?? null), "html", null, true);
        echo "\" data-filter-row=\"";
        echo twig_escape_filter($this->env, twig_upper_filter($this->env, ($context["name"] ?? null)), "html", null, true);
        echo "\">
    <td class=\"var-action\">
    ";
        // line 3
        if (($context["editable"] ?? null)) {
            // line 4
            echo "        <a href=\"#\" data-variable=\"";
            echo twig_escape_filter($this->env, ($context["name"] ?? null), "html", null, true);
            echo "\" class=\"editLink\">";
            echo PhpMyAdmin\Util::getIcon("b_edit", _gettext("Edit"));
            echo "</a>
    ";
        } else {
            // line 6
            echo "        <span title=\"";
            echo _gettext("This is a read-only variable and can not be edited");
            echo "\" class=\"read_only_var\">
            ";
            // line 7
            echo PhpMyAdmin\Util::getIcon("bd_edit", _gettext("Edit"));
            echo "
        </span>
    ";
        }
        // line 10
        echo "    </td>
    <td class=\"var-name\">
    ";
        // line 12
        if ((($context["doc_link"] ?? null) != null)) {
            // line 13
            echo "        <span title=\"";
            echo twig_escape_filter($this->env, twig_replace_filter(($context["name"] ?? null), ["_" => " "]), "html", null, true);
            echo "\">
            ";
            // line 14
            echo PhpMyAdmin\Util::showMySQLDocu($this->getAttribute(($context["doc_link"] ?? null), 1, [], "array"), false, (($this->getAttribute(($context["doc_link"] ?? null), 2, [], "array") . "_") . $this->getAttribute(($context["doc_link"] ?? null), 0, [], "array")), true);
            echo "
            ";
            // line 15
            echo twig_replace_filter(twig_escape_filter($this->env, ($context["name"] ?? null)), ["_" => "&nbsp;"]);
            echo "
            </a>
        </span>
    ";
        } else {
            // line 19
            echo "        ";
            echo twig_escape_filter($this->env, twig_replace_filter(($context["name"] ?? null), ["_" => " "]), "html", null, true);
            echo "
    ";
        }
        // line 21
        echo "    </td>
    <td class=\"var-value value";
        // line 22
        echo ((($context["is_superuser"] ?? null)) ? (" editable") : (""));
        echo "\">
    ";
        // line 23
        if ((($context["is_html_formatted"] ?? null) == false)) {
            // line 24
            echo "        ";
            echo twig_replace_filter(twig_escape_filter($this->env, ($context["value"] ?? null)), ["," => ",&#8203;"]);
            echo "
    ";
        } else {
            // line 26
            echo "        ";
            echo ($context["value"] ?? null);
            echo "
    ";
        }
        // line 28
        echo "    </td>
</tr>
";
    }

    public function getTemplateName()
    {
        return "server/variables/variable_row.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  108 => 28,  102 => 26,  96 => 24,  94 => 23,  90 => 22,  87 => 21,  81 => 19,  74 => 15,  70 => 14,  65 => 13,  63 => 12,  59 => 10,  53 => 7,  48 => 6,  40 => 4,  38 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "server/variables/variable_row.twig", "/var/www/html/templates/server/variables/variable_row.twig");
    }
}
