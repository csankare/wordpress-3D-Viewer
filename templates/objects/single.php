<?php get_header(); ?>

<div class="row">&nbsp;</div>
<div class="container wp3dv-container">
    <?php while (have_posts()) : the_post(); ?>
        <?php 
            global $post ;
            $curr_post = clone $post ;
            $curr_post->wp3dv_description = get_post_meta($curr_post->ID, "wp3dv_description", true);
            $curr_post->wp3dv_year = get_post_meta($curr_post->ID, "wp3dv_year", true); 
            $curr_post->wp3dv_sketchfab_id = get_post_meta($curr_post->ID, "wp3dv_sketchfab_id", true);
            $curr_post->wp3dv_sketchfab_id_nbrand = get_post_meta($curr_post->ID, "wp3dv_sketchfab_id_nbrand", true);
            $curr_post->wp3dv_sketchfab_thumb_url = get_post_meta($curr_post->ID, 'wp3dv_sketchfab_thumb_url', true);
            $curr_post->wp3dv_sketchfab_passtoken = get_post_meta($curr_post->ID, 'wp3dv_sketchfab_passtoken', true);  
            $curr_post->taxonomies = wp_get_post_terms($curr_post->ID, "wp3dv-category", array()); 
            $statuses = Array('publish');
            if (is_user_logged_in()) array_push ($statuses, "private");
            // loading related objects' native data
            $relobjects = get_posts(Array(
                'posts_per_page'   => -1,
                'include'          => explode(":", get_post_meta($curr_post->ID, 'wp3dv_relobjects', true)),
                'post_type'        => 'wp3dv-object',
                'post_status'      => $statuses
            ));
            
            // loading related objects's custom data
            /*foreach ($relobjects as $relobject){
                $relobject->permalink = get_the_permalink($relobject->ID);
                $relobject->thumnailUrl = (has_post_thumbnail($relobject->ID)) ? get_the_post_thumbnail_url($relobject->ID) : WP3DV_BASE_URL."/assets/img/default-thumb.jpg" ;
            } */

            $curr_post->relobjects = $relobjects ;
            $post_json = wp_json_encode($curr_post);
        ?>
        <div class="row wp3dv-singleobject-container" id="wp3dv-singleobject-container-id">            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <center><h2 class="wp3dv-object-title"><?php echo $curr_post->post_title; ?></h2></center>
                </div>
                <div class="panel-body">
                    <!-- SHOWING THE OBJECT AND ITS DETAILS -->
                    <iframe id="wp3dv-object-iframe-id" style="width: 100%;"></iframe>
<?php 
                    if (! empty($curr_post->wp3dv_sketchfab_id_nbrand)){
?>
                        <div class="col-md-3 pull-right checkbox-inline wp3dv-object-brandbox">  
                            
                            <label class="pull-right btn btn-default checkbox-title" style="background-color: #B59D7A; ">     
                                <input type="checkbox" class="checkbox" name="wp3dv-branded" style=""/>
                                <i class="ionicons ion-no-smoking" style="font-size: 50px; color: black; font-weight: bolder;"></i>
                            </label>                          
                        </div>                        
<?php                     
                    }
?>

                </div>
                <div class="panel-footer" style="background-color: #FFF;">
                    <center class="col- md-12"><h3>DETAILS</h3>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               </h3></center>
                    <div class="row">
                        <center class="col-md-12 wp3dv-object-description"><?php echo $curr_post->wp3dv_description; ?></center>
                    </div>
                    <hr/>
                    <div class="row">
                        <center class="col-md-12" style="margin-bottom: 10px;"><h3>RELATED OJBECTS</h3></center>
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-12 wp3dv-object-relatedbox">
<?php 
                        foreach ($relobjects as $relobject){
                            
                            $permalink = get_the_permalink($relobject->ID);
                            $thumb_url = get_post_meta($relobject->ID, 'wp3dv_sketchfab_thumb_url', true);
                            if (!$thumb_url){
                                $thumb_url = (has_post_thumbnail($relobject->ID)) ? get_the_post_thumbnail_url($relobject->ID) : WP3DV_BASE_URL."/assets/img/default-thumb.jpg" ;
                            }//
?>
                            <div class="col-md-3">
                                <div class="thumbnail">
                                    <a href="<?php echo $permalink; ?>">
                                        <img src="<?php echo $thumb_url ; ?>" alt="<?php echo $relobject->post_title; ?>" style="width:256px; height: 144px;">
                                        <div class="caption">
                                            <h4><?php echo $relobject->post_title; ?></h4>
                                        </div>
                                    </a>
                                </div>                                
                            </div>
<?php                              
                        }    
?> 
                        </div>                    
                </div>
            </div>            
        </div>       
    <?php endwhile; ?>    
</div>
<script>
    var wp3dv_object = JSON.parse('<?php echo stripslashes($post_json) ; ?>');
</script>
<script src="https://static.sketchfab.com/api/sketchfab-viewer-1.0.0.js"></script> 
<script src="<?php echo WP3DV_BASE_URL."/assets/js/wp3dv-custom.js" ; ?>"></script>
<style>
    .wp3dv-container .wp3dv-object-title {
        font-weight: bolder ;
    }
    
    .wp3dv-container .wp3dv-singleobject-container {
    }
    
    .wp3dv-container .wp3dv-singleobject-container .wp3dv-object-details {
    }
    
    .wp3dv-container .wp3dv-singleobject-container .wp3dv-sketchfab-container {
    } 
    
    .wp3dv-container .wp3dv-singleobject-container .wp3dv-sketchfab-container .wp3dv-sketchfab-iframe {
        width: 100%;
        height: 500px;
        border-width: 0px;
    }
</style>
<?php get_footer(); ?>
