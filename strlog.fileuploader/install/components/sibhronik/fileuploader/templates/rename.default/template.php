<script>
var templateFolder = <?=CUtil::PhpToJSObject($templateFolder)?>;
</script>
<form id="uploader" action="javascript:void(null);" enctype="multipart/form-data">
	<label class="uploader_file_label" for="uploader_file">
		Выбрать файл
		<input type="file" id="uploader_file" class="uploader_file" name="uploader" />
	</label>
</form>