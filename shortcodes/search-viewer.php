<?php
/* Class of the viewer with search functionnality
 * shortcode will be include by typing [wp3dv-search-viewer]
 */
class WP3DV_SearchViewer{
    public static function init(){
        
    }
    
    public static function show(){
        $content = "" ;
        $statuses = Array("publish");
        if (is_user_logged_in()){
            array_push($statuses, "private");
        }
        
        $categories = get_terms(Array(
            'taxonomy' => 'wp3dv-category',
            'hide_empty' => true
        )); 
        
        $default_to_load = get_option("wp3dv_default_object");
        $defaultobject = get_posts(Array(
            'posts_per_page'   => -1,
            'include'          => Array($default_to_load),
            'post_type'        => 'wp3dv-object',
            'post_status'      => $status
        ));
        $curr_post = null ;
        foreach ($defaultobject as $obj){
            $curr_post = clone $obj ;
            $curr_post->wp3dv_description = get_post_meta($curr_post->ID, "wp3dv_description", true);
            $curr_post->wp3dv_year = get_post_meta($curr_post->ID, "wp3dv_year", true); 
            $curr_post->wp3dv_sketchfab_id = get_post_meta($curr_post->ID, "wp3dv_sketchfab_id", true);
            $curr_post->wp3dv_sketchfab_id_nbrand = get_post_meta($curr_post->ID, "wp3dv_sketchfab_id_nbrand", true);
            $curr_post->wp3dv_sketchfab_thumb_url = get_post_meta($curr_post->ID, 'wp3dv_sketchfab_thumb_url', true);
            $curr_post->thumb_url = $curr_post->wp3dv_sketchfab_thumb_url ;
            if (! $curr_post->thumb_url){
                $curr_post->thumb_url = (has_post_thumbnail($curr_post->ID)) ? 
                get_the_post_thumbnail_url($curr_post->ID) : WP3DV_BASE_URL."/assets/img/default-thumb.jpg" ;
            }            
            
            $curr_post->wp3dv_sketchfab_passtoken = get_post_meta($curr_post->ID, 'wp3dv_sketchfab_passtoken', true);  
            $curr_post->taxonomies = wp_get_post_terms($curr_post->ID, "wp3dv-category", array()); 
            $statuses = Array('publish');
            if (is_user_logged_in()) array_push ($statuses, "private");
            // loading related objects' native data
            $curr_post->relobjects = get_posts(Array(
                'posts_per_page'   => -1,
                'include'          => explode(":", get_post_meta($curr_post->ID, 'wp3dv_relobjects', true)),
                'post_type'        => 'wp3dv-object',
                'post_status'      => $statuses
            ));
            
            // loading related objects's custom data
            foreach ($curr_post->relobjects as $relobject){
                $relobject->permalink = get_the_permalink($relobject->ID);
                $relobject->wp3dv_sketchfab_thumb_url = get_post_meta($relobject->ID, 'wp3dv_sketchfab_thumb_url', true);
                $relobject->thumb_url = $relobject->wp3dv_sketchfab_thumb_url ;
                if (! $relobject->thumb_url){
                    $relobject->thumb_url = (has_post_thumbnail($relobject->ID)) ? 
                    get_the_post_thumbnail_url($relobject->ID) : WP3DV_BASE_URL."/assets/img/default-thumb.jpg" ;
                }                
            }          
            break ;
        }
        $defaultobject = $curr_post ;
        
        $objects = get_posts(Array(
            'posts_per_page'    => -1,
            'post_type'         => 'wp3dv-object',
            'orderby'           => 'title',
            'order'             => 'ASC',
            'post_status'       => $statuses
        ));
        
        foreach ($objects as $object){
            $object->wp3dv_description = get_post_meta($object->ID, "wp3dv_description", true);
            $object->wp3dv_year = get_post_meta($object->ID, "wp3dv_year", true); 
            $object->wp3dv_sketchfab_id = get_post_meta($object->ID, "wp3dv_sketchfab_id", true);
            $object->wp3dv_sketchfab_id_nbrand = get_post_meta($object->ID, "wp3dv_sketchfab_id_nbrand", true);
            $object->wp3dv_sketchfab_thumb_url = get_post_meta($object->ID, 'wp3dv_sketchfab_thumb_url', true);
            $object->thumb_url = $object->wp3dv_sketchfab_thumb_url ;
            if (! $object->thumb_url){
                $object->thumb_url = (has_post_thumbnail($object->ID)) ? 
                get_the_post_thumbnail_url($object->ID) : WP3DV_BASE_URL."/assets/img/default-thumb.jpg" ;
            }
            $object->wp3dv_sketchfab_passtoken = get_post_meta($object->ID, 'wp3dv_sketchfab_passtoken', true);  
            $object->categories = wp_get_post_terms($object->ID, "wp3dv-category", array()); 
            $object->permalink = get_the_permalink($object->ID);
            $status = Array('publish');
            if (is_user_logged_in()) {
                array_push ($status, "private");            
            }
            $object->relobjects = get_posts(Array(
                'posts_per_page'   => -1,
                'include'          => explode(":", get_post_meta($object->ID, 'wp3dv_relobjects', true)),
                'post_type'        => 'wp3dv-object',
                'post_status'      => $status
            ));
            foreach ($object->relobjects as $relobject){
                $relobject->permalink = get_the_permalink($relobject->ID);
                $relobject->wp3dv_sketchfab_id = get_post_meta($relobject->ID, "wp3dv_sketchfab_id", true);
                $relobject->wp3dv_sketchfab_id_nbrand = get_post_meta($relobject->ID, "wp3dv_sketchfab_id_nbrand", true);
                $relobject->wp3dv_sketchfab_thumb_url = get_post_meta($relobject->ID, 'wp3dv_sketchfab_thumb_url', true);
                $relobject->thumb_url = $relobject->wp3dv_sketchfab_thumb_url ;
                if (! $relobject->thumb_url){
                    $relobject->thumb_url = (has_post_thumbnail($relobject->ID)) ? 
                    get_the_post_thumbnail_url($relobject->ID) : WP3DV_BASE_URL."/assets/img/default-thumb.jpg" ;
                }
            }
        }
        $dumpObject = new stdClass();
        $dumpObject->defaultobject = $defaultobject;
        $dumpObject->categories = $categories ;
        $dumpObject->objects = $objects ;
        ob_start();
        // here the code 
?>
        <div class="wp3dv-object-viewer-search-container" id="wp3dv-object-viewer-search-container-id">   
            <div class="panel panel-default"> 
                <div class="panel-body" style="background-color: #B59D7A;"> 
                    <div class="row col-md-12"> 
                        <div class="form-group col-md-5" style="color: #FFF; font-size: 13px;"> 
                            <div class="col-md-12"> 
                                <center class="form-group" style="color: #FFF; font-size: 13px;">
                                    <label class="col-md-12">SELECT AN OBJECT:</label>  
                                </center>                                 
                            </div> 
                            <div class="col-md-12"> 
                                <select class="form-control wp3dv-object-object-select"> 
                                    <option array-key="" value="">Object</option>
                                </select>                                 
                            </div>                                                              
                        </div>
                        
                        <div class="form-group col-md-7" style="color: #FFF; font-size: 13px;"> 
                            <div class="col-md-12"> 
                                <center class="form-group" style="color: #FFF; font-size: 13px;">
                                    <label class="col-md-12">FIND OBJECTS BY:</label>   
                                </center>                                  
                            </div> 
                            <div class="row form-inline"> 
                                <div class="form-group col-md-2" style="color: #000; font-size: 13px;"> 
                                    <select class="form-control wp3dv-object-year-select"> 
                                        <option array-key="" value="">Year</option>
                                        <option value="1969">1969</option>
    <?php   
                                        $i = 0 ;
                                        for ($i = 1983 ; $i <= date("Y") ; $i++){
    ?>            
                                            <option value="<?php echo $i ; ?>"><?php echo $i ; ?></option>
    <?php            
                                        }
    ?>                                     
                                    </select> 
                                </div>
                                <div class="form-group col-md-4" style="color: #000; font-size: 13px;"> 
                                    <select class="form-control wp3dv-object-category-select" style="width:100%;"> 
                                        <option array-key="" value="">Category</option>
<?php 
                                    foreach ($dumpObject->categories as $category){
?> 
                                        <option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
<?php                                        
                                    }
?>                                         
                                    </select> 
                                </div> 
                                <div class="form-group col-md-5"> 
                                    <div class="input-group">
                                        <input type="text" class="form-control wp3dv-object-search-input" id="inlineFormInputGroup" placeholder="Search..." style="font-weight: bolder; font-size: 13px; background-color: #FFF; color: #B59D7A;">
                                        <div class="input-group-addon" style="background-color: #FFF;"><i class="glyphicon glyphicon-circle-arrow-down"></i></div>
                                    </div>
                                </div> 
                                <div class="col-md-1 pull-right checkbox-inline wp3dv-object-brandbox">                              
                                    <label class="pull-right btn btn-default form-inline checkbox-title" style="background-color: #B59D7A; ">     
                                        &nbsp;
                                        <input type="checkbox" class="checkbox" name="wp3dv-branded" style="margin-top: 10px;"/>
                                        <i class="ionicons ion-no-smoking" style="font-size: 35px; color: black; font-weight: bolder;"></i>
                                    </label>                          
                                </div>                                
                            </div>                                                              
                        </div>
                    </div>                          
                </div>
                <div class="panel-body wp3dv-object-caroussel-root"> 
                    <div class="row">
                        <a class="left col-md-1 col-xs-2" href="#wp3dv-object-caroussel-id" data-slide="prev">
                            <img src="<?php echo WP3DV_BASE_URL."/assets/img/chevron-left.png"; ?>" class="img img-responsive" style="vertical-align: middle;">
                        </a>
                        <div class="col-md-10 col-xs-8">
                            <div class="carousel slide" data-ride="carousel"  data-wrap="false" data-interval="false" id="wp3dv-object-caroussel-id">
                                <div class="carousel-inner" style="height: 144px; overflow: hidden;">                                 
                                </div>
                            </div>
                        </div>
                        <a class="right col-md-1 col-xs-2" href="#wp3dv-object-caroussel-id" data-slide="next">
                            <img src="<?php echo WP3DV_BASE_URL."/assets/img/chevron-right.png"; ?>" class="img img-responsive" style="vertical-align: middle;">
                        </a>
                    </div>   
                </div>
                <div class="panel-body">   
                    <iframe id="wp3dv-object-searchview-iframe-id" style="width: 100%;" style="border-width: 0px;">NO OBJECT SELECTED YET</iframe>
                    <div>&nbsp;</div> 
                </div>
                <div class="panel-body" style="background-color: #B59D7A; ">   
                    <div class="col-md-3 pull-left wp3dv-object-sharebox" style="font-size: 13px;  font-weight: bolder; font-family: sans-serif;">     
                    </div>  
                </div>                
                <div class="panel-footer " style="background-color: #FFF;">    
                    <center class="col- md-12"><h3 class="wp3dv-object-description-header"></h3>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               </h3></center>
                    <div class="row">
                        <center class="col-md-12 wp3dv-object-description"></center>
                        <div class="col-md-12">&nbsp;</div>
                    </div>
                    <hr/>
                    <center class="col-md-12" style="margin-bottom: 10px;"><h3 class="wp3dv-object-relatedbox-header"></h3>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               </h3></center>
                    <div class="col-md-12">&nbsp;</div>
                    <div class="row">
                        <center class="col-md-12 wp3dv-object-relatedbox"></center>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var wp3dv_dump = JSON.parse('<?php echo stripslashes(wp_json_encode($dumpObject));  ?>');
        </script>
        <style src="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"></style>
        <style>
            .wp3dv-item-selected {
                background: #B59D7A ;
            }
        </style>
        <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js"></script>
        <script src="https://static.sketchfab.com/api/sketchfab-viewer-1.0.0.js"></script> 
        <script src="<?php echo WP3DV_BASE_URL."/assets/js/jquery-silvertrack/src/jquery.silver_track.js" ; ?>"></script>
        <script src="<?php echo WP3DV_BASE_URL."/assets/js/jquery-silvertrack/src/plugins/jquery.silver_track.navigator.js" ; ?>"></script>
        <script src="<?php echo WP3DV_BASE_URL."/assets/js/wp3dv-viewer-search-custom.js" ; ?>"></script>
<?php                  
        $content .= ob_get_clean();
        return $content ;
    }
    
    public static function handle(){
        
    }
}

