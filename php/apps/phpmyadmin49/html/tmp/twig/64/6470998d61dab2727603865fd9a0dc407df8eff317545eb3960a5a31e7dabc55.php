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

/* server/databases/create.twig */
class __TwigTemplate_489f9ae64163bd127b271e622cdbcb20243da4313b68c5d37895fd2ff4c911d5 extends \Twig\Template
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
        echo "<ul>
    <li id=\"li_create_database\" class=\"no_bullets\">
        ";
        // line 3
        if (($context["is_create_db_priv"] ?? null)) {
            // line 4
            echo "            <form method=\"post\" action=\"server_databases.php\" id=\"create_database_form\" class=\"ajax\">
                <p><strong>
                    <label for=\"text_create_db\">
                        ";
            // line 7
            echo PhpMyAdmin\Util::getImage("b_newdb");
            echo "
                        ";
            // line 8
            echo _gettext("Create database");
            // line 9
            echo "                    </label>
                    ";
            // line 10
            echo PhpMyAdmin\Util::showMySQLDocu("CREATE_DATABASE");
            echo "
                </strong></p>

                ";
            // line 13
            echo PhpMyAdmin\Url::getHiddenInputs("", "", 5);
            echo "
                <input type=\"hidden\" name=\"reload\" value=\"1\" />
                ";
            // line 15
            if ( !twig_test_empty(($context["dbstats"] ?? null))) {
                // line 16
                echo "                    <input type=\"hidden\" name=\"dbstats\" value=\"1\" />
                ";
            }
            // line 18
            echo "
                <input type=\"text\" name=\"new_db\" value=\"";
            // line 19
            echo twig_escape_filter($this->env, ($context["db_to_create"] ?? null), "html", null, true);
            echo "\"
                    maxlength=\"64\" class=\"textfield\" id=\"text_create_db\" required
                    placeholder=\"";
            // line 21
            echo _gettext("Database name");
            echo "\" />
                ";
            // line 22
            echo PhpMyAdmin\Charsets::getCollationDropdownBox(            // line 23
($context["dbi"] ?? null),             // line 24
($context["disable_is"] ?? null), "db_collation", null,             // line 27
($context["server_collation"] ?? null), true);
            // line 29
            echo "
                <input type=\"submit\" value=\"";
            // line 30
            echo _gettext("Create");
            echo "\" id=\"buttonGo\" />
            </form>
        ";
        } else {
            // line 33
            echo "            ";
            // line 34
            echo "            <p><strong>
                ";
            // line 35
            echo PhpMyAdmin\Util::getImage("b_newdb");
            echo "
                ";
            // line 36
            echo _gettext("Create database");
            // line 37
            echo "                ";
            echo PhpMyAdmin\Util::showMySQLDocu("CREATE_DATABASE");
            echo "
            </strong></p>

            <span class=\"noPrivileges\">
                ";
            // line 41
            echo PhpMyAdmin\Util::getImage("s_error", "", ["hspace" => 2, "border" => 0, "align" => "middle"]);
            // line 45
            echo "
                ";
            // line 46
            echo _gettext("No Privileges");
            // line 47
            echo "            </span>
        ";
        }
        // line 49
        echo "    </li>
</ul>
";
    }

    public function getTemplateName()
    {
        return "server/databases/create.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  123 => 49,  119 => 47,  117 => 46,  114 => 45,  112 => 41,  104 => 37,  102 => 36,  98 => 35,  95 => 34,  93 => 33,  87 => 30,  84 => 29,  82 => 27,  81 => 24,  80 => 23,  79 => 22,  75 => 21,  70 => 19,  67 => 18,  63 => 16,  61 => 15,  56 => 13,  50 => 10,  47 => 9,  45 => 8,  41 => 7,  36 => 4,  34 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "server/databases/create.twig", "/var/www/html/templates/server/databases/create.twig");
    }
}
