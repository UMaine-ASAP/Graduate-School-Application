<?php
// $Id: template.php,v 1.1.2.15 2009/05/13 11:19:43 jwolf Exp $

/**
 * Initialize theme settings
 */
if (is_null(theme_get_setting('user_notverified_display')) || theme_get_setting('rebuild_registry')) {
	
  // Auto-rebuild the theme registry during theme development.
  if(theme_get_setting('rebuild_registry')) {
    drupal_set_message(t('The theme registry has been rebuilt. <a href="!link">Turn off</a> this feature on production websites.', array('!link' => url('admin/build/themes/settings/' . $GLOBALS['theme']))), 'warning');
  }

  global $theme_key;
  // Get node types
  $node_types = node_get_types('names');
  
  /**
   * The default values for the theme variables. Make sure $defaults exactly
   * matches the $defaults in the theme-settings.php file.
   */
  $defaults = array(
    'user_notverified_display'              => 1,
    'breadcrumb_display'                    => 0,
    'search_snippet'                        => 1,
    'search_info_type'                      => 1,
    'search_info_user'                      => 1,
    'search_info_date'                      => 1,
    'search_info_comment'                   => 1,
    'search_info_upload'                    => 1,
    'mission_statement_pages'               => 'home',
    'front_page_title_display'              => 'title_slogan',
    'page_title_display_custom'             => '',
    'other_page_title_display'              => 'ptitle_slogan',
    'other_page_title_display_custom'       => '',
    'configurable_separator'                => ' | ',
    'meta_keywords'                         => '',
    'meta_description'                      => '',
    'taxonomy_display_default'              => 'only',
    'taxonomy_format_default'               => 'vocab',
    'taxonomy_enable_content_type'          => 0,
    'submitted_by_author_default'           => 1,
    'submitted_by_date_default'             => 1,
    'submitted_by_enable_content_type'      => 0,
    'readmore_default'                      => t('Read more'),
    'readmore_title_default'                => t('Read the rest of this posting.'),
    'readmore_prefix_default'               => '',
    'readmore_suffix_default'               => '',
    'readmore_enable_content_type'          => 0,
    'comment_singular_default'              => t('1 comment'),
    'comment_plural_default'                => t('@count comments'),
    'comment_title_default'                 => t('Jump to the first comment of this posting.'),
    'comment_prefix_default'                => '',
    'comment_suffix_default'                => '',
    'comment_new_singular_default'          => t('1 new comment'),
    'comment_new_plural_default'            => t('@count new comments'),
    'comment_new_title_default'             => t('Jump to the first new comment of this posting.'),
    'comment_new_prefix_default'            => '',
    'comment_new_suffix_default'            => '',
    'comment_add_default'                   => t('Add new comment'),
    'comment_add_title_default'             => t('Add a new comment to this page.'),
    'comment_add_prefix_default'            => '',
    'comment_add_suffix_default'            => '',
    'comment_node_default'                  => t('Add new comment'),
    'comment_node_title_default'            => t('Share your thoughts and opinions related to this posting.'),
    'comment_node_prefix_default'           => '',
    'comment_node_suffix_default'           => '',
    'comment_enable_content_type'           => 0,
    'rebuild_registry'                      => 0,
  );
  
  // Make the default content-type settings the same as the default theme settings,
  // so we can tell if content-type-specific settings have been altered.
  $defaults = array_merge($defaults, theme_get_settings());
  
  // Set the default values for content-type-specific settings
  foreach ($node_types as $type => $name) {
    $defaults["taxonomy_display_{$type}"]         = $defaults['taxonomy_display_default'];
    $defaults["taxonomy_format_{$type}"]          = $defaults['taxonomy_format_default'];
    $defaults["submitted_by_author_{$type}"]      = $defaults['submitted_by_author_default'];
    $defaults["submitted_by_date_{$type}"]        = $defaults['submitted_by_date_default'];
    $defaults["readmore_{$type}"]                 = $defaults['readmore_default'];
    $defaults["readmore_title_{$type}"]           = $defaults['readmore_title_default'];
    $defaults["readmore_prefix_{$type}"]          = $defaults['readmore_prefix_default'];
    $defaults["readmore_suffix_{$type}"]          = $defaults['readmore_suffix_default'];
    $defaults["comment_singular_{$type}"]         = $defaults['comment_singular_default'];
    $defaults["comment_plural_{$type}"]           = $defaults['comment_plural_default'];
    $defaults["comment_title_{$type}"]            = $defaults['comment_title_default'];
    $defaults["comment_prefix_{$type}"]           = $defaults['comment_prefix_default'];
    $defaults["comment_suffix_{$type}"]           = $defaults['comment_suffix_default'];
    $defaults["comment_new_singular_{$type}"]     = $defaults['comment_new_singular_default'];
    $defaults["comment_new_plural_{$type}"]       = $defaults['comment_new_plural_default'];
    $defaults["comment_new_title_{$type}"]        = $defaults['comment_new_title_default'];
    $defaults["comment_new_prefix_{$type}"]       = $defaults['comment_new_prefix_default'];
    $defaults["comment_new_suffix_{$type}"]       = $defaults['comment_new_suffix_default'];
    $defaults["comment_add_{$type}"]              = $defaults['comment_add_default'];
    $defaults["comment_add_title_{$type}"]        = $defaults['comment_add_title_default'];
    $defaults["comment_add_prefix_{$type}"]       = $defaults['comment_add_prefix_default'];
    $defaults["comment_add_suffix_{$type}"]       = $defaults['comment_add_suffix_default'];
    $defaults["comment_node_{$type}"]             = $defaults['comment_node_default'];
    $defaults["comment_node_title_{$type}"]       = $defaults['comment_node_title_default'];
    $defaults["comment_node_prefix_{$type}"]      = $defaults['comment_node_prefix_default'];
    $defaults["comment_node_suffix_{$type}"]      = $defaults['comment_node_suffix_default'];
  }
  
  // Get default theme settings.
  $settings = theme_get_settings($theme_key);
  
  // If content type-specifc settings are not enabled, reset the values
  if (!$settings['readmore_enable_content_type']) {
    foreach ($node_types as $type => $name) {
      $settings["readmore_{$type}"]                    = $settings['readmore_default'];
      $settings["readmore_title_{$type}"]              = $settings['readmore_title_default'];
      $settings["readmore_prefix_{$type}"]             = $settings['readmore_prefix_default'];
      $settings["readmore_suffix_{$type}"]             = $settings['readmore_suffix_default'];
    }
  }
  if (!$settings['comment_enable_content_type']) {
    foreach ($node_types as $type => $name) {
      $defaults["comment_singular_{$type}"]         = $defaults['comment_singular_default'];
      $defaults["comment_plural_{$type}"]           = $defaults['comment_plural_default'];
      $defaults["comment_title_{$type}"]            = $defaults['comment_title_default'];
      $defaults["comment_prefix_{$type}"]           = $defaults['comment_prefix_default'];
      $defaults["comment_suffix_{$type}"]           = $defaults['comment_suffix_default'];
      $defaults["comment_new_singular_{$type}"]     = $defaults['comment_new_singular_default'];
      $defaults["comment_new_plural_{$type}"]       = $defaults['comment_new_plural_default'];
      $defaults["comment_new_title_{$type}"]        = $defaults['comment_new_title_default'];
      $defaults["comment_new_prefix_{$type}"]       = $defaults['comment_new_prefix_default'];
      $defaults["comment_new_suffix_{$type}"]       = $defaults['comment_new_suffix_default'];
      $defaults["comment_add_{$type}"]              = $defaults['comment_add_default'];
      $defaults["comment_add_title_{$type}"]        = $defaults['comment_add_title_default'];
      $defaults["comment_add_prefix_{$type}"]       = $defaults['comment_add_prefix_default'];
      $defaults["comment_add_suffix_{$type}"]       = $defaults['comment_add_suffix_default'];
      $defaults["comment_node_{$type}"]             = $defaults['comment_node_default'];
      $defaults["comment_node_title_{$type}"]       = $defaults['comment_node_title_default'];
      $defaults["comment_node_prefix_{$type}"]      = $defaults['comment_node_prefix_default'];
      $defaults["comment_node_suffix_{$type}"]      = $defaults['comment_node_suffix_default'];
    }
  }
  
  // Don't save the toggle_node_info_ variables
  if (module_exists('node')) {
    foreach (node_get_types() as $type => $name) {
      unset($settings['toggle_node_info_'. $type]);
    }
  }
  // Save default theme settings
  variable_set(
    str_replace('/', '_', 'theme_'. $theme_key .'_settings'),
    array_merge($defaults, $settings)
  );
  // Force refresh of Drupal internals
  theme_get_setting('', TRUE);
}


