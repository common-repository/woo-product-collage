function checkCheckedPost()
{
    if(jQuery("#check_search_clicked").val() == 0){
	var a = new Array();
	a = document.getElementsByName("numbers[]");
	var p = 0;
	
	for(i=0; i<a.length; i++)
	{
		if(a[i].checked)
		{
			p=1;
		}
	}	
	
	if(p==0)
	{
		alert('Please select at least one check box!');
		return false;
	}
	
	if(document.dataform.action2.selectedIndex==0)
	{
		alert('Please select the action!');
		return false;
	}
	
	if(confirm('Are you sure want to continue?'))
	{
		var p=0;
		var a = new Array();
		
		a = document.getElementsByName("numbers[]");
		
		for(i=0;i<a.length;i++)
		{
			if(a[i].checked)
			{
				p = 1;
			}
		}
                
                
	}
	else
	{
		return false;
	}
        
    }    
}

function SelectAllpost(SelAllBox,no)
{	
    var	i =	0;
    var InnerBox = "";
    for(i=0; i<no; i++)
    {
        InnerBox = document.getElementById("category_"+i);
        if(SelAllBox.checked == true)
        {
            InnerBox.checked = true;
        }
        else
        {
            InnerBox.checked = false;
        }
    }
} 

jQuery(document).ready(function(){	
    jQuery("#formID").validationEngine('attach', {promptPosition : "centerRight"});
    //jQuery("#formIDNew").validationEngine('attach', {promptPosition : "centerRight"});          
});


function getProductList(){
    
    var str_selection = ""; 
    
    //jQuery('#category_id :selected').each(function(i, selected)
    
    jQuery('#category_id > option').each(function(i, selected){ 
      str_selection = str_selection + jQuery(selected).val() + ",";
    });   
    
    if(str_selection != ""){
        str_selection = str_selection + "0"
    }
    
    //alert(str_selection);
    
    
    var str_selection_already = ""; 
    
    jQuery('#product_id > option').each(function(i, selected){ 
      str_selection_already = str_selection_already + jQuery(selected).val() + ",";
    });   
    
    if(str_selection_already != ""){
        str_selection_already = str_selection_already + "0"
    }
    
    //alert(str_selection_already);
    
    
    
    var data = {
                    'action': 'wpc_Fnproduct_accto_category_list',
                    'str_selection': str_selection,
                    'str_selection_already' : str_selection_already
                };
                
                
    //jQuery("#my-content-id").html('');
    jQuery.post(ajaxurl, data, function(response) {                      
          //alert(response);                            
          //return false;
          
          var result = jQuery.parseJSON(response);

          
          jQuery("#product_id_select").html(result.product_multi);
          jQuery("#product_id").html(result.product_already_selected_multi);
          return false;
    });    
}

jQuery('#search_name').keypress(function(event){

	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13'){
            jQuery("#check_search_clicked").val(1);
		
	}
});

function getCollageCode(collage_id,home_url,custom_ajax_url){
    //alert(collage_id + "  " + home_url);
    
    var html_code = '<script src="'+home_url+'js/pcollage_script.js" type="text/javascript"></script>';    
    html_code = html_code + '<div id="pcollage_widget_container_'+collage_id+'" class="pcollage_widget_container_class" rel="'+custom_ajax_url+'"></div>';
       
       //alert(html_code);
       
    jQuery("#my_collage_id_textarea").val(html_code);          
    jQuery('#click_show_pop_' + collage_id).click();

    return false;       
}

function addCategoryToStack(){
    
    if (jQuery('#category_id_selected option:selected').val() != null) {
          var tempSelect = jQuery('#category_id_selected option:selected').val();
          jQuery('#category_id_selected option:selected').remove().appendTo('#category_id');
          jQuery("#category_id_selected").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
          jQuery("#category_id").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
          jQuery("#category_id").val(tempSelect);
          tempSelect = '';
          
          getProductList();
          
      } else {
          alert("Before add please select any position.");
      }
    
}


function removeCategoryToStack(){
    
    if (jQuery('#category_id option:selected').val() != null) {
          var tempSelect = jQuery('#category_id option:selected').val();
          jQuery('#category_id option:selected').remove().appendTo('#category_id_selected');
          jQuery("#category_id").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
          jQuery("#category_id_selected").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");

          jQuery("#category_id_selected").val(tempSelect);
          tempSelect = '';
          
          getProductList();
          
      } else {
          alert("Before remove please select any position.");
      }
    
}

function addProductToStack(){
    
    if (jQuery('#product_id_select option:selected').val() != null) {
          var tempSelect = jQuery('#product_id_select option:selected').val();
          
          //alert(tempSelect);
          
          if(jQuery("#product_id option[value='"+tempSelect+"']").length > 0){
            //alert("Value already exist.");
          }
          
          jQuery('#product_id_select option:selected').remove().appendTo('#product_id');
          jQuery("#product_id_select").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
          jQuery("#product_id").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
          jQuery("#product_id").val(tempSelect);
          tempSelect = '';
          
      } else {
          alert("Before add please select any position.");
      }
    
}


function removeProductToStack(){
    
    if (jQuery('#product_id option:selected').val() != null) {
          var tempSelect = jQuery('#product_id option:selected').val();
          
          //$("#yourSelect option[value='yourValue']").length > 0;
          
          jQuery('#product_id option:selected').remove().appendTo('#product_id_select');
          jQuery("#product_id").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
          jQuery("#product_id_select").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");

          jQuery("#product_id_select").val(tempSelect);
          tempSelect = '';
      } else {
          alert("Before remove please select any position.");
      }    
}

function getSubmitCollage(){
    jQuery('#category_id option').prop('selected', true);
    jQuery('#product_id option').prop('selected', true);
    
    return true;
}