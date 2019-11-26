$(document).ready(function(){
    $('tbody').sortable({
        update: function(event,ui){
            $(this).children().each(function(index){
                if($(this).attr('data-position')!=(index+1)){
                    $(this).attr('data-position',(index+1)).addClass('updated');
                }
            });
            saveNewPosition();
        }
    });
});
function saveNewPositionb(){
    var position = [];
    $('.updated').each(function(){
        position.push([$(this).attr('data-index'),$(this).attr('data-position')]);
        $(this).removeClass('updated');
    });
    $.ajax({
        url: "update_position.php",
        method: 'POST',
        datatype: 'text',
        data:{
            update:1,
            positions:positions
        },success:function(response){

        }
    });
}

function saveNewPosition(){
        var position = [];
        $('.updated').each(function(){
            position.push([$(this).attr('data-index'),$(this).attr('data-position')]);
            $(this).removeClass('updated');
        });
      var data = {
        action: 'myprefix_update_position',
        update:1,
        positions:position
      };
      jQuery.get(ajaxurl, data, function(response) {
        if(response.success === true) {
            
        }
        else{
          alert("sorry, position can't changed");
        }
      });
}
  
    /*var data = {
        action: "myprefix_view_information",
        id: "1"
      }
      
        jQuery.get(ajaxurl,data,function(response){
            jQuery('#view_list').html(response.data.list);
            jQuery('#records_num').html(response.data.num_records);
            //getTableList();
        });*/

