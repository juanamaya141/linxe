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

/* __string_template__8e8e451046ebf3ec6f9ad3f19bf1a821214a050ce9b76c80800c3e058206c381 */
class __TwigTemplate_874fd8c12d7831352da90d7d1a7c590f8c30d618eb466af116736c07e86661d3 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 5];
        $filters = ["escape" => 1];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
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
        echo "<span class=\"number\">";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_orden_como_funciona"] ?? null)), "html", null, true);
        echo "</span>
<img src=\"";
        // line 2
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_imagen_como_funciona"] ?? null)), "html", null, true);
        echo "\" alt=\"";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["name"] ?? null)), "html", null, true);
        echo "\">
<div class=\"cont-caracteristica\">
   <h4>";
        // line 4
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["name"] ?? null)), "html", null, true);
        echo "</h4>
   ";
        // line 5
        if (($context["field_subtitulo_como_funciona"] ?? null)) {
            echo "<p>";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_subtitulo_como_funciona"] ?? null)), "html", null, true);
            echo "</p>";
        }
        // line 6
        echo "</div>";
    }

    public function getTemplateName()
    {
        return "__string_template__8e8e451046ebf3ec6f9ad3f19bf1a821214a050ce9b76c80800c3e058206c381";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  77 => 6,  71 => 5,  67 => 4,  60 => 2,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{# inline_template_start #}<span class=\"number\">{{ field_orden_como_funciona }}</span>
<img src=\"{{ field_imagen_como_funciona }}\" alt=\"{{ name }}\">
<div class=\"cont-caracteristica\">
   <h4>{{ name }}</h4>
   {% if field_subtitulo_como_funciona %}<p>{{ field_subtitulo_como_funciona }}</p>{% endif %}
</div>", "__string_template__8e8e451046ebf3ec6f9ad3f19bf1a821214a050ce9b76c80800c3e058206c381", "");
    }
}
