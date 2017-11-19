<?php

/* 
 * Plugin Name: Wordpress 3D Viewer
 * Author Name: Cheikh Ahmet Tidjane SANKARE
 * Author URI: https://www.freelancer.com/u/whistiti.html
 * Plugin URI: https://www.freelancer.com/u/whisiti.html
 * Description: Manage and view 3D Objects from Sketchfab APIs. It depends on advanced-custom-fields plugin
 */

define("WP3DV_BASE_PATH", plugin_dir_path(__FILE__));
define("WP3DV_BASE_URL", plugin_dir_url(__FILE__));

require_once(WP3DV_BASE_PATH."/includes/3d-object.php");
require_once(WP3DV_BASE_PATH."/shortcodes/search-viewer.php");

class WP3DV_Plugin {
    protected static $languages ;
    protected static $translationSuffix = "_tr_";

    public static function getLanguages(){
        if (empty(self::$languages))
            self::$languages = Array(
                Array(
                    "code" => "de_DE",
                    "name" => "German"
                )/*,
                Array(
                    "code" => "fr_FR",
                    "name" => "French"
                )*/
            );

        return self::$languages;
    }
    public static function getTranslationSuffix(){
        return self::$translationSuffix;
    }

    public static function getTranslationKeySuffix($code){
        return self::$translationSuffix.$code;
    }

    public static function addLanguage($code, $name){
        self::$getLanguages();
        array_push(self::$languages, Array(
            "name" => $name,
            "code" => $code
        ));
    }

    public static function init(){
        add_action('init', Array("WP3DV_Plugin", "onInitHook"));
        add_action('add_meta_boxes', Array("WP3DV_3DObject", "createMetaBoxes"));
        add_action( 'edit_form_after_title', Array("WP3DV_3DObject", "titleTranslations"));
        add_action( 'wp3dv-category_add_form_fields', Array("WP3DV_3DObject", "addObjectCategoryFormField"));
        add_action( 'wp3dv-category_edit_form_fields', Array("WP3DV_3DObject", "addObjectCategoryFormFieldOnEditPage"));
        add_action( 'edit_wp3dv-category', Array("WP3DV_3DObject", "saveCategory"));
        add_action( 'create_wp3dv-category', Array("WP3DV_3DObject", "saveCategory"));
        add_action('save_post', Array("WP3DV_3DObject", "saveObject"));
        add_shortcode("wp3dv-search-viewer", Array("WP3DV_SearchViewer", "show"));
        add_filter('single_template', Array("WP3DV_3DObject", "singleTemplate"));
        add_action( 'admin_enqueue_scripts', Array(get_called_class(), "enqueueScriptsAndSyles"));
        add_action( 'wp_enqueue_scripts', Array(get_called_class(), "enqueueScriptsAndSyles"));
    }
    
    public static function onInitHook(){
        WP3DV_3DObject::registerPostType();
        WP3DV_3DObject::registerTaxonomies();
    }
    
    public static function onAdminInitHook(){}
    
    public static function enqueueScriptsAndSyles(){
        wp_register_style("wp3v_ionicons_css_min", "http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css");
        wp_register_style("wp3v_bootstrap_css_min", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
        wp_register_script("wp3v_bootstrap_js_min", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js");
        wp_enqueue_script("jquery");
        wp_enqueue_style("wp3v_bootstrap_css_min");
        wp_enqueue_style("wp3v_ionicons_css_min");
        wp_enqueue_script("wp3v_bootstrap_js_min");
    }
    
    public static function onActivation(){}
    
    public static function onDeactivation(){}
}

WP3DV_Plugin::init();
