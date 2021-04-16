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

/* server/databases/databases_footer.twig */
class __TwigTemplate_bd15b9d039e5f3a87edf34ca2f5f7752a54a85724032894d0e3ae1d436d892d5 extends \Twig\Template
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
        echo "<tfoot>
    <tr>
        ";
        // line 3
        if ((($context["is_superuser"] ?? null) || ($context["allow_user_drop_database"] ?? null))) {
            // line 4
            echo "            <th></th>
        ";
        }
        // line 6
        echo "        <th>
            ";
        // line 7
        echo _gettext("Total");
        echo ": <span id=\"filter-rows-count\">";
        // line 8
        echo twig_escape_filter($this->env, ($context["database_count"] ?? null), "html", null, true);
        // line 9
        echo "</span>
        </th>
        ";
        // line 11
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["column_order"] ?? null));
        foreach ($context['_seq'] as $context["stat_name"] => $context["stat"]) {
            if (twig_in_filter($context["stat_name"], twig_get_array_keys_filter(($context["first_database"] ?? null)))) {
                // line 12
                echo "            ";
                if (($this->getAttribute($context["stat"], "format", [], "array") === "byte")) {
                    // line 13
                    echo "                ";
                    $context["byte_format"] = PhpMyAdmin\Util::formatByteDown($this->getAttribute($context["stat"], "footer", [], "array"), 3, 1);
                    // line 14
                    echo "                ";
                    $context["value"] = $this->getAttribute(($context["byte_format"] ?? null), 0, [], "array");
                    // line 15
                    echo "                ";
                    $context["unit"] = $this->getAttribute(($context["byte_format"] ?? null), 1, [], "array");
                    // line 16
                    echo "            ";
                } elseif (($this->getAttribute($context["stat"], "format", [], "array") === "number")) {
                    // line 17
                    echo "                ";
                    $context["value"] = PhpMyAdmin\Util::formatNumber($this->getAttribute($context["stat"], "footer", [], "array"), 0);
                    // line 18
                    echo "            ";
                } else {
                    // line 19
                    echo "                ";
                    $context["value"] = htmlentities($this->getAttribute($context["stat"], "footer", [], "array"), 0);
                    // line 20
                    echo "            ";
                }
                // line 21
                echo "
            <th class=\"value\">
                ";
                // line 23
                if ($this->getAttribute($context["stat"], "description_function", [], "array", true, true)) {
                    // line 24
                    echo "                    <dfn title=\"";
                    echo twig_escape_filter($this->env, PhpMyAdmin\Charsets::getCollationDescr($this->getAttribute($context["stat"], "footer", [], "array")), "html", null, true);
                    echo "\">
                        ";
                    // line 25
                    echo twig_escape_filter($this->env, ($context["value"] ?? null), "html", null, true);
                    echo "
                    </dfn>
                ";
                } else {
                    // line 28
                    echo "                    ";
                    echo twig_escape_filter($this->env, ($context["value"] ?? null), "html", null, true);
                    echo "
                ";
                }
                // line 30
                echo "            </th>
            ";
                // line 31
                if (($this->getAttribute($context["stat"], "format", [], "array") === "byte")) {
                    // line 32
                    echo "                <th class=\"unit\">";
                    echo twig_escape_filter($this->env, ($context["unit"] ?? null), "html", null, true);
                    echo "</th>
            ";
                }
                // line 34
                echo "        ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['stat_name'], $context['stat'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 35
        echo "        ";
        if (($context["master_replication"] ?? null)) {
            // line 36
            echo "            <th></th>
        ";
        }
        // line 38
        echo "        ";
        if (($context["slave_replication"] ?? null)) {
            // line 39
            echo "            <th></th>
        ";
        }
        // line 41
        echo "        <th></th>
    </tr>
</tfoot>
</table>
</div>

";
        // line 48
        if ((($context["is_superuser"] ?? null) || ($context["allow_user_drop_database"] ?? null))) {
            // line 49
            echo "    ";
            $this->loadTemplate("select_all.twig", "server/databases/databases_footer.twig", 49)->display(twig_to_array(["pma_theme_image" =>             // line 50
($context["pma_theme_image"] ?? null), "text_dir" =>             // line 51
($context["text_dir"] ?? null), "form_name" => "dbStatsForm"]));
            // line 54
            echo "
    ";
            // line 55
            echo PhpMyAdmin\Util::getButtonOrImage("", "mult_submit ajax", _gettext("Drop"), "b_deltbl");
            // line 60
            echo "
";
        }
        // line 62
        echo "
";
        // line 64
        if (twig_test_empty(($context["dbstats"] ?? null))) {
            // line 65
            echo "    ";
            echo call_user_func_array($this->env->getFunction('Message_notice')->getCallable(), [_gettext("Note: Enabling the database statistics here might cause heavy traffic between the web server and the MySQL server.")]);
            echo "
    <ul>
        <li class=\"li_switch_dbstats\">
            <a href=\"server_databases.php\" data-post=\"";
            // line 68
            echo PhpMyAdmin\Url::getCommon(["dbstats" => "1"], "");
            echo "\" title=\"";
            echo _gettext("Enable statistics");
            echo "\">
                <strong>";
            // line 69
            echo _gettext("Enable statistics");
            echo "</strong>
            </a>
        </li>
    </ul>
";
        }
        // line 74
        echo "</form>
</div>
";
    }

    public function getTemplateName()
    {
        return "server/databases/databases_footer.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  189 => 74,  181 => 69,  175 => 68,  168 => 65,  166 => 64,  163 => 62,  159 => 60,  157 => 55,  154 => 54,  152 => 51,  151 => 50,  149 => 49,  147 => 48,  139 => 41,  135 => 39,  132 => 38,  128 => 36,  125 => 35,  118 => 34,  112 => 32,  110 => 31,  107 => 30,  101 => 28,  95 => 25,  90 => 24,  88 => 23,  84 => 21,  81 => 20,  78 => 19,  75 => 18,  72 => 17,  69 => 16,  66 => 15,  63 => 14,  60 => 13,  57 => 12,  52 => 11,  48 => 9,  46 => 8,  43 => 7,  40 => 6,  36 => 4,  34 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "server/databases/databases_footer.twig", "/var/www/html/templates/server/databases/databases_footer.twig");
    }
}
