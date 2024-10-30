function showResult(str, res_num)
{
	var div1 = document.getElementById("plugin_normal");
	var div2 = document.getElementById("plugin_livesearch");
if (str == "")
  { 
	div1.style.display = "block";
	div2.style.display = "none";
  } else {
	div1.style.display = "none";
	div2.style.display = "block";
  } 
  
  $('.ss').each(function(){
		$(this).load("http://lunar-horse.tv/de/wp-content/plugins/authors-widget/livesearch.php?s=" + str + "&n=" + res_num); 
  });
  
}
