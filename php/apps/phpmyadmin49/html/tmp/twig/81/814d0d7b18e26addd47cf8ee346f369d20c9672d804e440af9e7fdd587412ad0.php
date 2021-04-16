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

/* console/display.twig */
class __TwigTemplate_aa9c44acb3883beab4703bf5383ef3caae1fa727be27cd36bb727562d221704b extends \Twig\Template
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
        echo "<div id=\"pma_console_container\">
    <div id=\"pma_console\">
        ";
        // line 4
        echo "        ";
        $this->loadTemplate("console/toolbar.twig", "console/display.twig", 4)->display(twig_to_array(["parent_div_classes" => "collapsed", "content_array" => [0 => [0 => "switch_button console_switch", 1 => _gettext("Console"), "image" =>         // line 7
($context["image"] ?? null)], 1 => [0 => "button clear", 1 => _gettext("Clear")], 2 => [0 => "button history", 1 => _gettext("History")], 3 => [0 => "button options", 1 => _gettext("Options")], 4 => ((        // line 11
(isset($context["cfg_bookmark"]) || array_key_exists("cfg_bookmark", $context))) ? ([0 => "button bookmarks", 1 => _gettext("Bookmarks")]) : (null)), 5 => [0 => "button debug hide", 1 => _gettext("Debug SQL")]]]));
        // line 15
        echo "        ";
        // line 16
        echo "        <div class=\"content\">
            <div class=\"console_message_container\">
                <div class=\"message welcome\">
                    <span id=\"instructions-0\">
                        ";
        // line 20
        echo _gettext("Press Ctrl+Enter to execute query");
        // line 21
        echo "                    </span>
                    <span class=\"hide\" id=\"instructions-1\">
                        ";
        // line 23
        echo _gettext("Press Enter to execute query");
        // line 24
        echo "                    </span>
                </div>
                ";
        // line 26
        if ( !twig_test_empty(($context["sql_history"] ?? null))) {
            // line 27
            echo "                    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_reverse_filter($this->env, ($context["sql_history"] ?? null)));
            foreach ($context['_seq'] as $context["_key"] => $context["record"]) {
                // line 28
                echo "                        <div class=\"message history collapsed hide";
                // line 29
                echo ((preg_match("@^SELECT[[:space:]]+@i", $this->getAttribute($context["record"], "sqlquery", [], "array"))) ? (" select") : (""));
                echo "\"
                            targetdb=\"";
                // line 30
                echo twig_escape_filter($this->env, $this->getAttribute($context["record"], "db", [], "array"), "html", null, true);
                echo "\" targettable=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["record"], "table", [], "array"), "html", null, true);
                echo "\">
                            ";
                // line 31
                $this->loadTemplate("console/query_action.twig", "console/display.twig", 31)->display(twig_to_array(["parent_div_classes" => "action_content", "content_array" => [0 => [0 => "action collapse", 1 => _gettext("Collapse")], 1 => [0 => "action expand", 1 => _gettext("Expand")], 2 => [0 => "action requery", 1 => _gettext("Requery")], 3 => [0 => "action edit", 1 => _gettext("Edit")], 4 => [0 => "action explain", 1 => _gettext("Explain")], 5 => [0 => "action profiling", 1 => _gettext("Profiling")], 6 => ((                // line 40
(isset($context["cfg_bookmark"]) || array_key_exists("cfg_bookmark", $context))) ? ([0 => "action bookmark", 1 => _gettext("Bookmark")]) : (null)), 7 => [0 => "text failed", 1 => _gettext("Query failed")], 8 => [0 => "text targetdb", 1 => _gettext("Database"), "extraSpan" => $this->getAttribute(                // line 42
$context["record"], "db", [], "array")], 9 => [0 => "text query_time", 1 => _gettext("Queried time"), "extraSpan" => (($this->getAttribute(                // line 46
$context["record"], "timevalue", [], "array", true, true)) ? ($this->getAttribute(                // line 47
$context["record"], "timevalue", [], "array")) : (_gettext("During current session")))]]]));
                // line 51
                echo "                            <span class=\"query\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["record"], "sqlquery", [], "array"), "html", null, true);
                echo "</span>
                        </div>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['record'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 54
            echo "                ";
        }
        // line 55
        echo "            </div><!-- console_message_container -->
            <div class=\"query_input\">
                <span class=\"console_query_input\"></span>
            </div>
        </div><!-- message end -->
        ";
        // line 61
        echo "        <div class=\"mid_layer\"></div>
        ";
        // line 63
        echo "        <div class=\"card\" id=\"debug_console\">
            ";
        // line 64
        $this->loadTemplate("console/toolbar.twig", "console/display.twig", 64)->display(twig_to_array(["parent_div_classes" => "", "content_array" => [0 => [0 => "button order order_asc", 1 => _gettext("ascending")], 1 => [0 => "button order order_desc", 1 => _gettext("descending")], 2 => [0 => "text", 1 => _gettext("Order:")], 3 => [0 => "switch_button", 1 => _gettext("Debug SQL")], 4 => [0 => "button order_by sort_count", 1 => _gettext("Count")], 5 => [0 => "button order_by sort_exec", 1 => _gettext("Execution order")], 6 => [0 => "button order_by sort_time", 1 => _gettext("Time taken")], 7 => [0 => "text", 1 => _gettext("Order by:")], 8 => [0 => "button group_queries", 1 => _gettext("Group queries")], 9 => [0 => "button ungroup_queries", 1 => _gettext("Ungroup queries")]]]));
        // line 79
        echo "            <div class=\"content debug\">
                <div class=\"message welcome\"></div>
                <div class=\"debugLog\"></div>
            </div> <!-- Content -->
            <div class=\"templates\">
                ";
        // line 84
        $this->loadTemplate("console/query_action.twig", "console/display.twig", 84)->display(twig_to_array(["parent_div_classes" => "debug_query action_content", "content_array" => [0 => [0 => "action collapse", 1 => _gettext("Collapse")], 1 => [0 => "action expand", 1 => _gettext("Expand")], 2 => [0 => "action dbg_show_trace", 1 => _gettext("Show trace")], 3 => [0 => "action dbg_hide_trace", 1 => _gettext("Hide trace")], 4 => [0 => "text count hide", 1 => _gettext("Count")], 5 => [0 => "text time", 1 => _gettext("Time taken")]]]));
        // line 95
        echo "            </div> <!-- Template -->
        </div> <!-- Debug SQL card -->
        ";
        // line 97
        if (($context["cfg_bookmark"] ?? null)) {
            // line 98
            echo "            <div class=\"card\" id=\"pma_bookmarks\">
                ";
            // line 99
            $this->loadTemplate("console/toolbar.twig", "console/display.twig", 99)->display(twig_to_array(["parent_div_classes" => "", "content_array" => [0 => [0 => "switch_button", 1 => _gettext("Bookmarks")], 1 => [0 => "button refresh", 1 => _gettext("Refresh")], 2 => [0 => "button add", 1 => _gettext("Add")]]]));
            // line 107
            echo "                <div class=\"content bookmark\">
                    ";
            // line 108
            echo ($context["bookmark_content"] ?? null);
            echo "
                </div>
                <div class=\"mid_layer\"></div>
                <div class=\"card add\">
                    ";
            // line 112
            $this->loadTemplate("console/toolbar.twig", "console/display.twig", 112)->display(twig_to_array(["parent_div_classes" => "", "content_array" => [0 => [0 => "switch_button", 1 => _gettext("Add bookmark")]]]));
            // line 118
            echo "                    <div class=\"content add_bookmark\">
                        <div class=\"options\">
                            <label>
                                ";
            // line 121
            echo _gettext("Label");
            echo ": <input type=\"text\" name=\"label\">
                            </label>
                            <label>
                                ";
            // line 124
            echo _gettext("Target database");
            echo ": <input type=\"text\" name=\"targetdb\">
                            </label>
                            <label>
                                <input type=\"checkbox\" name=\"shared\">";
            // line 127
            echo _gettext("Share this bookmark");
            // line 128
            echo "                            </label>
                            <button type=\"submit\" name=\"submit\">";
            // line 129
            echo _gettext("OK");
            echo "</button>
                        </div> <!-- options -->
                        <div class=\"query_input\">
                            <span class=\"bookmark_add_input\"></span>
                        </div>
                    </div>
                </div> <!-- Add bookmark card -->
            </div> <!-- Bookmarks card -->
        ";
        }
        // line 138
        echo "        ";
        // line 139
        echo "        <div class=\"card\" id=\"pma_console_options\">
            ";
        // line 140
        $this->loadTemplate("console/toolbar.twig", "console/display.twig", 140)->display(twig_to_array(["parent_div_classes" => "", "content_array" => [0 => [0 => "switch_button", 1 => _gettext("Options")], 1 => [0 => "button default", 1 => _gettext("Set default")]]]));
        // line 147
        echo "            <div class=\"content\">
                <label>
                    <input type=\"checkbox\" name=\"always_expand\">";
        // line 149
        echo _gettext("Always expand query messages");
        // line 150
        echo "                </label>
                <br>
                <label>
                    <input type=\"checkbox\" name=\"start_history\">";
        // line 153
        echo _gettext("Show query history at start");
        // line 154
        echo "                </label>
                <br>
                <label>
                    <input type=\"checkbox\" name=\"current_query\">";
        // line 157
        echo _gettext("Show current browsing query");
        // line 158
        echo "                </label>
                <br>
                <label>
                    <input type=\"checkbox\" name=\"enter_executes\">
                        ";
        // line 162
        echo _gettext("Execute queries on Enter and insert new line with Shift + Enter. To make this permanent, view settings.");
        // line 165
        echo "                </label>
                <br>
                <label>
                    <input type=\"checkbox\" name=\"dark_theme\">";
        // line 168
        echo _gettext("Switch to dark theme");
        // line 169
        echo "                </label>
                <br>
            </div>
        </div> <!-- Options card -->
        <div class=\"templates\">
            ";
        // line 175
        echo "            ";
        $this->loadTemplate("console/query_action.twig", "console/display.twig", 175)->display(twig_to_array(["parent_div_classes" => "query_actions", "content_array" => [0 => [0 => "action collapse", 1 => _gettext("Collapse")], 1 => [0 => "action expand", 1 => _gettext("Expand")], 2 => [0 => "action requery", 1 => _gettext("Requery")], 3 => [0 => "action edit", 1 => _gettext("Edit")], 4 => [0 => "action explain", 1 => _gettext("Explain")], 5 => [0 => "action profiling", 1 => _gettext("Profiling")], 6 => ((        // line 184
(isset($context["cfg_bookmark"]) || array_key_exists("cfg_bookmark", $context))) ? ([0 => "action bookmark", 1 => _gettext("Bookmark")]) : (null)), 7 => [0 => "text failed", 1 => _gettext("Query failed")], 8 => [0 => "text targetdb", 1 => _gettext("Database"), "extraSpan" => ""], 9 => [0 => "text query_time", 1 => _gettext("Queried time"), "extraSpan" => ""]]]));
        // line 190
        echo "        </div>
    </div> <!-- #console end -->
</div> <!-- #console_container end -->
";
    }

    public function getTemplateName()
    {
        return "console/display.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  231 => 190,  229 => 184,  227 => 175,  220 => 169,  218 => 168,  213 => 165,  211 => 162,  205 => 158,  203 => 157,  198 => 154,  196 => 153,  191 => 150,  189 => 149,  185 => 147,  183 => 140,  180 => 139,  178 => 138,  166 => 129,  163 => 128,  161 => 127,  155 => 124,  149 => 121,  144 => 118,  142 => 112,  135 => 108,  132 => 107,  130 => 99,  127 => 98,  125 => 97,  121 => 95,  119 => 84,  112 => 79,  110 => 64,  107 => 63,  104 => 61,  97 => 55,  94 => 54,  84 => 51,  82 => 47,  81 => 46,  80 => 42,  79 => 40,  78 => 31,  72 => 30,  68 => 29,  66 => 28,  61 => 27,  59 => 26,  55 => 24,  53 => 23,  49 => 21,  47 => 20,  41 => 16,  39 => 15,  37 => 11,  36 => 7,  34 => 4,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "console/display.twig", "/var/www/html/templates/console/display.twig");
    }
}
