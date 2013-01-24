<html>
<head>
<title>Twitter Update JS</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>	<? // jQuery, Obvs. ?>
<script type="text/javascript" src="js/slimbox2.js"></script>													<? // Make the nudie-pics look good ?>
<script type="text/javascript" src="js/twatbox.js"></script>													<? // The little bit of JS that makes the magic happen ?>
<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/twats.css" type="text/css" media="screen" />
</head>
<body>
	<h2>Recent Twits on the Twatter.</h2>
	<div id="twats">	<? // I am REALLY hoping you get the Colbert reference here...  Because, in hind sight, I ran with it... ?>
		<div class="tweet" id="t<? print $status['id_str'] ?>" style="background-image:<? 
			
			
			// Account to the unlikely event that a user has neither a background image or color set.
			if (isset($status['user']['profile_background_image_url']) && $status['user']['profile_background_image_url']!='')
				print "url(".$status['user']['profile_background_image_url'].")";	// If user background image set, doit.
			
			elseif (isset($status['user']['profile_background_color']) && $status['user']['profile_background_color']!='')
				print "url(".$status['user']['profile_background_color'].")";		// If user background color set, doit. (hey, you never know)
			
			else
				print "#FFF";
			
			?>">
			<span class="content">

				<span class="user">
					<a href="http://www.twitter.com/<? print $status['user']['screen_name'] ?>" target="_blank"><img src="<? 
				
				// The same as above, only for profile pictures.  Again, unlikely, but I just like to make sure.
				if (isset($status['user']['profile_image_url']) && $status['user']['profile_image_url']!='')
					print $status['user']['profile_image_url'];	// If user profile image set, doit.
				
				else
					print "https://az31353.vo.msecnd.net/c04/nbpp.png";
			
				?>" /></a>
					<? print $status['user']['screen_name'] ?>
				</span>
				
				<span class="text">
					<?
					
					// Replace Media and and Link URLS with active links.
					$status_text = $status['text'];
					if (is_array($status['entities']['media']))
						foreach ($status['entities']['media'] as $media)
							$status_text = str_replace($media['url'], "<a href='".$media['media_url']."' class='twit_image' target='_blank' rel='lightbox'>".$media['url']."</a>", $status_text);
	
					if (is_array($status['entities']['urls']))
						foreach ($status['entities']['urls'] as $link)
							$status_text = str_replace($link['url'], "<a href='".$link['url']."' class='twit_link' target='_blank' >".$link['url']."</a>", $status_text);
						
					print $status_text; ?>
					
				</span>
				<span class="post_info">Posted on: <? 
					print $status['created_at'] 	// I am going to say this once, here: 
													// I know this is in UTC.
													// Yes I could change it to local time.
													// That's not the point. ?> <a href="http://www.twitter.com/<? print $status['user']['screen_name'] ?>" target="_blank" onclick="loadBackground('<? print $status['user']['profile_background_image_url'] ?>'); return false;" ><img src="css/drop_box.gif" title="Load this user\' background image in this page."/></a></span>;

			</span>
		</div>
	</div>
</body>
</html>