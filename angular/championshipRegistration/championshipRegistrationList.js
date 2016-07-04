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
        controller: ['backendService',
            function(backendService) {
                'use strict';

                var $ctrl = this;

                // TODO: List the submitted registration
                // TODO: Add possibility to edit submitted registration
                // TODO: Hide access to the form of a category a club cannot register (anymore) (e.g. ligue A)

                backendService.getOpenCategoriesBySeason()
                    .then(function(openCategoriesBySeason) {
                        $ctrl.openCategoriesBySeason = openCategoriesBySeason;
                        $ctrl.openCategoriesBySeason = $ctrl.openCategoriesBySeason.map(function(categoryBySeason) {
                            categoryBySeason.seasonName = categoryBySeason.season + ' - ' + (categoryBySeason.season + 1);
                            return categoryBySeason;
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
        templateUrl: 'angular/championshipRegistration/championshipRegistrationList.tpl.html'
    });

