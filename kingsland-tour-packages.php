<?php
/*
Plugin Name: Kingsland Tour Packages
Description: A plugin to manage tour packages.
Version: 1.0
URI: https://SERPDIGISOLUTION.com
Author: Jitendra Kumawat
*/
// Load Elementor
// At the beginning of your form

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_post_meta')) {
    require_once(ABSPATH . 'wp-includes/post.php');
}

if (!function_exists('esc_url')) {
    require_once(ABSPATH . 'wp-includes/formatting.php');
}

if (!function_exists('esc_attr')) {
    require_once(ABSPATH . 'wp-includes/formatting.php');
}

if (!function_exists('checked')) {
    require_once(ABSPATH . 'wp-includes/general-template.php');
}


function register_kingsland_custom_widget($widgets_manager)
{
    require_once(__DIR__ . '/widgets/kingsland-travel-package-widget.php');
    $widgets_manager->register(new \Kingsland_Travel_Package_Widget());
}
add_action('elementor/widgets/register', 'register_kingsland_custom_widget');

function enqueue_kingsland_styles()
{
    wp_enqueue_style('kingsland-widget-style', plugin_dir_url(__FILE__) . 'assets/css/widget.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

}
add_action('wp_enqueue_scripts', 'enqueue_kingsland_styles');

// Enqueue admin styles
function kingsland_enqueue_admin_styles()
{
    wp_enqueue_style(
        'kingsland-admin-styles', // Handle for the stylesheet
        plugin_dir_url(__FILE__) . 'assets/css/admin.css', // Path to the CSS file
        array(), // Dependencies (if any)
        '1.0.0', // Version number
        'all' // Media type
    );
}
add_action('admin_enqueue_scripts', 'kingsland_enqueue_admin_styles');

// Enqueue admin scripts
function kingsland_enqueue_admin_scripts()
{
    wp_enqueue_script(
        'kingsland-admin-scripts', // Handle for the script
        plugin_dir_url(__FILE__) . 'js/Kings.js', // Path to the JavaScript file
        array('jquery', 'wp-mediaelement'), // Dependencies (if any)
        '1.0.0', // Version number
        true // Load in footer
    );
    wp_enqueue_script(
        'kingsland-gallery-script', // Handle for the gallery script
        plugin_dir_url(__FILE__) . 'js/gallery.js', // Path to the JavaScript file
        array('jquery', 'wp-mediaelement'), // Dependencies (if any)
        '1.0.0', // Version number
        true // Load in footer
    );
    // Enqueue the WordPress media uploader
    if (is_admin()) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'kingsland_enqueue_admin_scripts');

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include WordPress core functions
require_once(ABSPATH . 'wp-admin/includes/post.php');

// 


// Register Custom Post Type for Tour Packages
function kingsland_register_tour_packages()
{
    $labels = array(
        'name' => _x('Tour Packages', 'Post Type General Name', 'kingsland-tour-packages'),
        'singular_name' => _x('Tour Package', 'Post Type Singular Name', 'kingsland-tour-packages'),
        'menu_name' => __('Tour Packages', 'kingsland-tour-packages'),
        'name_admin_bar' => __('Tour Package', 'kingsland-tour-packages'),
        'archives' => __('Item Archives', 'kingsland-tour-packages'),
        'attributes' => __('Item Attributes', 'kingsland-tour-packages'),
        'parent_item_colon' => __('Parent Item:', 'kingsland-tour-packages'),
        'all_items' => __('All Items', 'kingsland-tour-packages'),
        'add_new_item' => __('Add New Item', 'kingsland-tour-packages'),
        'add_new' => __('Add New', 'kingsland-tour-packages'),
        'new_item' => __('New Item', 'kingsland-tour-packages'),
        'edit_item' => __('Edit Item', 'kingsland-tour-packages'),
        'update_item' => __('Update Item', 'kingsland-tour-packages'),
        'view_item' => __('View Item', 'kingsland-tour-packages'),
        'view_items' => __('View Items', 'kingsland-tour-packages'),
        'search_items' => __('Search Item', 'kingsland-tour-packages'),
        'not_found' => __('Not found', 'kingsland-tour-packages'),
        'not_found_in_trash' => __('Not found in Trash', 'kingsland-tour-packages'),
        'featured_image' => __('Featured Image', 'kingsland-tour-packages'),
        'set_featured_image' => __('Set featured image', 'kingsland-tour-packages'),
        'remove_featured_image' => __('Remove featured image', 'kingsland-tour-packages'),
        'use_featured_image' => __('Use as featured image', 'kingsland-tour-packages'),
        'insert_into_item' => __('Insert into item', 'kingsland-tour-packages'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'kingsland-tour-packages'),
        'items_list' => __('Items list', 'kingsland-tour-packages'),
        'items_list_navigation' => __('Items list navigation', 'kingsland-tour-packages'),
        'filter_items_list' => __('Filter items list', 'kingsland-tour-packages'),
    );


    $args = array(
        'label' => __('Tour Package', 'kingsland-tour-packages'),
        'description' => __('Custom post type for tour packages', 'kingsland-tour-packages'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'author'),
        'taxonomies' => array('category', 'post_tag'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-palmtree',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'tour-packages'),
    );

    register_post_type('tour_package', $args);
}
add_action('init', 'kingsland_register_tour_packages');

// Add Meta Boxes for Tour Package Details
function kingsland_add_tour_package_meta_boxes()
{
    add_meta_box('tour_package_details', 'Tour Package Details', 'kingsland_tour_package_details_callback', 'tour_package', 'normal', 'high');

}
add_action('add_meta_boxes', 'kingsland_add_tour_package_meta_boxes');

// Meta Box Callback Function

function kingsland_tour_package_details_callback($post)
{
    // Add nonce for security
    wp_nonce_field('kingsland_tour_package_nonce_action', 'kingsland_tour_package_nonce');
    $gallery_images = get_post_meta($post->ID, '_package_gallery_images', true);

    // Retrieve current meta values
    $fields = [
        'trip_location' => get_post_meta($post->ID, 'trip_location', true),
        'duration' => get_post_meta($post->ID, 'duration', true),
        'hotel_info' => get_post_meta($post->ID, 'hotel_info', true),
        'price' => get_post_meta($post->ID, 'price', true),
        'old_price' => get_post_meta($post->ID, 'old_price', true),
        'highlights' => maybe_unserialize(get_post_meta($post->ID, 'highlights', true)),
        'itinerary' => maybe_unserialize(get_post_meta($post->ID, 'itinerary', true)),
        'hotels' => maybe_unserialize(get_post_meta($post->ID, 'hotels', true)),
        'stay_info' => get_post_meta($post->ID, 'stay_info', true),
        'inclusions' => maybe_unserialize(get_post_meta($post->ID, 'inclusions', true)),
        'exclusions' => maybe_unserialize(get_post_meta($post->ID, 'exclusions', true)),
        'reviews' => get_post_meta($post->ID, 'reviews', true),
        'faqs' => maybe_unserialize(get_post_meta($post->ID, 'faqs', true)),
        'hotel_star' => get_post_meta($post->ID, 'hotel_star', true),
        'services' => maybe_unserialize(get_post_meta($post->ID, 'services', true)),
        'discount' => get_post_meta($post->ID, 'discount', true),
        'destinations_covered' => get_post_meta($post->ID, 'destinations_covered', true), // Corrected line
        'accommodation' => get_post_meta($post->ID, 'accommodation', true), // Added line
        'things_to_do' => get_post_meta($post->ID, 'things_to_do', true),
        'gallery' => maybe_unserialize(get_post_meta($post->ID, 'gallery', true)),
        // Add slideshow fields
        'slideshow_images' => get_post_meta($post->ID, 'slideshow_images', true),
        'slideshow_captions' => get_post_meta($post->ID, 'slideshow_captions', true),
        'slideshow_positions' => get_post_meta($post->ID, 'slideshow_positions', true),
        'destinations' => maybe_unserialize(get_post_meta($post->ID, 'destinations', true)),
    ];

    // Define available services
    $available_services = [
        'guide' => 'Guide',
        'hotel' => 'Hotel',
        'utensils' => 'Utensils',
        'car' => 'Car',
        'sightseeing' => 'Sightseeing',
    ];
    // Convert slideshow arrays if empty
    $fields['slideshow_images'] = is_array($fields['slideshow_images']) ? $fields['slideshow_images'] : array();
    $fields['slideshow_captions'] = is_array($fields['slideshow_captions']) ? $fields['slideshow_captions'] : array();
    $fields['slideshow_positions'] = is_array($fields['slideshow_positions']) ? $fields['slideshow_positions'] : array();

    // Render input fields for the meta box
    ?>


    <div class="admon-css-sidebar">
        <ul>
            <li>
                <a href="#accommodation" class="admon-css-tab-link active" data-tab="accommodation">Package Information</a>
            </li>

            <li>
                <a href="#gallery" class="admon-css-tab-link" data-tab="gallery">gallery</a>
            </li>



            <li>
                <a href="#Inclusions" class="admon-css-tab-link" data-tab="Inclusions">Inc/Exc</a>
            </li>
            <li>
                <a href="#itinerary" class="admon-css-tab-link" data-tab="itinerary">itinerary</a>
            </li>

            <li>
                <a href="#FAQs" class="admon-css-tab-link" data-tab="FAQs">FAQs</a>
            </li>
            <li>
                <a href="#Hotels" class="admon-css-tab-link" data-tab="Hotels">Hotels</a>
            </li>
            <li>
                <a href="#destinations" class="admon-css-tab-link" data-tab="destinations">Destinations</a>
            </li>
        </ul>
    </div>

    <div class="admon-css-content">

        <div id="accommodation" class="admon-css-tab-content active">
            <!--Accommodation  -->
            <label for="accommodation"><strong>Accommodation:</strong></label>
            <input type="text" name="accommodation" id="accommodation"
                value="<?php echo esc_attr($fields['accommodation']); ?>" />
            <!-- hotel star -->
            <label for="hotel_star">Hotel Star Rating:</label>
            <select id="hotel_star" name="hotel_star" style="width: 100%;">
                <option value="1 Star" <?php selected($fields['hotel_star'], '1 Star'); ?>>1 Star</option>
                <option value="2 Star" <?php selected($fields['hotel_star'], '2 Star'); ?>>2 Stars</option>
                <option value="3 Star" <?php selected($fields['hotel_star'], '3 Star'); ?>>3 Stars</option>
                <option value="4 Star" <?php selected($fields['hotel_star'], '4 Star'); ?>>4 Stars</option>
                <option value="5 Star" <?php selected($fields['hotel_star'], '5 Star'); ?>>5 Stars</option>
            </select>
            <!-- Things -->
            <label for="things_to_do"><strong>Things to do:</strong></label><br />
            <textarea name="things_to_do" id="things_to_do"><?php echo esc_textarea($fields['things_to_do']); ?></textarea>
            <!-- Trip -->
            <label for="trip_location">Trip Location:</label>
            <input type="text" id="trip_location" name="trip_location"
                value="<?php echo esc_attr($fields['trip_location']); ?>" style="width: 100%" />
            <div style="margin-bottom: 10px">
                <!-- destinations_covered -->
                <label for="destinations_covered">
                    <strong>Destinations Covered:</strong>
                </label>
                <input type="text" name="destinations_covered" id="destinations_covered"
                    value="<?php echo esc_attr($fields['destinations_covered']); ?>" style="width: 100%" />
            </div>
            <!--Duration  -->
            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" value="<?php echo esc_attr($fields['duration']); ?>"
                style="width: 100%" />
            <!--price  -->
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?php echo esc_attr($fields['price']); ?>"
                style="width: 100%" />

            <label for="old_price">Old Price:</label>
            <input type="number" id="old_price" name="old_price" value="<?php echo esc_attr($fields['old_price']); ?>"
                style="width: 100%" />
            <!--  -->
            <label>Services:</label>
            <div style="display:flex; width: 100%;     justify-content: space-evenly;">
                <?php foreach ($available_services as $service_key => $service_label): ?>
                    <div>
                        <input type="checkbox" id="services_<?php echo esc_attr($service_key); ?>" name="services[]"
                            value="<?php echo esc_attr($service_key); ?>" <?php checked(in_array($service_key, (array) $fields['services'])); ?>>
                        <label
                            for="services_<?php echo esc_attr($service_key); ?>"><?php echo esc_html($service_label); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <label for="highlights">Highlights (comma-separated):</label>
            <input type="text" id="highlights" name="highlights" value="<?php echo esc_attr($fields['highlights']); ?>"
                style="width: 100%" />
        </div>

        <div id="gallery" class="admon-css-tab-content">
            <!-- Slideshow Section -->
            <div class="slideshow-meta-section">
                <h4>Slideshow Images</h4>
                <div id="slideshow-items">
                    <?php foreach ($fields['slideshow_images'] as $index => $image): ?>
                        <div class="slideshow-item"
                            style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; display:flex; gap:20px">
                            <img src="<?php echo esc_url($image); ?>" alt="Slideshow Image"
                                style="max-width: 25%; height: 25%; margin-top: 10px;">
                            <p>
                                <label>Caption:</label><br>
                                <input type="text" name="slideshow_captions[]"
                                    value="<?php echo esc_attr(isset($fields['slideshow_captions'][$index]) ? $fields['slideshow_captions'][$index] : ''); ?>"
                                    style="width: 100%;">
                                <!-- <label>Image URL:</label><br> -->
                                <!-- uploaded img show with -->

                                <input type="hidden" name="slideshow_images[]" value="<?php echo esc_attr($image); ?>">
                                <button type="button" class="upload-image button" style="margin-top: 5px;">Upload
                                    Image</button>
                                <button type="button" class="remove-slide button"
                                    style="width: 100px;height: 20px;    margin-left: 5px;    margin-top: 5px;">Remove
                                    Slide</button>
                            </p>
                            <p>
                                <label>Position:</label><br>
                                <select name="slideshow_positions[]">

                                    <option value="top-left" <?php selected(isset($fields['slideshow_positions'][$index]) ? $fields['slideshow_positions'][$index] : '', 'top-left'); ?>>Top Left</option>
                                    <option value="top-right" <?php selected(isset($fields['slideshow_positions'][$index]) ? $fields['slideshow_positions'][$index] : '', 'top-right'); ?>>Top Right</option>
                                    <option value="bottom-left" <?php selected(isset($fields['slideshow_positions'][$index]) ? $fields['slideshow_positions'][$index] : '', 'bottom-left'); ?>>Bottom Left</option>
                                    <option value="bottom-right" <?php selected(isset($fields['slideshow_positions'][$index]) ? $fields['slideshow_positions'][$index] : '', 'bottom-right'); ?>>Bottom Right</option>
                                    <option value="middle" <?php selected(isset($fields['slideshow_positions'][$index]) ? $fields['slideshow_positions'][$index] : '', 'middle'); ?>>Middle</option>
                                </select>

                            </p>

                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-slide" class="button">Add New Slide</button>
            </div>
            <script>
                jQuery(document).ready(function ($) {
                    // Add new slide
                    $('#add-slide').click(function () {
                        var newSlide = `
                                                                                                                                                                                                                                                                                                                                                                            <div class="slideshow-item" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; display:flex; gap:20px">
                                                                                                                                                                                                                                                                                                                                                                            <img src="" alt="Slideshow Image" style="max-width: 25%; height: 25%; margin-top: 10px;">
                                                                                                                                                                                                                                                                                                                                                                            <p>
                                                                                                                                                                                                                                                                                                                                                                            <label>Caption:</label><br>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" name="slideshow_captions[]" value="" style="width: 100%;">
                             
                                                                                                                                                                                                                                                                                                                                                                            <input type="hidden" name="slideshow_images[]" value="">
                                                                                                                                                                                                                                                                                                                                                                            <button type="button" class="upload-image button" style="margin-top: 5px;">Upload Image</button>
                                                                                                                                                                                                                                                                                                                                                                            <button type="button" class="remove-slide button" style="width: 100px;height: 20px;margin-left: 5px;margin-top: 5px;">Remove Slide</button>
                                                                                                                                                                                                                                                                                                                                                                            </p>
                                                                                                                                                                                                                                                                                                                                                                            <p>
                                                                                                                                                                                                                                                                                                                                                                            <label>Position:</label><br>
                                                                                                                                                                                                                                                                                                                                                                            <select name="slideshow_positions[]">
                                                                                                                                                                                                                                                                                                                                                                            <option value=" ">Top Left</option>
                                                                                                                                                                                                                                                                                                                                                                            <option value="top-right">Top Right</option>
                                                                                                                                                                                                                                                                                                                                                                            <option value="bottom-left">Bottom Left</option>
                                                                                                                                                                                                                                                                                                                                                                            <option value="bottom-right">Bottom Right</option>
                                                                                                                                                                                                                                                                                                                                                                            <option value="middle">Middle</option>
                                                                                                                                                                                                                                                                                                                                                                            </select>
                                                                                                                                                                                                                                                                                                                                                                            </p>
                                                                                                                                                                                                                                                                                                                                                                            </div>`;
                        $('#slideshow-items').append(newSlide);
                    });

                    // Remove slide
                    $(document).on('click', '.remove-slide', function () {
                        $(this).closest('.slideshow-item').remove();
                    });

                    // Image upload
                    $(document).on('click', '.upload-image', function (e) {
                        e.preventDefault();
                        var button = $(this);
                        var imageInput = button.prev('input');
                        var previewImage = button.closest('.slideshow-item').find('img');

                        var frame = wp.media({
                            title: 'Select or Upload Image',
                            button: {
                                text: 'Use this image'
                            },
                            multiple: false
                        });

                        frame.on('select', function () {
                            var attachment = frame.state().get('selection').first().toJSON();
                            imageInput.val(attachment.url);
                            previewImage.attr('src', attachment.url); // Update the preview image
                        });

                        frame.open();
                    });
                });
            </script>
        </div>

        <div id="Inclusions" class="admon-css-tab-content">
            <div style="display:flex;">
                <div>
                    <label>Inclusions:</label>
                    <div id="inclusions-repeater">
                        <?php
                        if (!empty($fields['inclusions']) && is_array($fields['inclusions'])) {
                            foreach ($fields['inclusions'] as $index => $inclusion) { ?>
                                <div class="inclusion-item" style="margin-bottom: 10px">
                                    <input type="text" name="inclusions[<?php echo $index; ?>]"
                                        value="<?php echo esc_attr($inclusion); ?>" style="width: 90%" />
                                    <button type="button" class="remove-inclusion-btn">Remove</button>
                                </div>
                                <?php
                            }
                        } else {
                            // Default input field if no inclusions exist
                            ?>
                            <div class="inclusion-item" style="margin-bottom: 10px">
                                <input type="text" name="inclusions[0]" style="width: 90%" />
                                <button type="button" class="remove-inclusion-btn">Remove</button>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <button type="button" id="add-inclusion-btn">Add Inclusion</button>
                </div>
                <div>
                    <label>Exclusions:</label>
                    <div id="exclusions-repeater">
                        <?php
                        if (!empty($fields['exclusions']) && is_array($fields['exclusions'])) {
                            foreach ($fields['exclusions'] as $index => $exclusion) { ?>
                                <div class="exclusion-item" style="margin-bottom: 10px">
                                    <input type="text" name="exclusions[<?php echo $index; ?>]"
                                        value="<?php echo esc_attr($exclusion); ?>" style="width: 90%" />
                                    <button type="button" class="remove-exclusion-btn">Remove</button>
                                </div>
                                <?php
                            }
                        } else {
                            // Default input field if no exclusions exist
                            ?>
                            <div class="exclusion-item" style="margin-bottom: 10px">
                                <input type="text" name="exclusions[0]" style="width: 90%" />
                                <button type="button" class="remove-exclusion-btn">Remove</button>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <button type="button" id="add-exclusion-btn">Add Exclusion</button>
                </div>
            </div>
        </div>

        <div id="itinerary" class="admon-css-tab-content">

            <label for="itinerary">Itinerary:</label>
            <div id="itinerary-repeater">
                <?php
                if (!empty($fields['itinerary'])) {
                    foreach ($fields['itinerary'] as $index => $day) {
                        // Convert tags array to comma-separated string if it exists
                        $tags_string = isset($day['day_tags']) ?
                            (is_array($day['day_tags']) ? implode(', ', $day['day_tags']) : $day['day_tags']) : '';
                        ?>

                        <div class="itinerary-item" style="margin-bottom: 10px">
                            <input type="text" name="itinerary[<?php echo $index; ?>][day_title]" placeholder="Day Title"
                                value="<?php echo esc_attr($day['day_title']); ?>" style="width: 100%; margin-right: 2%" />




                            <input type="text" name="itinerary[<?php echo $index; ?>][day_tags]"
                                placeholder="Day tags (comma-separated)" value="<?php echo esc_attr($tags_string); ?>"
                                style="width: 100%; margin-right: 2%" />

                            <input type="text" name="itinerary[<?php echo $index; ?>][day_label]" placeholder="Day activities"
                                value="<?php echo esc_attr($day['day_label']); ?>" style="width: 100%; margin-right: 2%" />

                            <button type="button" class="remove-itinerary-btn">Remove</button>
                        </div>
                        <?php
                    }
                } else {
                    // Default input fields if no itinerary exists
                    ?>
                    <?php $day = array('day_tags' => ''); ?>
                    <div class="itinerary-item" style="margin-bottom: 10px">
                        <input type="text" name="itinerary[0][day_title]" placeholder="Day Title"
                            style=" width: 100%; margin-right: 2% " />

                        <input type="text" name="itinerary[0][day_tags]" placeholder="Day Tags (comma-separated)"
                            style="width: 100%; margin-right: 2%" />

                        <input type="text" name="itinerary[0][day_label]" placeholder="Day "
                            style="width: 100%; margin-right: 2%" />

                    </div>
                    <?php
                }
                ?>
            </div>
            <button type="button" id="add-itinerary-btn">Add Itinerary Day</button>
        </div>

        <div id="FAQs" class="admon-css-tab-content">
            <h2>FAQs</h2>
            <label>FAQs:</label>
            <div id="faq-repeater">
                <?php
                // Check if FAQs exist and populate them
                $faqs = maybe_unserialize(get_post_meta($post->ID, 'faqs', true));
                if (is_array($faqs) && !empty($faqs)) {
                    foreach ($faqs as $index => $faq) { ?>
                        <div class="faq-item" style="margin-bottom: 10px">
                            <input type="text" name="faqs[<?php echo $index; ?>][question]" placeholder="Question"
                                value="<?php echo esc_attr($faq['question']); ?>" style="width: 48%; margin-right: 2%" />
                            <input type="text" name="faqs[<?php echo $index; ?>][answer]" placeholder="Answer"
                                value="<?php echo esc_attr($faq['answer']); ?>" style="width: 48%" />
                        </div>
                        <?php
                    }
                } else {
                    // Default input fields if no FAQs exist
                    ?>
                    <div class="faq-item" style="margin-bottom: 10px">
                        <input type="text" name="faqs[0][question]" placeholder="Question" class="margin-right-2"
                            style="width: 48%;" />
                        <input type="text" name="faqs[0][answer]" placeholder="Answer" style="width: 48%" />
                    </div>
                    <?php
                }
                ?>
            </div>
            <button type="button" id="add-faq-btn">Add FAQ</button>
        </div>
        <div id="Hotels" class="admon-css-tab-content">

            <label>Hotels:</label>
            <div id="hotels-repeater">
                <?php
                if (!empty($fields['hotels']) && is_array($fields['hotels'])) {
                    foreach ($fields['hotels'] as $index => $hotel) {
                        ?>
                        <div class="hotel-item" style="margin-bottom: 10px; display:flex; gap: 7px;    align-items: stretch</div>;">
                            <!-- uploaded img show with -->
                            <div>
                                <img src="<?php echo esc_url($hotel['image']); ?>" alt="Hotel Image" style="    width: 150px;
    height: 150px;
    margin-top: 10px;">
                                <div style="display:flex;    display: fle</div>x;
    justify-content: space-evenly;">
                                    <button type="button" class="upload-image-btn"
                                        data-target="hotels[<?php echo $index; ?>][image]" style="width: 72px; 
    height: 47px;  padding:0;">Upload
                                        Image</button>
                                    <button type="button" class="remove-hotel-btn" style="width: 72px;
    height: 47px; padding:0;">Remove</button>
                                </div>
                            </div>

                            <div style="display:inline">
                                <input type="text" name="hotels[<?php echo $index; ?>][name]" placeholder="Hotel Name"
                                    value="<?php echo esc_attr($hotel['name']); ?>" />
                                <input type="text" name="hotels[<?php echo $index; ?>][address]" placeholder="Hotel Address"
                                    value="<?php echo esc_attr($hotel['address']); ?>" />
                            </div>
                            <input type="hidden" name="hotels[<?php echo $index; ?>][image]"
                                value="<?php echo esc_attr($hotel['image']); ?>" />
                            <select name="hotels[<?php echo $index; ?>][rating]" style="width: 20%; height:10%">
                                <option value="1" <?php selected($hotel['rating'], '1'); ?>>1 Star</option>
                                <option value="2" <?php selected($hotel['rating'], '2'); ?>>2 Stars</option>
                                <option value="3" <?php selected($hotel['rating'], '3'); ?>>3 Stars</option>
                                <option value="4" <?php selected($hotel['rating'], '4'); ?>>4 Stars</option>
                                <option value="5" <?php selected($hotel['rating'], '5'); ?>>5 Stars</option>
                            </select>
                        </div>
                        <?php
                    }
                } else {
                    // Default input field if no hotels exist
                    ?>
                    <?php $hotel = array('image' => ''); ?>
                    <div class="hotel-item" style="margin-bottom: 10px; display:flex; gap: 7px; align-items: stretch;">
                        <!-- uploaded img show with -->
                        <div>
                            <img src="" alt="Hotel Image" style="width: 150px; height: 150px; margin-top: 10px;">
                            <div style="display:flex; justify-content: space-evenly;">
                                <button type="button" class="upload-image-btn" data-target="hotels[0][image]"
                                    style="width: 72px; height: 47px; padding:0;">Upload Image</button>
                                <button type="button" class="remove-hotel-btn"
                                    style="width: 72px; height: 47px; padding:0;">Remove</button>
                            </div>
                        </div>

                        <div style="display:inline">
                            <input type="text" name="hotels[0][name]" placeholder="Hotel Name" />
                            <input type="text" name="hotels[0][address]" placeholder="Hotel Address" />
                        </div>
                        <input type="hidden" name="hotels[0][image]" value="" />
                        <select name="hotels[0][rating]" style="width: 20%; height:10%">
                            <option value="1">1 Star</option>
                            <option value="2">2 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                    </div>
                    <?php
                }
                ?>
            </div>
            <button type="button" id="add-hotel-btn">Add Hotel</button>
        </div>

        <div id="destinations" class="admon-css-tab-content">



            <div class="destinations-container">
                <label><strong>Destinations:</strong></label>
                <div id="destinations-wrapper">
                    <?php if ($fields['destinations'] && is_array($fields['destinations'])):
                        foreach ($fields['destinations'] as $index => $destination):
                            ?>
                            <div class="destination-input-group">
                                <div>
                                    <div class="image-preview">
                                        <?php if (!empty($destination['image'])): ?>
                                            <img src="<?php echo esc_url($destination['image']); ?>" />
                                        <?php endif; ?>
                                    </div>
                                    <input type="text" name="destination[<?php echo $index; ?>][name]"
                                        placeholder="Destination Name" value="<?php echo esc_attr($destination['name']); ?>" />

                                    <input type="text" name="destination[<?php echo $index; ?>][destination_url]"
                                        placeholder="Destination URL"
                                        value="<?php echo esc_attr($destination['destination_url']); ?>" />
                                </div>


                                <input type="hidden" name="destination[<?php echo $index; ?>][image]"
                                    value="<?php echo esc_attr($destination['image']); ?>" class="destination-image-input" />

                                <button type="button" class="upload-destination-image">Upload Image</button>
                                <button type="button" class="remove-destination">Remove</button>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <div class="destination-input-group">
                            <div class="image-preview"></div>
                            <input type="text" name="destination[0][name]" placeholder="Destination Name" />
                            <input type="text" name="destination[0][destination_url]" placeholder="Destination URL" />
                            <input type="hidden" name="destination[0][image]" class="destination-image-input" />
                            <button type="button" class="upload-destination-image">Upload Image</button>
                            <button type="button" class="remove-destination">Remove</button>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" id="add-destination">Add Destination</button>
            </div>

            <script>
                jQuery(document).ready(function ($) {
                    let destinationIndex = <?php echo !empty($fields['destinations']) ? count($fields['destinations']) : 1; ?>;

                    // Add new destination
                    $('#add-destination').click(function () {
                        const html = `
                                                <div class="destination-input-group" >
                                                <div class="image-preview"></div>
                            <input type="hidden" name="destination[${destinationIndex}][image]" class="destination-image-input" />
                            <button type="button" class="upload-destination-image">Upload Image</button>
                            <button type="button" class="remove-destination">Remove</button>
                            <input type="text" name="destination[${destinationIndex}][name]" placeholder="Destination Name" />
                            <input type="text" name="destination[${destinationIndex}][destination_url]" placeholder="Destination URL" />
                                                                                                </div>`;
                        $('#destinations-wrapper').append(html);
                        destinationIndex++;
                    });

                    // Remove destination
                    $(document).on('click', '.remove-destination', function () {
                        $(this).closest('.destination-input-group').remove();
                    });

                    // Image upload
                    $(document).on('click', '.upload-destination-image', function (e) {
                        e.preventDefault();
                        const button = $(this);
                        const imageInput = button.siblings('.destination-image-input');
                        const imagePreview = button.siblings('.image-preview');

                        const frame = wp.media({
                            title: 'Select Destination Image',
                            button: {
                                text: 'Use this image'
                            },
                            multiple: false
                        });

                        frame.on('select', function () {
                            const attachment = frame.state().get('selection').first().toJSON();
                            imageInput.val(attachment.url);
                            imagePreview.html(`<img src="${attachment.url}" style="max-width: 100px;" />`);
                        });

                        frame.open();
                    });
                });
            </script>
            <style>
                /* write css for these */
                .destination-input-group {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                    padding: 15px;
                    border: 1px solid #ddd;
                    margin-bottom: 15px;
                    background: #fff;
                }

                .image-preview {
                    min-height: 100%;
                    border: 1px dashed #ccc;
                    margin-bottom: 10px;
                }

                .image-preview img {
                    width: 100%;
                    height: auto;
                }

                .destination-input-group input[type="text"] {
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                }

                .destination-input-group button {
                    padding: 8px 15px;
                    background: #0085ba;
                    color: #fff;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    margin-right: 10px;
                }

                .destination-input-group button:hover {
                    background: #006799;
                }

                .destination-input-group .remove-destination {
                    background: #dc3232;
                }

                .destination-input-group .remove-destination:hover {
                    background: #aa0000;
                }
            </style>



        </div>



        <?php
}

// Add this function to fix the FAQ and Itinerary saving

function kingsland_save_tour_package_meta_data($post_id)
{

    // Check if nonce is set and valid
    if (!isset($_POST['kingsland_tour_package_nonce']) || !wp_verify_nonce($_POST['kingsland_tour_package_nonce'], 'kingsland_tour_package_nonce_action')) {
        return;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }


    // Define the fields to save
    $fields = [
        'trip_location',
        'duration',
        'hotel_info',
        'price',
        'old_price',
        'highlights',
        'itinerary',
        'hotels',
        'stay_info',
        'inclusions',
        'exclusions',
        'reviews',
        'faqs',
        'hotel_star',
        'services',
        'deal_pr',
        'destinations_covered',
        'accommodation', // Added accommodation field here
        'things_to_do', // Add this line
        'gallery',
        'destinations',
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            switch ($field) {
                case 'reviews':
                    update_post_meta($post_id, $field, sanitize_textarea_field($_POST[$field]));
                    break;

                case 'faqs':
                    // Handle FAQ data
                    if (is_array($_POST[$field])) {
                        $faqs = array_filter($_POST[$field], function ($faq) {
                            return !empty($faq['question']) && !empty($faq['answer']);
                        });

                        // Sanitize each FAQ
                        $sanitized_faqs = array_map(function ($faq) {
                            return array(
                                'question' => sanitize_text_field($faq['question']),
                                'answer' => sanitize_text_field($faq['answer'])
                            );
                        }, $faqs);

                        if (!empty($sanitized_faqs)) {
                            update_post_meta($post_id, $field, maybe_serialize($sanitized_faqs));
                        } else {
                            delete_post_meta($post_id, $field);
                        }
                    }
                    break;

                case 'services':
                    // Handle services data
                    if (is_array($_POST[$field])) {
                        $services = array_map('sanitize_text_field', $_POST[$field]);
                        update_post_meta($post_id, $field, maybe_serialize($services));
                    }
                    break;

                case 'itinerary':
                    if (is_array($_POST[$field])) {
                        $itinerary = array_filter($_POST[$field], function ($day) {
                            return !empty($day['day_title']) && !empty($day['day_label']);
                        });

                        $sanitized_itinerary = array_map(function ($day) {
                            // Convert comma-separated string to array and trim whitespace
                            $tags = !empty($day['day_tags']) ?
                                array_map('trim', explode(',', $day['day_tags'])) :
                                array();

                            return array(
                                'day_title' => sanitize_text_field($day['day_title']),
                                'day_label' => sanitize_text_field($day['day_label']),
                                'day_tags' => array_filter($tags) // Remove empty tags
                            );
                        }, $itinerary);

                        if (!empty($sanitized_itinerary)) {
                            update_post_meta($post_id, $field, maybe_serialize($sanitized_itinerary));
                        } else {
                            delete_post_meta($post_id, $field);
                        }
                    }
                    break;

                case 'gallery':
                    // Handle gallery data
                    if (is_array($_POST[$field])) {
                        $gallery = array_map('sanitize_text_field', $_POST[$field]);
                        update_post_meta($post_id, $field, maybe_serialize($gallery));
                    }
                    break;
                case 'hotels':
                    // Handle hotels data
                    if (is_array($_POST[$field])) {
                        $hotels = array_filter($_POST[$field], function ($hotel) {
                            return !empty($hotel['name']) && !empty($hotel['rating']) && !empty($hotel['address']) && !empty($hotel['image']);
                        });

                        // Sanitize each hotel
                        $sanitized_hotels = array_map(function ($hotel) {
                            return array(
                                'name' => sanitize_text_field($hotel['name']),
                                'rating' => intval($hotel['rating']),
                                'address' => sanitize_text_field($hotel['address']),
                                'image' => sanitize_text_field($hotel['image'])
                            );
                        }, $hotels);

                        if (!empty($sanitized_hotels)) {
                            update_post_meta($post_id, $field, maybe_serialize($sanitized_hotels));
                        } else {
                            delete_post_meta($post_id, $field);
                        }
                    }
                    break;



                case 'highlights':
                    // Handle highlights data
                    $highlights = sanitize_text_field($_POST[$field]);
                    update_post_meta($post_id, $field, $highlights);
                    break;
                case 'inclusions':
                    // Handle inclusions data
                    if (is_array($_POST[$field])) {
                        $inclusions = array_filter($_POST[$field], function ($inclusion) {
                            return !empty($inclusion);
                        });

                        // Sanitize each inclusion
                        $sanitized_inclusions = array_map('sanitize_text_field', $inclusions);

                        if (!empty($sanitized_inclusions)) {
                            update_post_meta($post_id, $field, maybe_serialize($sanitized_inclusions));
                        } else {
                            delete_post_meta($post_id, $field);
                        }
                    }
                    break;

                case 'exclusions':
                    // Handle exclusions data
                    if (is_array($_POST[$field])) {
                        $exclusions = array_filter($_POST[$field], function ($exclusion) {
                            return !empty($exclusion);
                        });

                        // Sanitize each exclusion
                        $sanitized_exclusions = array_map('sanitize_text_field', $exclusions);

                        if (!empty($sanitized_exclusions)) {
                            update_post_meta($post_id, $field, maybe_serialize($sanitized_exclusions));
                        } else {
                            delete_post_meta($post_id, $field);
                        }
                    }
                    break;
                case 'destinations':

                case 'things_to_do': // Add this line
                    // Handle comma-separated fields
                    $value = sanitize_text_field($_POST[$field]);
                    update_post_meta($post_id, $field, $value);
                    break;

                case 'destinations_covered':
                    // Handle comma-separated fields
                    $value = sanitize_text_field($_POST[$field]);
                    update_post_meta($post_id, $field, $value);
                    break;
                case 'accommodation': // Added accommodation field here
                    // Handle comma-separated fields
                    $value = sanitize_text_field($_POST[$field]);
                    update_post_meta($post_id, $field, $value);
                    break;

                default:
                    update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
                    break;
            }
        } else {
            // Optionally, delete the meta if not set
            delete_post_meta($post_id, $field);
        }
    }
    // Save slideshow data
    if (isset($_POST['slideshow_images'])) {
        update_post_meta($post_id, 'slideshow_images', array_map('sanitize_text_field', $_POST['slideshow_images']));
    }
    if (isset($_POST['slideshow_captions'])) {
        update_post_meta($post_id, 'slideshow_captions', array_map('sanitize_text_field', $_POST['slideshow_captions']));
    }
    if (isset($_POST['slideshow_positions'])) {
        update_post_meta($post_id, 'slideshow_positions', array_map('sanitize_text_field', $_POST['slideshow_positions']));
    }



    if (isset($_POST['destination']) && is_array($_POST['destination'])) {
        // Filter out empty destinations
        $destinations = array_filter($_POST['destination'], function ($dest) {
            return !empty($dest['name']) || !empty($dest['destination_url']) || !empty($dest['image']);
        });

        // Sanitize and save each destination separately
        $sanitized_destinations = array();
        foreach ($destinations as $destination) {
            if (!empty($destination)) {
                $sanitized_destination = array(
                    'name' => sanitize_text_field($destination['name']),
                    'destination_url' => esc_url_raw($destination['destination_url']),
                    'image' => esc_url_raw($destination['image'])
                );
                $sanitized_destinations[] = $sanitized_destination;
            }
        }

        // Only save if there are valid destinations
        if (!empty($sanitized_destinations)) {
            update_post_meta($post_id, 'destinations', $sanitized_destinations);
        }
    } else {
        // Clear destinations if none submitted
        delete_post_meta($post_id, 'destinations');
    }
    // Add debugging
    error_log('POST data: ' . print_r($_POST, true));
    error_log('Saved meta fields: ' . print_r(get_post_meta($post_id), true));
}
add_action('save_post', 'kingsland_save_tour_package_meta_data');


// Load the template for displaying the package
function kingsland_load_single_package_template($single_template)
{
    global $post;

    if ($post->post_type === 'tour_package') {
        $single_template = plugin_dir_path(__FILE__) . 'template/single-tour-package.php';
    }

    return $single_template;
}
add_filter('single_template', 'kingsland_load_single_package_template');


