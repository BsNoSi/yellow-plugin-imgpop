<?php
// imgPop extension for YELLOW-CMS, https://github.com/BsNoSi/yellow-extension-imgpop
// imgpop Copyright (c)2018-now Norbert Simon, http://www.nosi.de for
// YELLOW-CMS Copyright (c)2013-now Datenstrom, https://datenstrom.se
// This file may be used and distributed under the terms of the public license.
//requires YELLOW 0.8.13 or higher

class YellowImgPop
{
	const Version = "1.6.1";
	var $yellow;			//access to API
// Handle initialisation
	function onLoad($yellow) {
		$this->yellow = $yellow;
	}
// Handle page content parsing of custom block
	public function onParseContentShortcut($page, $name, $text, $type) {
		$output = NULL;
		if($name=="imgpop") {
			list($TheImage, $TheTitle, $TheID, $TheClass) = $this->yellow->toolbox->getTextArguments($text);
			
			if(empty($text)) {
				$output = '<b>[imgpop (image dir+)TheImage TheTitle TheID TheClass]</b>';  
			}
			else {
				$TheID = $TheID ? : pathinfo($TheImage)["filename"];
				$TheClass = (!$TheClass) ? $TheClass = ' class = "ipop"' : $TheClass = ' class="' . $TheClass . ' ipop"';
				$TheImage = $this->yellow->system->get("imageDir").$TheImage;
				
				if(empty($TheTitle)) {
					$check = $this->yellow->system->get("imageDir").pathinfo($TheImage)["filename"] . ".txt";
					if (file_exists($check)) {
						$TheTitle = file_get_contents($check);
					}
					else {
						$exif = @exif_read_data($TheImage, 'COMMENT',true,false);
						$TheTitle = utf8_encode($exif['COMMENT'][0]);
					}
				}
				if(empty($TheTitle)) $TheTitle = $this->yellow->text->get("imgpop_NoTitle");
				$tip = strip_tags($TheTitle,"<br>,<br/>");
				$output = '<div' . $TheClass . '>';
				$output .= '<a class="iboxx" href="#' . $TheID . '"><img src="/' . $TheImage . '" title="'.$this->yellow->text->get("imgpop_zoom").'" /><span class="imag">🔍</span></a>';
				$output .= '<a class="imgbox" id="' . $TheID . '"href="#_"><img src="/' . $TheImage . '"><span class="imgboxtitle">'.$tip.'</span></a></div>';
			}
		}
		return $output;
	}
		
// Handle page extra data
	public function onParsePageExtra($page, $name) {
		$output = null;
		if ($name=="footer") {
			$extensionLocation = $this->yellow->system->get("serverBase").$this->yellow->system->get("extensionLocation");
			$output = "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$extensionLocation}imgpop.css\" />\n";
		}
		return $output;
	}
}
?>
