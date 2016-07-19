/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

angular
    .module('swisstchoukball.website')
    .component('stTeams', {
        controller: ['$q', 'backendService',
            function($q, backendService) {
                'use strict';

                var $ctrl = this;

                backendService.getEditions()
                    .then(function(editions) {
                        $ctrl.editions = editions.map(function(edition) {
                            edition.teams = [];
                            return edition;
                        });

                        backendService.getTeams()
                            .then(function(teams) {
                                // Distributing the teams in the matching edition
                                teams.map(function(team) {
                                    var editionIndex = $ctrl.editions.findIndex(function(edition) {
                                        return edition.id === team.editionId;
                                    });
                                    $ctrl.editions[editionIndex].teams.push(team);
                                });
                            });
                    });

                $ctrl.selectTeam = function(teamId) {
                    backendService.getTeam(teamId)
                        .then(function(team) {
                            $ctrl.selectedTeam = team;
                        });
                };

                $ctrl.unselectTeam = function() {
                    $ctrl.selectedTeam = undefined;
                };

            }],
        templateUrl: 'src/js/angular/teams/teams.tpl.html'
    });