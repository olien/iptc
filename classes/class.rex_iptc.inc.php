<?php

class rex_iptc {


  static public function set_media($params)
  {
    global $REX;
    $filename = $params['filename'];
    $filenamepath = $REX["INCLUDE_PATH"].'/../../files/'.$filename;
  
    $s = rex_sql::factory()->getArray('select * from ' . $REX['TABLE_PREFIX'] . 'file where filename="' . mysql_real_escape_string($filename) . '"');


	$dateiart = substr($filename, -3);

    if(count($s) == 1 AND ($dateiart == 'jpg' OR $dateiart == 'png')) {

      $file_id = $s[0]['file_id'];
      $author = rex_iptc::getImageInformations($filenamepath, 'author');
      $description = rex_iptc::getImageInformations($filenamepath, 'description');
  
      $u = rex_sql::factory();
      $u->setTable($REX['TABLE_PREFIX'] . 'file');
      $u->setWhere('file_id='.$file_id);
      $u->setValue('iptc_author', $author);
      $u->setValue('iptc_description', $description);
      $u->update();
	
    }


  }


  static public function getImageInformations($strFilename, $feld)  {
    $arrSize = getimagesize($strFilename,$arrInfo);
    $arrIPTC = iptcparse($arrInfo['APP13']);
    $arrReturn = array();
  
    $map = array(
    'author' 	=> '2#080',
    'description' => '2#120'
    );
  
  
    if (!isset($map[$feld]))
    return '';
    
    if (!isset($arrIPTC[$map[$feld]]))    
    return '';
    
    
    if (is_array($arrIPTC)) {
      $arrReturn = $arrIPTC[$map[$feld]][0];
      # $arrReturn['title']         = $arrIPTC['2#105'][0];
      # $arrReturn['description']   = $arrIPTC['2#120'][0];
      # $arrReturn['author']        = $arrIPTC['2#080'][0];
      # $arrReturn['keywords']      = $arrIPTC['2#025'][0];
      
    }
    
    return $arrReturn;
  }

  static public function show_form_info($params)
  {
      $vars = rex_sql::factory()->getArray('select * from rex_file where file_id='.$params["file_id"]);


	$dateiart = substr($vars[0][filename], -3);

	if ($dateiart == 'jpg' OR $dateiart == 'png') {

 echo "
 <script>
	 function showHide(shID) {
	    if (document.getElementById(shID)) {
	       if (document.getElementById(shID+'-show').style.display != 'none') {
	          document.getElementById(shID+'-show').style.display = 'none';
	          document.getElementById(shID).style.display = 'block';
	       }
	       else {
	          document.getElementById(shID+'-show').style.display = 'inline';
	          document.getElementById(shID).style.display = 'none';
	       }
	    }
	 }
 </script>
 ";
 
 echo '
	<style>
    /* This CSS is used for the Show/Hide functionality. */
      .more {
         display: none;
	 }
      a.showLink, a.hideLink {
         text-decoration: none;
         color: #0092C0;
        }

	#iptc .rex-form-row {
		background: #eee;
	}

   </style>
	 
   <div id="iptc-show" class="rex-form-row">
   <p class="rex-form-read">
   <label for="fwidth"></label>
	   <span id="fwidth" class="rex-form-read">
	   	<a href="#"  class="showLink" onclick="showHide(\'iptc\');return false;">IPTC Daten anzeigen</a>
	   </span>
   </p>
   </div>

  <div id="iptc" class="more">
   <div class="rex-form-row">
   <p class="rex-form-read">
      <label for="fwidth"></label>
		<span id="fwidth" class="rex-form-read">
		  <a href="#" id="iptc-hide" class="hideLink" onclick="showHide(\'iptc\');return false;">IPTC Daten verbergen</a>
	   </span>
   </p>
   ';
      foreach($vars[0] as $k => $v) {
          if (substr($k,0,5) == "iptc_") {
            echo '	  
            <p class="rex-form-read">
             <label for="fwidth">'.nl2br(htmlspecialchars($k)).'</label>
             <span id="fwidth" class="rex-form-read">'.nl2br(htmlspecialchars($v)).'</span>
             </p>
            ';
          }
      }
	  echo '</div></div>';
   }
  }
}

?>