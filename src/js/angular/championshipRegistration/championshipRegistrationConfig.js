/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

angular
    .module('swisstchoukball.website')
    .config(['$httpProvider', 'tagsInputConfigProvider',
        function($httpProvider, tagsInputConfigProvider) {
            'use strict';

            tagsInputConfigProvider.setDefaults('tagsInput', {
                placeholder: ''
            });
        }
    ]);