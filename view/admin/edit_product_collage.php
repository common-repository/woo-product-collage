<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$category_list_selected = "";
?>	
<div class="wrap cjswrap">
	<div id="icon-edit" class = "icon32"></div>	
	<h2><?php if($field_id):?><?php _e('Edit Product Collage') ?><?php else:?><?php _e('Add Product Collage') ?><?php endif;?></h2>
	<?php do_action('wpc_display_message');?>
	<div class="cjscontainer">
		<?php do_action('wpc_add_exist_user_before_form');?>
		<form action="" id="formID" method="post" enctype="multipart/form-data">
			<?php do_action('wpc_add_exist_user_top_form');?>
			<div class="postbox wpc_basic_info">
				<h3 class="hndle"><span><?php _e('Product Collage Information') ?></span></h3>
				<table width="100%">
                                        
					<tr>
						<td width="20%"><?php _e('Title:');?></td>
						<td>
						<input type="text" name="title" id="name" class="validate[required,custom[onlyLetterNumber]]" value="<?php echo @$field_data['title']; ?>"   /> </td>
					</tr>
                                        
                                        <tr>
						<td width="20%"><?php _e('Category:');?></td>
						<td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <select name="category_id_selected[]" id="category_id_selected" multiple="" class="multiple_dropbox">
                                                        <?php if(!empty($product_categories)) {
                                                                foreach($product_categories as $categories){
                                                                    $selected = "";
                                                                    if(@in_array($categories->term_id,$db_category_array)){
                                                                        //$selected = "selected";
                                                                        continue;
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $categories->term_id; ?>" <?php echo $selected; ?> ><?php echo wpc_extractParseFromDB($categories->name); ?></option>
                                                                <?php
                                                                }
                                                        } ?>
                                                                </select><br/><span  class="show_add_remove_cls"><a href="javascript:void();" onclick="return addCategoryToStack();"><?php _e('Add ->');?></a></span>
                                                            </td>
                                                            <td>
                                                                <select name="category_id[]" id="category_id" multiple="" class="validate[required] multiple_dropbox" >
                                                        <?php if(!empty($product_categories) && !empty($field_id) ) {
                                                                foreach($product_categories as $categories){
                                                                    $selected = "";
                                                                    if(@!in_array($categories->term_id,$db_category_array)){
                                                                        //$selected = "selected";
                                                                        continue;
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $categories->term_id; ?>" <?php echo $selected; ?> ><?php echo wpc_extractParseFromDB($categories->name); ?></option>
                                                                <?php
                                                                }
                                                                } ?>
                                                            </select>
                                                                <br/><span class="show_add_remove_cls"><a href="javascript:void();" onclick="return removeCategoryToStack();"><?php _e('<- Remove');?></a></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
					</tr>
                                        
					<tr>
						<td width="20%"><?php _e('Products:');?></td>
						<td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <select name="product_id_select[]" id="product_id_select" multiple="" class="multiple_dropbox"  >                                                        
                                                                    <?php echo $product_multi; ?>
                                                                </select>
                                                                <br/><span  class="show_add_remove_cls"><a href="javascript:void();" onclick="return addProductToStack();"><?php _e('Add ->');?></a></span>
                                                            </td>
                                                            <td>
                                                                <select name="product_id[]" id="product_id" multiple="" class="validate[required] multiple_dropbox"  >                                                        
                                                                    <?php echo $product_already_selected_multi; ?>
                                                                </select><br/><span  class="show_add_remove_cls"><a href="javascript:void();" onclick="return removeProductToStack();"><?php _e('<- Remove');?></a></span>
                                                            </td>
                                                        </tr>
                                                    </table>   
                                                </td>
					</tr>
					
					<tr>
						<td width="20%"><?php _e('Layout:');?></td>
						<td>
                                                    <?php if(!empty($arr_layout_collage)){                                                        
                                                            foreach($arr_layout_collage as $layout){
                                                                ?>
                                                                <div class="wp_layout_collage">
                                                                    <img src="<?php echo $layout['url']; ?>" title="<?php echo $layout['title']; ?>" width="200">
                                                                    <span><input type="radio" name="prod_layout" id="layout_select_<?php echo $layout['layout_id']; ?>" value="<?php echo $layout['layout_id']; ?>" <?php if(@$field_data['prod_layout'] == $layout['layout_id']){ echo "checked"; } ?>  class="validate[required]" ><?php echo $layout['title']; ?></span>
                                                                </div>
                                                                <?php 
                                                            }
                                                        
                                                    } ?>
                                                    
                                                    
						</td>
					</tr>
                                        
                                        <tr>
						<td width="20%"><?php _e('Color Scheme:');?></td>
						<td>                                                    
                                                    <input name="color_scheme" id="chosen-value" value="<?php if(!empty($field_data['color_scheme'])){ echo $field_data['color_scheme']; }else{ echo "000000"; } ?>" class="jscolor {valueElement:'chosen-value'}" >
                                                </td>
					</tr>                                       
                                        
					
					<tr>
						<td width="20%">&nbsp;</td>
						<td>
                                                    <input type="hidden" name="hidden_collage_product" value="yes">
                                                    
                                                    <input type="submit" value="<?php if(!empty($field_id)){  _e('Update'); }else{ _e('Add'); } ?>" name="<?php if($field_id):?>wpc_edit_exist_user_submit<?php else:?>wpc_add_exist_user_submit<?php endif;?>" class="wpc_submit_btn button button-primary button-large" onclick="return getSubmitCollage();" >                                                           
                                                        <input type="button" value="<?php _e('Cancel');?>" name="accommodation_preference_button_exist" class="wpc_submit_btn button button-primary button-large" onclick="document.location.href='<?php echo get_site_url()."/wp-admin/admin.php?page=wpc_product_collage_list"; ?>'" >      
                                                        
						</td>
					</tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
				</table>
			</div>
		</form>
		<?php do_action('wpc_exist_users_list_after_form');?>
	</div>
            
</div>