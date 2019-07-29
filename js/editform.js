$('#popup').dialog({
  autoOpen: false,
  modal: true,
  minWidth: 450,
  minHeight: 400,
  draggable: true,
  show: 'fade',
  hide: 'fade',
  title: "Updating Form",
  buttons: {
    
	"Save Updates3": function() {
	
	var $this = $(this); 
	
	var form_data = new FormData(document.getElementById('formEdit'));
		
		$.ajax({
		url: "querypushupdatesindb.php",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		cache: false,
		success: function() {
			
		$this.dialog('close');  
			
		location.reload(true);
		
     }
    });
	 
    },
    "Close!": function(){
      $(this).dialog('close');
    }
  }
});



$('.updateForm').click(function(e) {

  e.preventDefault();
  
  $.ajax({
    url: 'queryupdateform.php',
    type: 'GET',
    data: "id="+$(this).parent().next('td').next('td').next('td').next('td').text(),
    success: function(data) {
      $('#popup').html(data);
      $('#popup').dialog('open');
	  
    }
  });
});




