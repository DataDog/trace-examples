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

/* display/results/options_block.twig */
class __TwigTemplate_042d3cc35cf9ea1b0071db2779086643c01efd051d7b0f0b92c938938ae05469 extends \Twig\Template
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
        return array (  150 => 117,  145 => 114,  139 => 110,  134 => 107,  132 => 106,  131 => 102,  130 => 96,  126 => 95,  123 => 94,  118 => 91,  116 => 90,  115 => 86,  114 => 79,  111 => 78,  109 => 77,  104 => 74,  102 => 72,  101 => 70,  100 => 67,  97 => 66,  93 => 60,  91 => 58,  90 => 56,  88 => 53,  86 => 51,  85 => 49,  84 => 46,  80 => 44,  75 => 41,  73 => 40,  72 => 36,  71 => 30,  68 => 29,  66 => 28,  61 => 25,  59 => 24,  58 => 20,  56 => 14,  50 => 10,  46 => 8,  44 => 6,  43 => 5,  42 => 4,  41 => 3,  40 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "display/results/options_block.twig", "/var/www/public/phpmyadmin49/templates/display/results/options_block.twig");
    }
}