/**
 * Modify theme variables
 */
function phptemplate_preprocess(&$vars) {
  global $user;                                            // Get the current user
  $vars['is_admin'] = in_array('admin', $user->roles);     // Check for Admin, logged in
  $vars['logged_in'] = ($user->uid > 0) ? TRUE : FALSE;
}


function phptemplate_preprocess_page(&$vars) {
  // Remove sidebars if disabled
  if (!$vars['show_blocks']) {
    $vars['sidebar_first'] = '';
    $vars['sidebar_last'] = '';
  }
  // Build array of helpful body classes
  $body_classes = array();
  $body_classes[] = ($vars['logged_in']) ? 'logged-in' : 'not-logged-in';                                 // Page user is logged in
  $body_classes[] = ($vars['is_front']) ? 'front' : 'not-front';                                          // Page is front page
  if (isset($vars['node'])) {
    $body_classes[] = ($vars['node']) ? 'full-node' : '';                                                   // Page is one full node
    $body_classes[] = (($vars['node']->type == 'forum') || (arg(0) == 'forum')) ? 'forum' : '';             // Page is Forum page
    $body_classes[] = ($vars['node']->type) ? 'node-type-'. $vars['node']->type : '';                       // Page has node-type-x, e.g., node-type-page
  }
  else {
    $body_classes[] = (arg(0) == 'forum') ? 'forum' : '';                                                   // Page is Forum page
  }
  $body_classes[] = (module_exists('panels_page') && (panels_page_get_current())) ? 'panels' : '';        // Page is Panels page
  $body_classes[] = 'layout-'. (($vars['sidebar_first']) ? 'first-main' : 'main') . (($vars['sidebar_last']) ? '-last' : '');  // Page sidebars are active
  if ($vars['preface_first'] || $vars['preface_middle'] || $vars['preface_last']) {                       // Preface regions are active
    $preface_regions = 'preface';
    $preface_regions .= ($vars['preface_first']) ? '-first' : '';
    $preface_regions .= ($vars['preface_middle']) ? '-middle' : '';
    $preface_regions .= ($vars['preface_last']) ? '-last' : '';
    $body_classes[] = $preface_regions;
  }
  if ($vars['postscript_first'] || $vars['postscript_middle'] || $vars['postscript_last']) {              // Postscript regions are active
    $postscript_regions = 'postscript';
    $postscript_regions .= ($vars['postscript_first']) ? '-first' : '';
    $postscript_regions .= ($vars['postscript_middle']) ? '-middle' : '';
    $postscript_regions .= ($vars['postscript_last']) ? '-last' : '';
    $body_classes[] = $postscript_regions;
  }
  $body_classes = array_filter($body_classes);                                                            // Remove empty elements
  $vars['body_classes'] = implode(' ', $body_classes);                                                    // Create class list separated by spaces

  // Add preface & postscript classes with number of active sub-regions
  $region_list = array(
    'prefaces' => array('preface_first', 'preface_middle', 'preface_last'), 
    'postscripts' => array('postscript_first', 'postscript_middle', 'postscript_last')
  );
  foreach ($region_list as $sub_region_key => $sub_region_list) {
    $active_regions = array();
    foreach ($sub_region_list as $region_item) {
      if ($vars[$region_item]) {
        $active_regions[] = $region_item;
      }
    }
    $vars[$sub_region_key] = $sub_region_key .'-'. strval(count($active_regions));
  }


  // Generate menu tree from source of primary links
  $vars['primary_links_tree'] = menu_tree(variable_get('menu_primary_links_source', 'primary-links'));

  // TNT THEME SETTINGS SECTION
  // Display mission statement on all pages
  if (theme_get_setting('mission_statement_pages') == 'all') {
    $vars['mission'] = theme_get_setting('mission', false);  
  }
  
  // Hide breadcrumb on all pages
  if (theme_get_setting('breadcrumb_display') == 0) {
    $vars['breadcrumb'] = '';  
  }
  
  // Set site title, slogan, mission, page title & separator (unless using Page Title module)
  if (!module_exists('page_title')) {
    $title = t(variable_get('site_name', ''));
    $slogan = t(variable_get('site_slogan', ''));
    $mission = t(variable_get('site_mission', ''));
    $page_title = t(drupal_get_title());
    $title_separator = theme_get_setting('configurable_separator');
    if (drupal_is_front_page()) {                                                // Front page title settings
      switch (theme_get_setting('front_page_title_display')) {
        case 'title_slogan':
          $vars['head_title'] = drupal_set_title($title . $title_separator . $slogan);
          break;
        case 'slogan_title':
          $vars['head_title'] = drupal_set_title($slogan . $title_separator . $title);
          break;
        case 'title_mission':
          $vars['head_title'] = drupal_set_title($title . $title_separator . $mission);
          break;
        case 'custom':
          if (theme_get_setting('page_title_display_custom') !== '') {
            $vars['head_title'] = drupal_set_title(t(theme_get_setting('page_title_display_custom')));
          }
      }
    }
    else {                                                                       // Non-front page title settings
      switch (theme_get_setting('other_page_title_display')) {
        case 'ptitle_slogan':
          $vars['head_title'] = drupal_set_title($page_title . $title_separator . $slogan);
          break;
        case 'ptitle_stitle':
          $vars['head_title'] = drupal_set_title($page_title . $title_separator . $title);
          break;
        case 'ptitle_smission':
          $vars['head_title'] = drupal_set_title($page_title . $title_separator . $mission);
          break;
        case 'ptitle_custom':
          if (theme_get_setting('other_page_title_display_custom') !== '') {
            $vars['head_title'] = drupal_set_title($page_title . $title_separator . t(theme_get_setting('other_page_title_display_custom')));
          }
          break;
        case 'custom':
          if (theme_get_setting('other_page_title_display_custom') !== '') {
            $vars['head_title'] = drupal_set_title(t(theme_get_setting('other_page_title_display_custom')));
          }
      }
    }
    $vars['head_title'] = strip_tags($vars['head_title']);                       // Remove any potential html tags
  }
  
  // Set meta keywords and description (unless using Meta tags module)
  if (!module_exists('nodewords')) {
    if (theme_get_setting('meta_keywords') !== '') {
      $keywords = '<meta name="keywords" content="'. theme_get_setting('meta_keywords') .'" />';
      $vars['head'] .= $keywords ."\n";
    } 
    if (theme_get_setting('meta_description') !== '') {
      $keywords = '<meta name="description" content="'. theme_get_setting('meta_description') .'" />';
      $vars['head'] .= $keywords ."\n";
    } 
  }

  if (drupal_is_front_page()) {
    $vars['closure'] .= '<div id="legal-notice">Theme provided by <a href="http://www.acquia.com">Acquia, Inc.</a> under GPL license from TopNotchThemes <a href="http://www.topnotchthemes.com">Drupal themes</a></div>';
  }
}


