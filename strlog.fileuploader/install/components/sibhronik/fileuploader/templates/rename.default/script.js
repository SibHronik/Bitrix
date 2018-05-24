$(document).ready(function(){
    $('.uploader_file').change(function(){
        var form = $('#uploader');
        var file = $('#uploader_file');
        var data = new FormData(form);
        for (var i = 0; i < file.prop('files').length; i++) {
            ext = file.prop('files')[i].name.split('.'); 
            var name = ext[ext.length-1];
            if(name.toLowerCase() == 'json' || name.toLowerCase() == 'xml' || name.toLowerCase() == 'csv'){
                data.append('file', file.prop('files')[i]);
                $.ajax({
                    type: 'POST',
                    url: templateFolder + '/action.php',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (success) {
                        $('.notice').html(success);
                        console.log(success);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }else{
                $('.notice').text('Поддерживаются только форматы json, xml, xls и csv');
            }
        }
    });
});