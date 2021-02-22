jQuery(document).ready(function(){
	jQuery('#wp-admin-bar-view-site a').attr('target', '_blank');
	jQuery('#posts-filter .alignleft #filter-by-date').attr('onchange','this.form.submit()');
	jQuery('#posts-filter .alignleft #cat').attr('onchange','this.form.submit()');


	jQuery('#posts-filter table.wp-list-table.tags thead tr, #posts-filter table.wp-list-table.tags tfoot tr').prepend('<td class="idHolder">ID</td>');
	function addCatID(){
		jQuery('#posts-filter table.wp-list-table.tags tbody tr').each(function(){
			if(jQuery(this).find('td.catID').length<1){
				var thisID = jQuery(this).attr('id');
				thisID = thisID.replace('tag-','');
				jQuery(this).prepend('<td class="catID">'+thisID+'</td>');
			}
		});
	}addCatID();


	jQuery(document).ajaxComplete(function(){
		addCatID();
	});

	
})