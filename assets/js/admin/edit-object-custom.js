var wp3dv = {};
wp3dv.isEmpty = function isEmpty(value){
  return (value == null || value.length === 0);
}

wp3dv.getSketchfabThumb = function(id, width){
    var res = null ;
    jQuery.ajax({
        dataType: "json",
        url: "https://api.sketchfab.com/v3/models/"+id,
        async: false, 
        success: function(results) {
            var i, images = results.thumbnails.images ;
            for (i = 0 ; i < images.length ; i++){
                if (width == images[i].width){
                    res = images[i].url ;
                    break;
                }
            }            
        }
    })
    if (wp3dv.isEmpty(res)){
        alert("Unable To load Thumbnail from Sketchfab.\nSet object image field or Default thumbnail will be used.");
    }
    return res ;
}

window.onload = function(){
    jQuery(".wp3dv-relobjects-table").tablesorter();
    var oTable =jQuery(".wp3dv-relobjects-table").DataTable({
        "scrollY":        '50vh',
        "scrollCollapse": true,
        //"iDisplayLength": 100,
        "paging":         false
    });
    
    jQuery(".dataTables_filter label").addClass("pull-right");       
    
    jQuery("#wp3dv-edit-object-metabox .wp3dv-load-sketchfab-thumb-button").click(function(){
        var modelid = jQuery("#wp3dv-edit-object-metabox input[name='wp3dv-sketchfab-id']").val();
        var width = 720;
        if (wp3dv.isEmpty(modelid)){
            // model id field has not been set
            alert("Set Sketchfab ID field First !!");
        } else {
            // model id field has been set
            var url = wp3dv.getSketchfabThumb(modelid, width);
            //console.log(url);
            jQuery("#wp3dv-edit-object-metabox .wp3dv-load-sketchfab-thumb-image").attr("src", url);
            jQuery("#wp3dv-edit-object-metabox .wp3dv-load-sketchfab-thumb-image").css("width", width);
            jQuery("#wp3dv-edit-object-metabox .wp3dv-load-sketchfab-thumb-input").val(url);
        }
    });
};