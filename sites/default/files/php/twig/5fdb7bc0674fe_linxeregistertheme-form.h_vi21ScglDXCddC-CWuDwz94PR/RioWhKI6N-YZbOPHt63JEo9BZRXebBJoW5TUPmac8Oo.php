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

/* modules/custom/linxecredit/templates/linxeregistertheme-form.html.twig */
class __TwigTemplate_601ef1e8ea6ddfba82db8af0e7a0329aab6b19ab82ea9fddf7346873bbc34614 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = [];
        $filters = ["escape" => 6];
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
                                  
                                  <div class=\"form-row\">
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 6
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "nombre", [])), "html", null, true);
        echo "
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 11
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "apellido", [])), "html", null, true);
        echo "
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row\">
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 18
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "apellidos", [])), "html", null, true);
        echo "
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"doc\">
                                                <div class=\"form-group\">
                                                    ";
        // line 24
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "tipo_doc", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row\">
                                        
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 33
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "documento", [])), "html", null, true);
        echo "
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 38
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "feex", [])), "html", null, true);
        echo "
                                                <input type=\"text\" class=\"falseInput\" id=\"nf-expedicion\" name=\"nf-expedicion\" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row\">
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 46
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "celular", [])), "html", null, true);
        echo "
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 51
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "correo", [])), "html", null, true);
        echo "
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row\">
                                        <div class=\"col-md-12 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 58
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "actividadEconomica", [])), "html", null, true);
        echo "
                                            </div>
                                        </div>
                                    </div>

                                    <div class=\"form-row\">
                                        <div class=\"col-md-12 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 66
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "empresa", [])), "html", null, true);
        echo "
                                            </div>
                                        </div>
                                    </div>

                                    <div class=\"form-row\">
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                ";
        // line 74
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "tipoproducto", [])), "html", null, true);
        echo "
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                <div class=\"des\">
                                                    ";
        // line 80
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "destino", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id=\"layerOtro\"  >
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    ";
        // line 89
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "nombre_empresa", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    ";
        // line 96
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "nombre_contacto_rh", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    ";
        // line 103
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "email_contacto_rh", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    ";
        // line 108
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "telefono_contacto_rh", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id=\"layerAdelanto\" style=\"display:none\" >
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    <div class=\"des\">
                                                        ";
        // line 118
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "eps_adelanto", [])), "html", null, true);
        echo "
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    ";
        // line 126
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "afp_adelanto", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    <label class=\"label2 pl-md-2 text-md-left text-center\">¿HA COTIZADO LOS ÚLTIMOS 6 MESES A SEGURIDAD SOCIAL?</label>
                                                </div>
                                            </div>
                                            <div class=\"col-md-6 px-3 d-flex justify-content-center\">
                                                <div class=\"form-group d-flex justify-content-center\">
                                                    <div class=\"form-element custom-radio mr-md-4\">
                                                        ";
        // line 139
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "registra_pagos_6_meses1", [])), "html", null, true);
        echo "
                                                        <label class=\"form-check-label label\" for=\"descuento1\">Sí</label>
                                                    </div>
                                                    <div class=\"form-element custom-radio\">
                                                        ";
        // line 143
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "registra_pagos_6_meses2", [])), "html", null, true);
        echo "
                                                        <label class=\"form-check-label label\" for=\"descuento2\">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    ";
        // line 152
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "rango_salario_adelanto", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id=\"layerLibranza\" style=\"display:none\" >
                                        
                                        <div class=\"form-row\">
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    ";
        // line 162
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "fingreso", [])), "html", null, true);
        echo "
                                                    <input type=\"text\" class=\"falseInput\" id=\"nf-ingreso\" name=\"nf-ingreso\" />
                                                </div>
                                            </div>
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    ";
        // line 168
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "tipocontrato", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    <label class=\"label2 pl-md-2 text-md-left text-center\">¿TIENE ALGÚN DESCUENTO ADICIONAL A LO DE LEY?</label>
                                                </div>
                                            </div>
                                            <div class=\"col-md-6 px-3 d-flex justify-content-center\">
                                                <div class=\"form-group d-flex justify-content-center\">
                                                    <div class=\"form-element custom-radio mr-md-4\">
                                                        ";
        // line 181
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "descuento1", [])), "html", null, true);
        echo "
                                                        <label class=\"form-check-label label\" for=\"descuento1\">Sí</label>
                                                    </div>
                                                    <div class=\"form-element custom-radio\">
                                                        ";
        // line 185
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "descuento2", [])), "html", null, true);
        echo "
                                                        <label class=\"form-check-label label\" for=\"descuento2\">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-12 px-3\">
                                                <div class=\"form-group\">
                                                    ";
        // line 194
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "cargo", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class=\"form-row py-md-4 py-2\">
                                        <div class=\"col-12 px-3\">
                                            <div class=\"form-group\">
                                                <div class=\"form-element custom-check mr-0\">
                                                    ";
        // line 204
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "terminos", [])), "html", null, true);
        echo "
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row d-flex justify-content-center\">
                                        <div class=\"col-md-6 text-center\">
                                            ";
        // line 211
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["form"] ?? null), "actions", []), "submit", [])), "html", null, true);
        echo "
                                            ";
        // line 212
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_build_id", [])), "html", null, true);
        echo "
                                            ";
        // line 213
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_token", [])), "html", null, true);
        echo "
                                            ";
        // line 214
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["form"] ?? null), "form_id", [])), "html", null, true);
        echo "
                                        </div>
                                    </div>
                                    <div id=\"errorMsj\" class=\"errorMsj\"></div>

