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

/* modules/custom/linxecredit/templates/messageresponse.html.twig */
class __TwigTemplate_9c06b1e3efaa2f69ba27a698bd81ba5fa756ea6d250718c2919cf3400102345a extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = [];
        $filters = ["escape" => 3];
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
        echo "        <div class=\"cont-respuesta\">
            <div class=\"respuesta\" id=\"newregistro\">
            \t<img src=\"";
        // line 3
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["paththeme"] ?? null)), "html", null, true);
        echo "/images/forms/money@3x.png\" alt=\"Bienvenido a LINXE\" class=\"img-fluid\" style=\"width:100%\"/>
    \t\t\t<h2 class=\"text1\">";
        // line 4
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["titlemsg"] ?? null)), "html", null, true);
        echo "</h2>
                <div class=\"mensaje\">
                ";
        // line 6
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["message"] ?? null)), "html", null, true);
        echo "
                </div>
                <a href=\"/\" class=\"btn1 small\">";
        // line 8
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["labelbutton"] ?? null)), "html", null, true);
        echo "</a>
            </div>  
        </div>";
    }

    public function getTemplateName()
    {
        return "modules/custom/linxecredit/templates/messageresponse.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 8,  68 => 6,  63 => 4,  59 => 3,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("        <div class=\"cont-respuesta\">
            <div class=\"respuesta\" id=\"newregistro\">
            \t<img src=\"{{paththeme}}/images/forms/money@3x.png\" alt=\"Bienvenido a LINXE\" class=\"img-fluid\" style=\"width:100%\"/>
    \t\t\t<h2 class=\"text1\">{{titlemsg}}</h2>
                <div class=\"mensaje\">
                {{message}}
                </div>
                <a href=\"/\" class=\"btn1 small\">{{labelbutton}}</a>
            </div>  
        </div>", "modules/custom/linxecredit/templates/messageresponse.html.twig", "/var/www/linxe.edwcorp.com/public_html/modules/custom/linxecredit/templates/messageresponse.html.twig");
    }
}
