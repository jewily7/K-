
$(".board-icon").click(function(){
 	$("input[type='text']").fadeToggle();
});

$("input[type='text']").keypress(function(event){

	if(event.which === 13){
		var boardText = $(this).val();
		if($(this).val()==""){
			alert("Enter a valid board-name!");
		}
		else{
			$(this).val("");
			$("div.board").append("<div class='board-item'>" + boardText + "<a href='todo.html'><span class='icon-edit'><i class='fas fa-edit fa-sm'></i></span></a></div>")
		}
	}

	
});
