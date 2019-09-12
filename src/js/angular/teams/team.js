/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 *
 * TODO: use the Access control matrix to define what is visible or not
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
                $ctrl.unpaidLicensesCost = 0;

                $ctrl.updateUnpaidLicensesCost = function() {
                    $ctrl.unpaidLicensesCost = 0;
                    $ctrl.players.map(function(player) {
                        if(!player.licensePaymentDate) {
                            $ctrl.unpaidLicensesCost += $ctrl.team.edition.playerLicenseFee;
                        }
                    });
                };

                $ctrl.loadMembersForNewPlayers = function(query) {
                    return backendService.getClubMembers($ctrl.team.club.id, query)
                        .then(function(members) {
                            var deferred = $q.defer();

                            // Removing the members that are already in the team
                            // TODO: Also remove members that are already in another team of the same club
                            //       Hint: Could be a solution to have a field, in the data coming from backend, saying if the member is already member of a championship team this season
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
                        $ctrl.updateUnpaidLicensesCost();
                        $ctrl.sendingNewPlayers = false;
                    }, function() {
                        $ctrl.sendingNewPlayers = false;
                    });
                };

                $ctrl.updateUnpaidLicensesCost();

                $ctrl.removeLicense = function(player) {
                    if (window.confirm(
                        'Voulez-vous vraiment supprimer ' +
                        player.firstName + ' ' + player.lastName +
                        ' de votre équipe ?')) {
                        backendService.deleteChampionshipLicense(player.licenseId)
                            .then(function() {
                                $ctrl._removePlayer(player.id);
                                $ctrl.updateUnpaidLicensesCost();
                            }, function() {
                                window.alert('Erreur lors de la suppression du joueur');
                            });

                    }
                };

                $ctrl.canEditTeam = function() {
                    // Hacky way to hide the form. If the user cannot see the payment date, he cannot add edit the team
                    return $ctrl.team.feePaymentDate !== undefined;
                }

                $ctrl._removePlayer = function(playerId) {
                    $ctrl.players = $ctrl.players.filter(function(player) {
                        return player.id !== playerId;
                    });
                };

            }],
        templateUrl: 'src/js/angular/teams/team.tpl.html'
    });