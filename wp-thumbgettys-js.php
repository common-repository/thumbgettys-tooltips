<?
	header('Content-Type: application/javascript');
	
	function get_thumbgettys($app_id, $domain) {
		return 'http://www.thumbgettys.com/main/v/' . $app_id . '/site/' . $domain .  '/?url=';
	}
	
print '
$(document).ready(function() {
  $("a").hover(function(e){
    $("#large").css("top",(e.pageY+5)+"px")
      .css("left",(e.pageX+5)+"px")					
      .html("<img src=' . get_thumbgettys($_REQUEST['app'], $_REQUEST['domain']) . '" + $(this).attr("href") + " alt />")
      .fadeIn("slow");
    }, function(){ $("#large").fadeOut("fast"); });
});	
';
?>