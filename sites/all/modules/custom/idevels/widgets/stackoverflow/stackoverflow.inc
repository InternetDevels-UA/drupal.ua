<?php

/**
 * @file
 * Up Down Widget.
 */

/**
 * Implementation of hook_vud_widgets(). (Specialized)
 */
function idevels_stackoverflow_vud_widgets() {
  return array(
    'title' => t('stackoverflow'),
    'widget template' => 'widget',
  );
}