";
    }

    public function getTemplateName()
    {
        return "modules/custom/linxecredit/templates/linxeregistertheme-form.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  360 => 214,  356 => 213,  352 => 212,  348 => 211,  338 => 204,  325 => 194,  313 => 185,  306 => 181,  290 => 168,  281 => 162,  268 => 152,  256 => 143,  249 => 139,  233 => 126,  222 => 118,  209 => 108,  201 => 103,  191 => 96,  181 => 89,  169 => 80,  160 => 74,  149 => 66,  138 => 58,  128 => 51,  120 => 46,  109 => 38,  101 => 33,  89 => 24,  80 => 18,  70 => 11,  62 => 6,  55 => 1,);
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
                                  
                                  <div class=\"form-row\">
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.nombre }}
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.apellido }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row\">
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.apellidos }}
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"doc\">
                                                <div class=\"form-group\">
                                                    {{ form.tipo_doc }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row\">
                                        
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.documento }}
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.feex }}
                                                <input type=\"text\" class=\"falseInput\" id=\"nf-expedicion\" name=\"nf-expedicion\" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row\">
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.celular }}
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.correo }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row\">
                                        <div class=\"col-md-12 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.actividadEconomica }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class=\"form-row\">
                                        <div class=\"col-md-12 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.empresa }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class=\"form-row\">
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                {{ form.tipoproducto }}
                                            </div>
                                        </div>
                                        <div class=\"col-md-6 px-3\">
                                            <div class=\"form-group\">
                                                <div class=\"des\">
                                                    {{ form.destino }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id=\"layerOtro\"  >
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    {{ form.nombre_empresa }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    {{ form.nombre_contacto_rh }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    {{ form.email_contacto_rh }}
                                                </div>
                                            </div>
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    {{ form.telefono_contacto_rh }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id=\"layerAdelanto\" style=\"display:none\" >
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    <div class=\"des\">
                                                        {{ form.eps_adelanto }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    {{ form.afp_adelanto }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    <label class=\"label2 pl-md-2 text-md-left text-center\">¿HA COTIZADO LOS ÚLTIMOS 6 MESES A SEGURIDAD SOCIAL?</label>
                                                </div>
                                            </div>
                                            <div class=\"col-md-6 px-3 d-flex justify-content-center\">
                                                <div class=\"form-group d-flex justify-content-center\">
                                                    <div class=\"form-element custom-radio mr-md-4\">
                                                        {{ form.registra_pagos_6_meses1 }}
                                                        <label class=\"form-check-label label\" for=\"descuento1\">Sí</label>
                                                    </div>
                                                    <div class=\"form-element custom-radio\">
                                                        {{ form.registra_pagos_6_meses2 }}
                                                        <label class=\"form-check-label label\" for=\"descuento2\">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-12 px-3\">
                                                <div class=\"form-group\">
                                                    {{ form.rango_salario_adelanto }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id=\"layerLibranza\" style=\"display:none\" >
                                        
                                        <div class=\"form-row\">
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    {{ form.fingreso }}
                                                    <input type=\"text\" class=\"falseInput\" id=\"nf-ingreso\" name=\"nf-ingreso\" />
                                                </div>
                                            </div>
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    {{ form.tipocontrato }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-md-6 px-3\">
                                                <div class=\"form-group\">
                                                    <label class=\"label2 pl-md-2 text-md-left text-center\">¿TIENE ALGÚN DESCUENTO ADICIONAL A LO DE LEY?</label>
                                                </div>
                                            </div>
                                            <div class=\"col-md-6 px-3 d-flex justify-content-center\">
                                                <div class=\"form-group d-flex justify-content-center\">
                                                    <div class=\"form-element custom-radio mr-md-4\">
                                                        {{ form.descuento1 }}
                                                        <label class=\"form-check-label label\" for=\"descuento1\">Sí</label>
                                                    </div>
                                                    <div class=\"form-element custom-radio\">
                                                        {{ form.descuento2 }}
                                                        <label class=\"form-check-label label\" for=\"descuento2\">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=\"form-row\">
                                            <div class=\"col-12 px-3\">
                                                <div class=\"form-group\">
                                                    {{ form.cargo }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class=\"form-row py-md-4 py-2\">
                                        <div class=\"col-12 px-3\">
                                            <div class=\"form-group\">
                                                <div class=\"form-element custom-check mr-0\">
                                                    {{ form.terminos }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-row d-flex justify-content-center\">
                                        <div class=\"col-md-6 text-center\">
                                            {{ form.actions.submit }}
                                            {{ form.form_build_id }}
                                            {{ form.form_token }}
                                            {{ form.form_id }}
                                        </div>
                                    </div>
                                    <div id=\"errorMsj\" class=\"errorMsj\"></div>

", "modules/custom/linxecredit/templates/linxeregistertheme-form.html.twig", "/var/www/linxe.edwcorp.com/public_html/modules/custom/linxecredit/templates/linxeregistertheme-form.html.twig");
    }
}
