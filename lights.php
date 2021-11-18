<?php
	function mapToRange($value, $in_min, $in_max, $out_min, $out_max ) {
		return round(($value - $in_min) * ($out_max - $out_min) / ($in_max - $in_min) + $out_min);
	}
	
	$nc = " | /bin/nc -v 192.168.1.6 8189 -q 0";
	
	foreach ($_REQUEST as $key => $value) {
		// echo "$key => $value\n";
		
		switch ($key) {
			case "status":
				$cmd = '/bin/echo -n -e "\x38\x00\x00\x00\x10\x83"'.$nc;
				exec($cmd, $output, $retval);
				// echo $output;
				print_r($output);
				break;
			case "power":
				// echo "Power: $value";
				$cmd = '/bin/echo -n -e "\x38\x00\x00\x00\xAA\x83"'.$nc;
				exec($cmd, $output, $retval);
				echo $retval;
				break;
			case "color":
				echo "Color: $value";
				sscanf($value, "#%02x%02x%02x", $r, $g, $b);
				$cmd = '/bin/echo -n -e "\x38\x'.sprintf("%02x", $r).'\x'.sprintf("%02x", $g).'\x'.sprintf("%02x", $b).'\x22\x83"'.$nc;
				exec($cmd, $output, $retval);
				echo $retval;
				break;
				
			case "mono":
				switch ($value) {
					case "breathing":
						// echo "Breathing";
						$cmd = "CE";
						break;
					case "stack":
						// echo "Stack";
						$cmd = "CF";
						break;
					case "meteor":
						// echo "Meteor";
						$cmd = "D0";
						break;
					case "wave":
						// echo "Wave";
						$cmd = "D1";
						break;
					case "flash":
						// echo "Flash";
						$cmd = "D2";
						break;
					case "static":
						// echo "Static";
						$cmd = "D3";
						break;
					case "catch":
						// echo "Catch";
						$cmd = "D4";
						break;
					default:
						// echo "Unknown mono-mode";
				}
				
				$cmd = '/bin/echo -n -e "\x38\x'.$cmd.'\x00\x00\x2C\x83"'.$nc;
				exec($cmd, $output, $retval);
				echo $retval;
				// echo "\n";
				break;
			case "brightness":
				// echo "Brightness: $value";
				$value = sprintf("%02x", mapToRange($value, 0, 100, hexdec("00"), hexdec("FF")));
				$cmd = '/bin/echo -n -e "\x38\x'.$value.'\x00\x00\x2A\x83"'.$nc;
				exec($cmd, $output, $retval);
				echo $retval;
				break;
			case "speed":
				// echo "Speed: $value";
				$value = sprintf("%02x", mapToRange($value, 0, 100, hexdec("00"), hexdec("FF")));
				$cmd = '/bin/echo -n -e "\x38\x'.$value.'\x00\x00\x03\x83"'.$nc;
				exec($cmd, $output, $retval);
				echo $retval;
				break;
			case "mode":
				switch ($value) {
					case "manual":
						echo "Manual";
						break;
					case "auto":
						// echo "Auto";
						$cmd = '/bin/echo -n -e "\x38\x00\x00\x00\x06\x83"'.$nc;
						exec($cmd, $output, $retval);
						echo $retval;
						break;
					default:
						// echo "1-180";
						$value = sprintf("%02x", mapToRange($value, 1, 180, hexdec("00"), hexdec("B3")));
						$cmd = '/bin/echo -n -e "\x38\x'.$value.'\x00\x00\x2c\x83"'.$nc;
						exec($cmd, $output, $retval);
						echo $retval;
				}
				break;
			default:
				// echo "Unknown";
		}
		// echo "\n";
	}
?>