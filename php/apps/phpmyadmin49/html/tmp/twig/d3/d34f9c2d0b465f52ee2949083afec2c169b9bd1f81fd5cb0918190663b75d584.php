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

/* server/databases/table_header.twig */
class __TwigTemplate_c3bfc9ec32578b1b5acda4b14ce5e44045979175a524d514ab3d374b9de97de7 extends \Twig\Template
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
        echo "<thead>
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
            <a href=\"server_databases.php";
        // line 7
        echo PhpMyAdmin\Url::getCommon(($context["url_params"] ?? null));
        echo "\">
                ";
        // line 8
        echo _gettext("Database");
        // line 9
        echo "                ";
        echo (((($context["sort_by"] ?? null) == "SCHEMA_NAME")) ? (PhpMyAdmin\Util::getImage(("s_" .         // line 10
($context["sort_order"] ?? null)),         // line 11
($context["sort_order_text"] ?? null))) : (""));
        // line 12
        echo "
            </a>
        </th>
        ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["column_order"] ?? null));
        foreach ($context['_seq'] as $context["stat_name"] => $context["stat"]) {
            if (twig_in_filter($context["stat_name"], twig_get_array_keys_filter(($context["first_database"] ?? null)))) {
                // line 16
                echo "            ";
                $context["url_params"] = twig_array_merge(($context["url_params"] ?? null), ["sort_by" =>                 // line 17
$context["stat_name"], "sort_order" => ((((                // line 18
($context["sort_by"] ?? null) == $context["stat_name"]) && (($context["sort_order"] ?? null) == "desc"))) ? ("asc") : ("desc"))]);
                // line 20
                echo "
            <th";
                // line 21
                echo ((($this->getAttribute($context["stat"], "format", [], "array") === "byte")) ? (" colspan=\"2\"") : (""));
                echo ">
                <a href=\"server_databases.php";
                // line 22
                echo PhpMyAdmin\Url::getCommon(($context["url_params"] ?? null));
                echo "\">
                    ";
                // line 23
                echo twig_escape_filter($this->env, $this->getAttribute($context["stat"], "disp_name", [], "array"), "html", null, true);
                echo "
                    ";
                // line 24
                echo (((($context["sort_by"] ?? null) == $context["stat_name"])) ? (PhpMyAdmin\Util::getImage(("s_" .                 // line 25
($context["sort_order"] ?? null)),                 // line 26
($context["sort_order_text"] ?? null))) : (""));
                // line 27
                echo "
                </a>
            </th>
        ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['stat_name'], $context['stat'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        echo "        ";
        if (($context["master_replication"] ?? null)) {
            // line 32
            echo "            <th>";
            echo _gettext("Master replication");
            echo "</th>
        ";
        }
        // line 34
        echo "        ";
        if (($context["slave_replication"] ?? null)) {
            // line 35
            echo "            <th>";
            echo _gettext("Slave replication");
            echo "</th>
        ";
        }
        // line 37
        echo "        <th>";
        echo _gettext("Action");
        echo "</th>
    </tr>
</thead>
";
    }

    public function getTemplateName()
    {
        return "server/databases/table_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 37,  110 => 35,  107 => 34,  101 => 32,  98 => 31,  88 => 27,  86 => 26,  85 => 25,  84 => 24,  80 => 23,  76 => 22,  72 => 21,  69 => 20,  67 => 18,  66 => 17,  64 => 16,  59 => 15,  54 => 12,  52 => 11,  51 => 10,  49 => 9,  47 => 8,  43 => 7,  40 => 6,  36 => 4,  34 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "server/databases/table_header.twig", "/var/www/html/templates/server/databases/table_header.twig");
    }
}
