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

/* themes/custom/linxe/templates/page--front.html.twig */
class __TwigTemplate_0456aa14a32f56c78fbfead459f02856cbcd91697e4de83761b860677c6a0128 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 158];
        $filters = ["escape" => 3];
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


    <main id=\"content\">
        <div class=\"main-wrap\">
            <!-- Slider principal -->
            <div id=\"slider-home\">
                ";
        // line 21
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "newsliderhome", [])), "html", null, true);
        echo "

                <div class=\"tabbers tabbers-simulador\">
                    <ul>
                        <li data-target=\"credito\" class=\"tab tab-active\">
                            CRÉDITO
                        </li>
                        <li data-target=\"adelanto\" class=\"tab\">
                            ADELANTO
                        </li>
                    </ul>

                    <div class=\"tabs-cont\">
                        <div class=\"cotizador tab-c\" id=\"credito\">
                            <div class=\"col-12 d-flex justify-content-md-between justify-content-center flex-md-row flex-column w-100 px-md-3 px-0\">
                                <div class=\"d-flex mt-0 px-0 order-md-2 order-1\">
                                    ";
        // line 37
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "main_simulador", [])), "html", null, true);
        echo "
                                </div>
                            </div>
                        </div>
                        <div class=\"adelanto tab-c oculto\" id=\"adelanto\">
                            ";
        // line 42
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "adelanto_simulador", [])), "html", null, true);
        echo "
                        </div>

                    </div>
                </div>

                
                
            </div>
            <!-- Slider principal Fin-->

            <!-- slider caracteristicas -->
            
            ";
        // line 55
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "dest_beneficios", [])), "html", null, true);
        echo "
                
            <!-- slider caracteristicas -->


            <!-- banner uno -->
            <div class=\"banner-uno\" style=\"background: url(";
        // line 61
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["url_bannersecondary"] ?? null)), "html", null, true);
        echo "); background-size: cover;\">
                ";
        // line 62
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "banner_requisitos", [])), "html", null, true);
        echo "
            </div>
            <!-- banner uno fin -->

            <!-- Requisitos -->
            <div class=\"requisitos\">
                <div class=\"cont-requisitos\">
                    <div class=\"titulos\">
                        ";
        // line 70
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "texto_requisitos", [])), "html", null, true);
        echo "
                    </div>
                    
                    <div class=\"carousel\">
                        ";
        // line 74
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "seccion_requisitos", [])), "html", null, true);
        echo "
                    </div>
                </div>

                
                ";
        // line 79
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "mockup_requisitos", [])), "html", null, true);
        echo "
                
            </div>
            <!-- Requisitos fin -->

            <!-- contratos -->
            <div class=\"contratos\" style=\"background: url(";
        // line 85
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["url_bannerthird"] ?? null)), "html", null, true);
        echo "); background-size: cover;\">
                ";
        // line 86
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "banner_contrato", [])), "html", null, true);
        echo "
            </div>
            <!-- contratos fin -->

            <!-- como funcion -->
            <div class=\"como-funciona\">
                <div class=\"titulos\">
                    ";
        // line 93
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "texto_comofunciona", [])), "html", null, true);
        echo "
                </div>

                <div class=\"tabbers\">
                    <ul>
                        <li data-target=\"empleados\" class=\"tab tab-active\">
                            EMPLEADOS
                        </li>
                        <li data-target=\"empresas\" class=\"tab\">
                            EMPRESAS
                        </li>
                    </ul>

                    <div class=\"tabs-cont\">
                        <div id=\"empleados\" class=\"tab-c\">
                            ";
        // line 108
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "seccion_comofunciona", [])), "html", null, true);
        echo "
                        </div>

                        <div id=\"empresas\" class=\"tab-c oculto\">
                            ";
        // line 112
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "seccion_comofunciona_empresa", [])), "html", null, true);
        echo "
                        </div>
                    </div>
                </div>
            </div>
            <!-- como funcion fin -->

            <!-- aliados -->
            <div class=\"aliados\">
                <div class=\"titulos\">
                    ";
        // line 122
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "texto_aliados", [])), "html", null, true);
        echo "
                </div>

                <div class=\"carousel\">
                    ";
        // line 126
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "seccion_aliados", [])), "html", null, true);
        echo "
                </div>
            </div>
            <!-- aliados fin -->
        </div>
    </main>

    <footer>
        <div class=\"cont-footer\">
            ";
        // line 135
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer_menu", [])), "html", null, true);
        echo "

            ";
        // line 137
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer_contacto", [])), "html", null, true);
        echo "
        </div>

        <div class=\"redes\">
            ";
        // line 141
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer_redes", [])), "html", null, true);
        echo "
        </div>

        <div class=\"copy-foot\">
            ";
        // line 145
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer_copyright", [])), "html", null, true);
        echo "
        </div>
    </footer>

    <div id=\"loginformlayer\" class=\"modal fade\" role=\"dialog\">
        ";
        // line 150
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "login_form", [])), "html", null, true);
        echo "
    </div>
    <div id=\"rememberformlayer\" class=\"modal fade\" role=\"dialog\">
        ";
        // line 153
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "remember_form", [])), "html", null, true);
        echo "
    </div>

    <!-- modal -->

    ";
        // line 158
        if ($this->getAttribute(($context["page"] ?? null), "popupwebinar_form", [])) {
            // line 159
            echo "            ";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "popupwebinar_form", [])), "html", null, true);
            echo "
    ";
        }
        // line 161
        echo "    <!-- modal fin -->
