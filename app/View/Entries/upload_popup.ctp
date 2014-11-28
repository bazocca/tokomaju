<div id="upload-popup" class="media upload-popup">
	<div class="layout-header">
		<div class="sidebar-title">
			<h4>Media Library</h4>
			<a href="javascript:void(0);" class="close"><i class="icon-remove icon-white"></i></a>
		</div>
	</div>
	
	<div class="layout-content">
		<div class="tabs">
			<ul class="nav nav-tabs">			
				<li class="active"><a href="#tabs2">Upload from Local Disk</a></li>
			</ul>
			
			<div id="tabs2" class="tabs-container">
				<!-- The file upload form used as target for the file upload widget -->			
				<?php				
					echo $this->Form->create('Entry', array('action'=>'UploadHandler','type'=>'file','id'=>'fileupload','inputDefaults' => array('label' =>false , 'div' => false)));	
				?>
						<input type="hidden" value="<?php echo $myTypeSlug; ?>" name="data[Type][slug]" />
						<!-- The loading indicator is shown during file processing -->
						<div class="tabs-content">
							<div class="fileupload-progress fade">
									<!-- The global progress bar -->
									<div class="progress progress-success progress-striped active">
											<div class="bar" style="width:0%;"></div>
									</div>
									<!-- The extended global progress information -->
									<div class="progress-extended">&nbsp;</div>
							</div>
								
							<div class="fileupload-loading"></div>
							<br>
							<!-- The table listing the files available for upload/download -->
							<table class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
						</div>
						
						<div class="popup-action">
							<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
							<div class="fileupload-buttonbar">
										<!-- The fileinput-button span is used to style the file input field as button -->
										<span class="btn fileinput-button">
												<i class="icon-plus"></i>
												<span>Add Files...</span>
												<input type="file" name="files[]" multiple>
										</span>
										<button type="submit" class="btn btn-primary start">
												<span>Start Upload</span>
										</button>
										<button type="reset" class="btn cancel">
												<span>Clear Queue</span>
										</button>									
								<!-- The global progress information -->
								
							</div>
						</div>
				<?php echo $this->Form->end(); ?>
			</div>
			
		</div>
	</div>
</div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span>{%=locale.fileupload.start%}</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="#" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="#" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td colspan="2"></td>
        {% } %}        
    </tr>
{% } %}
</script>

<!-- The localization script -->
<script src="<?php echo $imagePath; ?>js/uploadfile/locale.js"></script>
<!-- The main application script -->
<script src="<?php echo $imagePath; ?>js/uploadfile/main.js"></script>