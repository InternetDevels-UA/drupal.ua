<?php

/**
 * Implementation of hook_theme().
 */
function idevels_theme() {
  $content_profile_extra_templates = variable_get('content_profile_extra_templates', array());
  $content_profile_extra_templates[] = 'profile_header';
  variable_set('content_profile_extra_templates', $content_profile_extra_templates);

  return array(
   'profile_header' => array(
      'arguments' => array('account' => NULL, 'teaser' => NULL),
      'template' => 'profile-header',
    ),
    'comment_form' => array(
       'arguments' => array('form' => NULL),
     ),
  );
}

/**
 * Preprocess page template.
 */
function idevels_preprocess_page(&$vars) {
//dsm($vars);
#  $vars['search'] = drupal_get_form('sphinxsearch_search_box');
//  $vars['user_menu_block'] = user_menu_block();
  $destinantion = (isset($_REQUEST['destination'])) ? $_REQUEST['destination'] : '';
  if ($destinantion == 'node/add/events' && isset($vars['tabs'])) {
    $vars['tabs'] = substr_replace($vars['tabs'], '?destination=front', 54, 0);
  }
  $node = $vars['node'];

  if (arg(0) == 'gallery') {
    unset($vars['breadcrumb']);
    $vars['head_title'] = t('Sites Gallery') .' | '. variable_get('site_name', 'Drupal');
  }
  elseif (arg(0) == 'groups' && arg(1) == '') {
    $vars['head_title'] = t('Recent in groups') .' | '. variable_get('site_name', 'Drupal');
  }
  elseif (arg(0) == 'user' && is_numeric(arg(1))) {
    // Handle profile pages.
    unset($vars['breadcrumb']);
    unset($vars['tabs']);
    unset($vars['tabs2']);
    unset($vars['title']);
    $user = user_load(arg(1));

    // We need to link user picture to account if we're on inner profile pages.
    $teaser = (bool)arg(2);

    // Attach profile header to the page (picture, title and tabs).
    $vars['pre_content'] .= theme('profile_header', $user, $teaser);
  }

  if (!$user->uid && arg(0) == 'user' && !is_numeric(arg(2))) {
    unset($vars['breadcrumb']);
    //unset($vars['tabs']);
    if (arg(1) == 'register') {
      $vars['title'] = t('Registration');
    }
  }

  $theme_key = 'idevels';
  $settings = theme_get_settings($theme_key);
  $themes = list_themes();
  $theme_object = $themes[$theme_key];

  if ($settings['toggle_logo']) {
    if ($settings['default_logo']) {
//      $logo = file_create_url(dirname($theme_object->filename) .'/drua_logo.png');
      $logo = file_create_url('drua_logo.png');
    }
    elseif ($settings['logo_path']) {
      $logo = file_create_url($settings['logo_path']);
    }
    $vars['logo'] = theme('image', $logo, '', '', NULL, FALSE);
  }

  if ($_GET['q'] == 'user/login') {
    $vars['title'] = t('Login');
  }
  elseif ($_GET['q'] == 'user/password') {
    $vars['title'] = t('Password recovery');
  }
  elseif ($_GET['q'] == 'resources') {
    $vars['title'] = t('Resources');
  }
}

/**
 * Implementation of hook_preprocess_node().
 */
