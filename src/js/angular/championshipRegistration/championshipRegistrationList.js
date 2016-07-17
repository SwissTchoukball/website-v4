/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 *
 * TODO: divide this componenent in several smaller ones
 */

angular
    .module('swisstchoukball.website')
    .component('stChampionshipRegistrationList', {
        bindings: {
            clubId: '@stClubId'
        },
        controller: ['$q', 'backendService',
            function($q, backendService) {
                'use strict';

                var $ctrl = this;

                var gotClub = backendService.getClub($ctrl.clubId)
                    .then(function(club) {
                        $ctrl.club = club;
                    });

                var getClubTeams = function() {
                    return backendService.getClubTeams($ctrl.clubId)
                        .then(function(teams) {
                            $ctrl.teams = teams;
                        });
                };

                var gotClubTeams = getClubTeams();

                // We load the categories by season only when the club and its teams are loaded.
                $q.all([gotClub, gotClubTeams]).then(function() {
                    backendService.getOpenCategoriesBySeason()
                        .then(function(openCategoriesBySeason) {
                            $ctrl.openCategoriesBySeason = openCategoriesBySeason;
                            // We take only the form where a club is allowed to register a team
                            $ctrl.openCategoriesBySeason = $ctrl.openCategoriesBySeason.filter(function(categoryBySeason) {
                                if (!categoryBySeason.category.isNbSpotLimitedByClub) {
                                    // If there is no limitation in number of registrations
                                    return true;
                                } else {
                                    // If there is a limitation in number of registrations
                                    // We get the number of spots that the club is allowed to take
                                    var championshipSpotsInThisCategoryBySeason = $ctrl.club.championshipSpots.find(function(category) {
                                        return category.categoryId === categoryBySeason.category.id;
                                    });
                                    var nbSpots = 0;
                                    if (championshipSpotsInThisCategoryBySeason) {
                                        nbSpots = championshipSpotsInThisCategoryBySeason.nbSpots;
                                    }

                                    // We get the number of teams the club has already registered
                                    var teamsInThisCategoryBySeason = $ctrl.teams.filter(function(team) {
                                        return team.categoryBySeasonId === categoryBySeason.id;
                                    }).length;

                                    if (nbSpots - teamsInThisCategoryBySeason > 0) {
                                        return true;
                                    }
                                }
                                return false;
                            });
                        });
                    });

                // The four methods below are a BAD way of doing routing.
                $ctrl.selectCategoryBySeason = function(categoryBySeason) {
                    $ctrl.selectedCategoryBySeason = categoryBySeason;
                };

                $ctrl.unselectCategoryBySeason = function() {
                    $ctrl.selectedCategoryBySeason = undefined;
                    getClubTeams();
                };

                $ctrl.selectTeam = function(teamId) {
                    backendService.getTeam(teamId)
                        .then(function(team) {
                            $ctrl.selectedTeam = team;
                        });
                };

                $ctrl.unselectTeam = function() {
                    $ctrl.selectedTeam = undefined;
                };

                $ctrl.sendRegistration = function(registration) {
                    return backendService.postChampionshipTeamRegistration(registration);
                };

            }],
        templateUrl: 'src/js/angular/championshipRegistration/championshipRegistrationList.tpl.html'
    });

