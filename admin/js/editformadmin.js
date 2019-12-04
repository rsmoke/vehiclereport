
$('.updateFormAdmin').click(function(e) {

  e.preventDefault();

  $.ajax({
    url: 'queryupdateformadmin.php',
    type: 'GET',
    data: "id="+$(this).parent().next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').text(),
    success: function(data) {

      $('#popup').html(data);
      dialog.dialog('open');

    }
  });
});



$('.deleteForm').click(function(e) {

  e.preventDefault();

	var promptDelete = confirm('Are you sure you want to delete this item?');

	if (promptDelete) {
	     $.ajax({
			url: 'querydeleteform.php',
			type: 'POST',
			data: "id="+$(this).parent().next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').next('td').text(),

			success: function(data) {
				location.reload(true);
			}
		});
	} else {
	   return; //break out of the function early
	}
});

dialog = $('#popup').dialog({
  autoOpen: false,
  modal: true,
  minWidth: 420,
  minHeight: 400,
  draggable: true,
  show: 'fade',
  hide: 'fade',
  title: "Updating Form",
  buttons: {

	"Save Updates": function() {

		$.ajax({
        url: "querypushupdatesindbadmin.php",
        type: "POST",
		data: $("#formEdit").serialize().replace(/\'/g,'\\\''),
        success: function() {

		dialog.dialog( "close" );

		location.reload(true);

        }
      });

    },
    "Close": function(){
      dialog.dialog( "close" );
    }
  }

});




