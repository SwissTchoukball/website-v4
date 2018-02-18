<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/includes/DB.class.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/includes/models/Club.class.php';

class ClubService
{
    public static function getClub($clubID) {
        $db = new DB();

        $db->bind('clubId', $clubID);

        $query = ClubService::generateGetClubQueryBeginning();
        $query .= " WHERE c.id = :clubId LIMIT 1";

        $club = $db->query($query);

        if (sizeof($club) > 0) {
            return new Club($club[0]);
        } else {
            return false;
        }
    }

    public static function getClubList($excludeNonMembers = true, $orderByCanton = false)
    {
        $queryPart_statusFiltering = "";
        if ($excludeNonMembers) {
            $queryPart_statusFiltering = "AND (c.statusId = 1 OR c.statusId = 2)";
        }

        $queryPart_orderBy = "ORDER BY ";
        if ($orderByCanton) {
            $queryPart_orderBy .= "cantonName, ";
        }
        $queryPart_orderBy .= "statusId, sortName";

        $query = ClubService::generateGetClubQueryBeginning();
        $query .= " WHERE c.statusId = cs.id
            {$queryPart_statusFiltering}
            {$queryPart_orderBy}";

        $db = new DB();

        return array_map(function($club){return new Club($club);}, $db->query($query));
    }

    public static function addClub($club) {
        $db = new DB();

        $db->bind('shortName', $club->shortName);
        $db->bind('fullName', $club->fullName);
        $db->bind('sortName', $club->sortName);
        $db->bind('address', $club->address);
        $db->bind('npa', $club->npa);
        $db->bind('city', $club->city);
        $db->bind('cantonId', $club->cantonId);
        $db->bind('phone', $club->phone);
        $db->bind('email', $club->email);
        $db->bind('emailsOfficialComm', $club->emailsOfficialComm);
        $db->bind('emailsTournamentComm', $club->emailsTournamentComm);
        $db->bind('url', $club->url);
        $db->bind('committeeComposition', $club->committeeComposition);
        $db->bind('coachJSID', $club->coachJSID);
        $db->bind('statusId', $club->statusId);
        $db->bind('lastEdit', date('Y-m-d'));
        $db->bind('lastEditorID', $_SESSION['__idUser__']);

        $query =
            "INSERT INTO `clubs`(`club`, `nomComplet`, `nomPourTri`, `adresse`, `npa`, `ville`, `canton`, `telephone`,
                    `email`, `emailsOfficialComm`, `emailsTournamentComm`, `url`, `committeeComposition`, `coachJSID`,
                    `statusId`, `lastEdit`, `lastEditorID`)
            VALUES (:shortName, :fullName, :sortName, :address, :npa, :city, :cantonId, :phone,
                    :email, :emailsOfficialComm, :emailsTournamentComm, :url, :committeeComposition, :coachJSID,
                    :statusId, :lastEdit, :lastEditorID)";

