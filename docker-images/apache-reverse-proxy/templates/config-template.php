<?php
	$staticApp = getenv('STATIC_APP');
	$dynamicApp = getenv('DYNAMIC_APP');
?>
<VirtualHost *:80>
	ServerName demo.res.ch

	ProxyPass '/api/animals/' 'http://<?php print "$dynamicApp"?>/'
	ProxyPassReverse '/api/animals/' 'http://<?php print "$dynamicApp"?>/'
	  
	ProxyPass '/' 'http://<?php print "$staticApp"?>/'
	ProxyPassReverse '/' 'http://<?php print "$staticApp"?>/'
</VirtualHost>