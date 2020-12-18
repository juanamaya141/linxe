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

/* themes/custom/linxe/templates/html.html.twig */
class __TwigTemplate_26e590dfe012a3a4debe53581bf281820d5cbb583695d080e1bf10d26e905f3b extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = [];
        $filters = ["escape" => 2, "raw" => 4, "safe_join" => 7];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape', 'raw', 'safe_join'],
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
        echo "<!DOCTYPE html>
<html ";
        // line 2
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["html_attributes"] ?? null)), "html", null, true);
        echo ">
 <head>
    <head-placeholder token=\"";
        // line 4
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null)));
        echo "\">
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <title>";
        // line 7
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->safeJoin($this->env, $this->sandbox->ensureToStringAllowed(($context["head_title"] ?? null)), " | "));
        echo "</title>
    <link rel=\"apple-touch-icon\" sizes=\"57x57\" href=\"images/apple-icon-57x57.png\">
    <link rel=\"apple-touch-icon\" sizes=\"60x60\" href=\"images/apple-icon-60x60.png\">
    <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">
    <link rel=\"apple-touch-icon\" sizes=\"76x76\" href=\"images/apple-icon-76x76.png\">
    <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">
    <link rel=\"apple-touch-icon\" sizes=\"120x120\" href=\"images/apple-icon-120x120.png\">
    <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/apple-icon-144x144.png\">
    <link rel=\"apple-touch-icon\" sizes=\"152x152\" href=\"images/apple-icon-152x152.png\">
    <link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"images/apple-icon-180x180.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"192x192\"  href=\"images/android-icon-192x192.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"images/favicon-32x32.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"96x96\" href=\"images/favicon-96x96.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"images/favicon-16x16.png\">
    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"/images/favicon.ico\" />
    <css-placeholder token=\"";
        // line 22
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null)));
        echo "\">
    <js-placeholder token=\"";
        // line 23
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null)));
        echo "\">
 </head>
 <body ";
        // line 25
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null)), "html", null, true);
        echo ">
  ";
        // line 26
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page_top"] ?? null)), "html", null, true);
        echo "
  ";
        // line 27
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page"] ?? null)), "html", null, true);
        echo "
  ";
        // line 28
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page_bottom"] ?? null)), "html", null, true);
        echo "
  <js-bottom-placeholder token=\"";
        // line 29
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null)));
        echo "\">
    

    <!-- Start of HubSpot Embed Code -->

    <script type=\"text/javascript\" id=\"hs-script-loader\" async defer src=\"//js.hs-scripts.com/6100706.js\"></script>

    <!-- End of HubSpot Embed Code -->
    <!-- Hotjar Tracking Code for www.linxe.com -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:2044115,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>

    <script data-ad-client=\"ca-pub-6357625683511816\" async src=\"https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
 </body>
</html>";
    }

    public function getTemplateName()
    {
        return "themes/custom/linxe/templates/html.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  112 => 29,  108 => 28,  104 => 27,  100 => 26,  96 => 25,  91 => 23,  87 => 22,  69 => 7,  63 => 4,  58 => 2,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html {{ html_attributes }}>
 <head>
    <head-placeholder token=\"{{ placeholder_token|raw }}\">
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <title>{{ head_title|safe_join(' | ') }}</title>
    <link rel=\"apple-touch-icon\" sizes=\"57x57\" href=\"images/apple-icon-57x57.png\">
    <link rel=\"apple-touch-icon\" sizes=\"60x60\" href=\"images/apple-icon-60x60.png\">
    <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">
    <link rel=\"apple-touch-icon\" sizes=\"76x76\" href=\"images/apple-icon-76x76.png\">
    <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">
    <link rel=\"apple-touch-icon\" sizes=\"120x120\" href=\"images/apple-icon-120x120.png\">
    <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/apple-icon-144x144.png\">
    <link rel=\"apple-touch-icon\" sizes=\"152x152\" href=\"images/apple-icon-152x152.png\">
    <link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"images/apple-icon-180x180.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"192x192\"  href=\"images/android-icon-192x192.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"images/favicon-32x32.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"96x96\" href=\"images/favicon-96x96.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"images/favicon-16x16.png\">
    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"/images/favicon.ico\" />
    <css-placeholder token=\"{{ placeholder_token|raw }}\">
    <js-placeholder token=\"{{ placeholder_token|raw }}\">
 </head>
 <body {{ attributes }}>
  {{ page_top }}
  {{ page }}
  {{ page_bottom }}
  <js-bottom-placeholder token=\"{{ placeholder_token|raw }}\">
    

    <!-- Start of HubSpot Embed Code -->

    <script type=\"text/javascript\" id=\"hs-script-loader\" async defer src=\"//js.hs-scripts.com/6100706.js\"></script>

    <!-- End of HubSpot Embed Code -->
    <!-- Hotjar Tracking Code for www.linxe.com -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:2044115,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>

    <script data-ad-client=\"ca-pub-6357625683511816\" async src=\"https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
 </body>
</html>", "themes/custom/linxe/templates/html.html.twig", "/var/www/linxe.edwcorp.com/public_html/themes/custom/linxe/templates/html.html.twig");
    }
}
