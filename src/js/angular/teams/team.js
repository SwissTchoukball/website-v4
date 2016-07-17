/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

angular
    .module('swisstchoukball.website')
    .component('stTeam', {
        bindings: {
            team: '<stTeam',
            goBack: '&stGoBack'
        },
        controller: ['$q', 'backendService',
            function($q, backendService) {
                'use strict';

                var $ctrl = this;

                // List of the players shown to the user. Assigning a new variable so that it can be changed
                $ctrl.players = $ctrl.team.players;

                $ctrl.loadMembersForNewPlayers = function(query) {
                    return backendService.getClubMembers($ctrl.team.club.id, query)
                        .then(function(members) {
                            var deferred = $q.defer();

                            // Removing the members that are already in the team
                            deferred.resolve(members.filter(function(member) {
                                return !_.some($ctrl.team.players, {id: member.id});
                            }));

                            return deferred.promise;
                        });
                };

                $ctrl.submitNewPlayers = function() {
                    $ctrl.sendingNewPlayers = true;
                    backendService.postChampionshipPlayersRegistration({
                        'clubId': $ctrl.team.club.id, // We send the clubId for authorization
                        'teamId': $ctrl.team.id,
                        'playersId': $ctrl.newPlayers.map(function(player) {
                            return player.id;
                        })
                    }).then(function() {
                        $ctrl.players = $ctrl.players.concat($ctrl.newPlayers.map(function(player) {
                            player.licensePaymentDate = null;
                            return player;
                        }));
                        $ctrl.newPlayers = [];
                        $ctrl.sendingNewPlayers = false;
                    }, function() {
                        // TODO: handle error (show message to user)
                        $ctrl.sendingNewPlayers = false;
                    });
                };

            }],
        templateUrl: 'src/js/angular/teams/team.tpl.html'
    });