/**
 * @copyright   Swiss Tchoukball 2016
 * @author      David Sandoz <david.sandoz@tchoukball.ch>
 */

/* globals angular */

angular
    .module('st.championshipRegistrationForm')
    .component('stChampionshipRegistrationForm', {
        bindings: {

        },
        controller: ChampionshipRegistrationFormController,
        templateUrl: 'angular/championshipRegistrationForm/championshipRegistrationForm.tpl.html'
    });

function ChampionshipRegistrationFormController() {
    'use strict';
    
    var $ctrl = this;

    $ctrl.test = 'Hello world!';
}