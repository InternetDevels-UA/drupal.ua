<?php

/**
 * Implementation of hook_node_info().
 */
function helper_page_node_info() {
  $items = array(
    'helper' => array(
      'name' => t('Допомога'),
      'module' => 'features',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Проблема'),
      'has_body' => '1',
      'body_label' => t('Суть'),
      'min_word_count' => '0',
      'help' => '',
    ),
  );
  return $items;
}

/**
 * Implementation of hook_views_api().
 */
function helper_page_views_api() {
  return array(
    'api' => '2',
  );
}
