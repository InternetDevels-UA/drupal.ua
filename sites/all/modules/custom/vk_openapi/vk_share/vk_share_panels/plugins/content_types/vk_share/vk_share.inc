<?php
/**
 * @file
 * VK share plugin for ctools module. Render a VK Share button for Panels
 */
$plugin = array(
    'single'       => TRUE,
    'title'        => t('VK share button'),
    'description'  => t('Adds plugin with VK share button'),
    'all contexts' => TRUE,
    'category'     => t('VK'),
    'defaults'      => array('group' => NULL),
    'hook theme'   => 'vk_share_theme',
    'required context' => new ctools_context_required(t('Node'), 'node'),
  );

/**
 * Implementation of MODULENAME_FILENAME_content_type_render($subtype, $conf, $panel_args, &$context)
 * This is the ctools render callback.
 */
function vk_share_panels_vk_share_content_type_render($subtype, $conf, $panel_args, $context) {
  $block = new stdClass();
  
  $button = _render_vk_share_button($context['argument_nid_1']->data);
  $block->content = theme('vk_share_button', $button['title']);
  return $block;
}

function vk_share_theme(&$theme, $plugin) {
  $theme['vk_share_button'] = array(
    'arguments' => array('button' => NULL),
    'path'      => $plugin['path'],
    'template'  => 'vk-share'
  );
}