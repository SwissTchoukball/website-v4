/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

angular
    .module('swisstchoukball.website')
    .config(['$httpProvider', 'tagsInputConfigProvider',
        function($httpProvider, tagsInputConfigProvider) {
            'use strict';

            $httpProvider.interceptors.push('stHttpInterceptor');

            tagsInputConfigProvider.setDefaults('tagsInput', {
                placeholder: ''
            });
        }
    ]);


angular
    .module('swisstchoukball.website')
    .run(['amMoment',
        function(amMoment) {
            'use strict';

            amMoment.changeLocale(document.documentElement.lang);
        }
    ]);