function idevels_preprocess_node(&$vars) {

  global $language;
  $node = $vars['node'];

  // Different time-date format for different languages.
  switch ($language->language) {
    case 'ru':
    case 'uk':
      if (function_exists('ua_month_perfecty')) {
        $vars['date'] = ua_month_perfecty($vars['node']->created);
      }
      else {
        $vars['date'] = format_date($node->created, 'custom', "j F, Y");
      }
      break;
    case 'en':
      $vars['date'] = format_date($node->created, 'custom', "F jS, Y");
      break;
  }
  
  // REFACTOR: Document these.
  if ($node->type == 'website_showcase') {
    $node_path_arr = explode('/', $node->path);
    if ($node_path_arr[0] == 'content' && arg(0) != 'gallery') {
      drupal_goto('gallery/'. $node->nid); 
    }      
    $node->field_ws_screenshot[0]['view'] = l($node->field_ws_screenshot[0]['view'], $node->field_ws_url[0]['display_url'], array('html' => TRUE));
    $created_by_user = user_load($node->uid);

    $user_str = '<br clear="left"><br/>';
    if ($node->field_ws_author_url[0]['display_url'] != '') {
      $user_str .= l(strtoupper($created_by_user->name), $node->field_ws_author_url[0]['display_url'], array('attributes' => array('class' => 'showcase_info_link')));
    }
    else {
      $user_str .= l(strtoupper($created_by_user->name), 'users/'. $created_by_user->name, array('attributes' => array('class' => 'showcase_info_link')));
    }
    $node->username = $user_str;
    if ($node->field_ws_company_url[0]['view'] != '') {
      $node->field_ws_company_url[0]['view'] = '<br/>'. l($node->field_ws_company_url[0]['display_title'], $node->field_ws_company_url[0]['display_url'], array('attributes' => array('class' => 'showcase_info_link')));  
    }
    $node->created_text = t('Posted on') . ' ' . format_date($node->created, 'custom', 'l, F j, Y');
    //print $node->field_ws_screenshot[0]['view'];
    if ($GLOBALS['user']->uid == $node->uid || user_access('access content')) {
      $node->edit_link = '<br/>'. l(t('&laquo; Edit'), 'node/'. $node->nid .'/edit', array('html' => TRUE, 'attributes' => array('class' => 'showcase_info_link')));  ;  
    }
    else {
      $node->edit_link = '';
    }
  }

  if (module_exists('og')) {
    // Add group prefix.
    if (!$vars['page'] && og_is_group_post_type($node->type)) {
      if (!($page_node = menu_get_object() && og_is_group_type($page_node->type))) { // Do not add group prefix if we browse group nodes
        $og = $node->og_groups_both;
        
        if (!empty($og)) {
          foreach ($og as $gid => $group) {
            $og[$gid] = l($group, 'node/'. $gid);
            if ($translation = translation_node_get_translations($gid)) {
              if (isset($translation[$language->language])) {
                $og[$gid] = l($translation[$language->language]->title,
                	'node/'. $translation[$language->language]->nid);
              }
            }
            else {
              if ($group_node = node_load($gid)) {
                if ($group_node->tnid && $gid != $group_node->tnid) {
                  $translation = translation_node_get_translations($group_node->tnid);
                  if ($translation[$language->language]) {
                    $og[$gid] = l($translation[$language->language]->title,
                    	'node/'. $translation[$language->language]->nid);
                  }
                }
              }
            }
          }
          $gnode = node_load($gid);
          if ($gnode->field_group_image[0]['filepath']) {
            $vars['group_logo'] = theme('imagecache', 'tiny', $gnode->field_group_image[0]['filepath'], $prefix);
          }
          $description = $gnode->body;
          $prefix = implode(' : ', $og);
          $vars['title_prefix'] = $prefix;
          $vars['group_description'] = $description;
        }
      }
    }

    // Submitter info.
    if (og_is_group_post_type($node->type) || og_is_group_type($page_node->type)) {
      $vars['submitted'] = $vars['name'] .' / <span class="date">'. $vars['date'] .'</span>';
    }
  }
}

/**
 * Implementation of hook_preprocess_comment().
 */
function idevels_preprocess_comment(&$vars) {
  global $language;
  $comment = $vars['comment'];

  // Different time-date format for different languages.
  switch ($language->language) {
    case 'ru':
    case 'uk':
      if (function_exists('ua_month_perfecty')) {
        $vars['date'] = ua_month_perfecty($comment->timestamp);
      }
      else {
        $vars['date'] = format_date($comment->timestamp, 'custom', "j F, Y - H:i");
      }
      break;
    case 'en':
      $vars['date'] = format_date($comment->timestamp, 'custom', "F jS, Y - H:i");
      break;
  }
  $vars['user'] = theme('username', $comment);
  $vars['cid'] = $vars['comment']->cid;
}

