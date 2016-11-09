<?php
/*
Plugin Name: Widget Slider
Plugin URI: http://quantiklab.com/
Description: A simple plugin for widget slider. For multiple use please create many category and select category name from widget dropdown option.
Version: 1.0
Author: Suzon Khan
Author URI: http://quantiklab.com/
License: GPL2
*/


require_once dirname(__FILE__) . '/ws-include.php';


add_action('init', 'register_widget_slider');
function register_widget_slider()
{
    $labels = array(
        "name" => "Widget Slider",
        "singular_name" => "Add Slider",
        "add_new" => "Add Slider",
        "all_items" => "All Slider",
    );

    $args = array(
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "show_ui" => true,
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array("slug" => "widget-slider", "with_front" => true),
        "query_var" => true,
        "menu_icon" => plugin_dir_url(__FILE__) . "images/pt-icon.png",
        "supports" => array("title", "editor", "thumbnail"),
        "taxonomies" => array("category")
    );
    register_post_type("widget-slider", $args);

// End of cptui_register_my_cpts()
}


class wp_my_plugin extends WP_Widget
{

    // constructor
    function wp_my_plugin()
    {
        parent::WP_Widget(false, $name = __('Widget Slider', 'wp_widget_plugin'));
    }

    // widget form creation
    function form($instance)
    {

// Check values
        if ($instance) {
            $title = esc_attr($instance['title']);
            $offset = esc_attr($instance['offset']);
            $slides_number = esc_textarea($instance['slides_number']);
            $select_category = esc_attr($instance['select_category']); // Added
            $pageurl = esc_attr($instance['pageurl']);
        } else {
            $title = '';
            $offset = '';
            $slides_number = '';
            $select_category = ''; // Added
            $pageurl = '';
        }
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php _e('Widget Title:', 'wp_widget_plugin'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('offset'); ?>">
                <?php _e('Set offset value:', 'wp_widget_plugin'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('offset'); ?>"
                   name="<?php echo $this->get_field_name('offset'); ?>" type="text" value="<?php echo $offset; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('slides_number'); ?>">
                <?php _e('Number of slides:', 'wp_widget_plugin'); ?>
            </label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('slides_number'); ?>"
                   name="<?php echo $this->get_field_name('slides_number'); ?>" value="<?php echo $slides_number; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('pageurl'); ?>">
                <?php _e('Page title link URL:', 'wp_widget_plugin'); ?>
            </label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('pageurl'); ?>"
                   name="<?php echo $this->get_field_name('pageurl'); ?>" value="<?php echo $pageurl; ?>"/>
        </p>
        <p>
            <label
                for="<?php echo $this->get_field_id('select_category'); ?>"><?php _e('Select Category:', 'wp_widget_plugin'); ?></label>
            <select name="<?php echo $this->get_field_name('select_category'); ?>"
                    id="<?php echo $this->get_field_id('select_category'); ?>" class="widefat">
                <?php
                //$options = array('lorem', 'ipsum', 'dolorem');
                $args = array(
                    'type' => 'widget-slider',
                    'child_of' => 0,
                    'parent' => '',
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => 1,
                    'hierarchical' => 1,
                    'exclude' => '',
                    'include' => '',
                    'number' => '',
                    'taxonomy' => 'category',
                    'pad_counts' => false);
                $categories = get_categories($args);
                foreach ($categories as $category) {
                    echo '<option value="' . $category->name . '" id="' . $category->name . '"', $select_category == $category->name ? ' selected="selected"' : '', '>', $category->name, '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }

// update widget
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['offset'] = strip_tags($new_instance['offset']);
        $instance['slides_number'] = strip_tags($new_instance['slides_number']);
        $instance['pageurl'] = strip_tags($new_instance['pageurl']);
        $instance['select_category'] = strip_tags($new_instance['select_category']);
        return $instance;
    }

// display widget
    function widget($args, $instance)
    {
        extract($args);
        // these are the widget options
        $title = apply_filters('widget_title', $instance['title']);
        $offset = $instance['offset'];
        $slides_number = $instance['slides_number'];
        $pageurl = $instance['pageurl'];
        $select_category = $instance['select_category'];

        echo $before_widget;
        // Display the widget
        echo '<div class="widget-text wp_widget_plugin_box">';

        // Check if title is set
        if ($title) {
            echo $before_title; ?>
            <?php
            if($pageurl !==""){
                ?>
                <a href="<?php  echo $pageurl; ?>"><?php echo $title; ?></a>
                <?php
            }else{
                ?>
                <?php echo $title; ?>
                <?php
            }
            ?>

      <?php
        echo $after_title;
        } ?>

        <ul class="bxslider">
            <?php
            $args = array('post_type' => 'widget-slider', 'category_name' => $select_category, 'offset' => $offset, 'posts_per_page' => $slides_number);
            $loop = new WP_Query($args);
            while ($loop->have_posts()) : $loop->the_post(); ?>
                <li>
                     <?php the_post_thumbnail(array(400, 400)); ?>

                    <p class="product-name"><?php //the_title(); ?></p>

                </li>
            <?php endwhile;
            wp_reset_query();
            ?>
        </ul>

        <?php echo '</div>';
        echo $after_widget;


    }
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));


