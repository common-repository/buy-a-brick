<?php 
//version 1.1 of the plugin

//load the external XML file and fill arrays with parsed data
$count = 0;
$xml_ids = array();
$xml_widths = array();
$xml_heights = array();
$xml_tops = array();
$xml_lefts = array();
$xml_types = array();
$xml_products = array();
$xml_links = array();
$filename = get_option('buybrick_options');
$file = $filename['xmlpath'];
$image = $filename['imagepath'];
$offset = $filename['offset'];
$pbrickcolor = $filename['pbrickimg'];
$upbrickcolor = $filename['upbrickimg'];
$buttonimg = $filename['butimg'];
$width = $filename['imgwide'];
$height = $filename['imghigh'];
$totaltop = $filename['totposy'];
$totalleft = $filename['totposx'];

if (file_exists($_SERVER{'DOCUMENT_ROOT'}.$file)) {
		$xml = simplexml_load_file($_SERVER{'DOCUMENT_ROOT'}.$file);

   	foreach ($xml->item as $item) {
			if (isset($item['raised'])) {
				$xml_raised = $item['raised'];
			}
			$xml_ids[] = $item['id'];
			$xml_widths[] = $item['width'];
			$xml_heights[] = $item['height'];
			$xml_tops[] = $item['top'];
			$xml_lefts[] = $item['left'];
			$xml_types[] = $item['type'];
			$xml_products[] = $item['product_name'];
			$xml_links[] = $item['link_brick'];
		}// end foreach
		
		echo '<style type="text/css">'."\n".'ul#bricks {'."\n".'background: url('.$image.') no-repeat 0 0;'."\n".'width:'.$width.'px;'."\n".'height:'.$height.'px;}'."\n";
		echo 'ul#bricks li a:hover span {'."\n".'background: url('.$buttonimg.') no-repeat 0 0;'."\n".'}'."\n";
		echo 'ul#bricks li#total {'."\n".'left: '.$totalleft.'px; top: '.$totaltop.'px;}';
		//now create dynamic image map CSS
		foreach ($xml_ids as $key=>$val) {
			echo '#'.$val.' {';
			echo 'width: '.$xml_widths[$key].'px; ';
			echo 'height: '.$xml_heights[$key].'px; ';
			echo 'top: '.$xml_tops[$key].'px; ';
			echo 'left: '.$xml_lefts[$key].'px; ';
			if ($xml_types[$key] == 2) {
				echo 'background: url('.$image.') ';
				if ($xml_lefts[$key] == '0') {
					echo $xml_lefts[$key].'px -';
				}
				else {				
					echo '-'.$xml_lefts[$key].'px -';
				}
				echo ($height+$xml_tops[$key]).'px;';
				// border color if brick is purchased			
				echo 'border: 1px solid #'.$pbrickcolor.'; ';
				echo '}';
				echo "\n";		
				echo 'ul#bricks li#'.$val.' a:hover {';
				echo "\n";
				if ($xml_lefts[$key] == '0') {
					echo 'background-position: 0px -';
				}
				else {				
					echo 'background-position: -'.$xml_lefts[$key].'px -';
				}			
				echo ($height+$xml_tops[$key]).'px;';
				echo "\n";
				echo "}\n";
			}
			else {
				// border color if brick is unpurchased
				echo 'border: 1px solid #'.$upbrickcolor.'; ';
				echo '}';
				echo "\n";
			}
		}
		echo "</style>";
} // end if
else {
//oh drat, can't load the file!

echo '<div class="updated"><p><strong>Cannot open file</strong></p></div>'; 

	}



