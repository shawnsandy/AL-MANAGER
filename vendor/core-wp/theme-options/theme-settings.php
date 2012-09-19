<?php

/**
 * @package WordPress
 * @subpackage Core-WP
 * @author shawnsandy
 * @todo convert script into class
 * Based on theme options page by themeshaper.com
 * Adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
 */


add_action('admin_init', 'theme_options_init');
add_action('admin_menu', 'theme_options_add_page');

/**
 * Init plugin options to white list our options
 */
function theme_options_init() {
    register_setting('cwp_options', 'cwp_theme_options', 'theme_options_validate');
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
    $icon = CWP_URL .'/menu-images/process.png';
    $theme = get_current_theme();
   $theme_page = add_theme_page(__('Theme Settings', 'corewp'), __('Theme Settings', 'corewp'), 'edit_theme_options', 'theme_options', 'theme_options_do_page');
//    $theme_page = add_menu_page( __('Custom UI','corewp'), __('Custom UI','corewp'), 'manage_options', 'cwp_custom_ui', 'cwp_options_advanced', $icon, 62 );
//    add_submenu_page('cwp_custom_ui', __('UI Settings','corewp'), __('UI Settings','corewp'), 'manage_options', 'cwp_theme_settings', 'theme_options_do_page');
    add_action('load-' . $theme_page, 'cwp_theme_help_tabs');
}

function cwp_options_advanced(){
   ?>
    <div class="wrap">
            <h2><?php screen_icon(); ?><?php _e('Custom UI Options','corewp') ?></h2>
        <!-- class content -->
    </div>
    <!-- ###### -->
    <?php
}


/* *****************************************************************************
 * Help Tabs
 * *****************************************************************************
 */
function cwp_theme_help_tabs() {
    $screen = get_current_screen();
    $screen->add_help_tab(array(
        'id' => 'support-key-help', // This should be unique for the screen.
        'title' => 'Support Key',
        //'content' => '<p>Support key.</p>',
        'callback' => 'cwp_support_key_help',
            // Use 'callback' instead of 'content' for a function callback that renders the tab content.
    ));
    $screen->add_help_tab(array(
        'id' => 'theme-admin-help', // This should be unique for the screen.
        'title' => 'Theme Admin',
        'callback' => 'cwp_custom_ui_help'
            //'content' => '<p>Theme admin.</p>',
            // Use 'callback' instead of 'content' for a function callback that renders the tab content.
    ));

    $screen->add_help_tab(array(
        'id' => 'custom-ui-help', // This should be unique for the screen.
        'title' => 'Custom UI',
        'content' => '<p>Customizing the theme UI.</p>',
            // Use 'callback' instead of 'content' for a function callback that renders the tab content.
    ));

    $screen->add_help_tab(array(
        'id' => 'ga-help', // This should be unique for the screen.
        'title' => 'Google Analytics',
        'content' => '<p>Setting up your google analytics.</p>',
            // Use 'callback' instead of 'content' for a function callback that renders the tab content.
    ));

    $screen->add_help_tab(array(
        'id' => 'ga-search', // This should be unique for the screen.
        'title' => 'Google Search Integration',
        'callback' => 'cwp_google_help'
            //'content' => '<p>Setting up your google analytics.</p>',
            // Use 'callback' instead of 'content' for a function callback that renders the tab content.
    ));
    $screen->set_help_sidebar(" help screen");
}

function cwp_support_key_help() {
    echo "<p>For theme support and validation please enter a valid Support Key</p>";
    echo "<p>Your support key can be found in the Crafted and Pressed account center</p>";
}

function cwp_custom_ui_help() {
    echo "<p>Custom UI</p>";
    echo "<p></p>";
    echo "<p></p>";
    echo "<p></p>";
}

function cwp_google_help() {
    echo '<p>Google Search Integration</p>';
    ?>
    <a href="http://www.google.com/cse/" target="_blank">http://www.google.com/cse/</a>
    <?php
}


/**
 * *****************************************************************************
 * Theme options
 * *****************************************************************************
 */



/**
 * Create arrays for our select and radio options
 *
 */

