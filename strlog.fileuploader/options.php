<?php
use \Bitrix\Main\Localization\Loc;
?>
<div class="adm-detail-block">
	<div class="adm-detail-content-wrap">
		<div class="adm-detail-content" style="padding: 12px;">
			<div class="adm-detail-title"><?=Loc::getMessage("FILEUPLOAD_CHOOSE_FILE");?></div>
			<div class="adm-detail-content-item-block">
				<?php 
				$APPLICATION->IncludeComponent(
					"sibhronik:fileuploader", 
					".default", 
					array(
						"COMPONENT_TEMPLATE" => ".default",
					),
					false
				);
				?>
			</div>
			<div class="adm-detail-content-item-block">
				<span class="notice"><?=Loc::getMessage("FILEUPLOAD_RESULT_HERE");?></span>
			</div>
		</div>
	</div>
</div>