";
    }

    public function getTemplateName()
    {
        return "themes/custom/linxe/templates/page--front.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  300 => 161,  294 => 159,  292 => 158,  284 => 153,  278 => 150,  270 => 145,  263 => 141,  256 => 137,  251 => 135,  239 => 126,  232 => 122,  219 => 112,  212 => 108,  194 => 93,  184 => 86,  180 => 85,  171 => 79,  163 => 74,  156 => 70,  145 => 62,  141 => 61,  132 => 55,  116 => 42,  108 => 37,  89 => 21,  77 => 12,  72 => 10,  65 => 6,  59 => 3,  55 => 1,);
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


    <main id=\"content\">
        <div class=\"main-wrap\">
            <!-- Slider principal -->
            <div id=\"slider-home\">
                {{ page.newsliderhome }}

                <div class=\"tabbers tabbers-simulador\">
                    <ul>
                        <li data-target=\"credito\" class=\"tab tab-active\">
                            CRÉDITO
                        </li>
                        <li data-target=\"adelanto\" class=\"tab\">
                            ADELANTO
                        </li>
                    </ul>

                    <div class=\"tabs-cont\">
                        <div class=\"cotizador tab-c\" id=\"credito\">
                            <div class=\"col-12 d-flex justify-content-md-between justify-content-center flex-md-row flex-column w-100 px-md-3 px-0\">
                                <div class=\"d-flex mt-0 px-0 order-md-2 order-1\">
                                    {{ page.main_simulador }}
                                </div>
                            </div>
                        </div>
                        <div class=\"adelanto tab-c oculto\" id=\"adelanto\">
                            {{ page.adelanto_simulador }}
                        </div>

                    </div>
                </div>

                
                
            </div>
            <!-- Slider principal Fin-->

            <!-- slider caracteristicas -->
            
            {{ page.dest_beneficios }}
                
            <!-- slider caracteristicas -->


            <!-- banner uno -->
            <div class=\"banner-uno\" style=\"background: url({{ url_bannersecondary }}); background-size: cover;\">
                {{ page.banner_requisitos }}
            </div>
            <!-- banner uno fin -->

            <!-- Requisitos -->
            <div class=\"requisitos\">
                <div class=\"cont-requisitos\">
                    <div class=\"titulos\">
                        {{ page.texto_requisitos }}
                    </div>
                    
                    <div class=\"carousel\">
                        {{ page.seccion_requisitos }}
                    </div>
                </div>

                
                {{ page.mockup_requisitos }}
                
            </div>
            <!-- Requisitos fin -->

            <!-- contratos -->
            <div class=\"contratos\" style=\"background: url({{ url_bannerthird }}); background-size: cover;\">
                {{ page.banner_contrato }}
            </div>
            <!-- contratos fin -->

            <!-- como funcion -->
            <div class=\"como-funciona\">
                <div class=\"titulos\">
                    {{ page.texto_comofunciona }}
                </div>

                <div class=\"tabbers\">
                    <ul>
                        <li data-target=\"empleados\" class=\"tab tab-active\">
                            EMPLEADOS
                        </li>
                        <li data-target=\"empresas\" class=\"tab\">
                            EMPRESAS
                        </li>
                    </ul>

                    <div class=\"tabs-cont\">
                        <div id=\"empleados\" class=\"tab-c\">
                            {{ page.seccion_comofunciona }}
                        </div>

                        <div id=\"empresas\" class=\"tab-c oculto\">
                            {{ page.seccion_comofunciona_empresa }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- como funcion fin -->

            <!-- aliados -->
            <div class=\"aliados\">
                <div class=\"titulos\">
                    {{page.texto_aliados}}
                </div>

                <div class=\"carousel\">
                    {{page.seccion_aliados}}
                </div>
            </div>
            <!-- aliados fin -->
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
    </div>

    <!-- modal -->

    {% if page.popupwebinar_form %}
            {{ page.popupwebinar_form }}
    {% endif %}
    <!-- modal fin -->
", "themes/custom/linxe/templates/page--front.html.twig", "/var/www/linxe.edwcorp.com/public_html/themes/custom/linxe/templates/page--front.html.twig");
    }
}