function phptemplate_preprocess_block(&$vars) {
  // Add regions with rounded blocks (e.g., sidebar_first, sidebar_last) to $rounded_regions array
  $rounded_regions = array('sidebar_first','sidebar_last','postscript_first','postscript_middle','postscript_last');
  $vars['rounded_block'] = (in_array($vars['block']->region, $rounded_regions)) ? TRUE : FALSE;
}


function phptemplate_preprocess_node(&$vars) {
  // Build array of handy node classes
  $node_classes = array();
  $node_classes[] = $vars['zebra'];                                      // Node is odd or even
  $node_classes[] = (!$vars['node']->status) ? 'node-unpublished' : '';  // Node is unpublished
  $node_classes[] = ($vars['sticky']) ? 'sticky' : '';                   // Node is sticky
  $node_classes[] = (isset($vars['node']->teaser)) ? 'teaser' : 'full-node';    // Node is teaser or full-node
  $node_classes[] = 'node-type-'. $vars['node']->type;                   // Node is type-x, e.g., node-type-page
  $node_classes = array_filter($node_classes);                           // Remove empty elements
  $vars['node_classes'] = implode(' ', $node_classes);                   // Implode class list with spaces
  
  // Add node_bottom region content
  $vars['node_bottom'] = theme('blocks', 'node_bottom');

  // Node Theme Settings
  
  // Date & author
  if (!module_exists('submitted_by')) {
    $date = t('Posted ') . format_date($vars['node']->created, 'medium');                 // Format date as small, medium, or large
    $author = theme('username', $vars['node']);
    $author_only_separator = t('Posted by ');
    $author_date_separator = t(' by ');
    $submitted_by_content_type = (theme_get_setting('submitted_by_enable_content_type') == 1) ? $vars['node']->type : 'default';
    $date_setting = (theme_get_setting('submitted_by_date_'. $submitted_by_content_type) == 1);
    $author_setting = (theme_get_setting('submitted_by_author_'. $submitted_by_content_type) == 1);
    $author_separator = ($date_setting) ? $author_date_separator : $author_only_separator;
    $date_author = ($date_setting) ? $date : '';
    $date_author .= ($author_setting) ? $author_separator . $author : '';
    $vars['submitted'] = $date_author;
  }

  // Taxonomy
  $taxonomy_content_type = (theme_get_setting('taxonomy_enable_content_type') == 1) ? $vars['node']->type : 'default';
  $taxonomy_display = theme_get_setting('taxonomy_display_'. $taxonomy_content_type);
  $taxonomy_format = theme_get_setting('taxonomy_format_'. $taxonomy_content_type);
  if ((module_exists('taxonomy')) && ($taxonomy_display == 'all' || ($taxonomy_display == 'only' && $vars['page']))) {
    $vocabularies = taxonomy_get_vocabularies($vars['node']->type);
    $output = '';
    $term_delimiter = ', ';
    foreach ($vocabularies as $vocabulary) {
      if (theme_get_setting('taxonomy_vocab_hide_'. $taxonomy_content_type .'_'. $vocabulary->vid) != 1) {
        $terms = taxonomy_node_get_terms_by_vocabulary($vars['node'], $vocabulary->vid);
        if ($terms) {
          $term_items = '';
          foreach ($terms as $term) {                        // Build vocabulary term items
            $term_link = l($term->name, taxonomy_term_path($term), array('attributes' => array('rel' => 'tag', 'title' => strip_tags($term->description))));
            $term_items .= '<li class="vocab-term">'. $term_link . $term_delimiter .'</li>';
          }
          if ($taxonomy_format == 'vocab') {                 // Add vocabulary labels if separate
            $output .= '<li class="vocab vocab-'. $vocabulary->vid .'"><span class="vocab-name">'. $vocabulary->name .':</span> <ul class="vocab-list">';
            $output .= substr_replace($term_items, '</li>', -(strlen($term_delimiter) + 5)) .'</ul></li>';
          }
          else {
            $output .= $term_items;
          }
        }
      }
    }
    if ($output != '') {
      $output = ($taxonomy_format == 'list') ? substr_replace($output, '</li>', -(strlen($term_delimiter) + 5)) : $output;
      $output = '<ul class="taxonomy">'. $output .'</ul>';
    }
    $vars['terms'] = $output;
  }
  else {
    $vars['terms'] = '';
  }
  
  // Node Links
  if (isset($vars['node']->links['node_read_more'])) {
    $node_content_type = (theme_get_setting('readmore_enable_content_type') == 1) ? $vars['node']->type : 'default';
    $vars['node']->links['node_read_more'] = array(
      'title' => _themesettings_link(
      theme_get_setting('readmore_prefix_'. $node_content_type),
      theme_get_setting('readmore_suffix_'. $node_content_type),
      t(theme_get_setting('readmore_'. $node_content_type)),
      'node/'. $vars['node']->nid,
      array(
        'attributes' => array('title' => t(theme_get_setting('readmore_title_'. $node_content_type))), 
        'query' => NULL, 'fragment' => NULL, 'absolute' => FALSE, 'html' => TRUE
        )
      ),
      'attributes' => array('class' => 'readmore-item'),
      'html' => TRUE,
    );
  }
  if (isset($vars['node']->links['comment_add'])) {
    $node_content_type = (theme_get_setting('comment_enable_content_type') == 1) ? $vars['node']->type : 'default';
    if ($vars['teaser']) {
      $vars['node']->links['comment_add'] = array(
        'title' => _themesettings_link(
        theme_get_setting('comment_add_prefix_'. $node_content_type),
        theme_get_setting('comment_add_suffix_'. $node_content_type),
        t(theme_get_setting('comment_add_'. $node_content_type)),
        "comment/reply/".$vars['node']->nid,
        array(
          'attributes' => array('title' => t(theme_get_setting('comment_add_title_'. $node_content_type))), 
          'query' => NULL, 'fragment' => 'comment-form', 'absolute' => FALSE, 'html' => TRUE
          )
        ),
        'attributes' => array('class' => 'comment-add-item'),
        'html' => TRUE,
      );
    }
    else {
      $vars['node']->links['comment_add'] = array(
        'title' => _themesettings_link(
        theme_get_setting('comment_node_prefix_'. $node_content_type),
        theme_get_setting('comment_node_suffix_'. $node_content_type),
        t(theme_get_setting('comment_node_'. $node_content_type)),
        "comment/reply/".$vars['node']->nid,
        array(
          'attributes' => array('title' => t(theme_get_setting('comment_node_title_'. $node_content_type))), 
          'query' => NULL, 'fragment' => 'comment-form', 'absolute' => FALSE, 'html' => TRUE
          )
        ),
        'attributes' => array('class' => 'comment-node-item'),
        'html' => TRUE,
      );
    }
  }
  if (isset($vars['node']->links['comment_new_comments'])) {
    $node_content_type = (theme_get_setting('comment_enable_content_type') == 1) ? $vars['node']->type : 'default';
    $vars['node']->links['comment_new_comments'] = array(
      'title' => _themesettings_link(
        theme_get_setting('comment_new_prefix_'. $node_content_type),
        theme_get_setting('comment_new_suffix_'. $node_content_type),
        format_plural(
          comment_num_new($vars['node']->nid),
          t(theme_get_setting('comment_new_singular_'. $node_content_type)),
          t(theme_get_setting('comment_new_plural_'. $node_content_type))
        ),
        "node/".$vars['node']->nid,
        array(
          'attributes' => array('title' => t(theme_get_setting('comment_new_title_'. $node_content_type))), 
          'query' => NULL, 'fragment' => 'new', 'absolute' => FALSE, 'html' => TRUE
        )
      ),
      'attributes' => array('class' => 'comment-new-item'),
      'html' => TRUE,
    );
  }
  if (isset($vars['node']->links['comment_comments'])) {
    $node_content_type = (theme_get_setting('comment_enable_content_type') == 1) ? $vars['node']->type : 'default';
    $vars['node']->links['comment_comments'] = array(
      'title' => _themesettings_link(
        theme_get_setting('comment_prefix_'. $node_content_type),
        theme_get_setting('comment_suffix_'. $node_content_type),
        format_plural(
          comment_num_all($vars['node']->nid),
          t(theme_get_setting('comment_singular_'. $node_content_type)),
          t(theme_get_setting('comment_plural_'. $node_content_type))
        ),
        "node/".$vars['node']->nid,
        array(
          'attributes' => array('title' => t(theme_get_setting('comment_title_'. $node_content_type))), 
          'query' => NULL, 'fragment' => 'comments', 'absolute' => FALSE, 'html' => TRUE
        )
      ),
      'attributes' => array('class' => 'comment-item'),
      'html' => TRUE,
    );
  }
  $vars['links'] = theme('links', $vars['node']->links, array('class' => 'links inline')); 
}


