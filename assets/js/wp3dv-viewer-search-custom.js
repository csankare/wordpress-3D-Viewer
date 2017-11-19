var wp3dv = {};

wp3dv.dump = wp3dv_dump ;
wp3dv.carouselDefaultItem = null ;
wp3dv.carouselItems = [] ;

wp3dv.isEmpty = function isEmpty(value){
  return (value == null || value.length === 0);
}

wp3dv.getExcerpt = function(text, size){
    return text.substring(0, size)+"...";
};

wp3dv.resizeFrame = function(frameSelector){
    jQuery(frameSelector).height((jQuery(frameSelector).width()*9)/16);
}

wp3dv.matchSearch = function(text, search){
    res = false ;
    var keys = search.trim().toUpperCase().split(/\s+/);
    var target = text.toUpperCase();
    var i;
    for (i = 0 ; i < keys.length; i++){
        if (target.search(keys[i]) >= 0){
            res = true ;
            break;
        }
    }
    return res ;    
};
wp3dv.inCategory = function(object, category_id){
    var i, res = false ;
    for (i = 0 ; i < object.categories.length; i++){
        if ( object.categories[i].term_id == parseInt(category_id)){
            res = true ;
            break;
        }
    }
    return res ;
};

wp3dv.readjustCarousel = function(){
    // for every slide in carousel, copy the next slide's item in the slide.
    // Do the same for the next, next item.
    jQuery('#wp3dv-object-caroussel-id .item').each(function(){
        var next = jQuery(this).next();
        if (!next.length) {
            //next = jQuery(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo(jQuery(this));

        if (next.next().length>0) {
            next.next().children(':first-child').clone().appendTo(jQuery(this));
        } else {
            //jQuery(this).siblings(':first').children(':first-child').clone().appendTo(jQuery(this));
        }
    });  
    if ( wp3dv.isEmpty(jQuery('#wp3dv-object-caroussel-id .active'))){
        jQuery('#wp3dv-object-caroussel-id .item').first().addClass("active");
    }
};

wp3dv.initSearchForm = function(objectSelectSelector, carouselSelector){
    //init categories 
    var i, current, carouselContent ;
    var activated = false ;
    if (!wp3dv.isEmpty(wp3dv.dump.defaultobject)){
        carouselContent = "";
        carouselContent += '<div class="item active">' ;
        carouselContent += '    <div class="col-md-4 col-xs-12 col-sm-12 wp3dv-carousel-item wp3dv-carousel-item-default">';
        carouselContent += '        <a href="javascript:wp3dv.selectCarousel(-1);">';
        carouselContent += '            <img src="'+wp3dv.dump.defaultobject.thumb_url+'" class="img img-thumbnail img-responsive" style="width: 256px; height: 144px;">';
        carouselContent += '        </a>';
        carouselContent += '    </div>';         
        carouselContent += '</div>';        
        jQuery(carouselSelector).append(carouselContent);
        wp3dv.carouselDefaultItem = carouselContent ;
        jQuery(carouselSelector).find(".wp3dv-carousel-item-default img").first().addClass("wp3dv-item-selected");
        activated = true ;
    }
    
    for (i = 0 ; i < wp3dv.dump.objects.length ; i++){   
        current = wp3dv.dump.objects[i] ;
        carouselContent = "";
        carouselContent += '<div class="item">' ;
        carouselContent += '    <div class="col-md-4 col-xs-12 col-sm-12 wp3dv-carousel-item wp3dv-carousel-item-'+i+'">';
        carouselContent += '        <a href="javascript:wp3dv.selectCarousel('+i+');">';
        carouselContent += '            <img src="'+current.thumb_url+'" class="img img-thumbnail img-responsive" style="width: 256px; height: 144px;">';
        carouselContent += '        </a>';       
        carouselContent += '    </div>';         
        carouselContent += '</div>';        
        jQuery(objectSelectSelector).append("<option value='"+i+"'>"+current.post_title+"</option>");
        jQuery(carouselSelector).append(carouselContent);
        wp3dv.carouselItems.push(carouselContent);
        if (! activated){
            jQuery(carouselSelector).find(".item").first().addClass("active");
            jQuery(carouselSelector).find(".wp3dv-carousel-item-"+i+" img").first().addClass("wp3dv-item-selected");
            activated = true ;
        }        
    }  
    wp3dv.readjustCarousel();
}

wp3dv.onSearch = function(searchSelector, categorySelector, yearSelector, objectSelector, carouselSelector){
    var i, current, carouselContent ;
    var search = jQuery(searchSelector).val();
    var category = jQuery(categorySelector).val();
    var year = jQuery(yearSelector).val();
    jQuery(carouselSelector).html("");
    //jQuery(carouselSelector).append(wp3dv.carouselDefaultItem);
    
    for (i = 0 ; i < wp3dv.dump.objects.length ; i++){
        current = wp3dv.dump.objects[i];
        var show = true ;
        if (! wp3dv.isEmpty(year) && current.wp3dv_year != year){
            show = false ;
        }
        
        if (! wp3dv.isEmpty(category) && (! wp3dv.inCategory(current, category))){
            show = false;
        }
        
        if (! wp3dv.isEmpty(search) && (! wp3dv.matchSearch(current.post_title, search))){
            show = false;
        }   
        
        if (show){
            jQuery(objectSelector+" option[value='"+i+"']").show();
            jQuery(carouselSelector).append(wp3dv.carouselItems[i]);
            console.log(wp3dv.carouselItems[i]);
        } else {
            jQuery(objectSelector+" option[value='"+i+"']").hide();
        }
    }   
    wp3dv.readjustCarousel();
}

wp3dv.fillFrame = function(brandid, nbrandid, pass, brand){
    var version = '1.0.0';
    var iframe = document.getElementById("wp3dv-object-searchview-iframe-id");
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
        fps_speed: 25,
        preload: 0,
        scrollwheel: 1,
        transparent: 0,
        password: pass,
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

wp3dv.showObject = function(object, rootContainer){
    var urlid        =  object.wp3dv_sketchfab_id ;
    var urlid_nbrand =  object.wp3dv_sketchfab_id_nbrand ;
    if (wp3dv.isEmpty(urlid_nbrand)){
        urlid_nbrand = urlid ;
        jQuery(".wp3dv-object-brandbox").hide();
    } else {
        jQuery(".wp3dv-object-brandbox").show();
    }   
    console.log("Ceci est un test");
    
    jQuery(rootContainer+" .wp3dv-object-sharebox").html('<a class="pull-left btn btn-primary glyphicon glyphicon-share" style="background-color: inherit; font-weight: bolder; color: #FFF; border-color: #FFF; border-width: 3px; border-radius: 3px; border-style: solid;" href="javascript:void(0);" data-clipboard-text="'+object.permalink+'" id="wp3dv-sharebox-copylink"  title="SHARE THE LINK OF THIS OBJECT">&nbsp;SHARE OBJECT</a>');
    jQuery(rootContainer+" .wp3dv-object-sharebox").show();
    
    if (! wp3dv.isEmpty(object.wp3dv_description)){
        jQuery(rootContainer+" .wp3dv-object-description").html(object.wp3dv_description);
    } else {
        jQuery(rootContainer+" .wp3dv-object-description").html("NO AVAILABLE DESCRIPTION FOR THIS OBJECT.");
    }

    if (! wp3dv.isEmpty(object.relobjects)){
        jQuery(rootContainer+" .wp3dv-object-relatedbox").html("");
        var content;
        for (i = 0 ; i < object.relobjects.length; i++){
            current = object.relobjects[i];
            content = "" ;
            content += '<div class="col-md-3">';
            content +=      '<div class="thumbnail">'
            content +=          '<a href="'+current.permalink+'">'
            content +=              '<img src="'+current.thumb_url+'" alt="'+current.post_title+'" style="width:256px; height: 144px;" border-color: blue;>'
            content +=              '<div class="caption">'
            content +=                  '<h4>'+current.post_title+'</h4>'
            content +=              '</div>'
            content +=          '</a>'
            content +=      '</div>'                              
            content += '</div>'
            jQuery(rootContainer+" .wp3dv-object-relatedbox").append(content);
        }        
        
    } else {
        jQuery(rootContainer+" .wp3dv-object-relatedbox").html("NO RELATED OBJECT.");
    }
    
    jQuery(rootContainer+" .wp3dv-object-description-header").html("DETAILS");
    jQuery(rootContainer+" .wp3dv-object-description-header").show();
    jQuery(rootContainer+" .wp3dv-object-description").show(); 
    
    jQuery(rootContainer+" .wp3dv-object-relatedbox-header").html("RELATED OBJECTS");
    jQuery(rootContainer+" .wp3dv-object-relatedbox-header").show();
    jQuery(rootContainer+" .wp3dv-object-relatedbox").show();  
    
    var brand = false ;
    var brandcheckbox = jQuery(rootContainer+" input[name='wp3dv-branded']");
    if (brandcheckbox.size() != 0 && brandcheckbox.is(":checked")){
        brand = true ;
    }  
    wp3dv.fillFrame(urlid, urlid_nbrand, object.wp3dv_sketchfab_passtoken, brand);
}

wp3dv.selectCarousel = function(index){
    var object = wp3dv.dump.defaultobject;
    jQuery(".wp3dv-item-selected").removeClass("wp3dv-item-selected");
    if (index >= 0){
        object = wp3dv.dump.objects[parseInt(index)] ;
        jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-carousel-item-"+index+" img").addClass("wp3dv-item-selected");
    } else {
        jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-carousel-item-default img").addClass("wp3dv-item-selected");
    }
    wp3dv.showObject(object, "#wp3dv-object-viewer-search-container-id");
};

window.onload = function(){
    // starting workers
    console.log(wp3dv.dump);
    
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-brandbox").hide();
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-sharebox").hide();
    
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-description-header").hide();
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-description").hide();
    
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-relatedbox-header").hide();
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-relatedbox").hide();
    
    wp3dv.initSearchForm("#wp3dv-object-viewer-search-container-id .wp3dv-object-object-select",
        "#wp3dv-object-caroussel-id .carousel-inner");
        
    jQuery(document).resize(function(){
        wp3dv.resizeFrame("#wp3dv-object-viewer-search-container-id #wp3dv-object-searchview-iframe-id");
    });
    jQuery(document).resize();
    
    var clipboard = new Clipboard('#wp3dv-sharebox-copylink');
    clipboard.on('success', function(e) {
        alert("The link to this Object has been copied to your clipboard.");
        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        alert("Failed copying object's link.");
    });    
    
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-year-select, #wp3dv-object-viewer-search-container-id .wp3dv-object-category-select").change(function(){
        wp3dv.onSearch(
            "#wp3dv-object-viewer-search-container-id .wp3dv-object-search-input", 
            "#wp3dv-object-viewer-search-container-id .wp3dv-object-category-select", 
            "#wp3dv-object-viewer-search-container-id .wp3dv-object-year-select", 
            "#wp3dv-object-viewer-search-container-id .wp3dv-object-object-select",
            "#wp3dv-object-caroussel-id .carousel-inner"
        );
    });
    
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-search-input").on('input',function(){
        wp3dv.onSearch(
            "#wp3dv-object-viewer-search-container-id .wp3dv-object-search-input", 
            "#wp3dv-object-viewer-search-container-id .wp3dv-object-category-select", 
            "#wp3dv-object-viewer-search-container-id .wp3dv-object-year-select", 
            "#wp3dv-object-viewer-search-container-id .wp3dv-object-object-select",
            "#wp3dv-object-caroussel-id .carousel-inner"
        );        
    });
    
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-object-select, #wp3dv-object-viewer-search-container-id .wp3dv-object-brandbox input[name='wp3dv-branded']").change(function(){
        var objectid = jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-object-select").val();
        if (! wp3dv.isEmpty(objectid)){
            objectid = parseInt(objectid);
            wp3dv.showObject(wp3dv.dump.objects[objectid], "#wp3dv-object-viewer-search-container-id");
        } else {
            if (! wp3dv.isEmpty(wp3dv.dump.defaultobject)){
                wp3dv.showObject(wp3dv.dump.defaultobject, "#wp3dv-object-viewer-search-container-id");
            }
        }
    });
    jQuery("#wp3dv-object-viewer-search-container-id .wp3dv-object-object-select, #wp3dv-object-viewer-search-container-id .wp3dv-object-brandbox input[name='wp3dv-branded']").change();
};