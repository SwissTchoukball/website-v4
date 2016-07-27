/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

angular
    .module('swisstchoukball.website')
    .factory('stHttpInterceptor', function($q) {
        'use strict';
        return {
            'request': function(config) {
                // do something on success
                return config;
            },
            'requestError': function(rejection) {
                // do something on error
                return $q.reject(rejection);
            },
            'response': function(response) {
                // do something on success
                return response;
            },
            'responseError': function(rejection) {
                // do something on error
                // TODO: Show alert message to user
                return $q.reject(rejection);
            }
        };
    });