<?
/**
 * Extension of Phirehose. Limits the output to 1 tweet containing actuall data (not a system message).
 *
 * Note: This is beta software - Please read the following carefully before using:
 *  - http://code.google.com/p/phirehose/wiki/Introduction
 *  - http://dev.twitter.com/pages/streaming_api
 * @author  Fenn Bailey <fenn.bailey@gmail.com>
 * @version 0.2.gitmaster
 */

class Twatter extends Phirehose {
	
	protected $template='';
	
	public function set_template($t) {									// Set the template to go to when we are ready.
		$this->template = getcwd().SEP.$t;
	}
	
	public function enqueueStatus($current) {
	
		$data = json_decode($current, true); 							// Response is in JSON, make it PHP friendly
	
		if (is_array($data) && isset($data['user']['screen_name'])) {	// Is this a status update or not?
			
			$this->disconnect();										// We got what we came for, stop receiving updates.

			if ($_POST['json']){										// If this is a JSON Request, just spew it out
				print_r($current);										// and don't go any further.
				die;													// die(), we're done here...
			}
			
			global $status;
			$status = json_decode($current, TRUE);						// Set our global $status to a REAL status.
			
			if(file_exists($this->template))							// If the template exists, load it up.
				include($this->template);					
			else
				die("Template count not be found at".$this->template);	// Else, die a horrible death.
			
		}

	}

}