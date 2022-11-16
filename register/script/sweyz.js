/* Script by Sweyz (You're allowed to delete this)*/

$(document).ready(function() {

	$("#enregistrer").click(function(){
		$("#ranks").slideUp("fast");
		$("#telecharger_contenu").slideUp("fast");
		$("#form").slideDown("fast");
	});

	$("#telecharger").click(function(){
		$("#form").slideUp("fast");
		$("#console").slideUp("fast");
		$("#ranks").slideUp("fast");
		$("#telecharger_contenu").slideDown("fast");
	});

	$("#rankings").click(function(){
		$("#form").slideUp("fast");
		$("#console").slideUp("fast");
		$("#telecharger_contenu").slideUp("fast");
		$("#ranks").slideDown("fast");
	});
	
	$("#cons0").delay(100).fadeIn("slow");
	$("#cons1").delay(900).fadeIn("slow");
	$("#cons2").delay(1800).fadeIn("slow");
	$("#cons3").delay(3500).fadeIn("slow");
	$("#cons4").delay(5000).fadeIn("slow");
	$("#cons5").delay(7000).fadeIn("slow");
});