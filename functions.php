<?php

add_theme_support( 'post-thumbnails' );

function change_post_menu_label() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'Products';
	$submenu['edit.php'][5][0] = 'All Products';
	$submenu['edit.php'][10][0] = 'Add Product';
	$submenu['edit.php'][16][0] = 'Product Tags';
	echo '';
}
function change_post_object_label() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	$labels->name = 'Products';
	$labels->singular_name = 'Product';
	$labels->add_new = 'Add Product';
	$labels->add_new_item = 'Add Product';
	$labels->edit_item = 'Edit Product';
	$labels->new_item = 'Product';
	$labels->view_item = 'View Product';
	$labels->search_items = 'Search Products';
	$labels->not_found = 'No Products found';
	$labels->not_found_in_trash = 'No Products found in Trash';
}
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );
add_action('wp_ajax_tsp_calc_type', 'tsp_calc_type');
add_action('wp_ajax_nopriv_tsp_calc_type', 'tsp_calc_type');
add_action('wp_ajax_tsp_stat_qty', 'tsp_stat_qty');
add_action('wp_ajax_nopriv_tsp_stat_qty', 'tsp_stat_qty');

add_post_type_support( 'post', 'page-attributes' ); //add menu order to posts

// AJAX FUNCTIONS

function tsp_calc_type() {
	global $wpdb;
	if (! wp_verify_nonce($_POST['nonce'], 'tsp_nonce') ){
		$return = 'bad nonce';
		die();
	}
	
	$cat = esc_html($_POST['cat']);
	$products = get_posts('posts_per_page=-1&cat='.$cat);
	$return = array();
	foreach($products as $product) {
		$price = get_field('price', $product->ID);
		$return[] = array('id' => $product->ID, 'slug' => $product->post_name, 'title' => $product->post_title, 'price' => $price);
	}
	
	echo json_encode($return);
	exit;
}

function tsp_stat_qty() {
	global $wpdb;
	if (! wp_verify_nonce($_POST['nonce'], 'tsp_nonce') ){
		$return = 'bad nonce';
		die();
	}
	
	$return = 'empty';
	$qty_price = esc_html($_POST['array']);
	$name = esc_html($_POST['size']);
	$stat_sizes = get_field('sizes', 382);

	foreach($stat_sizes as $size) {
		if($size['size'] == $name) {
			$return = $size['qty_price'];
		}
	}
	
	echo json_encode($return);
	exit;
}

// CATEGORY HAS PARENT

function category_has_parent($catid){
    $category = get_category($catid);
    if ($category->category_parent > 0){
        return true;
    }
    return false;
}
//*******************************************
// WP Nav Menus
//*******************************************

 // This theme uses wp_nav_menu() in two locations.
register_nav_menus( array(
	'header' => 'Main Navigation',
	'mini' => 'Mobile Navigation'
) );
class Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu{
 function start_lvl(&$output, $depth){
      $indent = str_repeat("\t", $depth); // don't output children opening tag (`<ul>`)
    }

    function end_lvl(&$output, $depth){
      $indent = str_repeat("\t", $depth); // don't output children closing tag
    }

    function start_el(&$output, $item, $depth, $args){
      // add spacing to the title based on the depth
      $item->title = str_repeat("&nbsp;", $depth * 4).$item->title;

        $attributes .= ' id="menu-item-'. $item->ID . '"' . $value;  
        $attributes  .= ! empty( $item->classes ) ? ' class="'.implode($item->classes, ' ') .'"' : '';  
        $attributes  .= ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';  
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';  
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';  
        $attributes .= ! empty( $item->url )        ? ' value="'   . esc_attr( $item->url        ) .'"' : '';  
        
        $item_output .= '<option'. $attributes .'>';  
        $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );  
        $item_output .= '</option>';  
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );  



      // no point redefining this method too, we just replace the li tag...
      $output = str_replace('<li', '<option', $output);
    }


//	  $class_names = 'class="'.implode($item->classes, ' ');


    function end_el(&$output, $item, $depth){
      $output .= "</option>\n"; // replace closing </li> with the option tag
    }
}
function my_myme_types($mime_types){
	$mime_types['eps'] = 'application/postscript'; 
	$mime_types['ai'] = 'application/postscript';
	$mime_types['ait'] = 'application/postscript';
	$mime_types['psd'] = 'application/postscript'; 
	$mime_types['tif'] = 'application/postscript';
    return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);//eof