/**
 * Format a username.
 *
 * The same as standard, but renders smal avatar & doesn;t show "not veridied".
 */
function idevels_username($object) {

  if ($object->uid && $object->name) {
    // Shorten the name when it is too long or it will break many tables.
    if (drupal_strlen($object->name) > 20) {
      $name = drupal_substr($object->name, 0, 15) .'...';
    }
    else {
      $name = $object->name;
    }
    $profile = content_profile_load('profile', $object->uid);
    if ($profile->field_image[0]) {
      $picture = $profile->field_image[0];
      if (!drupal_is_front_page()) {
        $avatar = theme('imagecache', 'tiny', $picture['filepath'], $picture['data']['alt'], $picture['data']['title']);
      }
    }

    if (user_access('access user profiles')) {
      $output .= l($avatar . $name, 'user/'. $object->uid, array('html' => TRUE, 'attributes' => array('title' => t('View user profile.'))));
    }
    else {
      $output .= $avatar . check_plain($name);
    }
    $output = '<span class="username">'. $output .'</span>';
  }
  else if ($object->name) {
    // Sometimes modules display content composed by people who are
    // not registered members of the site (e.g. mailing list or news
    // aggregator modules). This clause enables modules to display
    // the true author of the content.
    if (!empty($object->homepage)) {
      $output = l($object->name, $object->homepage, array('attributes' => array('rel' => 'nofollow')));
    }
    else {
      $output = check_plain($object->name);
    }
  }
  else {
    $output = check_plain(variable_get('anonymous', t('Anonymous')));
  }

  return $output;
}

/**
 * Implementation of hook_preprocess_block().
 */
function idevels_preprocess_block(&$vars, $hook) {
  // Allow links in block titles.
  if ($vars['block']->title_link && $vars['block']->subject) {
    $vars['block']->subject = '<a href="'. check_url($vars['block']->title_link) .'">'. $vars['block']->subject .'</a>';
  }
}


/**
 * Preprocess user profile header template. There is pretty complex code to
 * output avatar and tabs, so, we need the special care here.
 */
