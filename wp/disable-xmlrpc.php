<?php
add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', function($m){ unset($m['pingback.ping'], $m['pingback.extensions.getPingbacks']); return $m; });
