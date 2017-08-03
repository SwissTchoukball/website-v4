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

                $ctrl.hasNoAuthorization = false;

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

                /**
                 * Filters out the editions where a club is not allowed to register a team
                 *
                 * @param editions
                 * @returns {promise}
                 */
                var filterAllowedEditions = function(editions) {
                    return editions.filter(function(edition) {
                        if (!edition.category.isNbSpotLimitedByClub) {
                            // If there is no limitation in number of registrations
                            return true;
                        } else {
                            // If there is a limitation in number of registrations
                            // We get the number of spots that the club is allowed to take
                            var championshipSpotsInThisEdition = $ctrl.club.championshipSpots.find(function(category) {
                                return category.categoryId === edition.category.id;
                            });
                            var nbSpots = 0;
                            if (championshipSpotsInThisEdition) {
                                nbSpots = championshipSpotsInThisEdition.nbSpots;
                            }

                            // We get the number of teams the club has already registered
                            var teamsInThisEdition = $ctrl.teams.filter(function(team) {
                                return team.editionId === edition.id;
                            }).length;

                            if (nbSpots - teamsInThisEdition > 0) {
                                return true;
                            }
                        }
                        return false;
                    });
                };

                // We load the categories by season only when the club and its teams are loaded.
                $q.all([gotClub, gotClubTeams])
                    .then(function() {
                        backendService.getOpenEditions()
                            .then(function(openEditions) {
                                $ctrl.openEditions = openEditions;
                                // We take only the form where a club is allowed to register a team
                                $ctrl.openEditions = filterAllowedEditions($ctrl.openEditions);

                                //instantiating the date objects
                                $ctrl.openEditions = $ctrl.openEditions.map(function(edition) {
                                    edition.registrationDeadline = new Date(edition.registrationDeadline);
                                    edition.paymentDeadline = new Date(edition.paymentDeadline);
                                    return edition;
                                });
                            });
                    })
                    .catch(function(error) {
                        if (error.status === 403) {
                            $ctrl.hasNoAuthorization = true;
                        }
                    });

                // The four methods below are a BAD way of doing routing.
                $ctrl.selectEdition = function(edition) {
                    $ctrl.selectedEdition = edition;
                };

                $ctrl.unselectEdition = function() {
                    $ctrl.selectedEdition = undefined;
                    getClubTeams().then(function() {
                        $ctrl.openEditions = filterAllowedEditions($ctrl.openEditions);
                    });
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

