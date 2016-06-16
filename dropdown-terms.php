<?php
/*
 * Plugin Name: Dropdown terms
 * Plugin URI: https://yogurt-design.com
 * Description: Dropdown terms
 * Version: 1.0
 * Author: Tit@r
 * Author URI: https://yogurt-design.com
 * License: GPL2
 * Text Domain: wpdpt
 * Domain Path: /languages/
*/


/**
 * Подключение скриптов и стилей плагина
 */
function dropdown_terms_plugin_admin_init() {
  wp_enqueue_script( 'dropdown-terms', plugins_url( '/js/dropdown-terms.js', __FILE__ ), array( 'jquery' ) );

  wp_enqueue_style( 'dropdown-terms', plugins_url( '/css/dropdown-terms.css', __FILE__ ) );
}
add_action( 'admin_init', 'dropdown_terms_plugin_admin_init' );

// создаем новую колонку
add_filter('manage_edit-product_cat_columns', 'add_views_column', 20);
add_filter('manage_edit-category_columns', 'add_views_column', 20);
function add_views_column( $columns ){
  return insert_after($columns, 'cb', 'drop', 'Drop');
}

/**
 * Вставка нового значения в массив после определённого
 * @param array $input
 * @param $refKey
 * @param $insertKey
 * @param $insertValue
 * @return array
 */
function insert_after(array $input, $refKey, $insertKey, $insertValue) {
  if (!isset($input[$refKey]) || isset($input[$insertKey]))
    return $input;

  $keys  = array_keys($input);
  $index = array_search($refKey, $keys);

  $result = $input;
  return array_slice($result, 0, $index + 1, true)
  + array($insertKey => $insertValue)
  + array_slice($result, $index + 1, null, true);
}

// заполняем колонку данными
// wp-content/plugins/woocommerce/includes/admin/class-wc-admin-taxonomies.php
add_filter('manage_product_cat_custom_column', 'fill_views_column', 10, 3);
add_filter('manage_category_custom_column', 'fill_views_column', 10, 3);
function fill_views_column($columns, $column, $id) {
  if ( 'drop' == $column ) {
    global $wpdb;
    $parent = $wpdb->get_var("SELECT parent FROM $wpdb->term_taxonomy WHERE (term_id = $id AND parent = 0) LIMIT 0, 1");
    $children = $wpdb->get_var("SELECT parent FROM $wpdb->term_taxonomy WHERE parent = $id LIMIT 0, 1");
    if($children > 0) {
      $columns .= '<a href="#' . $id . '" class="drop_children">+</a>';
    }elseif($parent !== null){
      $columns .= '<span class="drop_children"></span>';
    }
  }

  return $columns;
}
