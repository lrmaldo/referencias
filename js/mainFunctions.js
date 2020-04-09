function performRequest()
{
	var idCliente = $('#idCliente').val();
	if (idCliente) {
		$.ajax({
			url: 'ajaxRequest.php',
			type: 'POST',
			dataType: 'JSON',
			data:{id:idCliente},
			beforeSend:function(){
				$('#resultsDiv').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><i class="fa fa-spinner fa-pulse fa-2x"></i></div>');
			},
			success:function(rdata) {
				if (rdata.status=='success') {
					$('#resultsDiv').html(rdata.content);
				}	
			}
		});
	}
}

function performSearch(e)
{
	if (e.keyCode == 13) {
        $('#btnSearch').click();
        return false;
    }
}