import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/assets/updatestyle/_updatestyle1.scss',
                'resources/assets/updatestyle/updatestyles.scss',
                'resources/assets/custom-theme/dark.scss',
                'resources/assets/custom-theme/sidemenu.scss',
                'resources/assets/custom-theme/skin-modes.scss',

                'resources/css/app.css',
                'resources/assets/custom-theme/custom/animated.css',


                'resources/assets/js/custom-summernote.js',
                'resources/assets/js/custom.js',
                'resources/assets/js/form-browser.js',
                'resources/assets/js/jquery.showmore.js',
                'resources/assets/js/select2.js',
                'resources/assets/js/turbolink.js',

                'resources/assets/js/support/support-admindash.js',
                'resources/assets/js/support/support-articles.js',
                'resources/assets/js/support/support-createticket.js',
                'resources/assets/js/support/support-customer.js',
                'resources/assets/js/support/support-landing.js',
                'resources/assets/js/support/support-sidemenu.js',
                'resources/assets/js/support/support-ticketview.js',

                // 'resources/assets/js/liveChat.js',
                // 'resources/assets/js/web-socket.js',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
              {
                src: (['resources/assets/images/', 'resources/assets/plugins/', 'resources/assets/js/openapi', 'resources/assets/sounds']),
                dest: 'assets/'
              }
            ]
          }),
        {
            name: 'blade',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            },
        }
    ],
});

