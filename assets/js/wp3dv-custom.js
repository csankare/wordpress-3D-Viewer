function wp3dv_fillObjectContainer(brandid, nbrandid, pass, brand){
    var version = '1.0.0';
    var iframe = document.getElementById("wp3dv-object-iframe-id");
    var urlid = (brand) ? brandid : nbrandid;  
    console.log(urlid);
    var client = new Sketchfab(version, iframe);
    client.init( urlid, {
        success: function onSuccess( api ){
            api.start();
            api.addEventListener( 'viewerready', function(){
                // API is ready to use
                // Insert your code here
                //console.log( 'Viewer is ready' );
                //api.stop();

            } );
        },
        error: function onError() {
            console.log( 'Viewer error' );
        },
        annotation: "off",
        annotation_cycle: "off",
        cardboard: "off",
        navigation: "orbit",
        camera: 1,
        password: pass,
        fps_speed: 25,
        preload: 0,
        scrollwheel: 1,
        transparent: 0,
        annotations_visible: 0,
        autospin: 0,
        autostart: 1,
        ui_controls: 0,
        ui_general_controls: 0,
        ui_settings: 0,
        ui_help: 0,
        ui_vr: 0,
        ui_fullscreen: 0,
        ui_animations: 0,
        ui_annotations: 0,
        ui_infos: 0
    });        
}

function wp3dv_reloadObject(){
    var brand = true ;
    var brandcheckbox = jQuery("#wp3dv-singleobject-container-id input[name='wp3dv-branded']");
    if (brandcheckbox.size() != 0 && brandcheckbox.is(":checked")){
        brand = false ;
    }
    wp3dv_fillObjectContainer(wp3dv_object.wp3dv_sketchfab_id, wp3dv_object.wp3dv_sketchfab_id_nbrand, wp3dv_object.wp3dv_sketchfab_passtoken, brand);
}

window.onload = function(){
    //console.log(wp3dv_object);
    jQuery("#wp3dv-object-iframe-id").height((jQuery("#wp3dv-object-iframe-id").width()*9)/16);
    wp3dv_reloadObject(); 
    jQuery("#wp3dv-singleobject-container-id input[name='wp3dv-branded']").change(function(){ 
        wp3dv_reloadObject();
    });  
     
    jQuery(window).resize(function(){
        jQuery("#wp3dv-object-iframe-id").height((jQuery("#wp3dv-object-iframe-id").width()*9)/16);
    });
};
