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

/* modules/custom/linxecredit/templates/linxelogintheme-form.html.twig */
class __TwigTemplate_0558d8ff9ee13b2573d74ef88c4d4d68ebc6e0ac77caf4a3619a06e81be926e9 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = [];
        $filters = ["escape" => 4];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape'],
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
        echo "
                        <form class=\"formularioin\" action=\"\" name=\"form_login\" id=\"form_login\">
                            <div class=\"form-group\">
                                ";
        // line 4
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "usuario", [])), "html", null, true);
        echo "
                            </div>
                            <div class=\"form-group\">
                                ";
        // line 7
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "password", [])), "html", null, true);
        echo "
                                <button id=\"show_password\" class=\"btn-showp\" type=\"button\"> <span class=\"fa fa-eye icon\"></span> </button>
                            </div>
                            <div class=\"ayuda\"><a id=\"ayudaIngresar\">¿Tienes problemas para ingresar?</a></div>
                            ";
        // line 11
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["form"] ?? null), "actions", []), "submit", [])), "html", null, true);
        echo "
                            ";
        // line 12
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_build_id", [])), "html", null, true);
        echo "
                            ";
        // line 13
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_token", [])), "html", null, true);
        echo "
                            ";
        // line 14
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_id", [])), "html", null, true);
        echo "
                            <div id=\"errorMsj\"></div>
                        </form>
";
    }

    public function getTemplateName()
    {
        return "modules/custom/linxecredit/templates/linxelogintheme-form.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 14,  81 => 13,  77 => 12,  73 => 11,  66 => 7,  60 => 4,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("
                        <form class=\"formularioin\" action=\"\" name=\"form_login\" id=\"form_login\">
                            <div class=\"form-group\">
                                {{ form.usuario }}
                            </div>
                            <div class=\"form-group\">
                                {{ form.password }}
                                <button id=\"show_password\" class=\"btn-showp\" type=\"button\"> <span class=\"fa fa-eye icon\"></span> </button>
                            </div>
                            <div class=\"ayuda\"><a id=\"ayudaIngresar\">¿Tienes problemas para ingresar?</a></div>
                            {{ form.actions.submit }}
                            {{ form.form_build_id }}
                            {{ form.form_token }}
                            {{ form.form_id }}
                            <div id=\"errorMsj\"></div>
                        </form>
", "modules/custom/linxecredit/templates/linxelogintheme-form.html.twig", "/var/www/linxe.edwcorp.com/public_html/modules/custom/linxecredit/templates/linxelogintheme-form.html.twig");
    }
}
