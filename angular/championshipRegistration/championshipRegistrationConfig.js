/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

angular
    .module('swisstchoukball.championshipRegistration')
    .config(['tagsInputConfigProvider',
        function(tagsInputConfigProvider) {
            'use strict';

            tagsInputConfigProvider.setDefaults('tagsInput', {
                placeholder: ''
            });
    }]);