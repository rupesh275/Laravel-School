const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

// Placeholder for CodeIgniter Assets
// User should copy assets from CI backend/ folder to resources/ci_assets/
// Then, define how to process them here. Example:

// if (mix.inProduction()) {
//     mix.version();
// }

// Example: Combining all Bootstrap CSS (if not using a CDN version)
// mix.styles([
//     'resources/ci_assets/bootstrap/css/bootstrap.min.css',
//     // Add other core CSS files from CI theme here
// ], 'public/css/ci_theme_core.css');

// Example: Combining all core JS
// mix.scripts([
//     'resources/ci_assets/custom/jquery.min.js', // Already loaded via CDN in layout for now
//     'resources/ci_assets/bootstrap/js/bootstrap.min.js', // Already loaded via CDN
//     // Add other core JS files from CI theme here (e.g., app.min.js if it's AdminLTE's)
// ], 'public/js/ci_theme_core.js');

// Example: Copying plugin directories (if they don't need individual processing)
// mix.copyDirectory('resources/ci_assets/plugins', 'public/plugins');

// Example: Copying image assets
// mix.copyDirectory('resources/ci_assets/dist/img', 'public/images/ci_dist'); // Adjust paths as needed

// --- IMPORTANT ---
// The user will need to:
// 1. Copy assets from their CodeIgniter 'backend/' directory to 'resources/ci_assets/' (or a similar path).
// 2. Uncomment and adjust the examples above to correctly process and bundle their specific CI assets.
// 3. Update Blade layouts to use mix('css/ci_theme_core.css'), mix('js/ci_theme_core.js'), etc.
// 4. Run 'npm install && npm run build' locally.
