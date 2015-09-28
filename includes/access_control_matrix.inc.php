<?php

/* !Roles */

function isAdmin()
{
    return $_SESSION['__userLevel__'] == 0;
}

function isWebsiteDirector()
{
    return $_SESSION['__userLevel__'] == 0;
}

function isCommitteeMember()
{
    return false;
}

function isFinancesDirector()
{
    return false;
}

function isCommCompDirector()
{
    return false;
}

function isCommCompMember()
{
    return false;
}

function isChampionshipManager()
{
    return false;
}

function isCupManager()
{
    return false;
}

function isCommArbDirector()
{
    return false;
}

function isCommArbMember()
{
    return false;
}

function isRefereesManager()
{
    return false;
}

function isRefereePointsManager()
{
    return false;
}

function isTchoukupDirector()
{
    return false;
}

function isTchoukupStaff()
{
    return false;
}

function isTranslator()
{
    return false;
}

function isClubOfficial()
{
    return false;
}

function isClubMembersManager()
{
    return false;
}

function isTeamManager()
{
    return false;
}

function isReferee()
{
    return false;
}

function isSwissSquadCoach()
{
    return false;
}

function isSwissSquadPlayer()
{
    return false;
}

/* !Accesses */

function hasFinanceAccess()
{
    return $_SESSION['__userLevel__'] <= 5;
}
function hasRefereeManagementAccess()
{
    return $_SESSION['__userLevel__'] <= 7;
}
function hasAllMembersManagementAccess()
{
    return $_SESSION['__userLevel__'] <= 8;
}
