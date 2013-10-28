<?php

$u = rex_sql::factory();
$u->setQuery('ALTER TABLE `rex_file` DROP `iptc_author`, DROP `iptc_description`;');

$REX['ADDON']['install']['iptc'] = 0;

?>