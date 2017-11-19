<?php


class WP3DV_3DObject {
    public static $errors = Array();
    
    public static function init(){
        
    }
    
    public static function registerPostType(){
        $labels = array(
            'name'                  => "3D Objects",
            'singular_name'         => "3D Object",
            'menu_name'             => "3D Objects",
            'name_admin_bar'        => "3D Object",
            'add_new'               => "Add New",
            'add_new_item'          => "Add New 3D Object",
            'new_item'              => "New 3D Object",
            'edit_item'             => "Edit Object",
            'view_item'             => "View Object",
            'all_items'             => "All 3D Objects",
            'search_items'          => "Search 3D objects",
            'parent_item_colon'     => "Parent Object",
            'not_found'             => "No Object Found",
            'not_found_in_trash'    => "No 3D Objects found in trash",
            'featured_image'        => "Object's Image",
            'set_featured_image'    => "Set Object's Image",
            'remove_featured_image' => "Remove Object's Image",
            'use_featured_image'    => "Use Object's Image",
            'archives'              => "3D Archives Objects",
            'insert_into_item'      => "Insert Into 3D Object",
            'uploaded_to_this_item' => "Upload to this 3D Object",
            'filter_items_list'     => "Filter 3D Objects List",
            'items_list_navigation' => "3D Objects List Navigation",
            'items_list'            => "3D Objects List"
        ); 
        
        $args = array(
            'labels'                => $labels,
            //'taxonomies'            => $taxonomies, 
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => 'wp3dv-object'),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'show_in_rest'          => true,
            //'rest_controller_class' => 'WP_REST_Posts_Controller',
            'hierarchical'          => false,
            'menu_position'         => null,
            'supports'              => array( 'title', /*'editor', 'author',*/ 'thumbnail'/*, 'excerpt', 'comments'*/ )
        ); 
        register_post_type('wp3dv-object', $args);
    }
    
    public static function registerTaxonomies(){
	//register_taxonomy_for_object_type('category', 'wp3dv-object');
	//register_taxonomy_for_object_type('post_tag', 'wp3dv-object'); 
        $labels = Array(
            'name'              => "3D Categories",
            'singular_name'     => "3D Category",
            'search_items'      => "Search 3D categories",
            'all_items'         => "All 3D Categories",
            'parent_item'       => "Parent Category",
            'parent_item_colon' => "Parent 3D Category",
            'edit_item'         => "Edit 3D Category",
            'update_item'       => "Update 3D category",
            'add_new_item'      => "Add New 3D Category",
            'new_item_name'     => "New 3D Category Name",
            'menu_name'         => "3D Category",
        );
        $args = Array(
            'hierarchical'      => true, // make it hierarchical (like categories)
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            //'rest_controller_class' => 'WP_REST_Posts_Controller',
            'query_var'         => true,
            'rewrite'           => Array('slug' => 'wp3dv-category')
        );
        register_taxonomy('wp3dv-category', Array('wp3dv-object'), $args); 
    }
    
    public static function registerFilters(){
	if(is_archive() && $query->is_main_query() && empty($query->query_vars['suppress_filters'])) {
            $query->set('post_type', array( 
                'post',
                'page',
                'wp3dv-object'
            ));
	}        
    }
    
    public static function createMetaBoxes(){
        $screens = Array('wp3dv-object');
        foreach ($screens as $screen) {
            add_meta_box(
                'wp3dv_sketchfab_id',
                "Other Data",
                Array("WP3DV_3DObject","metabox_html"),
                $screen
            );        
        }
    }
    
    public static function metabox_html($post){
        $post_id = $post->ID;
        $default_to_load = get_option("wp3dv_default_object");
        $sketchfab_id = get_post_meta($post->ID, 'wp3dv_sketchfab_id', true);
        $sketchfab_id_nbrand = get_post_meta($post->ID, 'wp3dv_sketchfab_id_nbrand', true);
        $year = get_post_meta($post->ID, 'wp3dv_year', true);  
        $asset_tag = get_post_meta($post->ID, 'wp3dv_asset_tag', true);  
        $sketchfab_pass = get_post_meta($post->ID, 'wp3dv_sketchfab_pass', true);  
        //$sketchfab_passtoken = get_post_meta($post->ID, 'wp3dv_sketchfab_passtoken', true);  
        $description = get_post_meta($post->ID, 'wp3dv_description', true); 
        $thumb_url = get_post_meta($post->ID, 'wp3dv_sketchfab_thumb_url', true); 
        $relobjects = explode(":", get_post_meta($post->ID, 'wp3dv_relobjects', true));     
?>
        <div class="row col-md-12">
        </div>
        <div class='row' id="wp3dv-edit-object-metabox" >            
            <div class="col-md-4 form-group">
                <label for="wp3dv-sketchfab-id">Sketchfab ID (BRANDED):</label>
                <input class="form-control" type="text" name="wp3dv-sketchfab-id" value="<?php echo $sketchfab_id ; ?>" placeholder="Object's ID from sketchfab" required/>
            </div>
            <div class="col-md-4 form-group">
                <label for="wp3dv-sketchfab-id-nbrand">Sketchfab ID (NON BRANDED):</label>
                <input class="form-control" type="text" name="wp3dv-sketchfab-id-nbrand" value="<?php echo $sketchfab_id_nbrand ; ?>" placeholder="Object's ID from sketchfab"/>
            </div> 
            <div class="col-md-4 form-group">
                <label for="wp3dv-sketchfab-pass">Sketchfab Password:</label>
                <input class="form-control" type="text" name="wp3dv-sketchfab-pass" value="<?php echo $sketchfab_pass; ?>" placeholder="Object's password from sketchfab"/>
            </div>             
            <div class="col-md-4 form-group">
                    <label for="wp3dv-year">Year:</label>
                    <select name="wp3dv-year" class="form-control" required>
                        <option value="">Select Year</option>
                        <option value="1969" <?php selected($year, '1969');?>>1969</option>
<?php   
                    for ($i = 1983 ; $i <= date("Y") ; $i++){
?>            
                        <option value="<?php echo $i ; ?>" <?php selected($year, (string) $i ) ; ?>><?php echo $i ; ?></option>
<?php            
                    }
?>            
                    </select>
            </div>      
            <div class="col-md-4 form-group">
                <label>Asset Tag:</label>
                <input class="form-control" type="text" name="wp3dv-asset-tag" placeholder="Object's Asset Tag" value="<?php echo $asset_tag; ?>"/>
            </div>  
            <div class="col-md-4 form-group">
                <label>&nbsp;</label>
                <label class="col-md-12">
                    <input class="form-control" type="checkbox" name="wp3dv-set-onload" value="onload" <?php echo ($default_to_load == $post->ID) ? "checked" : "" ?>/>
                    DEFAULT TO LOAD
                </label>             
            </div>              
            <div class="col-md-6 form-group">
                <label for="wp3dv-year">&nbsp;</label>
                <a href="#" class="btn btn-primary wp3dv-load-sketchfab-thumb-button">SKETCHFAB THUMB</a>  
            </div>               
            <div class="col-md-12 form-group">
                <input type="hidden" class="wp3dv-load-sketchfab-thumb-input" name="wp3dv-sketchfab-thumb-url" value="<?php echo $thumb_url; ?>"/>
                <!--<label for="wp3dv-description" class="col-md-12">Sketchfab Thumbnail</label>-->
                <div class="col-md-12">
                    <img class="img img-responsive img-thumbnail wp3dv-load-sketchfab-thumb-image" src="<?php echo $thumb_url; ?>">
                </div>                    
            </div>   
            
            <div class="col-md-12 form-group">
                <label for="wp3dv-description">Object's Description:</label>
                <?php wp_editor( $description, 'wp3dv-description'); ?>                 
            </div> 
            <?php foreach (WP3DV_Plugin::getLanguages() as $language) { ?>
                <?php $description_meta_key = "wp3dv_description".WP3DV_Plugin::getTranslationKeySuffix($language["code"]); ?>
                <?php $description_tr = get_post_meta($post_id, $description_meta_key, true); ?>
                <div class="col-md-12 form-group">
                    <label for="wp3dv-description_<?php echo $language["code"]; ?>">Object's <?php echo $language["name"]; ?> Description:</label>
                    <?php wp_editor( $description_tr, 'wp3dv-description_'.$language["code"]); ?>                 
                </div> 
            <?php } ?>
            
            
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2 style="font-weight: bolder; font-size: 15px;">Related Objects</h2></div>
                    <div class="panel-body">  
                    <table class="col-md-12 table table-responsive table-bordered display wp3dv-relobjects-table tablesorter">
                        <thead>
                            <tr>
                                <th><center class="col-md-12 btn btn-primary"><strong>RELATED</strong></center></th>
                                <th><center class="col-md-12 btn btn-primary"><strong>OBJECT NAME</strong></center></th>
                                <th><center class="col-md-12 btn btn-primary"><strong>OBJECT CATEGORIES</strong></center></th>
                                <th><center class="col-md-12 btn btn-primary"><strong>YEAR</strong></center></th>
                            </tr> 
                        </thead>
                        <tbody style="max-height: 400px; overflow: auto;">
<?php
                        $query = new WP_Query(array(
                            'post_type' => 'wp3dv-object',
                            "post_status" => array('publish', 'private'),
                            'posts_per_page'=> -1
                        ));  
                        if ($query->have_posts()){
                            while($query->have_posts()){
                                $query->the_post();
                                $the_post = get_post();
                                if (! in_array(strval($the_post->ID), $relobjects) || 
                                        strcasecmp(strval($post->ID), strval($the_post->ID)) == 0){continue;}

?>
                                    <tr>
                                        <td><center><input type="checkbox" class="form-control" name="wp3dv-relobjects[]" value="<?php echo $the_post->ID; ?>" checked></center></td>
                                        <td><center><?php echo get_the_title(); ?></center></td>
                                        <td>
                                            <center>
                                            <?php   
                                                $categories = implode("", get_the_taxonomies($the_post->ID));
                                                $categories = array_slice(explode(":", $categories), 1);
                                                $categories = implode("", $categories);
                                                echo $categories; //implode(" ", $categories) ;
                                            ?>
                                            </center>
                                        </td>
                                        <td><center><?php echo get_post_meta($the_post->ID, "wp3dv_year", true) ?></center></td>
                                    </tr>
<?php                                 
                            }
                            /* Restore original Post Data */
                            wp_reset_postdata();
                        }

                        $query = new WP_Query(array(
                            'post_type' => 'wp3dv-object',
                            "post_status" => array('publish', 'private'),
                            'posts_per_page'=> -1
                        ));  
                        if ($query->have_posts()){
                            while($query->have_posts()){
                                $query->the_post();
                                $the_post = get_post();
                                if (in_array(strval($the_post->ID), $relobjects) || 
                                        strcasecmp(strval($post->ID), strval($the_post->ID)) == 0){continue;}

?>
                                    <tr>
                                        <td><center><input type="checkbox" name="wp3dv-relobjects[]" value="<?php echo $the_post->ID; ?>"></center></td>
                                        <td><center><?php echo get_the_title(); ?></center></td>
                                        <td>
                                            <center>
                                            <?php   
                                                $categories = implode("", get_the_taxonomies());
                                                $categories = array_slice(explode(":", $categories), 1);
                                                $categories = implode("", $categories);
                                                echo $categories; //implode(" ", $categories) ;
                                            ?>
                                            </center>
                                        </td>
                                        <td><center><?php echo get_post_meta($the_post->ID, "wp3dv_year", true) ?></center></td>
                                    </tr>
<?php                                 
                            }
                            /* Restore original Post Data */
                            wp_reset_postdata();
                        }                        
?>                             
                        </tbody>
                        <tfoot>
                            <!--<tr>
                                <td><center><strong>RELATED</strong></center></td>
                                <td><center><strong>OBJECT NAME</strong></center></td>
                                <td><center><strong>YEAR</strong></center></td>
                            </tr> -->                            
                        </tfoot>
                    </table>  
                    <style src="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"></style>
                    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.7/js/jquery.tablesorter.min.js"></script> 
                    <script src="<?php echo WP3DV_BASE_URL."/assets/js/admin/edit-object-custom.js" ; ?>"></script>
                </div>                 
            </div>               
<?php 
    }
    
    public static function titleTranslations(){
        global $post;
        $post_id = $post->ID;
        if (strcmp($post->post_type, "wp3dv-object") !== 0) return ;
?> 
        <div class="row">
            <?php foreach (WP3DV_Plugin::getLanguages() as $language) { ?>
                <?php $title_meta_key = "wp3dv_title".WP3DV_Plugin::getTranslationKeySuffix($language["code"]); ?>
                <?php $title_tr = get_post_meta($post_id, $title_meta_key, true);; ?>
                <div class="col-md-12 form-group">
                    <label class="col-md-12">Object's <?php echo $language["name"]; ?> Title</label>
                    <input type="text" name="wp3dv-title_<?php echo $language["code"]; ?>" size="30"  class="form-control col-md-12" value="<?php echo $title_tr; ?>" id="title" spellcheck="true" autocomplete="off">
                </div>
            <?php } ?>
        </div>     
<?php
    }
    
    
    /// code inspired from this link: https://gist.github.com/ms-studio/fc21fd5720f5bbdfaddc
    public static function addObjectCategoryFormField(){
?> 
        <div class="row wp3dv-category-name-translations-container">
            <?php foreach (WP3DV_Plugin::getLanguages() as $language) { ?>
                <?php $name_meta_key = "wp3dv_name".WP3DV_Plugin::getTranslationKeySuffix($language["code"]); ?>
                <?php $name_tr = get_term_meta($term_id, $name_meta_key, true); ?>
                <div class="form-field col-md-1">&nbsp;</div>
                <div class="form-field col-md-11">
                    <label class="col-md-12" for="wp3dv-name_<?php echo $language["code"]; ?>_input">
                        Category's <?php echo $language["name"]; ?> Name
                    </label>
                    <input name="wp3dv-name_<?php echo $language["code"]; ?>" id="wp3dv-name_<?php echo $language["code"]; ?>_input" type="text"  size="40" aria-required="true">
                </div>
            <?php } ?>
        </div>
        <script>
            jQuery(document).ready(function($){
                //alert("salut");
                var container = jQuery(".wp3dv-category-name-translations-container");
                var afterTarget = jQuery(".form-field").first();
                console.log(afterTarget)
                var containerClone = container.clone(true, true);
                console.log(containerClone);
                container.remove();
                containerClone.insertAfter(afterTarget);
            });
        </script>
        <br/>       
<?php        
    }
    
    public static function addObjectCategoryFormFieldOnEditPage($term){
        $term_id = $term->term_id;
?> 
        <?php foreach (WP3DV_Plugin::getLanguages() as $language) { ?>
            <?php $name_meta_key = "wp3dv_name".WP3DV_Plugin::getTranslationKeySuffix($language["code"]); ?>
            <?php $name_tr = get_term_meta($term_id, $name_meta_key, true); ?>

            <tr class="form-field form-required term-name-wrap wp3dv-category-name-translation-item">
                <th scope="row">
                    <label class="col-md-12" for="wp3dv-name_<?php echo $language["code"]; ?>_input">
                        Category's <?php echo $language["name"]; ?> Name
                    </label>
                </th>
                <td>
                    <input name="wp3dv-name_<?php echo $language["code"]; ?>" id="wp3dv-name_<?php echo $language["code"]; ?>_input" type="text" value="<?php echo $name_tr; ?>" size="40" aria-required="true">
                </td>
            </tr>
        <?php } ?>
        <script>
            jQuery(document).ready(function($){
                var nameTranslations = jQuery(".wp3dv-category-name-translation-item");
                var nameTranslationsClone = nameTranslations.clone(true, true);
                var nameTranslationsAfterTarget = jQuery(".form-field.form-required.term-name-wrap").first();
                nameTranslations.remove();
                nameTranslationsClone.insertAfter(nameTranslationsAfterTarget);
            });
        </script>
    <br/>       
<?php          
    }
    
    public static function saveObject($post_id){       
        if (array_key_exists('wp3dv-sketchfab-id', $_POST)) {
            update_post_meta(
                $post_id,
                'wp3dv_sketchfab_id',
                sanitize_text_field($_POST['wp3dv-sketchfab-id'])
            );
        }  

        if (array_key_exists('wp3dv-sketchfab-id-nbrand', $_POST)) {
            update_post_meta(
                $post_id,
                'wp3dv_sketchfab_id_nbrand',
                trim(sanitize_text_field($_POST['wp3dv-sketchfab-id-nbrand']))
            );
        }          

        if (array_key_exists('wp3dv-sketchfab-pass', $_POST)) {
            $pass = sanitize_text_field($_POST['wp3dv-sketchfab-pass']);
            self::updateSketchfabPassword($post_id, $pass);
        } else {
            self::updateSketchfabPassword($post_id, "");
        }
        
        if (array_key_exists('wp3dv-year', $_POST)) {
            update_post_meta(
                $post_id,
                'wp3dv_year',
                sanitize_text_field($_POST["wp3dv-year"])
            );
        }
        
        if (array_key_exists('wp3dv-asset-tag', $_POST)) {
            $tag = trim(sanitize_text_field($_POST["wp3dv-asset-tag"]));
            if (! self::assetTagExists($tag)){
                update_post_meta(
                    $post_id,
                    'wp3dv_asset_tag',
                    $tag
                );                
            }
        }
        
        if (array_key_exists('wp3dv-set-onload', $_POST)){
            self::setDefaultToLoad($post_id);
        } else {
            $default = get_option("wp3dv_default_object");
            if ($default == $post_id){
                delete_option("wp3dv_default_object");
            }
        }
        
        if (array_key_exists('wp3dv-sketchfab-thumb-url', $_POST)) {
            update_post_meta(
                $post_id,
                'wp3dv_sketchfab_thumb_url',
                sanitize_text_field($_POST["wp3dv-sketchfab-thumb-url"])
            );
        }         
        
        if (array_key_exists('wp3dv-description', $_POST)) {
            update_post_meta(
                $post_id,
                'wp3dv_description',
                wp_kses_post(sanitize_textarea_field(htmlspecialchars($_POST['wp3dv-description'])))
            );
        }
        
        foreach (WP3DV_Plugin::getLanguages() as $language){
            $title_request_key = "wp3dv-title_".$language["code"];
            $title_meta_key = "wp3dv_title".WP3DV_Plugin::getTranslationKeySuffix($language["code"]);
            $title_tr = (array_key_exists($title_request_key, $_POST)) ? sanitize_text_field($_POST[$title_request_key]) : null ;
            update_post_meta($post_id, $title_meta_key, $title_tr);
                    
            $description_request_key = 'wp3dv-description_'.$language["code"];
            $description_meta_key = "wp3dv_description".WP3DV_Plugin::getTranslationKeySuffix($language["code"]);
            $description_tr = (array_key_exists($description_request_key, $_POST)) ? $_POST[$description_request_key] : null ;
            update_post_meta(
                $post_id,
                $description_meta_key,
                wp_kses_post(sanitize_textarea_field(htmlspecialchars($description_tr)))
            );
        }
        
        if (array_key_exists('wp3dv-relobjects', $_POST)) {
            self::makeRelated($post_id, $_POST["wp3dv-relobjects"]);
        } else {
            self::makeRelated($post_id, Array());
        }
    }
    
    
    public static function saveCategory($term_id){
        foreach (WP3DV_Plugin::getLanguages() as $language){
            
            
            $name_request_key = "wp3dv-name_".$language["code"];
            $name_meta_key = "wp3dv_name".WP3DV_Plugin::getTranslationKeySuffix($language["code"]);
            $name_tr = (array_key_exists($name_request_key, $_POST)) ? sanitize_text_field($_POST[$name_request_key]) : null ;
            //echo "toututututut \n".$name_tr ; exit();
            update_term_meta($term_id, $name_meta_key, $name_tr);
        }
    }
    
    function singleTemplate( $template_path ) {
        if ( get_post_type() == 'wp3dv-object'){
            if ( is_single() ) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ( $theme_file = locate_template(array ('single-wp3dv-object.php'))) {
                    $template_path = $theme_file;
                } else {
                    $template_path = WP3DV_BASE_PATH. '/templates/objects/single.php';
                }
            }
        }
        return $template_path;
    }  
    
    public static function assetTagExists($tag){
        $res = false ;
        $query = new WP_Query( Array( 
            'post_type'     => 'wp3dv-object', 
            'meta_key'      => 'wp3dv_asset_tag', 
            'meta_value'    => $tag 
        ));
        if ($query->have_posts()){
            $query->the_post();
            $res = get_the_ID();
            wp_reset_postdata();
        }
        return $res ;
    }
    
    public static function setDefaultToLoad($post_id){
        update_option("wp3dv_default_object", $post_id);
    }
    
    public static function updateSketchfabPassword($post_id, $pass){
        $token = hash("sha256", $pass);
        update_post_meta($post_id, 'wp3dv_sketchfab_pass', $pass);
        if (! empty($pass)){
            update_post_meta($post_id, 'wp3dv_sketchfab_passtoken', $token);
        }
    }
    
    
    public static function makeRelated($post_id, $rel_ids){
        self::removeRelations($post_id);
        update_post_meta(
            $post_id,
            'wp3dv_relobjects',
            implode(":", $rel_ids)
        );        
        foreach($rel_ids as $id){
            $relobjects = explode(":", get_post_meta($id, 'wp3dv_relobjects', true));
            if (empty($relobjects)){
                $relobjects = Array();
            }
            if (! array_search($post_id, $relobjects)){
                array_push($relobjects, $post_id);
            }
            update_post_meta(
                $id,
                'wp3dv_relobjects',
                implode(":", $relobjects)
            );
        }
    }
    
    /*
     * This function removes the $post_id post from all objects it is 
     * related
     */
    public static function removeRelations($post_id){
        $relobjects = explode(":", get_post_meta($post_id, 'wp3dv_relobjects', true));
        update_post_meta(
            $post_id,
            'wp3dv_relobjects',
            ""
        );         
        foreach ($relobjects as $id){
            $rel_ids = explode(":", get_post_meta($id, 'wp3dv_relobjects', true));
            $key = array_search($post_id, $rel_ids);
            if ($key !== FALSE){
                array_splice($rel_ids, $key, 1);
            }
            update_post_meta(
                $id,
                'wp3dv_relobjects',
                implode(":", $rel_ids)
            );            
        }
    }
}
