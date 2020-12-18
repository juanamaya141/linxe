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

/* modules/custom/linxecredit/templates/block/botoneslogin.html.twig */
class __TwigTemplate_6d4d71da0d15bb7d36624005b8015ef867cd7ee15ea6eaa9b912dd56af54d518 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 1];
        $filters = [];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                [],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "                                    ";
        if ((($context["islogin"] ?? null) == true)) {
            // line 2
            echo "                                        ";
            if ((($context["tipoproducto"] ?? null) == "adelanto")) {
                // line 3
                echo "                                        <a href=\"/dashboard/adelanto\" class=\"singin\">Mis Adelantos</a>
                                        ";
            } else {
                // line 4
                echo "  
                                        <a href=\"/dashboard/seleccion\" class=\"singin\">Mis Créditos</a>
                                        ";
            }
            // line 7
            echo "                                        <a href=\"/cerrarsesion\" class=\"singup\">Salir</a>
                                    ";
        } else {
            // line 8
            echo "     
                                        <button type=\"button\" class=\"singin modal-btn\" data-modal=\"login-form\">Ingresa</button>
                                        <a href=\"/registerform\" class=\"singup\">Regístrate</a>
                                    ";
        }
        // line 12
        echo "                                    ";
    }

    public function getTemplateName()
    {
        return "modules/custom/linxecredit/templates/block/botoneslogin.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  80 => 12,  74 => 8,  70 => 7,  65 => 4,  61 => 3,  58 => 2,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("                                    {% if islogin == true  %}
                                        {% if tipoproducto == \"adelanto\"  %}
                                        <a href=\"/dashboard/adelanto\" class=\"singin\">Mis Adelantos</a>
                                        {% else %}  
                                        <a href=\"/dashboard/seleccion\" class=\"singin\">Mis Créditos</a>
                                        {% endif %}
                                        <a href=\"/cerrarsesion\" class=\"singup\">Salir</a>
                                    {% else %}     
                                        <button type=\"button\" class=\"singin modal-btn\" data-modal=\"login-form\">Ingresa</button>
                                        <a href=\"/registerform\" class=\"singup\">Regístrate</a>
                                    {% endif %}
                                    ", "modules/custom/linxecredit/templates/block/botoneslogin.html.twig", "/var/www/linxe.edwcorp.com/public_html/modules/custom/linxecredit/templates/block/botoneslogin.html.twig");
    }
}
