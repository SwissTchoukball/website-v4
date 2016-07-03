/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

angular
    .module('swisstchoukball.championshipRegistration')
    .component('stChampionshipRegistrationForm', {
        bindings: {
            categoryBySeason: '<stCategoryBySeason',
            clubId: '@stClubId',
            goBack: '&stGoBack',
            sendRegistration: '&stSendRegistration'
        },
        controller: ['backendService',
            function(backendService) {
                'use strict';

                var $ctrl = this;

                $ctrl.homeVenue = [];
                $ctrl.teamManager = [];
                $ctrl.players = [];
                $ctrl.sending = false;

                backendService.getClub($ctrl.clubId)
                    .then(function(club) {
                        $ctrl.club = club;
                    });

                $ctrl.getTotalLicensesCost = function() {
                    return $ctrl.players.length * $ctrl.categoryBySeason.playerLicenseFee;
                };

                $ctrl.getTotalCost = function() {
                    return ($ctrl.players.length * $ctrl.categoryBySeason.playerLicenseFee) +
                        $ctrl.categoryBySeason.teamRegistrationFee;
                };

                $ctrl.loadVenues = function(query) {
                    //If there is already one home venue, we don't return any other suggestion
                    if($ctrl.homeVenue.length < 1) {
                        return backendService.getVenues(query);
                    }
                    return [];
                };

                $ctrl.loadMembersForTeamManager = function(query) {
                    //If there is already one team manager, we don't return any other suggestion
                    if($ctrl.teamManager.length < 1) {
                        return backendService.getClubMembers($ctrl.clubId, query);
                    }
                    return [];
                };

                $ctrl.loadMembersForPlayers = function(query) {
                    return backendService.getClubMembers($ctrl.clubId, query);
                };

                $ctrl.submitForm = function() {
                    $ctrl.sending = true;
                    $ctrl.sendRegistration({
                        'registration': {
                            'categoryBySeasonId': $ctrl.categoryBySeason.id,
                            'clubId': $ctrl.clubId,
                            'teamName': $ctrl.teamName,
                            'teamManagerId': $ctrl.teamManager[0].id,
                            'jerseyColorHome': $ctrl.jerseyColorHome,
                            'jerseyColorAway': $ctrl.jerseyColorAway,
                            'homeVenueId': $ctrl.homeVenue[0].id,
                            'playersId': $ctrl.players.map(function(player) {
                                return player.id;
                            })
                        }
                    }).then(function() {
                        $ctrl.goBack();
                        $ctrl.sending = false;
                    }, function() {
                        $ctrl.sending = false;
                    });

                };

            }],
        templateUrl: 'angular/championshipRegistration/championshipRegistrationForm.tpl.html'
    });

