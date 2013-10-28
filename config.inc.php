<?php

$mypage = 'iptc';
$REX['ADDON']['version'][$mypage] = '0.1';
$REX['ADDON']['author'][$mypage] = 'Jan Kristinus, Oliver Kreischer';

if($REX['REDAXO'])
{
  include $REX["INCLUDE_PATH"]."/addons/iptc/classes/class.rex_iptc.inc.php";

  rex_register_extension('MEDIA_ADDED', 'rex_iptc::set_media');
  rex_register_extension('MEDIA_UPDATED', 'rex_iptc::set_media');
  rex_register_extension('MEDIA_FORM_EDIT', 'rex_iptc::show_form_info');
	  
}

?>