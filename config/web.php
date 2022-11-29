<?php

return [
	'version' => env('WEB_VERSION', '0.0.1'),
	'filesystem' => env('WEB_FILESYSTEM', 'public'),

	'force_https' => env('FORCE_HTTPS', false),

	'tnt' => [
		'refresh_on_query' => env('TNT_REFRESH_ON_QUERY', false),
	],
];