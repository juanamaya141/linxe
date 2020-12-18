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

/* themes/custom/linxe/templates/node--webinar.html.twig */
class __TwigTemplate_bae3a669829ea6b53137628d3171df4c4f35ffce20c780a5ea5ecafdfaf91cac extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 99];
        $filters = ["escape" => 8, "date" => 102];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set'],
                ['escape', 'date'],
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

\t\t\t<!-- introduccion fin-->
            <!-- banner uno -->
            <div class=\"banner-uno bg-planear\">
            \t
            \t<div class=\"cont-banner-webinar\">
                    ";
        // line 8
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_titulo_banner_landing", []), 0, [])), "html", null, true);
        echo "
            \t</div>

            \t<div class=\"cont-banner\">
            \t\t";
        // line 12
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_texto_banner_landing", []), 0, [])), "html", null, true);
        echo "
                    <div class=\"cont-btn\">
                        <a href=\"#\" class=\"btn-general show-modal-webinar\" onclick=\"mostrarpp()\">SEPARA TU CUPO AQUÍ</a>
                    </div>
                </div>
            </div>
            <!-- banner uno fin -->

            <!-- expositor  -->
            <div class=\"expositor\">
                <div class=\"info-expo\">
                    <div class=\"foto-expo\">
                        <img src=\"";
        // line 24
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_imagen_expositor", []), 0, [])), "html", null, true);
        echo "\" alt=\"";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_nombre_expositor", []), 0, [])), "html", null, true);
        echo "\" />
                    </div>
                    <ul class=\"redes-expo\">
                        <li>
                            <a href=\"";
        // line 28
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_expositor_instagram", []), 0, [])), "html", null, true);
        echo "\" target=\"_Blank\">
                                <i class=\"fab fa-instagram\"></i>
                            </a>
                        </li>
                        <li>
                            <a href=\"";
        // line 33
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_expositor_linkedin", []), 0, [])), "html", null, true);
        echo "\" target=\"_Blank\">
                                <i class=\"fab fa-linkedin-in\"></i>
                            </a>
                        </li>
                        <li>
                            <a href=\"";
        // line 38
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_expositor_facebook", []), 0, [])), "html", null, true);
        echo "\" target=\"_Blank\">
                                <i class=\"fab fa-facebook-f\"></i>
                            </a>
                        </li>
                    </ul>

                    <h4>";
        // line 44
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_nombre_expositor", []), 0, [])), "html", null, true);
        echo "</h4>
                    <p>";
        // line 45
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_subtitulo_expositor", []), 0, [])), "html", null, true);
        echo "</p>
                </div>

                <div class=\"descrip-expo\">
                    <p>";
        // line 49
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_descripcion_expositor", []), 0, [])), "html", null, true);
        echo "</p>
                </div>

            </div>
            <!-- expositor fin -->

            <!-- webinar -->
            <div class=\"requisitos webinar-gratuito\">
            \t";
        // line 57
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "body", []), 0, [])), "html", null, true);
        echo "
            </div>
            <!-- webinar fin -->

            <!-- tiempo webinar -->
            <div class=\"tiempo-webinar\">
                <h2>";
        // line 63
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_fecha_y_hora_webinar", []), 0, [])), "html", null, true);
        echo "</h2>
                <h3>";
        // line 64
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_zona_horaria", []), 0, [])), "html", null, true);
        echo "</h3>
                <div class=\"cont-btn\">
                    <a href=\"#\" class=\"btn-general show-modal-webinar\" onclick=\"mostrarpp()\">SEPARA TU CUPO AQUÍ</a>
                </div>

\t\t\t\t

   \t\t\t\t
   \t\t\t\t<br>
   \t\t\t\t
\t\t\t\t<div id=\"elem\">

\t\t\t\t</div>
                <div class=\"cols3\">
                    <div class=\"col\">
                        <div class=\"bg-tiempo\">
                            <p id=\"dias_class\">03</p>
                            <span>DÍAS</span>
                        </div>
                    </div>
                    <div class=\"col\">
                        <div class=\"bg-tiempo\">
                            <p id=\"horas_class\">10</p>
                            <span>HORAS</span>
                        </div>
                    </div>
                    <div class=\"col\">
                        <div class=\"bg-tiempo\">
                            <p id=\"minutos_class\">50</p>
                            <span>MINUTOS</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- tiempo webinar fin-->
            ";
        // line 99
        $context["event_start_date"] = $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["content"] ?? null), "field_fecha_y_hora_webinar", []), 0, []), "#attributes", [], "array"), "datetime", [], "array");
        // line 100
        echo "            <script type=\"text/javascript\">

            \tvar fechahora = \"";
        // line 102
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["event_start_date"] ?? null)), "M d Y H:m:s", "America/Bogota"), "html", null, true);
        echo " GMT-0500\";
            \t
            </script>