function phptemplate_preprocess_comment(&$vars) {
  global $user;
  // Build array of handy comment classes
  $comment_classes = array();
  static $comment_odd = TRUE;                                                                             // Comment is odd or even
  $comment_classes[] = $comment_odd ? 'odd' : 'even';
  $comment_odd = !$comment_odd;
  $comment_classes[] = ($vars['comment']->status == COMMENT_NOT_PUBLISHED) ? 'comment-unpublished' : '';  // Comment is unpublished
  $comment_classes[] = ($vars['comment']->new) ? 'comment-new' : '';                                      // Comment is new
  $comment_classes[] = ($vars['comment']->uid == 0) ? 'comment-by-anon' : '';                             // Comment is by anonymous user
  $comment_classes[] = ($user->uid && $vars['comment']->uid == $user->uid) ? 'comment-mine' : '';         // Comment is by current user
  $node = node_load($vars['comment']->nid);                                                               // Comment is by node author
  $vars['author_comment'] = ($vars['comment']->uid == $node->uid) ? TRUE : FALSE;
  $comment_classes[] = ($vars['author_comment']) ? 'comment-by-author' : '';
  $comment_classes = array_filter($comment_classes);                                                      // Remove empty elements
  $vars['comment_classes'] = implode(' ', $comment_classes);                                              // Create class list separated by spaces
  // Date & author
  $submitted_by = t('by ') .'<span class="comment-name">'.  theme('username', $vars['comment']) .'</span>';
  $submitted_by .= t(' - ') .'<span class="comment-date">'.  format_date($vars['comment']->timestamp, 'small') .'</span>';     // Format date as small, medium, or large
  $vars['submitted'] = $submitted_by;
}


/**
 * Set defaults for comments display
 * (Requires comment-wrapper.tpl.php file in theme directory)
 */
function phptemplate_preprocess_comment_wrapper(&$vars) {
  $vars['display_mode']  = COMMENT_MODE_FLAT_EXPANDED;
  $vars['display_order'] = COMMENT_ORDER_OLDEST_FIRST;
  $vars['comment_controls_state'] = COMMENT_CONTROLS_HIDDEN;
}


/**
 * Adds a class for the style of view  
 * (e.g., node, teaser, list, table, etc.)
 * (Requires views-view.tpl.php file in theme directory)
 */
