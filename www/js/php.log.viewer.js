window.core = {};
window.core.applyFilter  = function(){
	var filter = $('#filter_text').val();
        var lines = $('p.log-line');
        for(var i = 0; i < lines.length; i++){
                        var line = $(lines[i]).text();
                        if(line.indexOf(filter) !== -1){
                                $(lines[i]).show();
                        }else{
                                $(lines[i]).hide();
                        }
                }
}

$(document).ready(function() {
	$('#filter_btn').unbind().bind('click', core.applyFilter);
});
