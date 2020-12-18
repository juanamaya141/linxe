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

/* modules/custom/linxecredit/templates/block/adelantohome.html.twig */
class __TwigTemplate_0ca0cc0c10e144c93274bbb809bdb01b4e7a1d8860305fcfbd11de6dddcce060 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = [];
        $filters = ["escape" => 10];
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
        echo "                        <div class=\"txt-adelanto\">
                                <h6>OBTÉN TU ADELANTO DE SALARIO</h6>
                            </div>
                            
                            <div class=\"form-element d-flex flex-column pt-md-3 pt-2 salario bb margin\">
                                <h6>¿CUÁL ES TU SALARIO APROXIMADO?</h6>
                                 <div class=\"form-item d-flex\">
                                    <button type=\"button\" id=\"btnMenos_an\" class=\"btnMenos\">
                                        <i class=\"boto-icon\">&#xe801;</i>
                                        <div class=\"rangos\" id=\"r_min_cantidad_an\">\$ ";
        // line 10
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["valor_min_an"] ?? null)), "html", null, true);
        echo "</div>
                                    </button>
                                    <div class=\"valor\">
                                        <span class=\"unidad mr-2\">\$</span>
                                        <span class=\"num\" id=\"cantidadVal_an\"></span>
                                        <input type=\"hidden\" id=\"cantidad_an\" name=\"cantidad_an\" value=\"";
        // line 15
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["valor_med_default_an"] ?? null)), "html", null, true);
        echo "\" />
                                    </div>
                                    <button type=\"button\" id=\"btnMas_an\" class=\"btnMas\">
                                        <i class=\"boto-icon\">&#xe800;</i>
                                        <div class=\"rangos\" id=\"r_max_cantidad_an\">\$ ";
        // line 19
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["valor_max_an"] ?? null)), "html", null, true);
        echo "<div>
                                    </button>
                                </div>
                                <input type=\"number\" name=\"cantidad_an\" id=\"cantidad_an\" class=\"d-none\" />
                            </div>

                            <div class=\"te-adelantamos\">
                                <h6>TE ADELANTAMOS</h6>
                                <p class=\"d-adelanto\">\$ <span id=\"valor_an\">";
        // line 27
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["valor_adelanto_default_an"] ?? null)), "html", null, true);
        echo "</span></p>
                                <p class=\"txt-plazo\">PLAZO MÁXIMO 30 DÍAS</p>
                            </div>

                            <a href=\"/registerform\" class=\"btn1 w-100 mt-auto\">¡SOLICÍTALO AQUÍ!</a>
                        </div>


                ";
    }

    public function getTemplateName()
    {
        return "modules/custom/linxecredit/templates/block/adelantohome.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  92 => 27,  81 => 19,  74 => 15,  66 => 10,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("                        <div class=\"txt-adelanto\">
                                <h6>OBTÉN TU ADELANTO DE SALARIO</h6>
                            </div>
                            
                            <div class=\"form-element d-flex flex-column pt-md-3 pt-2 salario bb margin\">
                                <h6>¿CUÁL ES TU SALARIO APROXIMADO?</h6>
                                 <div class=\"form-item d-flex\">
                                    <button type=\"button\" id=\"btnMenos_an\" class=\"btnMenos\">
                                        <i class=\"boto-icon\">&#xe801;</i>
                                        <div class=\"rangos\" id=\"r_min_cantidad_an\">\$ {{valor_min_an}}</div>
                                    </button>
                                    <div class=\"valor\">
                                        <span class=\"unidad mr-2\">\$</span>
                                        <span class=\"num\" id=\"cantidadVal_an\"></span>
                                        <input type=\"hidden\" id=\"cantidad_an\" name=\"cantidad_an\" value=\"{{valor_med_default_an}}\" />
                                    </div>
                                    <button type=\"button\" id=\"btnMas_an\" class=\"btnMas\">
                                        <i class=\"boto-icon\">&#xe800;</i>
                                        <div class=\"rangos\" id=\"r_max_cantidad_an\">\$ {{valor_max_an}}<div>
                                    </button>
                                </div>
                                <input type=\"number\" name=\"cantidad_an\" id=\"cantidad_an\" class=\"d-none\" />
                            </div>

                            <div class=\"te-adelantamos\">
                                <h6>TE ADELANTAMOS</h6>
                                <p class=\"d-adelanto\">\$ <span id=\"valor_an\">{{valor_adelanto_default_an}}</span></p>
                                <p class=\"txt-plazo\">PLAZO MÁXIMO 30 DÍAS</p>
                            </div>

                            <a href=\"/registerform\" class=\"btn1 w-100 mt-auto\">¡SOLICÍTALO AQUÍ!</a>
                        </div>


                ", "modules/custom/linxecredit/templates/block/adelantohome.html.twig", "/var/www/linxe.edwcorp.com/public_html/modules/custom/linxecredit/templates/block/adelantohome.html.twig");
    }
}
