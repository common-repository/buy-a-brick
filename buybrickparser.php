<?php
// version 1.1 of the plugin
//loads the XML file from disk and converts to several arrays for displaying on Wordpress settings form
	$count = 0;
	$xml_ids = array();
	$xml_products = array();
	$xml_links = array();
	$xml_bricks = array();
	$xml_tops = array();
	$xml_lefts = array();
	$xml_widths = array();
	$xml_heights = array();
	$options = get_option('buybrick_options');
	$file = $options['xmlpath'];
	$numfields = explode(',',$options['numfields']);
	$fullpath = array();
	//make fullpath array from bricklink options fields
	foreach ($numfields as $key=>$val) {
		$index = $key + 1;
		$fullpath[$key] = $options['bricklink'.$index];
	}
	$imagespath = $options['imagespath'];
	$pbrickimg = $imagespath.$options['pbrickimg'];
	$upbrickimg = $imagespath.$options['upbrickimg'];
	if (isset($_POST['xmledited'])) {
	//here we reformat the form data into XML...
		$new_xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><bricks></bricks>");
		foreach ($_POST as $key=>$val) {
		
			if (strpos($key,"id_")!==false) {
				$item = $new_xml->AddChild('item');
				$item->AddAttribute('id',$val);
				$item->AddAttribute('product_name',$_POST['product_'.$count]);
				$item->AddAttribute('link_brick',$_POST['link_'.$count]);
				$item->AddAttribute('type',$_POST['type_'.$count]);
				$item->AddAttribute('top',$_POST['top_'.$count]);
				$item->AddAttribute('left',$_POST['left_'.$count]);
				$item->AddAttribute('width',$_POST['width_'.$count]);
				$item->AddAttribute('height',$_POST['height_'.$count]);				
				if ($count==0) {
					$item->AddAttribute('raised',$_POST['total']);
				}
				$count++;
			} // end if statement
		} // end foreach statement
		//aaaand, write to XML file in specified location
		$new_xml->asXML($_SERVER{'DOCUMENT_ROOT'}.$file);
		
?>
<div class="updated"><p><strong><?php _e('File saved.' ); ?></strong></p></div> 
<?php
	} // end xmledited if statement
	// if the form has not been submitted, load the file into arrays...

	if (file_exists($_SERVER{'DOCUMENT_ROOT'}.$file)) {
		$xml = simplexml_load_file($_SERVER{'DOCUMENT_ROOT'}.$file);
		// damn, file is malformed...		
		if (!$xml) {
    		echo "Failed loading XML\n";
    		foreach(libxml_get_errors() as $error) {
        		echo "\n", $error->message;
   		 }
		}
   	foreach ($xml->item as $item) {
			if (isset($item['raised'])) {
				$xml_raised = $item['raised'];
			}
			$xml_tops[] = $item['top'];
			$xml_lefts[] = $item['left'];
			$xml_widths[] = $item['width'];
			$xml_heights[] = $item['height'];
			$xml_ids[] = $item['id'];
			$xml_products[] = $item['product_name'];
			$xml_links[] = $item['link_brick'];
			$xml_bricks[] = $item['type'];
		}// end foreach
	} 
	else {
?>
    		<div class="updated"><p><strong><?php _e('Cannot open file.' ); ?></strong></p></div> 
<?php 
	}

	//now put into a nice form
	echo '<div class="wrap"><p><table><tr><td style="width: 15em;"><u>Brick #:</u></td><td style="width: 22em;"><u>Donor/Message:</u></td><td style="width: 9em;"><u>Brick Amount:</u></td><td style="width: 9em;"><u>Purchased?</u></td><td style="width: 5.5em;"><u>Top</u></td><td style="width: 5.5em;"><u>Left</u></td><td style="width: 5.5em;"><u>Width</u></td><td><u>Height</u></tr></table>';
	echo '<form method="post" action="';
	echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
	echo '">';
	echo '<input type="hidden" name="xmledited" value="Y" />';
	// this loop displays XML arrays as form inputs 
	foreach ($xml_ids as $key=>$val) {
		echo '<p><input class="ids" size="8" name="id_'.$key.'" value="'.$val.'" />';
		echo '<input class="products" size="40" name="product_'.$key.'" value="'.$xml_products[$key].'" />';
		echo '<select name="link_'.$key.'">'; 
		foreach ($fullpath as $index=>$value) {
			// if the full link to the brick purchase page from the file matches one of the paths stipulated in the settings...	
			if ($xml_links[$key] == $value) {
				//write our dropdown option of value of brick and make it selected
				echo '<option selected="selected" value="'.$value.'">$'.$numfields[$index].' Brick</option>';
			}
			else {
				// otherwise just write out dropdown option of value of brick
				echo '<option value="'.$value.'">$'.$numfields[$index].' Brick</option>';
			}
		}		
		echo '</select>';
		echo '<select name="type_'.$key.'">';		
		// if the full link to the purchased brick image matches the path stipulated in the settings
		if ($xml_bricks[$key] == 2) {
		// write out dropdown option that brick is purchased as the selected option
			echo '<option selected="selected" value="2">Purchased</option>';
			echo '<option value="1">Unpurchased</option>';
		}
		else {
			echo '<option selected="selected" value="1">Unpurchased</option>';
			echo '<option value="2">Purchased</option>';	
		}	
		echo '</select>';
		echo '<input class="tops" size="5" name="top_'.$key.'" value="'.$xml_tops[$key].'" />';
		echo '<input class="lefts" size="5" name="left_'.$key.'" value="'.$xml_lefts[$key].'" />';
		echo '<input class="widths" size="5" name="width_'.$key.'" value="'.$xml_widths[$key].'" />';	
		echo '<input class="heights" size="5" name="height_'.$key.'" value="'.$xml_heights[$key].'" /></p>';			
	} //end foreach statement 
		
	// Output the total raised so far on the last line of the form
	if (isset($xml_raised)) {
		echo '<p>Total Raised so far:  <input type="text" name="total" value="'.$xml_raised.'"></p>';
	}
	echo '<p class="submit"><input type="submit" name="Save Changes"></p>';
	echo '</form>';
	echo '</div>';
?>