function phptemplate_preprocess_views_view(&$vars) {
  $vars['css_name'] = $vars['css_name'] .' view-style-'. views_css_safe(strtolower($vars['view']->type));
}


/**
 * Modify search results based on theme settings
 */
function phptemplate_preprocess_search_result(&$variables) {
  static $search_zebra = 'even';
  $search_zebra = ($search_zebra == 'even') ? 'odd' : 'even';
  $variables['search_zebra'] = $search_zebra;
  
  $result = $variables['result'];
  $variables['url'] = check_url($result['link']);
  $variables['title'] = check_plain($result['title']);

  // Check for existence. User search does not include snippets.
  $variables['snippet'] = '';
  if (isset($result['snippet']) && theme_get_setting('search_snippet')) {
    $variables['snippet'] = $result['snippet'];
  }
  
  $info = array();
  if (!empty($result['type']) && theme_get_setting('search_info_type')) {
    $info['type'] = check_plain($result['type']);
  }
  if (!empty($result['user']) && theme_get_setting('search_info_user')) {
    $info['user'] = $result['user'];
  }
  if (!empty($result['date']) && theme_get_setting('search_info_date')) {
    $info['date'] = format_date($result['date'], 'small');
  }
  if (isset($result['extra']) && is_array($result['extra'])) {
    // $info = array_merge($info, $result['extra']);  Drupal bug?  [extra] array not keyed with 'comment' & 'upload'
    if (!empty($result['extra'][0]) && theme_get_setting('search_info_comment')) {
      $info['comment'] = $result['extra'][0];
    }
    if (!empty($result['extra'][1]) && theme_get_setting('search_info_upload')) {
      $info['upload'] = $result['extra'][1];
    }
  }

  // Provide separated and grouped meta information.
  $variables['info_split'] = $info;
  $variables['info'] = implode(' - ', $info);

  // Provide alternate search result template.
  $variables['template_files'][] = 'search-result-'. $variables['type'];
}


/**
 * Override username theming to display/hide 'not verified' text
 */
function phptemplate_username($object) {
  if ($object->uid && $object->name) {
    // Shorten the name when it is too long or it will break many tables.
    if (drupal_strlen($object->name) > 20) {
      $name = drupal_substr($object->name, 0, 15) .'...';
    }
    else {
      $name = $object->name;
    }
    if (user_access('access user profiles')) {
      $output = l($name, 'user/'. $object->uid, array('attributes' => array('title' => t('View user profile.'))));
    }
    else {
      $output = check_plain($name);
    }
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
    // Display or hide 'not verified' text
    if (theme_get_setting('user_notverified_display') == 1) {
      $output .= ' ('. t('not verified') .')';
    }
  }
  else {
    $output = variable_get('anonymous', t('Anonymous'));
  }
  return $output;
}


/**
 * Set default form file input size 
 */
function phptemplate_file($element) {
  $element['#size'] = 40;
  return theme_file($element);
}


/**
 * Creates a link with prefix and suffix text
 *
 * @param $prefix
 *   The text to prefix the link.
 * @param $suffix
 *   The text to suffix the link.
 * @param $text
 *   The text to be enclosed with the anchor tag.
 * @param $path
 *   The Drupal path being linked to, such as "admin/content/node". Can be an external
 *   or internal URL.
 *     - If you provide the full URL, it will be considered an
 *   external URL.
 *     - If you provide only the path (e.g. "admin/content/node"), it is considered an
 *   internal link. In this case, it must be a system URL as the url() function
 *   will generate the alias.
 * @param $options
 *   An associative array that contains the following other arrays and values
 *     @param $attributes
 *       An associative array of HTML attributes to apply to the anchor tag.
 *     @param $query
 *       A query string to append to the link.
 *     @param $fragment
 *       A fragment identifier (named anchor) to append to the link.
 *     @param $absolute
 *       Whether to force the output to be an absolute link (beginning with http:).
 *       Useful for links that will be displayed outside the site, such as in an RSS
 *       feed.
 *     @param $html
 *       Whether the title is HTML or not (plain text)
 * @return
 *   an HTML string containing a link to the given path.
 */
function _themesettings_link($prefix, $suffix, $text, $path, $options) {
  return $prefix . (($text) ? l($text, $path, $options) : '') . $suffix;
}

/**
*Implementation of hook_theme().
*/
function acquia_marina_theme($existing, $type, $theme, $path) {
	return array(
	    'contact-mail-page' => array(
	      'arguments' => array('form' => NULL),
		  'template' => 'contact-form-template',
	    ),
	  );
}

function acquia_marina_preprocess_contact_mail_page(&$vars) {
	$vars['template_files'] = array('contact-form-template');
	$vars['whole_form'] = drupal_render($vars['form']);
}


function custom_user_bar() {
  global $user;                                                               
  $output = '';

  if (!$user->uid) {                                                          
    $output .= drupal_get_form('user_login_block');                           
  }                                                                           
  else {                                                                      
 
    $output .= theme('item_list', array(
      l(t('Your account'), 'user/'.$user->uid, array('title' => t('Edit your account'))),
      l(t('Sign out'), 'logout')));
      
   $output .= t('<p class="user-info">Welcome !user!</p>', array('!user' => theme('username', $user)));
  }
  $output = '<div id="user-bar">'.$output.'</div>';
     
  return $output;
}


#function acquia_marina_preprocess_node(&$variables) {
#  $node = $variables['node'];
#  if (!empty($node) && $node->nid == $the_specific_node_id) {
#    drupal_add_js("sites/all/modules/conditional_fields/conditional_fields.js", "theme");
#    //drupal_add_css(path_to_theme(). "/file.css", "theme");
#  }
#}


/**
*Implementation of hook_form_alter().
*/

//function gradapplication_form_alter(&$form, $form_state, $form_id) {
//print "stuff goes here: ". print_r($form);
//}

/**
*Implementation of hook_theme().
*/
#function acquia_marina_theme() {
#	return array(
#	    'gradapplication_form' => array(
#	      'arguments' => array('form' => NULL),
#	    ),
#	  );
#}

// The new theme function called by hook_theme
//function acquia_marina_gradapplication_form($form) {
  // hide the label
  //$vars['form']['group_personal_information'][]
//  return drupal_render($form);
//}





/////////////////////////////////////////////////////
//  GRAD APPLICATION INPUT FORM THEME FUNCTIONS  //
/////////////////////////////////////////////////