        $db->query($query);
        return $db->lastInsertId();
    }

    public static function editClub($club) {
        $db = new DB();

        $db->bind('id', $club->id);
        $db->bind('shortName', $club->shortName);
        $db->bind('fullName', $club->fullName);
        $db->bind('sortName', $club->sortName);
        $db->bind('address', $club->address);
        $db->bind('npa', $club->npa);
        $db->bind('city', $club->city);
        $db->bind('cantonId', $club->cantonId);
        $db->bind('phone', $club->phone);
        $db->bind('email', $club->email);
        $db->bind('emailsOfficialComm', $club->emailsOfficialComm);
        $db->bind('emailsTournamentComm', $club->emailsTournamentComm);
        $db->bind('url', $club->url);
        $db->bind('committeeComposition', $club->committeeComposition);
        $db->bind('coachJSID', $club->coachJSID);
        $db->bind('statusId', $club->statusId);
        $db->bind('lastEdit', date('Y-m-d'));
        $db->bind('lastEditorID', $_SESSION['__idUser__']);

        $query =
            "UPDATE clubs
            SET adresse = :address, npa = :npa, ville = :city, telephone = :phone, email = :email,
                emailsOfficialComm = :emailsOfficialComm, emailsTournamentComm = :emailsTournamentComm, url = :url,
                committeeComposition = :committeeComposition, coachJSID = :coachJSID,
                lastEdit = :lastEdit, lastEditorID = :lastEditorID";

        if ($_SESSION['__userLevel__'] <= 0) {
            $query .= ", club = :shortName, nomComplet = :fullName, nomPourTri = :sortName, canton = :cantonId, statusId = :statusId";
        }
        $query .= " WHERE id = :id";

        $db->query($query);
    }

    public static function getNumberOfClubs($excludeNonMembers = true) {
        $query = "SELECT COUNT(*) AS nbClubs FROM clubs c";
        if ($excludeNonMembers) {
            $query .= " WHERE (c.statusId = 1 OR c.statusId = 2)";
        }

        $db = new DB();

        return $db->query($query)[0]['nbClubs'];
    }

    public static function getClubsStats($orderBy) {
        $query = "SELECT
			c.club,
			c.nbIdClub AS id,
			COUNT(if(p.idStatus=3,1,NULL)) AS nbMembresActifs,
			COUNT(if(p.idStatus=6,1,NULL)) AS nbMembresJuniors,
			COUNT(if(p.idStatus=5,1,NULL)) AS nbMembresSoutiens,
			COUNT(if(p.idStatus=4,1,NULL)) AS nbMembresPassifs,
			COUNT(if(p.idStatus=23,1,NULL)) AS nbMembresVIP,
			COUNT(if(p.idStatus!=3 AND p.idStatus!=4 AND p.idStatus!=5 AND p.idStatus!=6 AND p.idStatus!=23,1,NULL)) AS nbMembresAutres,
			COUNT(p.idDbdPersonne) AS nbMembresTotal
		 FROM clubs c
		 LEFT OUTER JOIN DBDPersonne p ON c.nbIdClub = p.idClub
		 WHERE c.statusId = 1 OR c.statusId = 2
		 GROUP BY p.idClub ";

        switch ($orderBy) {
            case 'ID':
                $requeteNombreMembresParClub .= "ORDER BY id DESC";
            case 'club':
                $requeteNombreMembresParClub .= "ORDER BY club ASC";
            case 'actifs':
                $requeteNombreMembresParClub .= "ORDER BY nbMembresActifs DESC";
            case 'juniors':
                $requeteNombreMembresParClub .= "ORDER BY nbMembresJuniors DESC";
            case 'soutiens':
                $requeteNombreMembresParClub .= "ORDER BY nbMembresSoutiens DESC";
            case 'passifs':
                $requeteNombreMembresParClub .= "ORDER BY nbMembresPassifs DESC";
            case 'VIP':
                $requeteNombreMembresParClub .= "ORDER BY nbMembresVIP DESC";
            case 'autres':
                $requeteNombreMembresParClub .= "ORDER BY nbMembresAutres DESC";
            case 'total':
                $requeteNombreMembresParClub .= "ORDER BY nbMembresTotal DESC";
            default:
                $requeteNombreMembresParClub .= "ORDER BY c.nomPourTri ASC";
        }
        $requeteNombreMembresParClub .= ", club ASC";

        $db = new DB();
        return $db->query($query);
    }

    public static function getGenderDistribution() {
        $query =
            "SELECT
			COUNT(if(p.idStatus=3 && p.idSexe=2,1,NULL)) AS nbHActifs,
			COUNT(if(p.idStatus=3 && p.idSexe=3,1,NULL)) AS nbFActifs,
			COUNT(if(p.idStatus=3 && p.idSexe=1,1,NULL)) AS nbIActifs,
			COUNT(if(p.idStatus=6 && p.idSexe=2,1,NULL)) AS nbHJuniors,
			COUNT(if(p.idStatus=6 && p.idSexe=3,1,NULL)) AS nbFJuniors,
			COUNT(if(p.idStatus=6 && p.idSexe=1,1,NULL)) AS nbIJuniors
		 FROM DBDPersonne p, clubs c
		 WHERE (p.idStatus = 3 OR p.idStatus = 6)
		 	AND c.nbIdClub = p.idClub
		 	AND (c.statusId = 1 OR c.statusId = 2)";

        $db = new DB();
        return $db->query($query)[0];
    }

    public static function getClubFeeData($clubId, $startSeasonYear) {
        $db = new DB();

        $db->bind('clubId', $clubId);
        $db->bind('startSeasonYear', $startSeasonYear);

        $query = "SELECT c.club, c.nomComplet, cc.montant, cc.datePaiement, cc.nbMembresActifs, cc.nbMembresJuniors,
            cc.nbMembresSoutiens, cc.nbMembresPassifs, cc.nbMembresVIP, cc.nbMembresVIPOfferts, cc.montantMembresActifs,
            cc.montantMembresJuniors, cc.montantMembresSoutiens, cc.montantMembresPassifs, cc.montantMembresVIP
          FROM clubs c, Cotisations_Clubs cc
          WHERE c.id = :clubId
          AND c.nbIdClub = cc.idClub
          AND cc.annee = :startSeasonYear
          LIMIT 1";

        return $db->query($query)[0];
    }

    /**
     * Provide the fee state for all the clubs, for the seasons that start or end during last year.
     */
    public static function getClubsFeeState() {
        $db = new DB();

        $db->bind('yearThreshold', date('Y') - 2);

        $query = "SELECT Cotisations_Clubs.id, annee AS seasonStartYear, clubs.club AS clubName,
              Cotisations_Clubs.idClub AS clubId, montant AS amount, datePaiement AS paymentDate
			 FROM Cotisations_Clubs, clubs
			 WHERE Cotisations_Clubs.idClub=clubs.nbIdClub
			 AND annee >= :yearThreshold
			 ORDER BY annee DESC";

        return $db->query($query);
    }

    public static function markClubFeeAsPaid($clubId, $seasonStartYear, $paymentDate) {
        $db = new DB();

        $db->bind('clubId', $clubId);
        $db->bind('seasonStartYear', $seasonStartYear);
        $db->bind('paymentDate', $paymentDate);

        $query = "UPDATE Cotisations_Clubs
        SET datePaiement = :paymentDate
        WHERE annee = :seasonStartYear
        AND idClub = :clubId";

        $db->query($query);
    }

    public static function markClubFeeAsUnpaid($clubId, $seasonStartYear) {
        $db = new DB();

        $db->bind('clubId', $clubId);
        $db->bind('seasonStartYear', $seasonStartYear);

        $query = "UPDATE Cotisations_Clubs
        SET datePaiement = NULL
        WHERE annee = :seasonStartYear
        AND idClub = :clubId";

        $db->query($query);
    }

    public static function getClubsContactEmails() {
        $query = "SELECT c.club as name, c.emailsOfficialComm, c.emailsTournamentComm
            FROM clubs c
            WHERE c.statusId != 3";

        $db = new DB();

        return $db->query($query);
    }

    private static function generateGetClubQueryBeginning() {
        return "SELECT c.id, c.nbIdClub, c.club AS shortName, c.nomComplet AS fullName, c.nomPourTri AS sortName,
          c.statusId, cs.name{$_SESSION['__langue__']} AS statusName, cs.fixedFeeAmount,
          c.adresse AS address, c.npa, c.ville AS city,
          c.canton AS cantonId, ca.nomCanton{$_SESSION['__langue__']} AS cantonName, ca.sigle AS cantonAbbreviation,
          c.telephone AS phoneNumber, c.email, c.url, c.facebookUsername, c.twitterUsername, c.flickrUsername,
          c.emailsOfficialComm AS officialCommMailingList, c.emailsTournamentComm AS tournamentsCommMailingList,
          c.committeeComposition, c.coachJSID,
          
          c.idPresident AS presidentId, p.nom as presidentLastName, p.prenom AS presidentFirstName,
          p.adresse AS presidentAddress, p.npa AS presidentNpa, p.ville AS presidentCity,
          p.telPrive AS presidentPhoneNumber, p.portable AS presidentMobilePhoneNumber, p.email AS presidentEmail,
          
          lastEdit, lastEditorID, CONCAT(editor.prenom, editor.nom) AS lastEditorName
          
        FROM clubs_status cs, clubs c
        LEFT OUTER JOIN Canton ca ON c.canton = ca.id
        LEFT OUTER JOIN DBDPersonne p ON c.idPresident = p.idDbdPersonne
        LEFT OUTER JOIN Personne editor ON c.lastEditorID = editor.id";
    }
}