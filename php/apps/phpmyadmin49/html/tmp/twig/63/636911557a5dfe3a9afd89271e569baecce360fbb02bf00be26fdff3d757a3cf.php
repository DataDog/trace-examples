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

/* server/databases/databases_header.twig */
class __TwigTemplate_7498e22cc26a6f00ead052312df1f43c11dbf646a0125599fe98852d44152000 extends \Twig\Template
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
        echo "<div id=\"tableslistcontainer\">
    ";
        // line 2
        echo PhpMyAdmin\Util::getListNavigator(        // line 3
($context["database_count"] ?? null),         // line 4
($context["pos"] ?? null),         // line 5
($context["url_params"] ?? null), "server_databases.php", "frame_content",         // line 8
($context["max_db_list"] ?? null));
        // line 9
        echo "
    <form class=\"ajax\" action=\"server_databases.php\" method=\"post\" name=\"dbStatsForm\" id=\"dbStatsForm\">
        ";
        // line 11
        echo PhpMyAdmin\Url::getHiddenInputs(($context["url_params"] ?? null));
        echo "
        ";
        // line 12
        $context["url_params"] = twig_array_merge(($context["url_params"] ?? null), ["sort_by" => "SCHEMA_NAME", "sort_order" => ((((        // line 14
($context["sort_by"] ?? null) == "SCHEMA_NAME") && (($context["sort_order"] ?? null) == "asc"))) ? ("desc") : ("asc"))]);
        // line 16
        echo "        <div class=\"responsivetable\">
            <table id=\"tabledatabases\" class=\"data\">
                ";
        // line 18
        $this->loadTemplate("server/databases/table_header.twig", "server/databases/databases_header.twig", 18)->display(twig_to_array(["url_params" =>         // line 19
($context["url_params"] ?? null), "sort_by" =>         // line 20
($context["sort_by"] ?? null), "sort_order" =>         // line 21
($context["sort_order"] ?? null), "sort_order_text" => (((        // line 22
($context["sort_order"] ?? null) == "asc")) ? (_gettext("Ascending")) : (_gettext("Descending"))), "column_order" =>         // line 23
($context["column_order"] ?? null), "first_database" =>         // line 24
($context["first_database"] ?? null), "master_replication" =>         // line 25
($context["master_replication"] ?? null), "slave_replication" =>         // line 26
($context["slave_replication"] ?? null), "is_superuser" =>         // line 27
($context["is_superuser"] ?? null), "allow_user_drop_database" =>         // line 28
($context["allow_user_drop_database"] ?? null)]));
    }

    public function getTemplateName()
    {
        return "server/databases/databases_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  64 => 28,  63 => 27,  62 => 26,  61 => 25,  60 => 24,  59 => 23,  58 => 22,  57 => 21,  56 => 20,  55 => 19,  54 => 18,  50 => 16,  48 => 14,  47 => 12,  43 => 11,  39 => 9,  37 => 8,  36 => 5,  35 => 4,  34 => 3,  33 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "server/databases/databases_header.twig", "/var/www/html/templates/server/databases/databases_header.twig");
    }
}
