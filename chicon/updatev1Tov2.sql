INSERT INTO `chicon_db`.`service_list` (`srvGlobalId`, `common_name`, `icon`, `description`, `exec_script`, `config_script`, `exec_freq`) VALUES (NULL, 'IFTTT RGB', 'css/images/ifttt_rgb_icon.png', 'IFTT RGB service. Light a RGB led group when an IFTTT event occured', 'srvScript/exec_ifttt_rgb.php', 'srvScript/config_ifttt_rgb.php', '1000');
INSERT INTO `chicon_db`.`led_service_list` (`id`, `id_led_service`, `id_service`, `led_type`, `common_name`, `description`) VALUES (NULL, '1', '6', '4', 'RGB Iftt', 'Light when a ifttt event occurs');

#INSERT INTO `chicon_db`.`service_list` (`srvGlobalId`, `common_name`, `icon`, `description`, `exec_script`, `config_script`, `exec_freq`) VALUES (NULL, 'IFTTT Binary', 'css/images/ifttt_binary_icon.png', 'IFTT Binary service. Light a On/off led group when an IFTTT event occured', 'srvScript/exec_ifttt_binary.php', 'srvScript/config_ifttt_binary.php', '1000');
#INSERT INTO `chicon_db`.`led_service_list` (`id`, `id_led_service`, `id_service`, `led_type`, `common_name`, `description`) VALUES (NULL, '1', '7', '1', 'Binary Iftt', 'Light on/off when a ifttt event occurs');

#INSERT INTO `chicon_db`.`service_list` (`srvGlobalId`, `common_name`, `icon`, `description`, `exec_script`, `config_script`, `exec_freq`) VALUES (NULL, 'IFTTT Blinking', 'css/images/ifttt_blinking_icon.png', 'IFTT Blinking service. Blink led group when an IFTTT event occured', 'srvScript/exec_ifttt_blink.php', 'srvScript/config_ifttt_blink.php', '1000');
#INSERT INTO `chicon_db`.`led_service_list` (`id`, `id_led_service`, `id_service`, `led_type`, `common_name`, `description`) VALUES (NULL, '1', '8', '8', 'Blinking Iftt', 'Blink when a ifttt event occurs');

#INSERT INTO `chicon_db`.`service_list` (`srvGlobalId`, `common_name`, `icon`, `description`, `exec_script`, `config_script`, `exec_freq`) VALUES (NULL, 'IFTTT Tricolor', 'css/images/ifttt_tricolor_icon.png', 'IFTT Tricolor service. Ligh Tricolor led group when an IFTTT event occured', 'srvScript/exec_ifttt_tricolor.php', 'srvScript/config_ifttt_tricolor.php', '1000');
#INSERT INTO `chicon_db`.`led_service_list` (`id`, `id_led_service`, `id_service`, `led_type`, `common_name`, `description`) VALUES (NULL, '1', '9', '2', 'Tricolor Iftt', 'Tricolor light when IFTTT event occurs');