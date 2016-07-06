/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

angular
    .module('swisstchoukball.championshipRegistration')
    .component('stChampionshipRegistrationList', {
        bindings: {
            clubId: '@stClubId'
        },
        controller: ['$q', 'backendService',
            function($q, backendService) {
                'use strict';

                var $ctrl = this;

                // TODO: Add possibility to see submitted registration (or simply to see team)
                // TODO: Add possibility to add players

                backendService.getClub($ctrl.clubId)
                    .then(function(club) {
                        $ctrl.club = club;
                    });

                backendService.getClubTeams($ctrl.clubId)
                    .then(function(teams) {
                        $ctrl.teams = teams;
                    });

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

                $ctrl.selectCategoryBySeason = function(categoryBySeason) {
                    $ctrl.selectedCategoryBySeason = categoryBySeason;
                };

                $ctrl.unselectCategoryBySeason = function() {
                    $ctrl.selectedCategoryBySeason = undefined;
                };

                $ctrl.sendRegistration = function(registration) {
                    return backendService.postChampionshipTeamRegistration(registration);
                };

            }],
        templateUrl: 'src/js/angular/championshipRegistration/championshipRegistrationList.tpl.html'
    });

