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

/* themes/custom/linxe/templates/block--linxeloginblock.html.twig */
class __TwigTemplate_054900fdaf6939c12035296ab5f47fcd960f916fb14b21559198fec1ee70fe3e extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 33, "block" => 48];
        $filters = ["clean_class" => 35, "escape" => 49];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'block'],
                ['clean_class', 'escape'],
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
        // line 33
        $context["classes"] = [0 => "block", 1 => ("block-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed($this->getAttribute(        // line 35
($context["configuration"] ?? null), "provider", [])))), 2 => ("block-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 36
($context["plugin_id"] ?? null))))];
        // line 39
        echo "
  
<div class=\"modal-dialog modal-dialog-centered modal-md\" role=\"document\">
    <div class=\"modal-content\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><i class=\"fas fa-times\"></i></button>
        <div class=\"modal-body\">
            <div class=\"container\">
                <div class=\"row\">
                    <div class=\"col-12 cont-form\">
                        ";
        // line 48
        $this->displayBlock('content', $context, $blocks);
        // line 51
        echo "                    </div>
                </div>
            </div>
        </div>
    </div>
</div>";
    }

    // line 48
    public function block_content($context, array $blocks = [])
    {
        // line 49
        echo "\t\t\t\t\t    \t";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null)), "html", null, true);
        echo "
\t\t\t\t\t  \t";
    }

    public function getTemplateName()
    {
        return "themes/custom/linxe/templates/block--linxeloginblock.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 49,  82 => 48,  73 => 51,  71 => 48,  60 => 39,  58 => 36,  57 => 35,  56 => 33,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Default theme implementation to display a block.
 *
 * Available variables:
 * - plugin_id: The ID of the block implementation.
 * - label: The configured label of the block if visible.
 * - configuration: A list of the block's configuration values.
 *   - label: The configured label for the block.
 *   - label_display: The display settings for the label.
 *   - provider: The module or other provider that provided this block plugin.
 *   - Block plugin specific settings will also be stored here.
 * - content: The content of this block.
 * - attributes: array of HTML attributes populated by modules, intended to
 *   be added to the main container tag of this template.
 *   - id: A valid HTML ID and guaranteed unique.
 * - title_attributes: Same as attributes, except applied to the main title
 *   tag that appears in the template.
 * - content_attributes: Same as attributes, except applied to the main content
 *   tag that appears in the template.
 * - title_prefix: Additional output populated by modules, intended to be
 *   displayed in front of the main title tag that appears in the template.
 * - title_suffix: Additional output populated by modules, intended to be
 *   displayed after the main title tag that appears in the template.
 *
 * @see template_preprocess_block()
 *
 * @ingroup themeable
 */
#}
{%
  set classes = [
    'block',
    'block-' ~ configuration.provider|clean_class,
    'block-' ~ plugin_id|clean_class,
  ]
%}

  
<div class=\"modal-dialog modal-dialog-centered modal-md\" role=\"document\">
    <div class=\"modal-content\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><i class=\"fas fa-times\"></i></button>
        <div class=\"modal-body\">
            <div class=\"container\">
                <div class=\"row\">
                    <div class=\"col-12 cont-form\">
                        {% block content %}
\t\t\t\t\t    \t{{ content }}
\t\t\t\t\t  \t{% endblock %}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>", "themes/custom/linxe/templates/block--linxeloginblock.html.twig", "/var/www/linxe.edwcorp.com/public_html/themes/custom/linxe/templates/block--linxeloginblock.html.twig");
    }
}
