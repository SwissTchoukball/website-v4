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
            '$q', '$http', '$window',
            function($q, $http, $window) {
                var BackendService = function() {
                };

                var apiUrl = 'https://api.tchoukball.ch';
                if ($window.location.hostname === 'localhost') {
                    apiUrl = 'http://localhost:8082';
                }
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
                    }).then(function(response) {
                        deferred.resolve(response.data);
                    }, function(response) {
                        deferred.reject(response);
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
                    }).then(function(response) {
                        deferred.resolve(response);
                    }, function(response) {
                        deferred.reject(response);
                    });

                    return deferred.promise;
                };

                var sendDeleteRequest = function(url) {
                    var deferred = $q.defer();
                    var headers = {
                        'Accept-Language': lang
                    };
                    // Add Authorization headers if user is connected
                    if (basicAuth) {
                        headers['Authorization'] = basicAuth; // jshint ignore: line
                    }
                    $http.delete(apiUrl + url, {
                        headers: headers
                    }).then(function(response) {
                        deferred.resolve(response.data);
                    }, function(response) {
                        deferred.reject(response);
                    });

                    return deferred.promise;
                };

                /*--------------*
                 * GET requests *
                 *--------------*/

                BackendService.prototype.getClub = function(clubId) {
                    return sendGetRequest('/club/' + clubId);
                };

                BackendService.prototype.getClubMembers = function(clubId, query) {
                    return sendGetRequest('/club/' + clubId + '/members?query=' + query);
                };

                BackendService.prototype.getClubTeams = function(clubId) {
                    return sendGetRequest('/club/' + clubId + '/teams');
                };

                BackendService.prototype.getEditions = function() {
                    return sendGetRequest('/championship/editions');
                };

                BackendService.prototype.getOpenEditions = function() {
                    return sendGetRequest('/championship/editions?status=open');
                };

                BackendService.prototype.getTeams = function() {
                    return sendGetRequest('/championship/teams');
                };

                BackendService.prototype.getTeam = function(teamId) {
                    return sendGetRequest('/championship/team/' + teamId);
                };

                BackendService.prototype.getVenues = function(query) {
                    return sendGetRequest('/venues?query=' + query);
                };


                /*---------------*
                 * POST requests *
                 *---------------*/

                BackendService.prototype.postChampionshipTeamRegistration = function(registration) {
                    return sendPostRequest('/championship/register-team', registration);
                };

                BackendService.prototype.postChampionshipPlayersRegistration = function(registration) {
                    return sendPostRequest('/championship/register-players', registration);
                };


                /*---------------*
                 * DELETE requests *
                 *---------------*/

                BackendService.prototype.deleteChampionshipLicense = function(licenseId) {
                    return sendDeleteRequest('/championship/license/' + licenseId);
                };


                return new BackendService();
            }
        ]);
})();
