<?php

namespace Drupal\contactomodule\Plugin\views\query;

use Drupal\views\Plugin\views\query\QueryPluginBase;

/**
 * ContactoBusiness views query plugin which wraps calls to the ContactoBusiness API in order to
 * expose the results to views.
 *
 * @ViewsQuery(
 *   id = "contactobusiness",
 *   title = @Translation("Contacto Business"),
 *   help = @Translation("Query against the Contacto Business API.")
 * )
 */
class ContactoBusiness extends QueryPluginBase {

	public function ensureTable($table, $relationship = NULL) {
	  return '';
	}
	public function addField($table, $field, $alias = '', $params = array()) {
	  return $field;
	}

	
}