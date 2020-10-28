<?php
/**
* Plugin Name: Pirate Game Plugin
* Plugin URI:
* Description: Creates a styled staff directory dynamically based off of data base
* Version: 1.0
* Author: Elliot Roe
* Author URI:
**/

function er_staff_directory() {

  //Creates an array of section info containing both title and id used for searches
  $sections = array(
    array(
      "title" => "Co-Editors",
      "id" => "CoEditor"
    ),
    array(
      "title" => "News Editors",
      "id" => "NewsEditor"
    ),
    array(
      "title" => "Opinion Editors",
      "id" => "OpinionEditor"
    ),
    array(
      "title" => "In-depth Editors",
      "id" => "InDepthEditor"
    ),
    array(
      "title" => "Feature Editors",
      "id" => "FeatureEditor"
    ),
    array(
      "title" => "Sports Editors",
      "id" => "SportsEditor"
    ),
    array(
      "title" => "Backpage Editors",
      "id" => "BackpageEditor"
    ),
    array(
      "title" => "Website Coordinator",
      "id" => "WebsiteCoordinator"
    ),
    array(
      "title" => "Graphics Editors",
      "id" => "GraphicsEditor"
    ),
    array(
      "title" => "Graphics Staff",
      "id" => "GraphicsStaff"
    ),
    array(
      "title" => "Staff Reporters",
      "id" => "StaffReporter"
    ));

    foreach ($sections as $section) {
      $content = '<h2 class="er-section-title">' . $section["title"] . '</h2>';

      // Searches for users in the section
      $users = new WP_User_Query( array(
          'search'         => '*'.esc_attr( $section["id"] ),
          'search_columns' => array(
              'user_login'
          ),
      ) );
      $users_found = $users->get_results();
      foreach ($users_found as $user) {
        // Gets suer id for meta searches
        $id = $user->ID;
        // Bunch of meta searches for the info needed
        $user_url = get_user_meta($id, 'user_url', true);
        $safe_username = str_replace('.', '', get_user_meta($id, 'username', true));
        $first = get_user_meta($id, 'first_name', true);
        $last = get_user_meta($id, 'last_name', true);
        $description = get_user_meta($id, 'description', true);

        // Processes bio make it at most 123 characters long
        if (strlen($description)>130) {
          // Cuts bio down to 123 characters
          $description = substr($description, 0, 123);
          // Cuts off the partial word if there is one
          $description = substr($description, 0, strrpos($description,' ')) . ' [...]';
        }

        // Building html
        $content .= '<div class="er-section-wrapper">';
        $content .= '<a class="er-staff-link" href="' . $user_url . '">';
        $content .= '<div class="er-staff-widget" id="' . $safe_username . '">';
        $content .= '<img src="https://bexleytorch.org/wp-content/uploads/2020/10/IMG_2191.jpg" alt="https://media.istockphoto.com/vectors/default-profile-picture-avatar-photo-placeholder-vector-illustration-vector-id1223671392?k=6&m=1223671392&s=170667a&w=0&h=zP3l7WJinOFaGb2i1F4g8IS2ylw0FlIaa6x3tP9sebU=" class="er-staff-picture">';
        $content .= '<span class="er-staff-text">';
        $content .= '<h3 class="er-staff-name">' . $first . ' ' . $last . '</h3>';
        $content .= '<p class="er-staff-bio">'. $description .' [...]</p>';
        $content .= '</span>';
        $content .= '</div>';
        $content .= '</a>';
      }
    }

    return $content

}

add_shortcode('staff-directory','er_staff_directory');
