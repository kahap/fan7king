<div class="row">
	<div class="input-field col s12">
		<input type="text" name="<?php echo $valueIn["COLUMN_NAME"]; ?>" value="<?php echo $action == "edit" ? $data[0][$valueIn["COLUMN_NAME"]] : ""; ?>">
		<label class=""><?php echo $valueIn["COLUMN_COMMENT"]; ?></label>
		<label id="<?php echo $valueIn["COLUMN_NAME"]; ?>Err" class="error"></label>
	</div>
</div>