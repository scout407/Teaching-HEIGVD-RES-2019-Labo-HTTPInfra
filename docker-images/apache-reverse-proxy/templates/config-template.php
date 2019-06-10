<?php
	$dynamic_app1 = getenv('DYNAMIC_APP1');
	$dynamic_app2 = getenv('DYNAMIC_APP2');
	$dynamic_app3 = getenv('DYNAMIC_APP3');
	$static_app1 = getenv('STATIC_APP1');
	$static_app2 = getenv('STATIC_APP2');
	$static_app3 = getenv('STATIC_APP3');
?>
<VirtualHost *:80>
	ProxyRequests off
	ServerName demo.res.ch

	<Proxy balancer://express-cluster>
		BalancerMember 'http://<?php print "$dynamic_app1"?>'
		BalancerMember 'http://<?php print "$dynamic_app2"?>'
		BalancerMember 'http://<?php print "$dynamic_app3"?>'
	  
	Require all Granted
		ProxySet lbmethod=byrequests
	</Proxy>

	ProxyPass '/api/students/' 'balancer://express-cluster/'
	ProxyPassReverse '/api/students/' 'balancer://express-cluster/'



	<Proxy balancer://static-cluster>
		BalancerMember 'http://<?php print "$static_app1"?>'
		BalancerMember 'http://<?php print "$static_app1"?>'
		BalancerMember 'http://<?php print "$static_app1"?>'

	Require all Granted

	ProxySet lbmethod=byrequests

	</Proxy>
	ProxyPass  '/' 'balancer://static-cluster/'
	ProxyPassReverse '/' 'balancer://static-cluster/'
</VirtualHost>