INSERT INTO contactes VALUES ('937654321','SEM');
INSERT INTO `test`.`llar` (`adreca`, `contrasenya`, `usuari`, `periodeConfirmacio`) VALUES ('C/ Balmes n60 2n 2a', 'test', 'CasaPuig', '100'), ('C/ General Tapioca', 'test', 'PisBarriAntic', '100');
INSERT INTO `test`.`resident` (`horaArribada`, `idRfid`, `nom`, `teCaigudaActivada`, `teTardancaActivada`, `teMancaActivada`, `tePermanenciaActivada`, `usuariLlar`) VALUES ('16:00:00', '1', 'Joan', '1', '1', '1', '1', 'CasaPuig'), ('14:00:00', '2', 'Albert', '1', '1', '1', '1', 'CasaPuig'), ('13:00:00', '3', 'Anna', '1', '1', '1', '1', 'PisBarriAntic');
INSERT INTO `test`.`cuidador` (`telefon`, `nom`, `usuariLlar`) VALUES ('696396666', 'Alex Soms Batalla', 'CasaPuig'), ('633768939', 'Carles Gonzalez Pimas', 'PisBarriAntic');
INSERT INTO `test`.`emergencia` (`moment`, `tipus`, `usuariLlar`, `idRfidResident`) VALUES ('2012-12-31 23:59:59', 'Incendi', 'CasaPuig', NULL), ('2013-01-01 08:35:21', 'Caiguda', NULL, '1'), ('2012-12-28 13:00:00', 'Tardanca', NULL, '3');
INSERT INTO `test`.`notificacio` (`id`, `idCuidador`, `momentEmergencia`, `confirmada`, `esPotConfirmar`) VALUES (NULL, '696396666', '2012-12-31 23:59:59', '1', '0'), (NULL, '696396666', '2013-01-01 08:35:21', '1', '0'), (NULL, '633768939', '2012-12-28 13:00:00', '1', '0');
INSERT INTO `test`.`cuidador` (`telefon`, `nom`, `usuariLlar`) VALUES ('639564565', 'Joel Slenderman', 'CasaPuig'), ('698543515', 'Daniela Fernandez Espinola', 'CasaPuig');
