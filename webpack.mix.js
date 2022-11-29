const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/* Directory shortcuts */
mix.webpackConfig({
	resolve: {
		alias: {
			Mixins: path.resolve(__dirname, 'resources/js/mixins'),
			Components: path.resolve(__dirname, 'resources/js/components'),
			Views: path.resolve(__dirname, 'resources/js/views')
		}
	}
});

// if (!mix.inProduction()) {
//     mix.sourceMaps();
// }


mix.sass('resources/sass/app.scss', 'public/assets/admin/app.css');
mix.sass('resources/sass/vendor.scss', 'public/assets/admin/vendor.css');

mix.sass('resources/sass/web/app.scss', 'public/assets/web/app.css');
mix.sass('resources/sass/web/vendor.scss', 'public/assets/web/vendor.css');


// Keep at the bottom
mix.js('resources/js/app.js', 'public/assets/app.js')
	.extract([
		'vue', 'vuejs-dialog', 'axios',
		'@fortawesome/fontawesome-free', 
		'toastr', 
		'bootstrap', 'admin-lte', 
		'flatpickr'
	]);

mix.version();

mix.disableNotifications();
