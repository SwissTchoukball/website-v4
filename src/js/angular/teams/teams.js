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

                backendService.getCategoriesBySeason()
                    .then(function(categoriesBySeason) {
                        $ctrl.categoriesBySeason = categoriesBySeason.map(function(categoryBySeason) {
                            categoryBySeason.teams = [];
                            return categoryBySeason;
                        });

                        backendService.getTeams()
                            .then(function(teams) {
                                // Distributing the teams in the matching categoryBySeason
                                teams.map(function(team) {
                                    var categoryBySeasonIndex = $ctrl.categoriesBySeason.findIndex(function(categoryBySeason) {
                                        return categoryBySeason.id === team.categoryBySeasonId;
                                    });
                                    $ctrl.categoriesBySeason[categoryBySeasonIndex].teams.push(team);
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