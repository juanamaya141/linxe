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

/* modules/custom/linxecredit/templates/linxeremembertheme-form.html.twig */
class __TwigTemplate_e852313839439c142b3a2b5a7644bab72e164f1538e054f587ba9053a137f663 extends \Twig\Template
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
                        <form class=\"formularioin\" action=\"\" name=\"form_help\" id=\"form_help\">
                            <div class=\"form-group\">
                                ";
        // line 4
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "nombre", [])), "html", null, true);
        echo "
                            </div>
                            <div class=\"form-group\">
                                ";
        // line 7
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "apellido", [])), "html", null, true);
        echo "
                            </div>
                            <div class=\"form-group\">
                                ";
        // line 10
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "documento", [])), "html", null, true);
        echo "
                            </div>
                            <div class=\"form-group\">
                                ";
        // line 13
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "empresa", [])), "html", null, true);
        echo "
                            </div>
                            
                            ";
        // line 16
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["form"] ?? null), "actions", []), "submit", [])), "html", null, true);
        echo "
                            ";
        // line 17
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_build_id", [])), "html", null, true);
        echo "
                            ";
        // line 18
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_token", [])), "html", null, true);
        echo "
                            ";
        // line 19
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_id", [])), "html", null, true);
        echo "
                            <div id=\"errorMsj\" class=\"errorMsj\"></div>
                        </form>
";
    }

    public function getTemplateName()
    {
        return "modules/custom/linxecredit/templates/linxeremembertheme-form.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  96 => 19,  92 => 18,  88 => 17,  84 => 16,  78 => 13,  72 => 10,  66 => 7,  60 => 4,  55 => 1,);
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
                        <form class=\"formularioin\" action=\"\" name=\"form_help\" id=\"form_help\">
                            <div class=\"form-group\">
                                {{ form.nombre }}
                            </div>
                            <div class=\"form-group\">
                                {{ form.apellido }}
                            </div>
                            <div class=\"form-group\">
                                {{ form.documento }}
                            </div>
                            <div class=\"form-group\">
                                {{ form.empresa }}
                            </div>
                            
                            {{ form.actions.submit }}
                            {{ form.form_build_id }}
                            {{ form.form_token }}
                            {{ form.form_id }}
                            <div id=\"errorMsj\" class=\"errorMsj\"></div>
                        </form>
", "modules/custom/linxecredit/templates/linxeremembertheme-form.html.twig", "/var/www/linxe.edwcorp.com/public_html/modules/custom/linxecredit/templates/linxeremembertheme-form.html.twig");
    }
}
