<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="wrap cjswrap">
  <div id="icon-edit" class = "icon32"></div>
  <h2><?php _e('Manage Products Collage') ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="new_collage" value="ADD NEW" class="button action"  title="ADD NEW" onclick="document.location.href='admin.php?page=wpc_product_collage&action=ourProductCollage'" /></h2>
	<p class="title_manage">&nbsp;</p>
	<?php 
        if(!empty($msg)){
            echo "<div class='wpc_success' > $msg </div>";
        }        
        ?>
                
	<form method="post" action="admin.php?page=wpc_product_collage_list" name="dataform" id="dataform">
            <?php if (!empty( $records ) ) { ?>
		<div class="alignleft actions" style="margin-bottom:15px;">
			<select name='action2' style="width:150px;">
                            <option value='' selected='selected'><?php _e('Bulk Actions') ?> </option>
                            <option value='wpc_delete_collage_report'><?php _e('Delete') ?></option>
			</select>
			<input type="submit" name="submit" id="doaction2" value="Apply" class="button action" onclick="return checkCheckedPost();" title="Click to Apply" />
		</div>
            <?php } ?>
            <div class="alignright actions" style="margin-bottom:15px;">
                <input type="text" name="search_name" id="search_name" autocomplete="off" placeholder="Search By Name" value="<?php echo @strip_tags(addslashes($_REQUEST['search_name'])); ?>">
                
                
                <!--<select id="search_sponsorship" name="search_sponsorship">
                    <option value="">Select Sponsorship</option>
                    <?php if($sponsorships_args->have_posts() && 0) {
                            while($sponsorships_args->have_posts()) : $sponsorships_args->the_post();
                                $post_id = get_the_id();

                                $selected = "";
                                if($post_id == @$_REQUEST['search_sponsorship']){
                                    $selected = "selected";
                                }
                                ?>
                                <option value="<?php echo $post_id; ?>" <?php echo $selected; ?> ><?php echo get_the_title(); ?></option>
                                <?php
                            endwhile;
                        } ?>
                </select>-->
                
			<input type="submit" name="submit" id="doaction2" value="Search" class="button action"  title="Click to Search" />
            </div>
		<div class="report ">                    
                    <?php                     
                    if ( empty( $columns ) || empty( $records ) ) {
				echo "<div class=\"report\"><p class=\"nodata\">No records found.</p></div>";
                    }else{                    
                    ?>                    
			<?php do_action( 'wpc_exist_users_list_view_table' ) ?>
			<table class="widefat post fixed cjstable" >
				<thead>
					<tr>
						<th width="2%">
							<input name='numbers[]' type='checkbox' id='category_0' value='' onclick='javascript:SelectAllpost(this,<?php echo (count($records)+2) ?>);' style='margin:0px;'/>
						</th>
						<?php foreach ( $columns as $key => $label ): ?>
							<th class="client-<?php esc_attr_e( $key ); ?>" scope="col"><?php esc_html_e( $label ); ?></th>
					<?php endforeach; ?>
						<th><?php _e('Action') ?></th>
					</tr>
				</thead>
				<tbody id="report_rows">
					<?php $counter	=	0;
						if($record_array):
                                                
					?>
					<?php foreach ( $record_array as $record ): 
                                            
                                             $upgrade_request = $record['upgrade'];
                                            ?>
                                    <tr class="<?php if(!empty($upgrade_request)){ echo "highlightsection_row"; } ?>" >
							<td class="client_id">
								<input type="checkbox" name="numbers[]" id="category_<?php echo ($counter+1); ?>" value="<?php echo $record_ids_array[$counter];?>" />
							</td>
							<?php foreach ( $columns as $key => $label ): ?>
							<td class="client-<?php esc_attr_e( $key ); ?>">
								<?php if ( isset( $record[$key] ) ) { echo $record[$key]; } ?>
							</td>
							<?php endforeach; ?>
							<td>	
								&nbsp;&nbsp;<a href="admin.php?page=wpc_product_collage&action=ourProductCollage&id=<?php echo $record_ids_array[$counter];?>" title="<?php _e('Edit') ?>"><img src="<?php echo WPC_URL;?>/images/edit_button.png" height="15" title="<?php _e('Edit') ?>" /></a>
                                                                
                                                                &nbsp;&nbsp;<a href="javascript:void()" onclick="return getCollageCode('<?php echo $record['id']; ?>','<?php echo WPC_URL; ?>','<?php echo admin_url( 'admin-ajax.php' ); ?>');" title="<?php _e('View Code') ?>"><img src="<?php echo WPC_URL;?>/images/details.png" height="15" title="<?php _e('View Code') ?>" /></a>      
                                                                
                                                                
                                                                <a href="#TB_inline?width=400&height=300&inlineId=my_collage_id_display" class="thickbox" id="click_show_pop_<?php echo $record['id']; ?>" style="display: none;" >1</a>
                                                                
                                                                
								
								
							</td>
						</tr>
					<?php $counter++; endforeach; endif;?>
				</tbody>
				<thead>
					<tr>
						<th width="2%">
							<input name='numbers[]' type='checkbox' id='category_<?php echo (count($records)+1) ?>' value='' onclick='javascript:SelectAllpost(this,<?php echo (count($records)+2) ?>);' style='margin:0px;'/>
						</th>	
					<?php foreach ( $columns as $key => $label ): ?>
						<th class="client-<?php esc_attr_e( $key ); ?>" scope="col"><?php esc_html_e( $label ); ?></th>
					<?php endforeach; ?>
						<th><?php _e('Action') ?></th>
					</tr>
				</thead>
			</table>
                    <?php } ?>
		</div>
            <div id="my_collage_id_display" style="display:none;">
                <h3>Copy & Paste Below code<br/></h3>
                <textarea name="my_collage_id_textarea"  id="my_collage_id_textarea"></textarea>
                
            </div>
            
            <input type="hidden" name="check_search_clicked" id="check_search_clicked" value="0">
	</form>
	
<?php do_action( 'wpc_view_pagination',$total_pages ) ?>
</div>