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

/* modules/custom/linxecredit/templates/block/simuladorhome.html.twig */
class __TwigTemplate_2aafba98d9d842f151f264b8a2b154e3ecbad12c3d44c494c49a4f24b60f1e35 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = [];
        $filters = ["escape" => 28];
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
        echo "                            <div class=\"cont-form d-flex w-100\" id=\"simulador\">
                                <div class=\"formulario d-flex flex-column w-100\" id=\"homesimulador\" name=\"simulador\">
                                    <div class=\"form-element d-flex flex-column pt-md-3 pt-2 cuanto-necesita bb margin\">
                                        <label for=\"cantidad\">¿Cuanto Necesitas?</label>
                                        <div class=\"form-item d-flex\">
                                            <button type=\"button\" id=\"btnMenos\" class=\"btnMenos\">
                                                <i class=\"boto-icon\">&#xe801;</i>
                                                <div class=\"rangos\" id=\"r_min_cantidad\"></div>
                                            </button>
                                            <div class=\"valor\">
                                                <span class=\"unidad mr-2\">\$</span>
                                                <span class=\"num\" id=\"catidadVal\"></span>
                                            </div>
                                            <button type=\"button\" id=\"btnMas\" class=\"btnMas\">
                                                <i class=\"boto-icon\">&#xe800;</i>
                                                <div class=\"rangos\" id=\"r_max_cantidad\"></div>
                                            </button>
                                        </div>
                                        <input type=\"number\" name=\"cantidad\" id=\"cantidad\" class=\"d-none\" />
                                    </div>
                                    <div class=\"form-element plazo bb margin\">
                                        <label for=\"plazo\">¿A qué plazo?</label>
                                        <div class=\"valor\">
                                            <span class=\"num mr-2\" id=\"plazoVal\"></span>
                                            <span class=\"unidad\">MESES</span>
                                        </div>
                                        <div class=\"newRange\">
                                            <input type=\"range\" id=\"plazo\" class=\"custom-range\" name=\"plazo\" min=\"";
        // line 28
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["gl_meses_min"] ?? null)), "html", null, true);
        echo "\"
                                                max=\"";
        // line 29
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["gl_meses_max"] ?? null)), "html", null, true);
        echo "\" />
                                        </div>
                                        <!--
                                        <div class=\"form-item\">
                                            <div class=\"rangos\" id=\"r_min_meses\"></div>
                                            <div class=\"rangos\" id=\"r_max_meses\"></div>
                                        </div>
                                        -->
                                    </div>
                                    <div class=\"form-element border-bottom-0 cuota margin\">
                                        <label for=\"cuota\">Tu cuota mensual</label>
                                        <div class=\"valor\">
                                            <span class=\"unidad mr-2\">\$</span>
                                            <span class=\"num\" id=\"cuotaVal\"></span>
                                        </div>
                                        <input type=\"text\" name=\"cuota\" id=\"cuota\" class=\"d-none\" />
                                    </div>
                                    <a href=\"/registerform\" class=\"btn1 w-100 mt-auto\">¡Solicítalo Aquí!</a>
                                </div>
                            </div>";
    }

    public function getTemplateName()
    {
        return "modules/custom/linxecredit/templates/block/simuladorhome.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  88 => 29,  84 => 28,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("                            <div class=\"cont-form d-flex w-100\" id=\"simulador\">
                                <div class=\"formulario d-flex flex-column w-100\" id=\"homesimulador\" name=\"simulador\">
                                    <div class=\"form-element d-flex flex-column pt-md-3 pt-2 cuanto-necesita bb margin\">
                                        <label for=\"cantidad\">¿Cuanto Necesitas?</label>
                                        <div class=\"form-item d-flex\">
                                            <button type=\"button\" id=\"btnMenos\" class=\"btnMenos\">
                                                <i class=\"boto-icon\">&#xe801;</i>
                                                <div class=\"rangos\" id=\"r_min_cantidad\"></div>
                                            </button>
                                            <div class=\"valor\">
                                                <span class=\"unidad mr-2\">\$</span>
                                                <span class=\"num\" id=\"catidadVal\"></span>
                                            </div>
                                            <button type=\"button\" id=\"btnMas\" class=\"btnMas\">
                                                <i class=\"boto-icon\">&#xe800;</i>
                                                <div class=\"rangos\" id=\"r_max_cantidad\"></div>
                                            </button>
                                        </div>
                                        <input type=\"number\" name=\"cantidad\" id=\"cantidad\" class=\"d-none\" />
                                    </div>
                                    <div class=\"form-element plazo bb margin\">
                                        <label for=\"plazo\">¿A qué plazo?</label>
                                        <div class=\"valor\">
                                            <span class=\"num mr-2\" id=\"plazoVal\"></span>
                                            <span class=\"unidad\">MESES</span>
                                        </div>
                                        <div class=\"newRange\">
                                            <input type=\"range\" id=\"plazo\" class=\"custom-range\" name=\"plazo\" min=\"{{gl_meses_min}}\"
                                                max=\"{{gl_meses_max}}\" />
                                        </div>
                                        <!--
                                        <div class=\"form-item\">
                                            <div class=\"rangos\" id=\"r_min_meses\"></div>
                                            <div class=\"rangos\" id=\"r_max_meses\"></div>
                                        </div>
                                        -->
                                    </div>
                                    <div class=\"form-element border-bottom-0 cuota margin\">
                                        <label for=\"cuota\">Tu cuota mensual</label>
                                        <div class=\"valor\">
                                            <span class=\"unidad mr-2\">\$</span>
                                            <span class=\"num\" id=\"cuotaVal\"></span>
                                        </div>
                                        <input type=\"text\" name=\"cuota\" id=\"cuota\" class=\"d-none\" />
                                    </div>
                                    <a href=\"/registerform\" class=\"btn1 w-100 mt-auto\">¡Solicítalo Aquí!</a>
                                </div>
                            </div>", "modules/custom/linxecredit/templates/block/simuladorhome.html.twig", "/var/www/linxe.edwcorp.com/public_html/modules/custom/linxecredit/templates/block/simuladorhome.html.twig");
    }
}
