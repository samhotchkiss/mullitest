jQuery(function($) {
	
	// Make this a function to keep it DRY
	function reload_slimbox() {
		$("a[rel^='lightbox']").slimbox({}, null, function(el) {
			return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});
	
	}

	// Tale the raw JSON from the server and generate a Tweet box for it.  Same as on the PHP side.
	function new_twit(status) {
		
		//	Account for no Profile Pic.
		user_photo = ((status.user.profile_image_url !== 'undefined' && status.user.profile_image_url != '') ? status.user.profile_image_url : 'https://az31353.vo.msecnd.net/c04/nbpp.png');
		
		// Working copy of the Tweet text.
		status_text = status.text;
		
		// Replace Media URLs
		for (var i in status.entities.media) {
			status_text = status_text.replace(status.entities.media[i].url, '<a href="'+status.entities.media[i].media_url+'" class="twit_image"  target="_blank" rel="lightbox">'+status.entities.media[i].url+'</a>');
		}
		
		// Replace Link URLs
		for (var i in status.entities.urls) {
			status_text = status_text.replace(status.entities.urls[i].url, '<a href="'+status.entities.urls[i].url+'" class="twit_link"  target="_blank" >'+status.entities.urls[i].url+'</a>');
		}
		
		// Generate the HTML object.  Yes there are JS comands to do this, in this instance, this is clearer.
		twit = '<div class="tweet" id="t'+status.id_str+'" style="background-image:url('+status.user.profile_background_image_url+'); background-color:'+status.user.profile_background_color+';">';
		twit += '	<span class="content">';
		twit += '		<span class="user">';
		twit += '			<a href="http://www.twitter.com/'+status.user.screen_name+'" target="_blank"><img src="'+user_photo+'" /></a>';
		twit += '				'+status.user.screen_name;
		twit += '		</span>';
		twit += '		<span class="text">';
		twit += '			'+status_text;
		twit += '		</span>';
		twit += '		<span class="post_info">Posted on: '+status.created_at+' <a href="http://www.twitter.com/'+status.user.screen_name+'" target="_blank" onClick="loadBackground('+status.user.profile_background_image_url+')" ><img src="css/drop_box.gif" title="Load this user\' background image in this page."/></a></span>';
		twit += '</div>';
		
		// Retrun this new HTML object
		return $(twit);
	
	}
	
	function loadBackground($url) {
		if($('body').css('background-image') == 'url('+$url+')')
			$('body').css('background-image', '');
		else
			$('body').css('background-image', 'url('+$url+')');
	}
	
	// Reload every 10 seconds.
	setInterval(function() {
		$.post('index.php', {json:true}, function(status) {
			
			
			$twit = new_twit(status);			// Generate
			$twit.hide().prependTo('#twats');	// Make it hidden
			
			reload_slimbox();					// Reload lightbox for image links
			
			$twit.fadeIn(600);					// Fade in (AWESOME TRANSITION!!)
			
		}, "json");
	},10000);
	
	reload_slimbox();							// Reload lightbox for initial Tweet
	
});