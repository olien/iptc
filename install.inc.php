<?php

$u = rex_sql::factory();
$u->setQuery('ALTER TABLE `rex_file` ADD `iptc_author` VARCHAR( 255 ) NOT NULL ,
ADD `iptc_description` TEXT NOT NULL;');

$REX['ADDON']['install']['iptc'] = true;

?>