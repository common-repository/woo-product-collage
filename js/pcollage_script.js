(function() {

    // Localize jQuery variable
    var jQuery;

    /******** Load jQuery if not present *********/
    if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.4.2') {
        var script_tag = document.createElement('script');
        script_tag.setAttribute("type","text/javascript");
        script_tag.setAttribute("src",
            "http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js");
        if (script_tag.readyState) {
          script_tag.onreadystatechange = function () { // For old versions of IE
              if (this.readyState == 'complete' || this.readyState == 'loaded') {
                  scriptLoadHandler();
              }
          };
        } else {
          script_tag.onload = scriptLoadHandler;
        }
        // Try to find the head, otherwise default to the documentElement
        (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
    } else {
        // The jQuery version on the window is the one we want to use
        jQuery = window.jQuery;
        main();
    }

    /******** Called once jQuery has loaded ******/
    function scriptLoadHandler() {
        // Restore $ and window.jQuery to their previous values and store the
        // new jQuery in our local jQuery variable
        jQuery = window.jQuery.noConflict(true);
        // Call our main function
        main(); 
    }

    /******** Our main function ********/
    function main() {
        jQuery(document).ready(function($) {
            
            jQuery( ".pcollage_widget_container_class" ).each(function( index ) {                
                
                var collage_id = jQuery( this ).attr('id');                
                var ajaxurl = jQuery( this ).attr('rel');     
                
                
                
                var data_array = {
                    'action': 'wpc_pcollage_api_code',
                    'callback': 'yes',
                    'collage_id' : collage_id
                };
                
                
                $.ajax({                    
                    url: ajaxurl,
                    data: data_array,
                    crossDomain: true,
                    jsonpCallback: 'callback',
                    contentType: "application/json; charset=utf-8",
                    dataType: "jsonp",
                    success: function (data) {
                         //alert(data);
                         var obj_response = jQuery.parseJSON(data);                         
                         //alert(obj_response.html);
                         //return false;
                         
                         if(obj_response.html != ""){
                            $('#' + collage_id).html(obj_response.html);
                          }
                          else{
                              $('#' + collage_id).html("Please check the id again.");
                          }
                    }
                });                
            });
        });
    }

    })(); // We call our anonymous function immediately