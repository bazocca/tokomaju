<form action="<?php echo $imagePath.$url_lang.'search'; ?>" accept-charset="utf-8" method="post" enctype="multipart/form-data">
	<div class="input-group">
		<input REQUIRED placeholder="search here ..." type="text" class="form-control" name="data[search]" value="<?php echo $_POST['data']['search']; ?>">
	    <span class="input-group-btn">
	      <button class="btn btn-default" type="submit">
	      	<span class="glyphicon glyphicon-search"></span>
	      </button>
	    </span>
	</div>
</form>