function idevels_preprocess_profile_header(&$vars) {
  global $user;

  //$profile_vars = $vars['content_profile']->get_variables('profile', $vars['teaser'], TRUE);
  
  $vars['user_name'] = '';
  if (isset($profile_vars['field_first_name'][0]['value']) && !empty($profile_vars['field_first_name'][0]['value'])) {
    $vars['user_name'] = check_plain($profile_vars['field_first_name'][0]['value']) .' ';
  }
  if (isset($profile_vars['field_last_name'][0]['value']) && !empty($profile_vars['field_last_name'][0]['value'])) {
    $vars['user_name'] .= check_plain($profile_vars['field_last_name'][0]['value']);
  }
  
  $vars['user_login'] = ($vars['user_name'] ? ' <span class="profile-aka">aka</span> ' : '')
    . $vars['account']->name;
  $arg = arg();
  $tabs_arr = explode("\n", theme('menu_local_tasks'));
  $edit_link = l(t('Edit'), 'user/' . arg(1) . '/edit/profile');
  if ($arg[0] == 'user' && $user->uid == $arg[1] && !user_access('access administration pages')) {
    array_splice($tabs_arr, 2, 0, '<li>' . $edit_link . '</li>');
  }
  if ($arg[0] == 'user' && in_array($arg[3], array('email', 'password')) && $user->uid == $arg[1] && user_access('access administration pages')) {
    array_splice($tabs_arr, 2, 0, '<li class="active">' . $edit_link . '</li>');
  }
  $newtabs = array(
    'profile',
    'newsletter',
    'email',
    'password'
  );
  if ($arg[2] == 'edit' && (in_array($arg[3], $newtabs)) && !user_access('access administration pages')) {
    $tabs_arr[2] = '<li class="active">' . $edit_link . '</li>';
  }
  if ($arg[2] == 'edit' && ($arg[3] == 'profile' || $arg[3] == 'newsletter')) {
    $tabs_arr = array_reverse($tabs_arr);
    foreach ($tabs_arr as $key => $tab) {
      if (preg_match("/\\/user\\/" . arg(1) . "\\/edit\"/", $tab)) {
        unset($tabs_arr[$key]);
        break;
      }
    }
    $tabs_arr = array_reverse($tabs_arr);
  }
  if ($arg[2] == 'edit' && ($arg[3] == 'email' || $arg[3] == 'password')) {

    foreach ($tabs_arr as $key => $tab) {
      if (preg_match("/\\/edit\\/email/", $tab)) {
        $new_tabs = array(
          '<li>' . l(t('Profile'), 'user/' . arg(1) . '/edit/profile') . '</li>',
          '<li>' . l(t('Newsletters'), 'user/' . arg(1) . '/edit/newsletter') . '</li>',
        );
        array_splice($tabs_arr, $key, 0, $new_tabs);
      }
    }
  }
  $tabs = implode("\n", $tabs_arr);
  /*
   * Replace patterns for:
   *  - user/%/edit -> user/%/edit/profile
   *  - 'Profile' -> t('Profile')
   *  - 'Newsletter' -> t('Newsletter')
   *  - 'View' -> t('View')
   */
  $user_link = drupal_get_path_alias('user/' . $user->uid);
  $patterns = array(
    "/\\/user\\/" . arg(1) . "\\/edit\"/",
    "/Profile/",
    "/edit\\/newsletter\"( class=\"active\")?>(.*)<\\/a><\\/li>/",
    "/" . preg_quote($user_link, '/') . "\"( class=\"active\")?>(.*)<\\/a><\\/li>/",
  );
  $replacements = array(
    '/user/' . arg(1) . '/edit/profile"',
    t('Profile'),
    'edit/newsletter"$1>' . t('Newsletters') . '</a></li>',
    $user_link . '"$1>' . t('View') . '</a></li>',
  );
  $links = preg_replace($patterns, $replacements, $tabs);
  $vars['tabs'] = $links;
}

///**
// * Search box theming.
// */
//function idevels_sphinxsearch_search_box($form) {
//  $form['inline']['submit']['#prefix'] = '<span class="button bsmall binner sblue"><span>';
//  $form['inline']['submit']['#suffix'] = '</span></span>';
//  return drupal_render($form);
//}

/**
 * Top buttons block.
 */
//function user_menu_block() {
//  global $user, $language;
//
//  if ($user->uid) {
//    $menu = '<span class="button bsmall sgreen"><span>'. l($user->name, 'user') .'</span></span>&nbsp;&nbsp;&nbsp;'.
//            '<span class="button bsmall sblue"><span>'. l(t('Tracker'), 'user/'. $user->uid .'/tracker') .'</span></span>&nbsp;'.
//            '<span class="button bsmall sblue"><span>'. l(t('Bookmarks'), 'user/'. $user->uid .'/bookmarks') .'</span></span>&nbsp;'.
//            '&nbsp;&nbsp;<span class="button bsmall sblue"><span>'. l(t('Log out'), 'logout') .'</span></span>';
//  }
//  else {
//    $options = array(
//    	'query' => array(
//    		'destination' => drupal_get_path_alias(implode('/', arg())),
//       ),
//    );
//    $menu = '<span class="button bsmall sgreen"><span>'. l(t('Log in'). ' / ' . t('Register'), 'user/login', $options) .'</span></span>&nbsp;';
//            //'<span class="button bsmall sred"><span>'. l(t('Register'), 'user/register', $options) .'</span></span>';
//  }
//
//  // Add language switcher.
//  if (arg(0) == 'node' && arg(1) != '') {
//    $node = node_load(arg(1));
//    if ($node->tnid) {
//      $translation = translation_node_get_translations($node->tnid);
//    }
//  }
//  else {
//    $path = drupal_is_front_page() ? '<front>' : $_GET['q'];
//  }
//
//  $languages = language_list('enabled');
//  foreach ($languages[1] as $lang) {
//    if ($lang->language != $language->language) {
//      if (isset($translation[$lang->language]->nid)) {
//        $path = 'node/'. $translation[$lang->language]->nid;
//      }
//      $menu .= '&nbsp;&nbsp;&nbsp;<span class="lang">'.
////        l(($lang->language == 'uk' ? t('Ukrainian language') : t('Russian language')), $path,
//        l($lang->native, $path,
//        array(
//          'language' => $lang,
//          'attributes' => array('class' => 'language-link'),
//        )) .'</span>';
//    }
//  }
//
//  return $menu;
//}

