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

/* themes/custom/linxe/templates/page.html.twig */
class __TwigTemplate_7024419112183c561d681a5c73b20725915c232d423fdd6efe6e1fc505f1165b extends \Twig\Template
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
        echo "    <header class=\"fixed-top\">
        <div class=\"topBar\">
          ";
        // line 3
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "infoheader", [])), "html", null, true);
        echo "
        </div>
        <div class=\"menuBar\">
              ";
        // line 6
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "logo", [])), "html", null, true);
        echo "
            <button type=\"button\" class=\"btnMenu\" data-toggle=\"collapse\" data-target=\"#collapsenav\"
                aria-expanded=\"false\" aria-controls=\"collapsenav\"><i class=\"fas fa-bars\"></i></button>
            <nav id=\"collapsenav\" class=\"collapse\">
              ";
        // line 10
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "primary_menu", [])), "html", null, true);
        echo "
            </nav>
              ";
        // line 12
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "botoneslogin", [])), "html", null, true);
        echo "
        </div>
    </header>

    <main class=\"pb-md-6 pb-4\">
      <div class=\"main-wrap mt-md-6 mt-4\">
          ";
        // line 18
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "messages", [])), "html", null, true);
        echo "
          ";
        // line 19
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "content", [])), "html", null, true);
        echo "
      </div>
    </main>

    <footer>
        <div class=\"cont-footer\">
            ";
        // line 25
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer_menu", [])), "html", null, true);
        echo "

            ";
        // line 27
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer_contacto", [])), "html", null, true);
        echo "
        </div>

        <div class=\"redes\">
            ";
        // line 31
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer_redes", [])), "html", null, true);
        echo "
        </div>

        <div class=\"copy-foot\">
            ";
        // line 35
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer_copyright", [])), "html", null, true);
        echo "
        </div>
    </footer>
    <div id=\"loginformlayer\" class=\"modal fade\" role=\"dialog\">
        ";
        // line 39
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "login_form", [])), "html", null, true);
        echo "
    </div>
    <div id=\"rememberformlayer\" class=\"modal fade\" role=\"dialog\">
        ";
        // line 42
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "remember_form", [])), "html", null, true);
        echo "
    </div>";
    }

    public function getTemplateName()
    {
        return "themes/custom/linxe/templates/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  131 => 42,  125 => 39,  118 => 35,  111 => 31,  104 => 27,  99 => 25,  90 => 19,  86 => 18,  77 => 12,  72 => 10,  65 => 6,  59 => 3,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("    <header class=\"fixed-top\">
        <div class=\"topBar\">
          {{ page.infoheader }}
        </div>
        <div class=\"menuBar\">
              {{ page.logo }}
            <button type=\"button\" class=\"btnMenu\" data-toggle=\"collapse\" data-target=\"#collapsenav\"
                aria-expanded=\"false\" aria-controls=\"collapsenav\"><i class=\"fas fa-bars\"></i></button>
            <nav id=\"collapsenav\" class=\"collapse\">
              {{ page.primary_menu }}
            </nav>
              {{ page.botoneslogin }}
        </div>
    </header>

    <main class=\"pb-md-6 pb-4\">
      <div class=\"main-wrap mt-md-6 mt-4\">
          {{ page.messages }}
          {{ page.content }}
      </div>
    </main>

    <footer>
        <div class=\"cont-footer\">
            {{ page.footer_menu }}

            {{ page.footer_contacto }}
        </div>

        <div class=\"redes\">
            {{ page.footer_redes }}
        </div>

        <div class=\"copy-foot\">
            {{ page.footer_copyright }}
        </div>
    </footer>
    <div id=\"loginformlayer\" class=\"modal fade\" role=\"dialog\">
        {{ page.login_form }}
    </div>
    <div id=\"rememberformlayer\" class=\"modal fade\" role=\"dialog\">
        {{ page.remember_form }}
    </div>", "themes/custom/linxe/templates/page.html.twig", "/var/www/linxe.edwcorp.com/public_html/themes/custom/linxe/templates/page.html.twig");
    }
}
