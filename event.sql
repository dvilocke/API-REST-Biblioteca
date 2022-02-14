DROP EVENT IF EXISTS `eliminar tokens`; CREATE DEFINER=`root`@`localhost` EVENT `eliminar tokens` ON SCHEDULE EVERY 30 MINUTE STARTS '2022-02-14 09:05:16'
ENDS '2022-02-28 09:05:16' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM tokens WHERE tokens.generatedTime < CURRENT_TIMESTAMP - INTERVAL 30 MINUTE