/**
 * Theme comments form.
 */
function idevels_comment_form($form) {
  // Remove author.
  if ($form['_author']) {
    unset($form['_author']);
  }

  // Handle filter slider.
  unset($form['comment_filter']['format']);
  $form['comment_filter'][1] = array(
    '#type' => 'value',
    '#value' => variable_get('filter_default_format', 1)
  );
  $tips = _filter_tips(variable_get('filter_default_format', 1), FALSE);
  $form['comment_filter']['format']['guidelines'] = array(
    '#title' => t('Formatting guidelines'),
    '#value' => theme('filter_tips', $tips, FALSE, $extra),
  );
  $form['comment_filter']['format']['#weight'] = 0.001;

  // Remove title from comments textarea and set it height to 5.
  $form['comment_filter']['comment']['#title'] = '';
  $form['comment_filter']['comment']['#rows'] = '5';

  $form['mail']['#description'] = '';

  // Place code and styles to animate the slider.
  $form['comment_filter']['format']['guidelines']['#prefix'] = '<div class="guidelines nolink">';
  $form['comment_filter']['format']['guidelines']['#suffix'] = '<a href="#" class="guidelink minor">'. t('Formatting details') .'</a></div>';
  $form['#suffix'] .= '<script type="text/javascript">
      $(".guidelines.nolink").removeClass(\'nolink\');

      $(".guidelink").click(function(){
        $(this).siblings(".tips").slideToggle("fast");
        $(this).parent().toggleClass("expanded")
        return false;
      });
    </script>';
  $form['#suffix'] .= '<div class="cleardiv"></div>';

  // Prettyfy notifier.
  unset($form['notify']['#description']);

  $output .= drupal_render($form);
  return $output;
}

/**
 * Add a "Comments" heading above comments except on forum pages.
 */
function idevels_preprocess_comment_wrapper(&$vars) {
  if ($vars['content'] && $vars['node']->type != 'forum') {
    $vars['content'] = '<h2 class="comments">'. t('Comments') .'</h2>'.  $vars['content'];
  }
  if (!user_access('post comments')) {
    $vars['content'] .= '<div class="messages warning mt40">'. theme('comment_post_forbidden', $vars['node']) .'</div>';
  }
}

/**
 * Checks if the current page is panel. This global value is being set in templates.
 * @TODO: Find a better way to do this without touching templates.
 */
if (module_exists('panels')) {
  function is_panel() {
    global $is_panel;
    return $is_panel === TRUE;
  }
}


/**
 * Profile theming 
 */

/**
 * Output user groups (in string).
 */
function idevels_preprocess_views_view_unformatted__profile_group__block_1(&$vars) {
  $output = '';
  $first = TRUE;
  foreach ($vars['view']->result as $row) {
    if ($first) {
      $first = FALSE;
    }
    else {
      $output .= ', ';
    }
    $output .= l($row->node_title, 'node/'. $row->nid);
  }
  $vars['full_rows'] = $output;
}


/**
 * User profile: Bookmarks.
 */
function idevels_preprocess_flag(&$vars) {
  if ((isset($_REQUEST['destination']) && arg(0) == 'flag' && arg(2) == 'bookmarks'
    && (strpos($_REQUEST['destination'], 'user/') === 0)) || (arg(0) == 'user' && arg(2) == '')) {
    $vars['link_text'] = '&nbsp;';
  }
}


/**
 * User profile: Bookmarks.
 * Remove references to other people's profiles
 */
function idevels_preprocess_views_view_field__profile_bookmarks__block_1__ops(&$vars) {
  global $user;
  if (!(arg(0) == 'user' && $user->uid == arg(1) && arg(2) == '')) {
    $vars['output'] = '<div class="img-flag-action">&nbsp;</div>';
  }
}


/**
 * Display a message to a user if they are not allowed to fill out a form.
 *
 * @param $node
 *   The webform node object.
 * @param $teaser
 *   If this webform is being displayed as the teaser view of the node.
 * @param $page
 *   If this webform node is being viewed as the main content of the page.
 * @param $submission_count
 *   The number of submissions this user has already submitted. Not calculated
 *   for anonymous users.
 * @param $limit_exceeded
 *   Boolean value if the submission limit for this user has been exceeded.
 * @param $allowed_roles
 *   A list of user roles that are allowed to submit this webform.
 */
function idevels_webform_view_messages($node, $teaser, $page, $submission_count, $limit_exceeded, $allowed_roles) {
  global $user;

  $type = 'notice';
  $cached = $user->uid == 0 && variable_get('cache', 0);

  // If not allowed to submit the form, give an explanation.
  if (array_search(TRUE, $allowed_roles) === FALSE && $user->uid != 1) {
    if (empty($allowed_roles)) {
      // No roles are allowed to submit the form.
      $message = t('Submissions for this form are closed.');
    }
    elseif (isset($allowed_roles[2])) {
      // The "authenticated user" role is allowed to submit and the user is currently logged-out.
      $login = url('user/login', array('query' => drupal_get_destination()));
      $register = url('user/register', array('query' => drupal_get_destination()));
      if (variable_get('user_register', 1) == 0) {
        $message = t('You must <a href="!login">login</a> to view this form.', array('!login' => $login));
      }
      else {
        $message = t('You must <a href="!login">login</a> or <a href="!register">register</a> to view this form.', array('!login' => $login, '!register' => $register));
      }
    }
    else {
      // The user must be some other role to submit.
      $message = t('You do not have permission to view this form.');
    }
  }

  // If the user has submitted before, give them a link to their submissions.
  if ($submission_count > 0 && $node->webform['submit_notice'] == 1 && !$cached) {
    if (empty($message)) {
      $message = t('You have already submitted this form.') .' '.
        t('<a href="!url">View your previous submissions</a>.', array('!url' => url('node/'. $node->nid .'/submissions')));
    }
    else {
      $message .= ' '. t('<a href="!url">View your previous submissions</a>.', array('!url' => url('node/'. $node->nid .'/submissions')));
    }
  }

  if ($page && isset($message)) {
    drupal_set_message($message, $type);
  }
}


/**
 * User profile: Bookmarks.
 * Remove references to other people's profiles
 */
function idevels_preprocess_views_view_field__profile_activity__block_1__message_id(&$vars) {
  $vars['output'] = '<div class="profile-activity-icon-'. $vars['output'] .'">&nbsp;</div>';
}


/**
 * Theming term name.
 * Fix translate term. 
 */
function idevels_preprocess_views_view_field__og_most_popular_groups_by_term__tid(&$vars) {
  global $language;
  $vars['output'] = l(tt('taxonomy:term:'. $vars['row']->term_data_tid .':name',
    $vars['row']->term_data_name, $language->language),
    'taxonomy/term/'. $vars['row']->term_data_tid);
}

function idevels_content_multiple_values($element) {
  if ($element['#field_name'] == 'field_personal_website') {
    $field_name = $element['#field_name'];
    $field = content_fields($field_name);
    $output = '';
    if ($field['multiple'] >= 1) {
      $table_id = $element['#field_name'] . '_values';
      $order_class = $element['#field_name'] . '-delta-order';
      $required = !empty($element['#required']) ? '<span class="form-required" title="' . t('This field is required.') . '">*</span>' : '';
      $header = array(
        array(
          'data'    => t('!title: !required', array(
            '!title'    => $element['#title'],
            '!required' => $required
          )),
          'colspan' => 2,
        ),
        array(
          'data'  => t('Order'),
          'class' => 'content-multiple-weight-header',
        ),
      );
      $rows = array();
      // Sort items according to '_weight' (needed when the form comes back after
      // preview or failed validation)
      $items = array();
      foreach (element_children($element) as $key) {
        if ($key !== $element['#field_name'] . '_add_more') {
          $items[$element[$key]['#delta']] = &$element[$key];
        }
      }
      uasort($items, '_content_sort_items_value_helper');
      $element[$element['#field_name'] . '_add_more']['#value'] = '';
      // Add the items as table rows.
//    if (count($items) == 3 && array_key_exists('', $items) && isset($items['']) && $items[1]['#value']['value']) {
////      $items[0]['value']['#value'] = $items[1]['#value']['value'];
////      $items[1]['value']['#value'] = '';
//    }
      if ($items[1]['#value']['value'] && !$items[0]['#value']['value']) {
        $items[0]['value']['#value'] = $items[1]['#value']['value'];
        $items[1]['value']['#value'] = '';
      }
      if (!$items[0]['value']['#value']) {
//      unset($items[0]);
      }
      foreach ($items as $delta => $item) {
        if (!$item['value']['#value'] && $delta != end(array_keys($items))) {
          continue;
        }
        $item['_weight']['#attributes']['class'] = $order_class;
        $delta_element = drupal_render($item['_weight']);
        $cells = array(
          array(
            'data'  => '',
            'class' => 'content-multiple-drag',
          ),
          drupal_render($item),
          array(
            'data'  => $delta_element,
            'class' => 'delta-order',
          ),
        );
        if ($delta != end(array_keys($items))) {
          $cells[] = array(
            'data'  => '<div></div>',
            'class' => 'row-remove',
          );
        }
        else {
          $cells[] = array(
            'data'  => drupal_render($element[$element['#field_name'] . '_add_more']),
            'class' => 'row-add',
          );
        }
        $row_class = 'draggable';
        if (!$cells[1]) {
          continue;
        }
        $rows[] = array(
          'data'  => $cells,
          'class' => $row_class,
        );
      }
      $output .= theme('table', $header, $rows, array(
        'id'    => $table_id,
        'class' => 'content-multiple-table'
      ));
      $output .= $element['#description'] ? '<div class="description">' . $element['#description'] . '</div>' : '';
      drupal_add_tabledrag($table_id, 'order', 'sibling', $order_class);
      drupal_add_js(drupal_get_path('module', 'content') . '/js/content.node_form.js');
    }
    else {
      foreach (element_children($element) as $key) {
        $output .= drupal_render($element[$key]);
      }
    }
  } else {
    $field_name = $element['#field_name'];
    $field = content_fields($field_name);
    $output = '';

    if ($field['multiple'] >= 1) {
      $table_id = $element['#field_name'] .'_values';
      $order_class = $element['#field_name'] .'-delta-order';
      $required = !empty($element['#required']) ? '<span class="form-required" title="'. t('This field is required.') .'">*</span>' : '';

      $header = array(
        array(
          'data' => t('!title: !required', array('!title' => $element['#title'], '!required' => $required)),
          'colspan' => 2
        ),
        t('Order'),
      );
      $rows = array();

      // Sort items according to '_weight' (needed when the form comes back after
      // preview or failed validation)
      $items = array();
      foreach (element_children($element) as $key) {
        if ($key !== $element['#field_name'] .'_add_more') {
          $items[] = &$element[$key];
        }
      }
      usort($items, '_content_sort_items_value_helper');

      // Add the items as table rows.
      foreach ($items as $key => $item) {
        $item['_weight']['#attributes']['class'] = $order_class;
        $delta_element = drupal_render($item['_weight']);
        $cells = array(
          array('data' => '', 'class' => 'content-multiple-drag'),
          drupal_render($item),
          array('data' => $delta_element, 'class' => 'delta-order'),
        );
        $rows[] = array(
          'data' => $cells,
          'class' => 'draggable',
        );
      }

      $output .= theme('table', $header, $rows, array('id' => $table_id, 'class' => 'content-multiple-table'));
      $output .= $element['#description'] ? '<div class="description">'. $element['#description'] .'</div>' : '';
      $output .= drupal_render($element[$element['#field_name'] .'_add_more']);

      drupal_add_tabledrag($table_id, 'order', 'sibling', $order_class);
    }
    else {
      foreach (element_children($element) as $key) {
        $output .= drupal_render($element[$key]);
      }
    }
  }

  return $output;
}

/**
 * Theming group_comments__panel_pane_2__timestamp.
 * Make months looks better. 
 */
function idevels_preprocess_views_view_field__group_comments__panel_pane_2__timestamp(&$vars) {
  if (function_exists('ua_month_perfecty')) {
    $vars['output'] = ua_month_perfecty($vars['row']->comments_timestamp);
  }
}

/**
 * Theming question__block_2__created_1.
 * Make months looks better. 
 */
function idevels_preprocess_views_view_field__question__block_2__created_1(&$vars) {
  if (function_exists('ua_month_perfecty')) {
    $vars['output'] = ua_month_perfecty($vars['view']->result[$vars['id']-1]->node_created);
  }
}

/**
 * Theming Events__panel_pane_2__field_event_date_value.
 * Wrap in time tag and add class pastevent or not-pastevent
 */
function idevels_preprocess_views_view_field__Events__panel_pane_2__field_event_date_value(&$vars) {
  if (strtotime($vars['row']->node_data_field_latitude_field_event_date_value2) < time()) {
    $vars['output'] = '<time class="pastevent">' . $vars['output'] . '</time>';
  }
  else {
    $vars['output'] = '<time class="not-pastevent">' . $vars['output'] . '</time>';
  }
}

/**
 * For Event report: if report is empty we'll show this message 'No information available yet'. 
 */
function idevels_preprocess_content_field(&$vars) {
  if ($vars['field_name'] == 'field_report' && empty($vars['items'][0]['value'])) {
    $vars['items'][0]['view'] = t('No information available yet');
    $vars['field_empty'] = FALSE;
    $vars['items'][0]['empty'] = FALSE;
  }
}

/**
 * Event page trming. Add metatags to images. 
 */
function idevels_preprocess_panels_pane(&$vars) {
  $title = $vars['display']->context['argument_nid_1']->title;
  if ($vars['pane']->subtype == 'field_events_logo') {
    $insert_to_logo = $title . '" title="' . $title;
    $vars['content'] = substr_replace($vars['content'], $insert_to_logo, strpos($vars['content'], 'alt="') + 5, 0);
  }
  elseif ($vars['pane']->subtype == 'field_photos') {
    $i = 0;
    while (strpos($vars['content'], 'alt=""') > 0) {
      $i++;
      $vars['content'] = substr_replace($vars['content'], $title . '_№' . (string) $i, strpos($vars['content'], 'alt=""') + 5, 0);
      $vars['content'] = substr_replace($vars['content'], '_№' . (string) $i, strpos($vars['content'], 'title="' . $title . '"') + 7 + strlen($title), 0);
      $vars['content'] = substr_replace($vars['content'], '_№' . (string) $i, strpos($vars['content'], 'title="' . $title . '"') + 7 + strlen($title), 0);
    }
  }
}