//@todo remove unused arrays
$select_options = array(
    '0' => array(
        'value' => '0',
        'label' => __('Zero', 'cwp_toolbox')
    ),
    '1' => array(
        'value' => '1',
        'label' => __('One', 'cwp_toolbox')
    ),
    '2' => array(
        'value' => '2',
        'label' => __('Two', 'cwp_toolbox')
    ),
    '3' => array(
        'value' => '3',
        'label' => __('Three', 'cwp_toolbox')
    ),
    '4' => array(
        'value' => '4',
        'label' => __('Four', 'cwp_toolbox')
    ),
    '5' => array(
        'value' => '3',
        'label' => __('Five', 'cwp_toolbox')
    )
);

$radio_options = array(
    'yes' => array(
        'value' => 'yes',
        'label' => __('Yes', 'cwp_toolbox')
    ),
    'no' => array(
        'value' => 'no',
        'label' => __('No', 'cwp_toolbox')
    ),
    'maybe' => array(
        'value' => 'maybe',
        'label' => __('Maybe', 'cwp_toolbox')
    )
);


/**
 *
 */
function cwp_theme_tabs($current = 'theme_desc') {
    $cur = get_current_theme();
    $tabs = array('theme_desc' => $cur, 'options_tab' => 'Options');
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=theme_options&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}

/**
 * *****************************************************************************
 * SETTINGS PAGE
 * *****************************************************************************
 */

function theme_options_do_page() {
    include_once CWP_PATH . '/theme-options/ui-options.php';
}

/**
 * *****************************************************************************
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 * *****************************************************************************
 */
function theme_options_validate($input) {
    global $select_options, $radio_options;



    // Our checkbox value is either 0 or 1
    if (!isset($input['offline']))
        $input['offline'] = null;
    $input['offline'] = ( $input['offline'] == 1 ? 1 : 0 );


    // Our checkbox value is either 0 or 1
    if (!isset($input['defaultpages']))
        $input['defaultpages'] = null;
    $input['defaultpages'] = ( $input['defaultpages'] == 1 ? 1 : 0 );

    // Our checkbox value is either 0 or 1
    if (!isset($input['saveoptions']))
        $input['saveoptions'] = null;
    $input['saveoptions'] = ( $input['saveoptions'] == 1 ? 1 : 0 );



//
//    // Our select option must actually be in our array of select options
//    if (!array_key_exists($input['selectinput'], $select_options))
//        $input['selectinput'] = null;





    if (isset($input['supportkey']) AND intval( $input['supportkey'] ))
    $input['supportkey'] = intval( $input['supportkey']) ;
    else $input['supportkey'] = '';




    if(isset($input['themeadmin'])):
      $_tadmin = $input['themeadmin'];
      $admin_can = user_can($_tadmin, 'administrator');
      if($admin_can)
          $input['themeadmin'] = $input['themeadmin'];
      else $input['themeadmin'] = '';
    endif;


    if(isset($input['uidefault']) AND get_post_type($input['uidefault']) == 'cwp_uioptions'):
        $input['uidefault'] = $input['uidefault'];
    else : $input['uidefault'] = '';
    endif;



    //google validation

    if(isset($input['ga']) and !empty($input['ga']))
        $input['ga'] = esc_js($input['ga']);

    if(isset($input['gakey']))
        $input['gakey'] = esc_attr ($input['gakey']);
    else $input['gakey'] = '';

    if(isset($input['gsearchbox']) and !empty($input['gsearchbox']) AND current_user_can('unfiltered_html'))
        $input['gsearchbox'] = stripslashes($input['gsearchbox']);
    else $input['gsearchbox'] = '';

    if(isset($input['gsearchpage']) and !empty($input['gsearchpage']) AND current_user_can('unfiltered_html'))
        $input['gsearchpage'] = stripslashes($input['gsearchpage']);
    else $input['gsearchpage'] = '';

    /**
     * twitter widgets*****************************************
     */

    if(isset($input['twitterwidget']) and !empty($input['twitterwidget']) AND current_user_can('unfiltered_html'))
        $input['twitterwidget'] = stripslashes($input['twitterwidget']);
    else $input['twitterwidget'] = '';


    /**
     * FBAPPID Face book appid
     */
    if(isset($input['fbappid']) AND intval($input['fbappid']))
        $input['fbappid'] = intval($input['fbappid']);
    else $input['fbappid'] = '';

    return $input;
}

//    if ( current_user_can('unfiltered_html') )
//	$text = stripslashes( $widget_text['text'] );

/**
 * ************************DEFAULT PLUGINS**************************************
 */

