<?php

class Club
{
    public $id;
    public $shortName;
    public $fullName;
    public $sortName;
    public $address;
    public $npa;
    public $city;
    public $cantonId;
    public $phoneNumber;
    public $email;
    public $emailsOfficialComm;
    public $emailsTournamentComm;
    public $url;
    public $committeeComposition;
    public $coachJSID;
    public $statusId;
    public $president;
    public $lastEdit;
    public $lastEditorID;
    public $lastEditorName;

    public function __construct($rawClub)
    {
        $this->id = $rawClub['id'];
        $this->shortName = $rawClub['shortName'];
        $this->fullName = $rawClub['fullName'];
        $this->sortName = $rawClub['sortName'];
        $this->address = $rawClub['address'];
        $this->npa = $rawClub['npa'];
        $this->city = $rawClub['city'];
        $this->cantonId = $rawClub['cantonId'];
        $this->cantonName = $rawClub['cantonName'];
        $this->cantonAbbreviation = $rawClub['cantonAbbreviation'];
        $this->phoneNumber = $rawClub['phoneNumber'];
        $this->email = $rawClub['email'];
        $this->officialCommMailingList = $rawClub['officialCommMailingList'];
        $this->tournamentsCommMailingList = $rawClub['tournamentsCommMailingList'];
        $this->url = $rawClub['url'];
        $this->committeeComposition = $rawClub['committeeComposition'];
        $this->coachJSID = $rawClub['coachJSID'];
        $this->statusId = $rawClub['statusId'];
        $this->statusName = $rawClub['statusName'];
        $this->president = [
            'id' => $rawClub['presidentId'],
            'lastName' => $rawClub['presidentLastName'],
            'firstName' => $rawClub['presidentFirstName'],
            'address' => $rawClub['presidentAddress'],
            'npa' => $rawClub['presidentNpa'],
            'city' => $rawClub['presidentCity'],
            'phoneNumber' => $rawClub['presidentPhoneNumber'],
            'mobilePhoneNumber' => $rawClub['presidentMobilePhoneNumber'],
            'email' => $rawClub['presidentEmail']
        ];
        $this->lastEdit = $rawClub['lastEdit'];
        $this->lastEditorID = $rawClub['lastEditorID'];
        $this->lastEditorName = $rawClub['lastEditorName'];
    }

    public function isAffiliated() {
        return $this->statusId == 1 || $this->statusId == 2;
    }

    public function isNewClub() {
        return $this->id == null;
    }
}