\t

";
    }

    public function getTemplateName()
    {
        return "themes/custom/linxe/templates/node--webinar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  199 => 102,  195 => 100,  193 => 99,  155 => 64,  151 => 63,  142 => 57,  131 => 49,  124 => 45,  120 => 44,  111 => 38,  103 => 33,  95 => 28,  86 => 24,  71 => 12,  64 => 8,  55 => 1,);
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

\t\t\t<!-- introduccion fin-->
            <!-- banner uno -->
            <div class=\"banner-uno bg-planear\">
            \t
            \t<div class=\"cont-banner-webinar\">
                    {{content.field_titulo_banner_landing.0}}
            \t</div>

            \t<div class=\"cont-banner\">
            \t\t{{content.field_texto_banner_landing.0}}
                    <div class=\"cont-btn\">
                        <a href=\"#\" class=\"btn-general show-modal-webinar\" onclick=\"mostrarpp()\">SEPARA TU CUPO AQUÍ</a>
                    </div>
                </div>
            </div>
            <!-- banner uno fin -->

            <!-- expositor  -->
            <div class=\"expositor\">
                <div class=\"info-expo\">
                    <div class=\"foto-expo\">
                        <img src=\"{{content.field_imagen_expositor.0}}\" alt=\"{{content.field_nombre_expositor.0}}\" />
                    </div>
                    <ul class=\"redes-expo\">
                        <li>
                            <a href=\"{{content.field_expositor_instagram.0}}\" target=\"_Blank\">
                                <i class=\"fab fa-instagram\"></i>
                            </a>
                        </li>
                        <li>
                            <a href=\"{{content.field_expositor_linkedin.0}}\" target=\"_Blank\">
                                <i class=\"fab fa-linkedin-in\"></i>
                            </a>
                        </li>
                        <li>
                            <a href=\"{{content.field_expositor_facebook.0}}\" target=\"_Blank\">
                                <i class=\"fab fa-facebook-f\"></i>
                            </a>
                        </li>
                    </ul>

                    <h4>{{content.field_nombre_expositor.0}}</h4>
                    <p>{{content.field_subtitulo_expositor.0}}</p>
                </div>

                <div class=\"descrip-expo\">
                    <p>{{content.field_descripcion_expositor.0}}</p>
                </div>

            </div>
            <!-- expositor fin -->

            <!-- webinar -->
            <div class=\"requisitos webinar-gratuito\">
            \t{{content.body.0}}
            </div>
            <!-- webinar fin -->

            <!-- tiempo webinar -->
            <div class=\"tiempo-webinar\">
                <h2>{{content.field_fecha_y_hora_webinar.0}}</h2>
                <h3>{{content.field_zona_horaria.0}}</h3>
                <div class=\"cont-btn\">
                    <a href=\"#\" class=\"btn-general show-modal-webinar\" onclick=\"mostrarpp()\">SEPARA TU CUPO AQUÍ</a>
                </div>

\t\t\t\t

   \t\t\t\t
   \t\t\t\t<br>
   \t\t\t\t
\t\t\t\t<div id=\"elem\">

\t\t\t\t</div>
                <div class=\"cols3\">
                    <div class=\"col\">
                        <div class=\"bg-tiempo\">
                            <p id=\"dias_class\">03</p>
                            <span>DÍAS</span>
                        </div>
                    </div>
                    <div class=\"col\">
                        <div class=\"bg-tiempo\">
                            <p id=\"horas_class\">10</p>
                            <span>HORAS</span>
                        </div>
                    </div>
                    <div class=\"col\">
                        <div class=\"bg-tiempo\">
                            <p id=\"minutos_class\">50</p>
                            <span>MINUTOS</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- tiempo webinar fin-->
            {% set event_start_date = content.field_fecha_y_hora_webinar.0[\"#attributes\"][\"datetime\"] %}
            <script type=\"text/javascript\">

            \tvar fechahora = \"{{ event_start_date|date('M d Y H:m:s', 'America/Bogota') }} GMT-0500\";
            \t
            </script>



\t

", "themes/custom/linxe/templates/node--webinar.html.twig", "/var/www/linxe.edwcorp.com/public_html/themes/custom/linxe/templates/node--webinar.html.twig");
    }
}
