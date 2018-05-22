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

?>

<script>
	$(document).ready(function(){
		$(document).on('click', 'input', function(){ 
			var calendarPick = $(this).attr('name');
			if (calendarPick == 'CALENDAR_TYPE[]') {
				BX.calendar({node: this, field: this, bTime: false});
			}
		});
	});
</script>