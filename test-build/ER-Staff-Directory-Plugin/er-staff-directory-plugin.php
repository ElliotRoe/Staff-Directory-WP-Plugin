<?php
/**
* Plugin Name: Staff Directory Shortcode Plugin
* Plugin URI:
* Description: Creates a styled staff directory dynamically based off of data base
* Version: 1.0
* Author: Elliot Roe
* Author URI:
**/

function er_staff_directory() {

  //Enqueues style sheet needed
  wp_enqueue_style('er-staff-style', plugin_dir_url(__FILE__) . 'css/er-staff-style.css');

  //Creates an array of section info containing both title and id used for searches

  $sections = array(

    "Management" => array(
      "Supervisor" => "Supervisor",
      "CoEditor" => "Co-Editor",
      "WebsiteCoordinator" => "Website Coordinator"
    ),

    "News" => array(
      "NewsEditor" => "News Editor"
    ),

    "Opinion" => array(
      "OpinionEditor" => "Opinion Editor"
    ),

    "In-depth" => array(
      "InDepthEditor" => "In-depth Editor"
    ),

    "Feature" => array(
      "FeatureEditor" => "Feature Editor"
    ),

    "Sports" => array(
      "SportsEditor" => "Sports Editor"
    ),

    "Backpage" => array(
      "BackpageEditor" => "Backpage Editor"
    ),

    "Graphics" => array(
      "GraphicsEditor" => "Graphics Editor",
      "AssistantGraphicsEditor" => "Assistant Graphics Editor",
      "GraphicsStaff" => "Graphics Staff"
    ),

    "Staff Reporters" => array(
      "StaffReporter" => "Staff Reporter"
    ),
  );

  // Gets a defualt profile picture
  $defualt_url = 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png';
  // Makes sure that the global exists before calling it
  global $simple_local_avatars;
  $local_set = isset($simple_local_avatars);
  if ( $local_set ) {
    $defualt_url = $simple_local_avatars->get_default_avatar_url(100);
  }

  $content = '';
  foreach ($sections as $section_title => $id_array) {
    $content .= '<div class="block-header"><h3 class="er-section-title block-title">' . $section_title . '</h3></div>';
    $content .= '<div class="er-section-wrapper">';

    // Creates widget for each user containing a id specified
    foreach ($id_array as $position_id => $position) {

      // Searches for users with specific id
      $users = new WP_User_Query( array(
          'search'         => '*'.esc_attr( $position_id ),
          'search_columns' => array('user_login'),
      ) );
      $users_found = $users->get_results();

      // Creates a widget for each user found in the search
      foreach ($users_found as $user) {
        // Gets suer id for meta searches
        $id = $user->ID;
        // Bunch of meta searches for the info needed
        $user_url = get_user_meta($id, 'user_url', true);
        $safe_username = str_replace('.', '', get_user_meta($id, 'username', true));
        $first = get_user_meta($id, 'first_name', true);
        $last = get_user_meta($id, 'last_name', true);

        /*
        $description = get_user_meta($id, 'description', true);

        // Processes bio make it at most 123 characters long
        if (strlen($description)>50) {
          // Cuts bio down to 123 characters
          $description = substr($description, 0, 50);
          // Cuts off the partial word if there is one
          $description = substr($description, 0, strrpos($description,' ')) . ' [...]';
        }
        */

        // Makes sure that the function exists before calling it
        if ( $local_set ) {
          $pic_url = $simple_local_avatars->get_simple_local_avatar_url($id, 100);
          if (empty($pic_url)) {
            $pic_url = $defualt_url;
          }
        }

        // Building html
        $content .= '<a class="er-staff-link" href="' . $user_url . '">';
        $content .= '<div class="er-staff-widget" id="' . $safe_username . '">';
        $content .= '<img src="' . $pic_url . '" alt="' . $first . ' ' . $last . 'Staff Picture' . '" class="er-staff-picture">';
        $content .= '<span class="er-staff-text">';
        $content .= '<h5 class="er-staff-name">' . $first . '<br>' . $last . '</h5>';
        $content .= '<p class="er-staff-position">'. $position .'</p>';
        $content .= '</span>';
        $content .= '</div>';
        $content .= '</a>';
      }
    }
    $content .= '</div>';
  }

  return $content;

}

add_shortcode('staff-directory','er_staff_directory');