//grad application theme function
#function acquia_marina_theme($existing, $type, $theme, $path) {
#  return array(
#    'gradapplication_node_form' => array(
#        'arguments' => array('form' => NULL),
#        'template' => 'node-edit-gradapplication',
#    ),
#	'gradapplication_node_form2' => array(
#    	'arguments' => array('form' => NULL),
#    	'template' => 'node-edit-gradapplication2',
#	),
#	'gradapplication_node_form3' => array(
#    	'arguments' => array('form' => NULL),
#    	'template' => 'node-edit-gradapplication3',
#	),
#	'gradapplication_node_form4' => array(
#    	'arguments' => array('form' => NULL),
#    	'template' => 'node-edit-gradapplication4',
#	),
#	'gradapplication_node_form5' => array(
#    	'arguments' => array('form' => NULL),
#    	'template' => 'node-edit-gradapplication5',
#	),
#  );
#}
#
#function acquia_marina_preprocess_gradapplication_node_form(&$vars) {
#	//if(arg(3) == 1) {
#		$vars['template_files'] = array('node-edit-gradapplication');
#		
#		$vars['whole_form'] = drupal_render($vars['form']);
#	//}
#}
#
#//grad application preprocess function
#function acquia_marina_preprocess_gradapplication_node_form(&$vars) {
#	print "<pre>";
#//	print_r($vars['form']['group_personal_information']['field_first_name']['0']['value']['#size']);
#//	print_r ($vars['form']['group_personal_information']['field_place_of_birth']['0']['city']);
#//	print_r ($vars['form']['group_personal_information']['field_phone_primary']);
#//	print_r($vars['form']['group_international_information']);
#	print "</pre>";
#	
#//	print "arg1: ". arg(1). " ";
#//	print "arg2: ". arg(2). " ";
#//	print "arg3: ". arg(3). " ";
#//	print "arg4: ". arg(4). " ";
#	
#	//buttons
#	$vars['submit'] = drupal_render($vars['form']['buttons']['submit']);
#	$vars['prev'] = drupal_render($vars['form']['buttons']['previous']);
#	$vars['next'] = drupal_render($vars['form']['buttons']['next']);
#	$vars['preview'] = drupal_render($vars['form']['buttons']['preview']);
#	$vars['delete'] = drupal_render($vars['form']['buttons']['delete']);
#	$vars['save'] = drupal_render($vars['form']['buttons']['save']);
#	$vars['all_buttons'] = drupal_render($vars['form']['buttons']['all']);
#
#if(arg(3) == 1) {
#	$vars['template_files'] = array('node-edit-gradapplication');
#	
#    //personal information group	
#	//$vars['personal_information_group'] = drupal_render($vars['form']['group_personal_information']);
#	$vars['first_name'] = drupal_render($vars['form']['group_personal_information']['field_first_name']);
#	$vars['middle_name'] = drupal_render($vars['form']['group_personal_information']['field_middle_name']);
#	$vars['last_name'] = drupal_render($vars['form']['group_personal_information']['field_last_name']);
#	$vars['maiden_name'] = drupal_render($vars['form']['group_personal_information']['field_maiden_name']);
#	$vars['email_address'] = drupal_render($vars['form']['group_personal_information']['field_email_address']);
#	$vars['date_of_birth'] = drupal_render($vars['form']['group_personal_information']['field_dob']);
#	$vars['ssn'] = drupal_render($vars['form']['group_personal_information']['field_ssn']);
#	$vars['gender'] = drupal_render($vars['form']['group_personal_information']['field_gender']);
#   
#    //place of birth group
#	$vars['form']['group_personal_information']['field_place_of_birth']['0']['city']['#title'] = 'City of Birth';
#	$vars['city_of_birth'] = drupal_render($vars['form']['group_personal_information']['field_place_of_birth']['0']['city']);
#	$vars['form']['group_personal_information']['field_place_of_birth']['0']['province']['#title'] = 'State or Province of Birth';
#	$vars['state_of_birth'] = drupal_render($vars['form']['group_personal_information']['field_place_of_birth']['0']['province']);
#	$vars['form']['group_personal_information']['field_place_of_birth']['0']['country']['#title'] = 'Country of Birth';
#	$vars['country_of_birth'] = drupal_render($vars['form']['group_personal_information']['field_place_of_birth']['0']['country']);
#	$vars['form']['group_personal_information']['field_coc']['0']['country']['#title'] = 'Country of Citizenship';
#	$vars['country_of_citizenship'] = drupal_render($vars['form']['group_personal_information']['field_coc']['0']['country']);
#	$vars['residency_status'] = drupal_render($vars['form']['group_personal_information']['field_residency']);
#	$vars['ethnic_background'] = drupal_render($vars['form']['group_personal_information']['field_ethnic']);
#   
#    //permanent address group
#	$vars['form']['group_personal_information']['field_permanent_address']['0']['street']['#title'] = 'Street Address';
#	$vars['perm_street_1'] = drupal_render($vars['form']['group_personal_information']['field_permanent_address']['0']['street']);
#	$vars['form']['group_personal_information']['field_permanent_address']['0']['additional']['#title'] = '';
#	$vars['perm_street_2'] = drupal_render($vars['form']['group_personal_information']['field_permanent_address']['0']['additional']);
#	$vars['perm_city'] = drupal_render($vars['form']['group_personal_information']['field_permanent_address']['0']['city']);
#	$vars['perm_state'] = drupal_render($vars['form']['group_personal_information']['field_permanent_address']['0']['province']);
#	$vars['perm_zip'] = drupal_render($vars['form']['group_personal_information']['field_permanent_address']['0']['postal_code']);
#	$vars['perm_country'] = drupal_render($vars['form']['group_personal_information']['field_permanent_address']['0']['country']);
#   
#    //mailing address group
#	$vars['form']['group_personal_information']['field_mailing_address']['0']['street']['#title'] = 'Street Address';
#	$vars['mail_street_1'] = drupal_render($vars['form']['group_personal_information']['field_mailing_address']['0']['street']);
#	$vars['form']['group_personal_information']['field_mailing_address']['0']['additional']['#title'] = '';
#	$vars['mail_street_2'] = drupal_render($vars['form']['group_personal_information']['field_mailing_address']['0']['additional']);
#	$vars['mail_city'] = drupal_render($vars['form']['group_personal_information']['field_mailing_address']['0']['city']);
#	$vars['mail_state'] = drupal_render($vars['form']['group_personal_information']['field_mailing_address']['0']['province']);
#	$vars['mail_zip'] = drupal_render($vars['form']['group_personal_information']['field_mailing_address']['0']['postal_code']);
#	$vars['mail_country'] = drupal_render($vars['form']['group_personal_information']['field_mailing_address']['0']['country']);
#   	
#    //phone numbers
#	$vars['phone_primary'] = drupal_render($vars['form']['group_phone_numbers']['field_phone_primary']);
#	$vars['phone_work'] = drupal_render($vars['form']['group_phone_numbers']['field_phone_work']);
#	$vars['phone_fax'] = drupal_render($vars['form']['group_phone_numbers']['field_phone_fax']);
#   
#    //other information
#	$vars['student_type'] = drupal_render($vars['form']['group_other_info']['field_student_type']);
#	$vars['entry_date'] = drupal_render($vars['form']['group_other_info']['field_entry_date']);
#	$vars['entry_year'] = drupal_render($vars['form']['group_other_info']['field_entry_date_year']);
#	
#	//emergency contact information
#    $vars['emergency_first'] = drupal_render($vars['form']['group_emergency']['field_e_fname']);
#	$vars['emergency_last'] = drupal_render($vars['form']['group_emergency']['field_e_lname']);
#	$vars['emergency_relation'] = drupal_render($vars['form']['group_emergency']['field_e_relationship']);
#	$vars['emergency_phone'] = drupal_render($vars['form']['group_emergency']['field_e_phone']);
#	
#	$vars['form']['group_emergency']['field_e_address']['0']['street']['#title'] = 'Street Address';
#	$vars['e_street_1'] = drupal_render($vars['form']['group_emergency']['field_e_address']['0']['street']);
#	$vars['form']['group_emergency']['field_e_address']['0']['additional']['#title'] = '';
#	$vars['e_street_2'] = drupal_render($vars['form']['group_emergency']['field_e_address']['0']['additional']);
#	$vars['e_city'] = drupal_render($vars['form']['group_emergency']['field_e_address']['0']['city']);
#	$vars['e_state'] = drupal_render($vars['form']['group_emergency']['field_e_address']['0']['province']);
#	$vars['e_zip'] = drupal_render($vars['form']['group_emergency']['field_e_address']['0']['postal_code']);
#	$vars['e_country'] = drupal_render($vars['form']['group_emergency']['field_e_address']['0']['country']);
#	
#	//language information
#	$vars['language'] = drupal_render($vars['form']['group_language']['field_language']);
#	$vars['english'] = drupal_render($vars['form']['group_language']['field_english']);
#
#    //crime information
#	#$vars['crime_group'] = drupal_render($vars['form']['group_crime_info']);
#    $vars['crime_radio'] = drupal_render($vars['form']['group_crime_info']['0']['field_crime_radio']);
#    $vars['crime'] = drupal_render($vars['form']['group_crime_info']['0']['field_crime']);
#    $vars['crime_doi'] = drupal_render($vars['form']['group_crime_info']['0']['field_doi']);
#    $vars['crime_explanation'] = drupal_render($vars['form']['group_crime_info']['0']['field_explination']);
#}   
#
#if(arg(3) == 2) {
#	$vars['template_files'] = array('node-edit-gradapplication2');
#	
#	//international information group
#	//$vars['international_group'] = drupal_render($vars['form']['group_international_information']);
#	//$vars['int'] = $vars['form']['group_international_information']['field_international_dep_citz'];
#	$vars['international_radio'] = drupal_render($vars['form']['group_international_information']['field_international']);
#	$vars['international_time'] = drupal_render($vars['form']['group_international_information']['field_international_time_us']);
#	$vars['international_degree'] = drupal_render($vars['form']['group_international_information']['field_international_plan_degree']);
#	$vars['international_where'] = drupal_render($vars['form']['group_international_information']['field_international_where']);
#	$vars['international_subject'] = drupal_render($vars['form']['group_international_information']['field_international_subject']);
#	$vars['international_when'] = drupal_render($vars['form']['group_international_information']['field_international_when']);
#	$vars['international_grad'] = drupal_render($vars['form']['group_international_information']['field_international_after_grad']);
#	$vars['international_employer'] = drupal_render($vars['form']['group_international_information']['field_international_employer']);
#	
#	//$vars['international_location'] = drupal_render($vars['form']['group_international_information']['field_international_location']);
#	$vars['international_location_name'] = drupal_render($vars['form']['group_international_information']['field_international_location']['0']['name']);
#	$vars['international_location_street1'] = drupal_render($vars['form']['group_international_information']['field_international_location']['0']['street']);
#	$vars['form']['group_international_information']['field_international_location']['0']['additional']['#title'] = '';
#	$vars['international_location_street2'] = drupal_render($vars['form']['group_international_information']['field_international_location']['0']['additional']);
#	$vars['international_location_city'] = drupal_render($vars['form']['group_international_information']['field_international_location']['0']['city']);
#	$vars['international_location_state'] = drupal_render($vars['form']['group_international_information']['field_international_location']['0']['province']);
#	$vars['international_location_zip'] = drupal_render($vars['form']['group_international_information']['field_international_location']['0']['postal_code']);
#	
#	$vars['international_start'] = drupal_render($vars['form']['group_international_information']['field_international_start']);
#	$vars['international_dependants'] = drupal_render($vars['form']['group_international_information']['field_international_dependants']);
#	$vars['international_dep_arrive'] = drupal_render($vars['form']['group_international_information']['field_international_dep_arrive']);
#	$vars['international_dep_relation'] = drupal_render($vars['form']['group_international_information']['field_international_dep_relation']);
#	$vars['international_dep_dob'] = drupal_render($vars['form']['group_international_information']['field_international_dep_dob']);
#	$vars['form']['group_international_information']['field_international_dep_cob']['0']['country']['#title'] = t('Country of Birth');
#	$vars['international_dep_cob'] = drupal_render($vars['form']['group_international_information']['field_international_dep_cob']['0']['country']);
#	$vars['form']['group_international_information']['field_international_dep_citz']['0']['country']["#title"] = t('Country of Citizenship');
#	$vars['international_dep_citz'] = drupal_render($vars['form']['group_international_information']['field_international_dep_citz']['0']['country']);
#	$vars['international_dep_fname'] = drupal_render($vars['form']['group_international_information']['field_international_e_first_name']);
#	$vars['international_dep_lname'] = drupal_render($vars['form']['group_international_information']['field_international_e_last_name']);
#	$vars['international_dep_street1'] = drupal_render($vars['form']['group_international_information']['field_international_e_addr']['0']['street']);
#	$vars['form']['group_international_information']['field_international_e_addr']['0']['additional']['#title'] = '';
#	$vars['international_dep_street2'] = drupal_render($vars['form']['group_international_information']['field_international_e_addr']['0']['additional']);
#	$vars['international_dep_city'] = drupal_render($vars['form']['group_international_information']['field_international_e_addr']['0']['city']);
#	$vars['international_dep_state'] = drupal_render($vars['form']['group_international_information']['field_international_e_addr']['0']['province']);
#	$vars['international_dep_zip'] = drupal_render($vars['form']['group_international_information']['field_international_e_addr']['0']['postal_code']);
#	$vars['form']['group_international_information']['field_international_e_addr']['0']['country']['#title'] = t('Country of Citizenship');
#	$vars['international_dep_country'] = drupal_render($vars['form']['group_international_information']['field_international_e_addr']['0']['country']);
#}
#
#if(arg(3) == 3) {
#	$vars['template_files'] = array('node-edit-gradapplication3');
#	
#	//academic history group
#	//$vars['academic_history_group'] = drupal_render($vars['form']['group_academic_history']);
#	$vars['int'] = $vars['form']['group_attended_institutions']['0']['field_date'];
#	$vars['applied'] = drupal_render($vars['form']['group_academic_history']['field_applied']);
#	$vars['app_date'] = drupal_render($vars['form']['group_academic_history']['field_applied_date']);
#	
#	//$vars['inst_group'] = drupal_render($vars['form']['group_attended_institutions']);
#    $vars['gpa'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_gpa1']);	
#    $vars['institution'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_institution']);
#    $vars['major'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_major']);
#    //$vars['where'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_where']);
#	$vars['city'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_where']['city']);
#	$vars['state'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_where']['province']);
#	$vars['country'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_where']['country']);
#	
#    //$vars['date'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_date']);
#	$vars['from_date'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_date']['value']);
#	$vars['to_date'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_date']['value2']);
#
#    $vars['degree'] = drupal_render($vars['form']['group_attended_institutions']['0']['field_degree']);
#
#	//violations group
#	//$vars['violation_group'] = drupal_render($vars['form']['group_disciplinary_violations']);
#	$vars['violation'] = drupal_render($vars['form']['group_disciplinary_violations']['0']['field_discip_post_sec']);
#	$vars['violation_date'] = drupal_render($vars['form']['group_disciplinary_violations']['0']['field_date_discip']);
#	$vars['violation_details'] = drupal_render($vars['form']['group_disciplinary_violations']['0']['field_details']);
#	
#	//courses group
#	//$vars['courses_group'] = drupal_render($vars['form']['group_courses']);
#	$vars['courses'] = drupal_render($vars['form']['group_courses']['field_courses']);
#	
#	//test scores group
#    //$vars['test_scores_group'] = drupal_render($vars['form']['group_test_scores']);
#    $vars['test'] = drupal_render($vars['form']['group_test_scores']['0']['field_test']);
#    $vars['status'] = drupal_render($vars['form']['group_test_scores']['0']['field_test_status']);
#    $vars['date'] = drupal_render($vars['form']['group_test_scores']['0']['field_test_date']);
#    $vars['sent'] = drupal_render($vars['form']['group_test_scores']['0']['field_sent']);
#    $vars['verbal'] = drupal_render($vars['form']['group_test_scores']['0']['field_verbal']);
#    $vars['analytical'] = drupal_render($vars['form']['group_test_scores']['0']['field_analytical']);
#    $vars['quantitative'] = drupal_render($vars['form']['group_test_scores']['0']['field_quantitative']);
#    $vars['subject'] = drupal_render($vars['form']['group_test_scores']['0']['field_subject']);
#
#}
#
#if(arg(3) == 4) {
#	$vars['template_files'] = array('node-edit-gradapplication4');
#	
#	//work history group
#	//$vars['work_history_group'] = drupal_render($vars['form']['group_work_history']);
#	$vars['studies'] = drupal_render($vars['form']['group_work_history']['field_studies']);
#	$vars['resume'] = drupal_render($vars['form']['group_work_history']['field_resume']);
#	$vars['honors'] = drupal_render($vars['form']['group_work_history']['field_honors']);
#	
#	//$vars['recommendations_group'] = drupal_render($vars['form']['group_recommendations']);
#	$vars['recommendations_first_name'] = drupal_render($vars['form']['group_recommendations']['0']['field_recommendation_first_name']);
#	$vars['recommendations_last_name'] = drupal_render($vars['form']['group_recommendations']['0']['field_recommendation_last_name']);
#	$vars['recommendations_employer'] = drupal_render($vars['form']['group_recommendations']['0']['field_recommendation_employer']);
#	$vars['recommendations_job_title'] = drupal_render($vars['form']['group_recommendations']['0']['field_recommendation_job_title']);
#	$vars['recommendations_address'] = drupal_render($vars['form']['group_recommendations']['0']['field_recommendation_address']);
#	$vars['recommendations_email'] = drupal_render($vars['form']['group_recommendations']['0']['field_recommendation_email']);
#	$vars['recommendations_relation'] = drupal_render($vars['form']['group_recommendations']['0']['field_recommendation_relation']);
#	$vars['recommendations_online'] = drupal_render($vars['form']['group_recommendations']['0']['field_recommendation_online']);
#	$vars['recommendations_prior'] = drupal_render($vars['form']['group_recommendations']['0']['field_recommendation_prior']);
#}
#
#if(arg(3) == 5) {
#	$vars['template_files'] = array('node-edit-gradapplication5');
#	
#	//educational objectives group
#	//$vars['educational_objectives_group'] = drupal_render($vars['form']['group_educational_objectives']);
#	$vars['attendance_status'] = drupal_render($vars['form']['group_education_objectives']['field_attendance_status']);
#	$vars['degree_objective'] = drupal_render($vars['form']['group_education_objectives']['field_degree_objective']);
#	$vars['program_study'] = drupal_render($vars['form']['group_education_objectives']['field_program_study']);
#	$vars['major'] = drupal_render($vars['form']['group_education_objectives']['field_majorarea']);
#	$vars['minor'] = drupal_render($vars['form']['group_education_objectives']['field_minorarea']);
#	$vars['nersp'] = drupal_render($vars['form']['group_education_objectives']['field_nersp']);
#	$vars['assistantship'] = drupal_render($vars['form']['group_education_objectives']['field_assistantship']);
#	$vars['department'] = drupal_render($vars['form']['group_education_objectives']['field_department']);
#	$vars['gradstudent'] = drupal_render($vars['form']['group_education_objectives']['field_gradstudent']);
#	$vars['correspondant'] = drupal_render($vars['form']['group_education_objectives']['field_correspondant']);
#}
#
#	$vars['whole_form'] = drupal_render($vars['form']);
#		//print_r ($vars['form']['group_personal_information']);
#	//	$vars['form']['group_personal_information']['field_middle_name']['0']['value']['#suffix'] = '&#60;br/&#62;';
#	//	print_r ($vars['form']['group_personal_information']['field_first_name']['0']['value']);
#	//	$vars['form']['group_personal_information']['field_ssn']['0']['value']['#size'] = 10;
#}
#
#