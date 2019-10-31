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

/* display/results/options_block.twig */
class __TwigTemplate_370009e0a1582bd9209bdeb1a4b1e071e6dee65e60ae54fe2d57cf910b503abc extends \Twig\Template
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
        echo "<form method=\"post\" action=\"sql.php\" name=\"displayOptionsForm\" class=\"ajax print_ignore\">
    ";
        // line 2
        echo PhpMyAdmin\Url::getHiddenInputs(["db" =>         // line 3
($context["db"] ?? null), "table" =>         // line 4
($context["table"] ?? null), "sql_query" =>         // line 5
($context["sql_query"] ?? null), "goto" =>         // line 6
($context["goto"] ?? null), "display_options_form" => 1]);
        // line 8
        echo "

    ";
        // line 10
        echo PhpMyAdmin\Util::getDivForSliderEffect("", _gettext("Options"));
        echo "
        <fieldset>
            <div class=\"formelement\">
                ";
        // line 14
        echo "                ";
        echo PhpMyAdmin\Util::getRadioFields("pftext", ["P" => _gettext("Partial texts"), "F" => _gettext("Full texts")],         // line 20
($context["pftext"] ?? null), true, true, "", ("pftext_" .         // line 24
($context["unique_id"] ?? null)));
        // line 25
        echo "
            </div>

            ";
        // line 28
        if ((($context["relwork"] ?? null) && ($context["displaywork"] ?? null))) {
            // line 29
            echo "                <div class=\"formelement\">
                    ";
            // line 30
            echo PhpMyAdmin\Util::getRadioFields("relational_display", ["K" => _gettext("Relational key"), "D" => _gettext("Display column for relationships")],             // line 36
($context["relational_display"] ?? null), true, true, "", ("relational_display_" .             // line 40
($context["unique_id"] ?? null)));
            // line 41
            echo "
                </div>
            ";
        }
        // line 44
        echo "
            <div class=\"formelement\">
                ";
        // line 46
        $this->loadTemplate("checkbox.twig", "display/results/options_block.twig", 46)->display(twig_to_array(["html_field_name" => "display_binary", "label" => _gettext("Show binary contents"), "checked" =>  !twig_test_empty(        // line 49
($context["display_binary"] ?? null)), "onclick" => false, "html_field_id" => ("display_binary_" .         // line 51
($context["unique_id"] ?? null))]));
        // line 53
        echo "                ";
        $this->loadTemplate("checkbox.twig", "display/results/options_block.twig", 53)->display(twig_to_array(["html_field_name" => "display_blob", "label" => _gettext("Show BLOB contents"), "checked" =>  !twig_test_empty(        // line 56
($context["display_blob"] ?? null)), "onclick" => false, "html_field_id" => ("display_blob_" .         // line 58
($context["unique_id"] ?? null))]));
        // line 60
        echo "            </div>

            ";
        // line 66
        echo "            <div class=\"formelement\">
                ";
        // line 67
        $this->loadTemplate("checkbox.twig", "display/results/options_block.twig", 67)->display(twig_to_array(["html_field_name" => "hide_transformation", "label" => _gettext("Hide browser transformation"), "checked" =>  !twig_test_empty(        // line 70
($context["hide_transformation"] ?? null)), "onclick" => false, "html_field_id" => ("hide_transformation_" .         // line 72
($context["unique_id"] ?? null))]));
        // line 74
        echo "            </div>


            ";
        // line 77
        if (($context["possible_as_geometry"] ?? null)) {
            // line 78
            echo "                <div class=\"formelement\">
                    ";
            // line 79
            echo PhpMyAdmin\Util::getRadioFields("geoOption", ["GEOM" => _gettext("Geometry"), "WKT" => _gettext("Well Known Text"), "WKB" => _gettext("Well Known Binary")],             // line 86
($context["geo_option"] ?? null), true, true, "", ("geoOption_" .             // line 90
($context["unique_id"] ?? null)));
            // line 91
            echo "
                </div>
            ";
        } else {
            // line 94
            echo "                <div class=\"formelement\">
                    ";
            // line 95
            echo twig_escape_filter($this->env, ($context["possible_as_geometry"] ?? null), "html", null, true);
            echo "
                    ";
            // line 96
            echo PhpMyAdmin\Util::getRadioFields("geoOption", ["WKT" => _gettext("Well Known Text"), "WKB" => _gettext("Well Known Binary")],             // line 102
($context["geo_option"] ?? null), true, true, "", ("geoOption_" .             // line 106
($context["unique_id"] ?? null)));
            // line 107
            echo "
                </div>
            ";
        }
        // line 110
        echo "            <div class=\"clearfloat\"></div>
        </fieldset>

        <fieldset class=\"tblFooters\">
            <input type=\"submit\" value=\"";
        // line 114
        echo _gettext("Go");
        echo "\" />
        </fieldset>
    </div>";
        // line 117
        echo "</form>
";
    }

    public function getTemplateName()
    {
        return "display/results/options_block.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  143 => 117,  138 => 114,  132 => 110,  127 => 107,  125 => 106,  124 => 102,  123 => 96,  119 => 95,  116 => 94,  111 => 91,  109 => 90,  108 => 86,  107 => 79,  104 => 78,  102 => 77,  97 => 74,  95 => 72,  94 => 70,  93 => 67,  90 => 66,  86 => 60,  84 => 58,  83 => 56,  81 => 53,  79 => 51,  78 => 49,  77 => 46,  73 => 44,  68 => 41,  66 => 40,  65 => 36,  64 => 30,  61 => 29,  59 => 28,  54 => 25,  52 => 24,  51 => 20,  49 => 14,  43 => 10,  39 => 8,  37 => 6,  36 => 5,  35 => 4,  34 => 3,  33 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "display/results/options_block.twig", "/var/www/html/templates/display/results/options_block.twig");
    }
}
