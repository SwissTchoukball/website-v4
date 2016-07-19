/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

angular
    .module('swisstchoukball.website')
    .component('stChampionshipRegistrationForm', {
        bindings: {
            edition: '<stEdition',
            club: '<stClub',
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
                $ctrl.submitted = false;

                $ctrl.getTotalLicensesCost = function() {
                    return $ctrl.players.length * $ctrl.edition.playerLicenseFee;
                };

                $ctrl.getTotalCost = function() {
                    return ($ctrl.players.length * $ctrl.edition.playerLicenseFee) +
                        $ctrl.edition.teamRegistrationFee;
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
                        return backendService.getClubMembers($ctrl.club.id, query);
                    }
                    return [];
                };

                $ctrl.loadMembersForPlayers = function(query) {
                    // TODO: filter out members already selected in other teams
                    // This is necessary only for league A and B. It would be better to have an intermediate
                    // grouping to do that.
                    return backendService.getClubMembers($ctrl.club.id, query);
                };

                $ctrl.submitForm = function() {
                    $ctrl.sending = true;
                    $ctrl.sendRegistration({
                        'registration': {
                            'editionId': $ctrl.edition.id,
                            'clubId': $ctrl.club.id,
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
                        $ctrl.sending = false;
                        $ctrl.submitted = true;
                    }, function() {
                        $ctrl.sending = false;
                    });
                };

            }],
        templateUrl: 'src/js/angular/championshipRegistration/championshipRegistrationForm.tpl.html'
    });

