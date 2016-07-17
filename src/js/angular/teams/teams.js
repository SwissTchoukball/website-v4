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

                backendService.getTeams()
                    .then(function(teams) {
                        $ctrl.teams = teams;
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