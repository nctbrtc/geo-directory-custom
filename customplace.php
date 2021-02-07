<?php
/*
Plugin Name: Airbnb 2 Wordpress
Description: Custom Coded Plugin by Necati B
Version: 1.0
Author: Necati B.
License: GNU
*/

add_action('admin_menu', 'airbnb');
function airbnb(){
 add_menu_page('Airbnb 2 Wordpress','Airbnb 2 Wordpress', 'manage_options', 'airbnb2wordpress', 'airbnb_2_wordpress','dashicons-car',4);
}


function airbnb_2_wordpress(){
 
function _uploadImageToMediaLibrary($postID, $url, $alt = "blabla") {

    require_once("../sites/$this->_wpFolder/wp-load.php");
    require_once("../sites/$this->_wpFolder/wp-admin/includes/image.php");
    require_once("../sites/$this->_wpFolder/wp-admin/includes/file.php");
    require_once("../sites/$this->_wpFolder/wp-admin/includes/media.php");

    $tmp = download_url( $url );
    $desc = $alt;
    $file_array = array();

    // Set variables for storage
    // fix file filename for query strings
    preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
    $file_array['name'] = basename($matches[0]);
    $file_array['tmp_name'] = $tmp;

    // If error storing temporarily, unlink
    if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']);
        $file_array['tmp_name'] = '';
    }

    // do the validation and storage stuff
    $id = media_handle_sideload( $file_array, $postID, $desc);

    // If error storing permanently, unlink
    if ( is_wp_error($id) ) {
        @unlink($file_array['tmp_name']);
        return $id;
    }

    return $id;
}	
	
	global $_POST;
    
    if(isset($_POST['guncelle'])){
        
        $post_title = ($_POST['post_title']);
		$content = ($_POST['content']);
		 $imgurl = ($_POST['imageUrl']);
         $cats = ($_POST['cats']);
		 $Location = ($_POST['Location']);
		
     $tags = $_POST['tags']; //load thread tags (custom tax) into array

$post = array( //our wp_insert_post args
		'post_title'	=> wp_strip_all_tags($post_title),
		'post_content'	=> $content,
		'post_category'	=> '9',
		'tax_input' 	=> array('thread_tag' => $tags),
		'post_status'	=> 'draft',
	'post_city'	=> 'Istanbul',
		'post_type' 	=> 'gd_place'
	);

	$post_id = wp_insert_post($post); //send our post, save the resulting ID
		
wp_set_object_terms( $post_id, $cats, 'gd_placecategory' );
wp_set_object_terms( $post_id, $tags, 'gd_place_tags' );

       
        
    }
   
    
        ?>


     <div style="display:flex">
		 
<div style="flex-basis: 75%; margin-right:20px; ">
	 
     <h1>Airbnb Automation </h1><form action="" method="post">
		<div><textarea  id="pasted" style="width:89%;" rows="2" placeholder="CTRL+V"></textarea> <a onclick="parse()" class="button button-primary button-large" style="width:10%;height: 45px;text-align: center;font-size: 18px;"> Parse</a></div>
		 
<label for="title">Title</label>
<input type="text" name="post_title" style="width:100%;margin-bottom:20px;" placeholder="Place Title" value="" id="title" spellcheck="true" autocomplete="off">

<label for="Location">Location</label>
<input type="text" name="Location" style="width:100%;margin-bottom:20px;" placeholder="Location" value="" id="Location" spellcheck="true" autocomplete="off">	
	
<label for="rooms">Rooms</label>
<input type="text" name="rooms" style="width:100%;margin-bottom:20px;" placeholder="Rooms" value="" id="Rooms" spellcheck="true" autocomplete="off">	
	
<label for="content">Description</label>
<textarea id="content" name="content" rows="15" style="width:100%;height:70%;" contenteditable></textarea>
	
<label for="url">Affiliation URL</label>
<input type="url" name="url" style="width:100%;margin-bottom:20px;" placeholder="URL" value="" id="url" spellcheck="true" autocomplete="off">

<label for="content">Image Urls</label>
<textarea id="imageUrl" name="imageUrl" rows="11" style="width:100%;height:70%;" contenteditable></textarea>
	
</div>
<div style="flex-basis: 22%; ">
		 
	
	<h2 class="hndle ui-sortable-handle">Tags / Amenities</h2>
	
	<textarea id="tags" name="tags" style="width:100%;height:100px;" ></textarea>
	<h2 class="hndle ui-sortable-handle">Category</h2>

	<select name="cats" id="cats">
 
 
				  <?php
		  $terms = get_terms(
			array(
				'taxonomy'   => 'gd_placecategory',
				'hide_empty' => false,
			)
		);
	 foreach($terms as $term) {
		echo ' <option value="'.$term->name.'">';
        echo $term->name;
		echo '</option>';
							}

?>
	</select>
	
	<h2 class="hndle ui-sortable-handle">Images</h2>
	
	<div class="imagess">

  
	</div>
	
 <input value="Save as a draft..." class="button" name="guncelle" type="submit">
</form>
 </div>

</div>  
<script>
	function parse() {
var pasted=document.querySelector("#pasted").value;

var purl=pasted.split('$url="').pop().split('"')[0];
	document.querySelector("#url").value=purl;
var title=pasted.split('$title="').pop().split('"')[0];
	document.querySelector("#title").value=title;
var loca=pasted.split('$location="').pop().split('"')[0];
	document.querySelector("#Location").value=loca;
		
var rooms=pasted.split('$rooms="').pop().split('"')[0];
	document.querySelector("#Rooms").value=rooms;

var amenities=pasted.split('$amenities="').pop().split('"')[0];

var img2=pasted.split('$img=').pop().split('"')[0];
	var imgs=img2.replace(/ ,/g, '\n');	
		imgs=imgs.replace(/ /g,'');	

		document.getElementById("imageUrl").value=imgs;
var allimg=img2.split(',');		
	document.getElementById("tags").value=amenities;

var longDesc=pasted.split('$longDesc="').pop().split('"')[0];
	document.querySelector("#content").value=longDesc;

/*		var imgList="<ul>"
for (i = 0; i < allimg.length; i++) {
 imgList+='<li> <input type="checkbox" id="myCheckbox'+i+'" /><label for="myCheckbox'+i+'"><img src="'+allimg[i]+'" /></label> </li><li>'
}		
		
imgList+='</ul>';		
		
document.getElementsByClassName('imagess')[0].innerHTML=imgList	;  
var content= "";
		content+='<b>Rooms : </b>'+rooms+'<br><b>Location : </b>'+loca+'<br><b>Description : </b>' +shortDesc+ '<br>' +purl+ longDesc;
document.querySelector("#content").value=content;		
		*/
		
}
</script>
<?php 
    
}

?>
