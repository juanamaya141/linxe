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

/* themes/custom/linxe/templates/node--preguntas-frecuentes.html.twig */
class __TwigTemplate_804887a67af016de2b8501ac7c9b6fdbcdcf79a6275ec2256c0b92741f88537f extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 69];
        $filters = ["escape" => 71];
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
        // line 69
        if ((($context["view_mode"] ?? null) == "teaser")) {
            // line 70
            echo "  \t
\t<div class=\"card-header\" id=\"headingFaqs";
            // line 71
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\">
\t\t<h3 class=\"m-0\">
\t\t\t<button type=\"button\" data-toggle=\"collapse\" data-target=\"#Faqs";
            // line 73
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\" aria-expanded=\"false\" aria-controls=\"Faqs";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["label"] ?? null), 0, [])), "html", null, true);
            echo "</button>
\t\t</h3>
\t</div>
\t<div class=\"collapse\" id=\"Faqs";
            // line 76
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\" aria-labelledby=\"headingFaqs";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\" data-parent=\"#faqs\">
\t\t<div class=\"card-body\">
\t\t\t";
            // line 78
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "body", []), 0, [])), "html", null, true);
            echo "
\t\t</div>
\t</div>
";
        } else {
            // line 82
            echo "  \t<div class=\"card-header\" id=\"headingFaqs";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\">
\t\t<h3 class=\"m-0\">
\t\t\t<button type=\"button\" data-toggle=\"collapse\" data-target=\"#Faqs";
            // line 84
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\" aria-expanded=\"false\" aria-controls=\"Faqs";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["label"] ?? null), 0, [])), "html", null, true);
            echo "</button>
\t\t</h3>
\t</div>
\t<div class=\"collapse\" id=\"Faqs";
            // line 87
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\" aria-labelledby=\"headingFaqs";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["node"] ?? null), "id", [])), "html", null, true);
            echo "\" data-parent=\"#faqs\">
\t\t<div class=\"card-body\">
\t\t\t";
            // line 89
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["content"] ?? null), "body", []), 0, [])), "html", null, true);
            echo "
\t\t</div>
\t</div>
";
        }
        // line 93
        echo "\t

";
    }

    public function getTemplateName()
    {
        return "themes/custom/linxe/templates/node--preguntas-frecuentes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  119 => 93,  112 => 89,  105 => 87,  95 => 84,  89 => 82,  82 => 78,  75 => 76,  65 => 73,  60 => 71,  57 => 70,  55 => 69,);
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
 * Bartik's theme implementation to display a node.
 *
 * Available variables:
 * - node: The node entity with limited access to object properties and methods.
 *   Only method names starting with \"get\", \"has\", or \"is\" and a few common
 *   methods such as \"id\", \"label\", and \"bundle\" are available. For example:
 *   - node.getCreatedTime() will return the node creation timestamp.
 *   - node.hasField('field_example') returns TRUE if the node bundle includes
 *     field_example. (This does not indicate the presence of a value in this
 *     field.)
 *   - node.isPublished() will return whether the node is published or not.
 *   Calling other methods, such as node.delete(), will result in an exception.
 *   See \\Drupal\\node\\Entity\\Node for a full list of public properties and
 *   methods for the node object.
 * - label: The title of the node.
 * - content: All node items. Use {{ content }} to print them all,
 *   or print a subset such as {{ content.field_example }}. Use
 *   {{ content|without('field_example') }} to temporarily suppress the printing
 *   of a given child element.
 * - author_picture: The node author user entity, rendered using the \"compact\"
 *   view mode.
 * - metadata: Metadata for this node.
 * - date: Themed creation date field.
 * - author_name: Themed author name field.
 * - url: Direct URL of the current node.
 * - display_submitted: Whether submission information should be displayed.
 * - attributes: HTML attributes for the containing element.
 *   The attributes.class element may contain one or more of the following
 *   classes:
 *   - node: The current template type (also known as a \"theming hook\").
 *   - node--type-[type]: The current node type. For example, if the node is an
 *     \"Article\" it would result in \"node--type-article\". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node--view-mode-[view_mode]: The View Mode of the node; for example, a
 *     teaser would result in: \"node--view-mode-teaser\", and
 *     full: \"node--view-mode-full\".
 *   The following are controlled through the node publishing options.
 *   - node--promoted: Appears on nodes promoted to the front page.
 *   - node--sticky: Appears on nodes ordered above other non-sticky nodes in
 *     teaser listings.
 *   - node--unpublished: Appears on unpublished nodes visible only to site
 *     admins.
 * - title_attributes: Same as attributes, except applied to the main title
 *   tag that appears in the template.
 * - content_attributes: Same as attributes, except applied to the main
 *   content tag that appears in the template.
 * - author_attributes: Same as attributes, except applied to the author of
 *   the node tag that appears in the template.
 * - title_prefix: Additional output populated by modules, intended to be
 *   displayed in front of the main title tag that appears in the template.
 * - title_suffix: Additional output populated by modules, intended to be
 *   displayed after the main title tag that appears in the template.
 * - view_mode: View mode; for example, \"teaser\" or \"full\".
 * - teaser: Flag for the teaser state. Will be true if view_mode is 'teaser'.
 * - page: Flag for the full page state. Will be true if view_mode is 'full'.
 * - readmore: Flag for more state. Will be true if the teaser content of the
 *   node cannot hold the main body content.
 * - logged_in: Flag for authenticated user status. Will be true when the
 *   current user is a logged-in member.
 * - is_admin: Flag for admin user status. Will be true when the current user
 *   is an administrator.
 *
 * @see template_preprocess_node()
 */
#}
{% if view_mode == \"teaser\" %}
  \t
\t<div class=\"card-header\" id=\"headingFaqs{{node.id}}\">
\t\t<h3 class=\"m-0\">
\t\t\t<button type=\"button\" data-toggle=\"collapse\" data-target=\"#Faqs{{node.id}}\" aria-expanded=\"false\" aria-controls=\"Faqs{{node.id}}\">{{label.0}}</button>
\t\t</h3>
\t</div>
\t<div class=\"collapse\" id=\"Faqs{{node.id}}\" aria-labelledby=\"headingFaqs{{node.id}}\" data-parent=\"#faqs\">
\t\t<div class=\"card-body\">
\t\t\t{{ content.body.0 }}
\t\t</div>
\t</div>
{% else %}
  \t<div class=\"card-header\" id=\"headingFaqs{{node.id}}\">
\t\t<h3 class=\"m-0\">
\t\t\t<button type=\"button\" data-toggle=\"collapse\" data-target=\"#Faqs{{node.id}}\" aria-expanded=\"false\" aria-controls=\"Faqs{{node.id}}\">{{label.0}}</button>
\t\t</h3>
\t</div>
\t<div class=\"collapse\" id=\"Faqs{{node.id}}\" aria-labelledby=\"headingFaqs{{node.id}}\" data-parent=\"#faqs\">
\t\t<div class=\"card-body\">
\t\t\t{{ content.body.0 }}
\t\t</div>
\t</div>
{% endif %}
\t

", "themes/custom/linxe/templates/node--preguntas-frecuentes.html.twig", "/var/www/linxe.edwcorp.com/public_html/themes/custom/linxe/templates/node--preguntas-frecuentes.html.twig");
    }
}
