jQuery(document).ready( function($) {
    view_list();
    //jQuery('#myTable').DataTable();
    
    jQuery('#wpfooter').hide();
    jQuery('input#title').focusout(function(e){
        if(jQuery('input#title').val() == ""){
         jQuery('input#title').css("border-color","red");
        }else{
          jQuery('input#title').css("border-color","black");
        }
    });

    jQuery('#discription').focusout(function(e){
      if(jQuery('#discription').val() == ""){
        jQuery('#discription').css("border-color","red");
      }
      else{
        jQuery('#discription').css("border-color","black");
      }
    });

    jQuery('input[type="checkbox"]').focusout(function(){
      if($(this).prop("checked") == true){
        jQuery('input[type="checkbox"]').css("border-color","black");
      }
      else if($(this).prop("checked") == false){
        jQuery('input[type="checkbox"]').css("border-color","red");
      }
    });

    jQuery('#myprefix-preview-image').change(function(e){
      jQuery('#myprefix_media_manager').css("background-color","#0069d9");
    });

    jQuery('input#myprefix_media_manager').click(function(e) {     
           e.preventDefault();
           var image_frame;
           if(image_frame){
               image_frame.open();
           }
           // Define image_frame as wp.media object
           image_frame = wp.media({
                         title: 'Select a Media',
                         multiple : false,
                         library : {
                              type : 'image',
                          }
                     });

                     image_frame.on('close',function() {
                         
                        // On close, get selections and save to the hidden input
                        // plus other AJAX stuff to refresh the image preview
                        var selection =  image_frame.state().get('selection');
                        var gallery_ids = new Array();
                        var my_index = 0;
                        selection.each(function(attachment) {
                           gallery_ids[my_index] = attachment['id'];
                           my_index++;
                        });
                        
                        var ids = gallery_ids.join(",");
                        jQuery('input#myprefix_image_id').val(ids);
                        Refresh_Image(ids);
                     });

                    image_frame.on('open',function() {
                      // On open, get the id from the hidden input
                      // and select the appropiate images in the media manager
                      var selection =  image_frame.state().get('selection');
                      var ids = jQuery('input#myprefix_image_id').val().split(',');
                      ids.forEach(function(id) {
                        var attachment = wp.media.attachment(id);
                        attachment.fetch();
                        selection.add( attachment ? [ attachment ] : [] );
                      });

                    });
                  image_frame.open();
   });

   

   jQuery('button#save').click(function(e) {
     var data = {
       action: 'myprefix_save_information',
       title: jQuery('#title').val(),
       discription : jQuery('#discription').val(),
       gender:jQuery("input[name='gender']:checked").val(),
       sport: getSports().toString(),
       status: jQuery("#status").val(),
       image_id: jQuery("#myprefix_image_id").val(),
       position: 0
     };
     if(validate_field()){
        jQuery.get(ajaxurl, data, function(response) {
              if(response.success === true) {
                alert("data saved successfully");
                view_list();
              }
              else{
                alert("failed to save data");
              }
          });
     }else{
       alert("There are some fields are empty, please fill it");
     }
   });
});


function validate_field(){
  if((jQuery('input#title').val() == "") || (jQuery('#discription').val() == "") || (jQuery('#myprefix_image_id').val()=="") ){
    if(jQuery('input#title').val() == ""){
      jQuery('input#title').css("border-color","red");
     }else{
       jQuery('input#title').css("border-color","black");
     }
     if(jQuery('#discription').val() == ""){
      jQuery('#discription').css("border-color","red");
      }
    else{
      jQuery('#discription').css("border-color","black");
    }
    if(jQuery('input[type="checkbox"]').prop("checked") == true){
      jQuery('input[type="checkbox"]').css("border-color","black");
    }
    else if(jQuery('input[type="checkbox"]').prop("checked")== false){
      jQuery('input[type="checkbox"]').css("border-color","red");
    }
    if(jQuery('#myprefix_image_id').val()==""){
      jQuery('#myprefix_media_manager').css("background-color","red");
    }else{
      jQuery('#myprefix_media_manager').css("background-color","#0069d9");
    }
    return false;
  }else{
    return true;
  }
}


function view_list(){
  var data = {
    action: "myprefix_view_information",
    id: "1"
  }
  
  jQuery.get(ajaxurl,data,function(response){
    //alert(response.data);
    //alert(response.data.num_records);
    //alert(response.data.list);
    jQuery('#view_list').html(response.data.list);
    jQuery('#records_num').html(response.data.num_records);
    getTableList();
  });
}

function getSports(){
    var sports = [];
    $.each($("input[name='sport']:checked"), function(){
        sports.push($(this).val());
    });
    return sports;
}
// Ajax request to refresh the image preview
function Refresh_Image(the_id){   
      var data = {
          action: 'myprefix_get_image',
          id: the_id
      };
      jQuery.get(ajaxurl, data, function(response) {
          if(response.success === true) {
              jQuery('#myprefix-preview-image').replaceWith( response.data.image );
          }
      });
}

function changeStatus(id){
  //tag = '#status_'+id;
  if(confirm("Do you really want to change status?")){
      a = $('select#status_'+id).val();
      var data = {
        action: 'myprefix_change_status',
        id: id,
        status: a.toString()
      };
        jQuery.get(ajaxurl, data, function(response) {
          if(response.success === true) {
              alert(response.data);
          }
          else{
            alert("sorry, status can't changed");
          }
        });
  }

}

function changeRowStatus(id){
  if(confirm("Do you really want to change status?")){
    a = $('select#status_'+id).val();
   // alert(a);
    var data = {
      action: 'wp_ajax_myprefix_update_position',
      id: id,
      status: a.toString()
    };
    jQuery.get(ajaxurl, data, function(response) {
      if(response.success === true) {
          alert(response.data);
      }
      else{
        alert("sorry, status can't changed");
      }
    });
    location.reload(true);
  }
}

function getTableList()
{
  var myTab = document.getElementById('view_list');
}
