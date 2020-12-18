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

/* modules/custom/linxecredit/templates/linxepopuptheme-form.html.twig */
class __TwigTemplate_0907e6a9d266d733c129bcd22d5ffc2a186922cd8288ab0568915346a07ce8f1 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 3];
        $filters = ["escape" => 9, "raw" => 14];
        $functions = ["drupal_entity" => 9];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape', 'raw'],
                ['drupal_entity']
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
                        
\t\t";
        // line 3
        if (($this->getAttribute($this->getAttribute(($context["form"] ?? null), "#myvars", [], "array"), "titulo", []) != "")) {
            // line 4
            echo "            <div class=\"modal-linxe\" >
                <div class=\"black-box\"></div>
                <div class=\"cont-modal\">
                    <div class=\"title-modal\">
                        <a href=\"#\" class=\"cerrar-modal\" onclick=\"closeppp()\">X</a>
                        ";
            // line 9
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Drupal\twig_tweak\TwigExtension')->drupalEntity("block_content", "29"), "html", null, true);
            echo "
                    </div>

                    <div class=\"copy-modal\">
                    \t<div id=\"block-textopopupwebinar\">
                    \t\t";
            // line 14
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["form"] ?? null), "#myvars", [], "array"), "textpopup", [])));
            echo "
                    \t</div>
                    </div>

                    \t<div class=\"formclass\">
                            ";
            // line 19
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "name", [])), "html", null, true);
            echo "
                            ";
            // line 20
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "email", [])), "html", null, true);
            echo "
                            ";
            // line 21
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "webinar_id", [])), "html", null, true);
            echo "
                            ";
            // line 22
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "webinar_schedule", [])), "html", null, true);
            echo "
                            ";
            // line 23
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["form"] ?? null), "actions", []), "submit", [])), "html", null, true);
            echo "
                            ";
            // line 24
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_build_id", [])), "html", null, true);
            echo "
                            ";
            // line 25
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_token", [])), "html", null, true);
            echo "
                            ";
            // line 26
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_id", [])), "html", null, true);
            echo "
                            
                            <div id=\"errorMsj\"></div>
                        </div>
                    
                    <div class=\"img-modal\">
                    \t";
            // line 32
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Drupal\twig_tweak\TwigExtension')->drupalEntity("block_content", "31"), "html", null, true);
            echo "
                    </div>
                </div>
            </div>
        ";
        }
    }

    public function getTemplateName()
    {
        return "modules/custom/linxecredit/templates/linxepopuptheme-form.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 32,  112 => 26,  108 => 25,  104 => 24,  100 => 23,  96 => 22,  92 => 21,  88 => 20,  84 => 19,  76 => 14,  68 => 9,  61 => 4,  59 => 3,  55 => 1,);
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
                        
\t\t{% if form['#myvars'].titulo != \"\"  %}
            <div class=\"modal-linxe\" >
                <div class=\"black-box\"></div>
                <div class=\"cont-modal\">
                    <div class=\"title-modal\">
                        <a href=\"#\" class=\"cerrar-modal\" onclick=\"closeppp()\">X</a>
                        {{drupal_entity('block_content', '29') }}
                    </div>

                    <div class=\"copy-modal\">
                    \t<div id=\"block-textopopupwebinar\">
                    \t\t{{form['#myvars'].textpopup | raw}}
                    \t</div>
                    </div>

                    \t<div class=\"formclass\">
                            {{ form.name }}
                            {{ form.email }}
                            {{ form.webinar_id }}
                            {{ form.webinar_schedule }}
                            {{ form.actions.submit }}
                            {{ form.form_build_id }}
                            {{ form.form_token }}
                            {{ form.form_id }}
                            
                            <div id=\"errorMsj\"></div>
                        </div>
                    
                    <div class=\"img-modal\">
                    \t{{drupal_entity('block_content', '31') }}
                    </div>
                </div>
            </div>
        {% endif %}", "modules/custom/linxecredit/templates/linxepopuptheme-form.html.twig", "/var/www/linxe.edwcorp.com/public_html/modules/custom/linxecredit/templates/linxepopuptheme-form.html.twig");
    }
}
