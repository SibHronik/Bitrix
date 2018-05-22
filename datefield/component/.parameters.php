<?php

CJSCore::Init(array("jquery"));
CJSCore::Init(array("jquery","date"));

$arComponentParameters = array(
	"GROUPS" => array(
		"CALENDAR" => array(
			"NAME" => GetMessage("CALENDAR")
		),
	),
	"PARAMETERS" => array(
		"CALENDAR_TIME" => array(
			"PARENT" => "CALENDAR",
			"NAME" => GetMessage("CALENDAR_TIME"),
			"TYPE" => "CHECKBOX",
		),
		"CALENDAR_TYPE" => array(
			"PARENT" => "CALENDAR",
			"NAME" => GetMessage("CALENDAR_DATE"),
			"TYPE" => "STRING",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "",
			"REFRESH" => "N",
			"COLS" => "10",
		),
	),
);

$is_time = '';
if ($arParams['CALENDAR_TIME'] == 'Y') {
	$is_time = true;
} else {
	$is_time = false;
}
?>

<script>
	$(document).ready(function(){
		var is_time = <?php echo $is_time;?>
		$(document).on('click', 'input', function(){ 
			var calendarPick = $(this).attr('name');
			if (calendarPick == 'CALENDAR_TIME') {
				if ($(this).is(':checked')) {
					is_time = true;
				} else {
					is_time = false;
				}
			}
			if (calendarPick == 'CALENDAR_TYPE[]') {
				BX.calendar({node: this, field: this, bTime: is_time});
			}
		});
	});
</script>