<script>
jQuery( document ).ready(function( $ ) {

	
	var file_frame;
	//modify 
	if(_dataIndex >= 0){
		//alert(config.slides[_dataIndex]);
		var currdata	= config.slides[_dataIndex];
		$("#pondolplugin-dialog-image").val(currdata.image);
		$("#pondolplugin-dialog-thumbnail").val(currdata.thumbnail); 
		$("#pondolplugin-dialog-image-title").val(currdata.title);
		$("#pondolplugin-dialog-image-description").val(currdata.description);
		//$("#pondolplugin-dialog-displaythumbnail").val(currdata.displaythumbnail);
		currdata.displaythumbnail == "true" ? $("#pondolplugin-dialog-displaythumbnail").attr("checked", true):"";
		$("#pondolplugin-dialog-weblink").val(currdata.weblink);
		$("#pondolplugin-dialog-linktarget").val(currdata.linktarget);
	}
	
	$(document).on("click", "#pondolplugin-dialog-select-image", function (event) {
           // alert("clicked");
		event.preventDefault();
		
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			//alert("open11");
			//file_frame.open();
			return;
		}else{
			
		
		
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: "Choose Image",
				button: {
					text: "Choose Image"
				},
				multiple: false
			});
	                
			// When an image is selected, run a callback.
			file_frame.on("select", function (event) {
				var selection = file_frame.state().get("selection");
				
				attachment = file_frame.state().get('selection').first().toJSON();
				//console.log("attachment");
				//console.log(attachment);
				$("#pondolplugin-dialog-image").val(attachment.url);
				$("#pondolplugin-dialog-thumbnail").val(attachment.sizes.thumbnail.url);
				$("#pondolplugin-dialog-image-title").val(attachment.title);
				$("#pondolplugin-dialog-image-description").val(attachment.description);
			});
			// Finally, open the modal
			console.log(file_frame);
			file_frame.open()
		}
		
		
	});//var media_upload_onclick = function (event) 
            
            
	//current data update
	$(document).on("click", "#pondolplugin-dialog-ok", function(e){
		
		if ($.trim($("#pondolplugin-dialog-image").val()).length <= 0) {
			//alert('Choose Image');
		}else{
			var obj = {
				image:$("#pondolplugin-dialog-image").val(), 
				thumbnail:$("#pondolplugin-dialog-thumbnail").val(), 
				title:$("#pondolplugin-dialog-image-title").val(), 
				description:$("#pondolplugin-dialog-image-description").val(),
				displaythumbnail:$("#pondolplugin-dialog-displaythumbnail").is(':checked'),
				weblink:$("#pondolplugin-dialog-weblink").val(),
				linktarget:$("#pondolplugin-dialog-linktarget").val()
			}
			
			//console.log("_dataIndex:"+_dataIndex);
			if(_dataIndex >= 0){
				config.slides[_dataIndex] = obj;
			//}else if(_dataIndex == -1){//no slides
			//	config.slides.push(obj);
			}else{
				config.slides.push(obj);
				
			}
			updateMediaTable();
			$(".pondolplugin-dialog-container").remove();
		}
		return
	});
	
	//close button(cancel button) click
	$(document).on("click", "#pondolplugin-dialog-cancel", function(e){
		$(".pondolplugin-dialog-container").remove()
	});

});
</script>

<div class='pondolplugin-dialog-container'>
    <div class='pondolplugin-dialog'>
        <table id='pondolplugin-dialog-form' class='form-table'>
            <tr>
                <th>Enter image URL</th>
                <td>
                <input name='pondolplugin-dialog-image' type='text' id='pondolplugin-dialog-image' value='' class='regular-text' />
                
                <input type='button' class='button' data-textid='pondolplugin-dialog-image' id='pondolplugin-dialog-select-image' value='Upload' />
                </td>
            </tr>
            <tr id='pondolplugin-dialog-image-display-tr' style='display:none;'>
                <th></th>
                <td><img id='pondolplugin-dialog-image-display' style='width:80px;height:80px;' /></td>
            </tr>
            <tr>
                <th>Thumbnail URL</th>
                <td>
                <input name='pondolplugin-dialog-thumbnail' type='text' id='pondolplugin-dialog-thumbnail' value='' class='regular-text' />
                <br/>
                <label>
                    <input name='pondolplugin-dialog-displaythumbnail' type='checkbox' id='pondolplugin-dialog-displaythumbnail' value='' />
                    Use thumbnail in carousel</label></td>
            </tr>
            <tr>
                <th>Title</th>
                <td>
                <input name='pondolplugin-dialog-image-title' type='text' id='pondolplugin-dialog-image-title' value='' class='large-text' />
                </td>
            </tr>
            <tr>
                <th>Description</th>
                <td>
                <textarea name='pondolplugin-dialog-image-description' type='' id='pondolplugin-dialog-image-description' value='' class='large-text' />
                </td>
            </tr>
            <tr>
                <th>Click to open web link</th>
                <td>
                <input name='pondolplugin-dialog-weblink' type='text' id='pondolplugin-dialog-weblink' value='' class='regular-text' />
                </td>
            </tr>
            <tr>
                <th>Set web link target</th>
                <td>
                	<select name="pondolplugin-dialog-linktarget" id="pondolplugin-dialog-linktarget">
                		<option value="">none</option>
                		<option value="_blank">blank</option>
                	</select>
                </td>
            </tr>
        </table>
        <br />
        <br />
        <div class='pondolplugin-dialog-buttons'>
            <input type='button' class='button button-primary' id='pondolplugin-dialog-ok' value='OK' />
            <input type='button' class='button' id='pondolplugin-dialog-cancel' value='Cancel' />
        </div>
    </div>
</div>