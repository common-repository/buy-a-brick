<?php

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
} // end if
else {
//oh drat, can't load the file!

echo '<div class="updated"><p><strong>Cannot open file</strong></p></div>'; 

}

echo '<div class="buyabrick"><ul id="bricks">';
//add links and text to hover states
foreach ($xml_ids as $key=>$val) {
	echo '<li id="'.$val.'"><a href="'.$xml_links[$key].'"><span>'.$xml_products[$key].'</span></a></li>';
	echo "\n";
}
echo '<li id="total">'.$xml_raised.'</li>'."\n";
echo "</ul></div>";