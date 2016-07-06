/**
 * Handles the connection with the Swiss Tchoukball API
 *
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

/* globals stAuthdata */

(function() {
    'use strict';

    angular
        .module('swisstchoukball.backend', []);

    angular
        .module('swisstchoukball.backend')
        .factory('backendService', [
            '$q', '$http',
            function($q, $http) {
                var BackendService = function() {
                };

                var apiUrl = 'http://localhost:8082'; //TODO handle dev/production separation
                var lang = document.documentElement.lang;
                var basicAuth;

                if (stAuthdata && stAuthdata.length > 0) {
                    basicAuth = 'Basic ' + stAuthdata;
                }

                var sendGetRequest = function(url) {
                    var deferred = $q.defer();
                    var headers = {
                        'Accept-Language': lang
                    };
                    // Add Authorization headers if user is connected
                    if (basicAuth) {
                        headers['Authorization'] = basicAuth; // jshint ignore: line
                    }
                    $http.get(apiUrl + url, {
                        headers: headers
                    })
                        .success(function(data) {
                            deferred.resolve(data);
                        })
                        .error(function(data) {
                            deferred.reject(data);
                        });

                    return deferred.promise;
                };

                var sendPostRequest = function(url, payload) {
                    var deferred = $q.defer();
                    var headers = {
                        'Accept-Language': lang
                    };
                    // Add Authorization headers if user is connected
                    if (basicAuth) {
                        headers['Authorization'] = basicAuth; // jshint ignore: line
                    }
                    $http.post(apiUrl + url, payload, {
                        headers: headers
                    }).success(function(data) {
                        deferred.resolve(data);
                    }).error(function(data) {
                        deferred.reject(data);
                    });

                    return deferred.promise;
                };

                /*--------------*\
                |* GET requests *|
                \*--------------*/

                BackendService.prototype.getClub = function(idClub) {
                    return sendGetRequest('/club/' + idClub);
                };

                BackendService.prototype.getClubMembers = function(idClub, query) {
                    return sendGetRequest('/club/' + idClub + '/members?query=' + query);
                };

                BackendService.prototype.getClubTeams = function(idClub) {
                    return sendGetRequest('/club/' + idClub + '/teams');
                };

                BackendService.prototype.getOpenCategoriesBySeason = function() {
                    return sendGetRequest('/championship/categories-by-season');
                };

                BackendService.prototype.getVenues = function(query) {
                    return sendGetRequest('/venues?query=' + query);
                };


                /*---------------*\
                |* POST requests *|
                \*---------------*/

                BackendService.prototype.postChampionshipTeamRegistration = function(registration) {
                    return sendPostRequest('/championship/register-team', registration);
                };


                return new BackendService();
            }
        ]);
})();
