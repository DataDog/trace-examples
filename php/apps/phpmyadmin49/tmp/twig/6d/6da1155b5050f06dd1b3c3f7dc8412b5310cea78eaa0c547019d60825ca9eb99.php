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

/* database/multi_table_query/form.twig */
class __TwigTemplate_390143d23c358a2ef02fcaf3975731e4d8f2caa167cbe61963ab6e5a591104c4 extends \Twig\Template
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
        $this->loadTemplate("secondary_tabs.twig", "database/multi_table_query/form.twig", 1)->display(twig_to_array(["url_params" => ["db" =>         // line 3
($context["db"] ?? null)], "sub_tabs" => [0 => ["link" => "db_multi_table_query.php", "text" => _gettext("Multi-table query")], 1 => ["link" => "db_qbe.php", "text" => _gettext("Query by example")]]]));
        // line 16
        echo PhpMyAdmin\Util::getDivForSliderEffect("query_div", _gettext("Query window"), "open");
        echo "
<form action=\"\" id=\"query_form\">
    <input type=\"hidden\" id=\"db_name\" value=\"";
        // line 18
        echo twig_escape_filter($this->env, ($context["db"] ?? null), "html", null, true);
        echo "\">
    <fieldset>
        ";
        // line 20
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["tables"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["table"]) {
            // line 21
            echo "            <div style=\"display:none\" id=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["table"], "hash", [], "any", false, false, false, 21), "html", null, true);
            echo "\">
                <option value=\"*\">*</option>
                ";
            // line 23
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["table"], "columns", [], "any", false, false, false, 23));
            foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                // line 24
                echo "                    <option value=\"";
                echo twig_escape_filter($this->env, $context["column"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["column"], "html", null, true);
                echo "</option>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 26
            echo "            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['table'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        echo "
        ";
        // line 29
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(0, ($context["default_no_of_columns"] ?? null)));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["id"]) {
            // line 30
            echo "            ";
            if (($context["id"] == 0)) {
                echo "<div style=\"display:none\" id=\"new_column_layout\">";
            }
            // line 31
            echo "            <fieldset style=\"display:inline\" class=\"column_details\">
                <select style=\"display:inline\" class=\"tableNameSelect\">
                    <option value=\"\">";
            // line 33
            echo _gettext("select table");
            echo "</option>
                    ";
            // line 34
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_array_keys_filter(($context["tables"] ?? null)));
            foreach ($context['_seq'] as $context["_key"] => $context["table"]) {
                // line 35
                echo "                        <option value=\"";
                echo twig_escape_filter($this->env, $context["table"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["table"], "html", null, true);
                echo "</option>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['table'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 37
            echo "                </select>
                <span>.</span>
                <select style=\"display:inline\" class=\"columnNameSelect\">
                    <option value=\"\">";
            // line 40
            echo _gettext("select column");
            echo "</option>
                </select>
                <br>
                <input type=\"checkbox\" checked=\"checked\" class=\"show_col\">
                <span>";
            // line 44
            echo _gettext("Show");
            echo "</span>
                <br>
                <input type=\"text\" placeholder=\"";
            // line 46
            echo _gettext("Table alias");
            echo "\" class=\"table_alias\">
                <input type=\"text\" placeholder=\"";
            // line 47
            echo _gettext("Column alias");
            echo "\" class=\"col_alias\">
                <br>
                <input type=\"checkbox\"
                    title=\"";
            // line 50
            echo _gettext("Use this column in criteria");
            echo "\"
                    class=\"criteria_col\">
                ";
            // line 52
            $this->loadTemplate("div_for_slider_effect.twig", "database/multi_table_query/form.twig", 52)->display(twig_array_merge($context, ["id" => ("criteria_div" .             // line 53
$context["id"]), "initial_sliders_state" => "closed", "message" => _gettext("criteria")]));
            // line 57
            echo "                <div>
                    <table>

                        <tr class=\"sort_order\" style=\"background:none\">
                            <td>";
            // line 61
            echo _gettext("Sort");
            echo "</td>
                            <td><input type=\"radio\" name=\"sort[";
            // line 62
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "]\">";
            echo _gettext("Ascending");
            echo "</td>
                            <td><input type=\"radio\" name=\"sort[";
            // line 63
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "]\">";
            echo _gettext("Descending");
            echo "</td>
                        </tr>

                        <tr class=\"logical_operator\" style=\"background:none;display:none\">
                            <td>";
            // line 67
            echo _gettext("Add as");
            echo "</td>
                            <td>
                                <input type=\"radio\"
                                    name=\"logical_op[";
            // line 70
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "]\"
                                    value=\"AND\"
                                    class=\"logical_op\"
                                    checked=\"checked\">
                                AND
                            </td>
                            <td>
                                <input type=\"radio\"
                                    name=\"logical_op[";
            // line 78
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "]\"
                                    value=\"OR\"
                                    class=\"logical_op\">
                                OR
                            </td>
                        </tr>

                        <tr style=\"background:none\">
                            <td>Op </td>
                            <td>
                                <select class=\"criteria_op\">
                                    <option value=\"=\">=</option>
                                    <option value=\">\">&gt;</option>
                                    <option value=\">=\">&gt;=</option>
                                    <option value=\"<\">&lt;</option>
                                    <option value=\"<=\">&lt;=</option>
                                    <option value=\"!=\">!=</option>
                                    <option value=\"LIKE\">LIKE</option>
                                    <option value=\"LIKE %...%\">LIKE %...%</option>
                                    <option value=\"NOT LIKE\">NOT LIKE</option>
                                    <option value=\"IN (...)\">IN (...)</option>
                                    <option value=\"NOT IN (...)\">NOT IN (...)</option>
                                    <option value=\"BETWEEN\">BETWEEN</option>
                                    <option value=\"NOT BETWEEN\">NOT BETWEEN</option>
                                    <option value=\"IS NULL\">IS NULL</option>
                                    <option value=\"IS NOT NULL\">IS NOT NULL</option>
                                    <option value=\"REGEXP\">REGEXP</option>
                                    <option value=\"REGEXP ^...\$\">REGEXP ^...\$</option>
                                    <option value=\"NOT REGEXP\">NOT REGEXP</option>
                                </select>
                            </td>
                            <td>
                                <select class=\"criteria_rhs\">
                                    <option value=\"text\">";
            // line 111
            echo _gettext("Text");
            echo "</option>
                                    <option value=\"anotherColumn\">";
            // line 112
            echo _gettext("Another column");
            echo "</option>
                                </select>
                            </td>
                        </tr>

                        <tr class=\"rhs_table\" style=\"display:none;background:none\">
                            <td></td>
                            <td>
                                <select  class=\"tableNameSelect\">
                                    <option value=\"\">";
            // line 121
            echo _gettext("select table");
            echo "</option>
                                    ";
            // line 122
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_array_keys_filter(($context["tables"] ?? null)));
            foreach ($context['_seq'] as $context["_key"] => $context["table"]) {
                // line 123
                echo "                                        <option value=\"";
                echo twig_escape_filter($this->env, $context["table"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["table"], "html", null, true);
                echo "</option>
                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['table'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 125
            echo "                                </select><span>.</span>
                            </td>
                            <td>
                                <select style=\"display:inline\" class=\"columnNameSelect\">
                                    <option value=\"\">";
            // line 129
            echo _gettext("select column");
            echo "</option>
                                </select>
                            </td>
                        </tr>

                        <tr style=\"background:none\" class=\"rhs_text\">
                            <td></td>
                            <td colspan=\"2\">
                                <input type=\"text\"
                                    style=\"width:91%\"
                                    class=\"rhs_text_val\"
                                    placeholder=\"";
            // line 140
            echo _gettext("Enter criteria as free text");
            echo "\">
                            </td>
                        </tr>

                        </table>
                    </div>
                </div>
                <a href=\"#\"
                    title=\"";
            // line 148
            echo _gettext("Remove this column");
            echo "\"
                    style=\"float:right;color:red\"
                    class=\"removeColumn\">
                    X
                </a>
            </fieldset>
            ";
            // line 154
            if (($context["id"] == 0)) {
                echo "</div>";
            }
            // line 155
            echo "        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['id'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 156
        echo "
        <fieldset style=\"display:inline\">
            <input type=\"button\" value=\"";
        // line 158
        echo _gettext("+ Add column");
        echo "\" id=\"add_column_button\">
        </fieldset>

        <fieldset>
            ";
        // line 162
        ob_start(function () { return ''; });
        // line 163
        echo "                <textarea id=\"MultiSqlquery\"
                    cols=\"80\"
                    rows=\"4\"
                    style=\"float:left\"
                    name=\"sql_query\"
                    dir=\"ltr\">
                </textarea>
            ";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
        // line 171
        echo "        </fieldset>
    </fieldset>

    <fieldset class=\"tblFooters\">
        <input type=\"button\" id=\"update_query_button\" value=\"";
        // line 175
        echo _gettext("Update query");
        echo "\">
        <input type=\"button\" id=\"submit_query\" value=\"";
        // line 176
        echo _gettext("Submit query");
        echo "\">
    </fieldset>
</form>
</div>";
        // line 180
        echo "<div id=\"sql_results\"></div>
";
    }

    public function getTemplateName()
    {
        return "database/multi_table_query/form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  375 => 180,  369 => 176,  365 => 175,  359 => 171,  349 => 163,  347 => 162,  340 => 158,  336 => 156,  322 => 155,  318 => 154,  309 => 148,  298 => 140,  284 => 129,  278 => 125,  267 => 123,  263 => 122,  259 => 121,  247 => 112,  243 => 111,  207 => 78,  196 => 70,  190 => 67,  181 => 63,  175 => 62,  171 => 61,  165 => 57,  163 => 53,  162 => 52,  157 => 50,  151 => 47,  147 => 46,  142 => 44,  135 => 40,  130 => 37,  119 => 35,  115 => 34,  111 => 33,  107 => 31,  102 => 30,  85 => 29,  82 => 28,  75 => 26,  64 => 24,  60 => 23,  54 => 21,  50 => 20,  45 => 18,  40 => 16,  38 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/multi_table_query/form.twig", "/var/www/public/phpMyAdmin49/templates/database/multi_table_query/form.twig");
    }
}
