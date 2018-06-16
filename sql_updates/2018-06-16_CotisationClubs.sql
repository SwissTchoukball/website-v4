ALTER TABLE `Cotisations_Clubs` ADD `montantMembresActifs` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresActifs`;
ALTER TABLE `Cotisations_Clubs` ADD `montantMembresJuniors` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresJuniors`;
ALTER TABLE `Cotisations_Clubs` ADD `montantMembresSoutiens` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresSoutiens`;
ALTER TABLE `Cotisations_Clubs` ADD `montantMembresPassifs` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresPassifs`;
ALTER TABLE `Cotisations_Clubs` ADD `montantMembresVIP` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresVIP`;
ALTER TABLE `Cotisations_Clubs` ADD `nbMembresVIPOfferts` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresVIP`;
-- TODO: Manually update the content of nbMembresVIPOfferts
-- To do so, run the query below and take the min between nbAboMaxOffers and nbMembresVIP.
# SELECT id, floor(((nbMembresActifs + nbMembresJuniors) / 20) + 1) AS nbAboMaxOffers, nbMembresVIP, nbMembresVIPOfferts
# FROM Cotisations_Clubs
# WHERE annee >= 2015

UPDATE Cotisations_Clubs SET montantMembresSoutiens=10, montantMembresVIP=10;
UPDATE Cotisations_Clubs SET montantMembresActifs=30, montantMembresJuniors=20 WHERE annee>=2016;
UPDATE Cotisations_Clubs SET montantMembresActifs=25, montantMembresJuniors=15 WHERE annee<2016;