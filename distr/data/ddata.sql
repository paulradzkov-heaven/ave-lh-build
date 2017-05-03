INSERT INTO %%PRFX%%_queries VALUES (1, 2, 100, 'Новостной запрос', '<li>[cpabrub:27][50] | <a href=\"[link]\">[cpabrub:10][150]</a> - <small>Просмотров: ([views]) | Комментариев: ([comments])</small></li>', '<ul>\r\n[content]\r\n</ul>', 'DokStart', 1, 1145447477, 'Выводит новости из рубрики 2', 'DESC', 0);#inst#

INSERT INTO %%PRFX%%_users VALUES (1, '%%PASS%%', '%%EMAIL%%', '%%STRASSE%%', '%%HNR%%', '%%PLZ%%', '%%ORT%%', '%%FON%%', '', '', '%%VORNAME%%', '%%NACHNAME%%', '%%USERNAME%%', 1, '', '%%ZEIT%%', 1, '%%ZEIT%%', 'ru', '', 0, 0, '0', '0', '%%PASS%%', '', 1);#inst#

INSERT INTO %%PRFX%%_user_groups VALUES (1, 'Администратор', 1, 0, '');#inst#
INSERT INTO %%PRFX%%_user_groups VALUES (2, 'Гость', 1, 0, '');#inst#
INSERT INTO %%PRFX%%_user_groups VALUES (3, 'Редактор', 1, 0, '');#inst#
INSERT INTO %%PRFX%%_user_groups VALUES (4, 'Пользователь', 1, 0, '');#inst#

INSERT INTO %%PRFX%%_user_permissions VALUES (1, 1, 'alles');#inst#
INSERT INTO %%PRFX%%_user_permissions VALUES (2, 2, '');#inst#
INSERT INTO %%PRFX%%_user_permissions VALUES (7, 8, '');#inst#
INSERT INTO %%PRFX%%_user_permissions VALUES (3, 3, 'adminpanel|docs|docs_comments|mediapool|mediapool_del');#inst#
INSERT INTO %%PRFX%%_user_permissions VALUES (4, 4, '');#inst#

INSERT INTO %%PRFX%%_documents VALUES (1, 1, '/', 'Главная', '', %%ZEIT%%, 0, %%ZEIT%%, 1, 1, 'Главная страница', '', 'index,follow', 1, 0, 0, 36, 0);#inst#
INSERT INTO %%PRFX%%_documents VALUES (2, 1, '/404/', '404 - Документ не найден', '', %%ZEIT%%, 0, %%ZEIT%%, 1, 0, '', '', 'noindex,nofollow', 1, 0, 0, 4, 0);#inst#
INSERT INTO %%PRFX%%_documents VALUES (3, 1, '/info/', 'О компании', '', %%ZEIT%%, 1776549600, %%ZEIT%%, 1, 1, 'О компании,контакты', '', 'index,follow', 1, 0, 0, 7, 0);#inst#
INSERT INTO %%PRFX%%_documents VALUES (4, 1, '/license/', 'Лицензионное соглашение', '', %%ZEIT%%, 1776549600, %%ZEIT%%, 1, 1, '', '', 'index,follow', 1, 0, 0, 5, 0);#inst#
INSERT INTO %%PRFX%%_documents VALUES (5, 1, '/contacts/', 'Контакты', '', %%ZEIT%%, 1776549600, %%ZEIT%%, 1, 1, '', '', 'index,follow', 1, 0, 0, 7, 0);#inst#
INSERT INTO %%PRFX%%_documents VALUES (6, 2, '/news/test/1/', 'Первая тестовая новость', '', %%ZEIT%%, 1776549600, %%ZEIT%%, 1, 1, 'Новость', '', 'index,follow', 1, 0, 0, 7, 5);#inst#
INSERT INTO %%PRFX%%_documents VALUES (7, 1, '/newsarhive/', 'Архив новостей', '', %%ZEIT%%, 1776549600, %%ZEIT%%, 1, 1, 'Архив новостей', '', 'index,follow', 1, 0, 0, 11, 0);#inst#
INSERT INTO %%PRFX%%_documents VALUES (8, 2, '/news/test/2/', 'Вторая тестовая новость', '', %%ZEIT%%, 1776549600, %%ZEIT%%, 1, 1, 'Новость 2,тестовая новость 2', '', 'index,follow', 1, 0, 0, 3, 5);#inst#
INSERT INTO %%PRFX%%_documents VALUES (9, 1, '/gallery/', 'Галерея изображений', '', %%ZEIT%%, 1778882400, %%ZEIT%%, 1, 1, 'Галерея,картинки,изображения', '', 'index,follow', 1, 0, 0, 29, 0);#inst#

INSERT INTO %%PRFX%%_document_fields VALUES (1, 1, 1, '<p>Установка системы прошла успешно!<br /></p>\r\n<p>Теперь Вы можете начать заполнять Ваши страницы информацией и создавать новые. Как вы могли заметить, мы создали для Вас несколько страниц - примеров.<br /> <br /> Если Вы авторизовались и владеете необходимыми правами, нажмите на левой стороне на &bdquo;Включить редактор&ldquo;, затем, нажав на значок редактирования, отредактируйте нужную страницу.<br /> <br /> Желаем приятной работы.</p>', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (40, 6, 6, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (53, 27, 6, '19.04.2006, 13:43', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (42, 23, 6, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (43, 24, 6, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (44, 25, 6, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (45, 26, 6, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (2, 2, 1, '/uploads/images/start.jpg', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (3, 1, 2, ' Извините, запрошенный Вами документ не найден.', 0);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (4, 2, 2, '', 0);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (7, 4, 1, 'Поздравляем!', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (8, 4, 2, '', 0);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (9, 13, 1, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (10, 13, 2, '', 0);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (11, 17, 2, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (12, 17, 1, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (13, 16, 1, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (14, 16, 2, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (15, 18, 1, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (16, 18, 2, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (39, 5, 6, 'Это тестовая новость. Она создана только для демонстрации возможностей и может быть отредактирована.', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (38, 10, 6, 'Тестовая новость', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (17, 4, 3, 'О компании', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (18, 13, 3, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (19, 1, 3, 'Внесите сюда информацию о вашей компании, фирме.', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (20, 2, 3, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (21, 17, 3, '%%USERNAME%%', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (22, 16, 3, '19.04.2006, 13:28', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (23, 18, 3, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (24, 4, 4, 'Наши условия заключения сделки', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (25, 13, 4, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (26, 1, 4, 'Пожайлуста, укажите здесь Ваши условия заключения сделки.', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (27, 2, 4, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (28, 17, 4, '%%USERNAME%%', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (29, 16, 4, '19.04.2006, 13:30', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (30, 18, 4, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (31, 4, 5, 'Обратная Связь', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (32, 13, 5, 'Задайте свой вопрос администрации сайта. Мы постараемся ответить Вам в ближайшее время.', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (33, 1, 5, '[cp_contact:1]', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (34, 2, 5, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (35, 17, 5, '%%USERNAME%%', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (36, 16, 5, '19.04.2006, 13:31', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (37, 18, 5, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (46, 4, 7, 'Архив новостей', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (47, 13, 7, 'Этот архив динамичен...', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (48, 1, 7, '[cprequest:1]', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (49, 2, 7, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (50, 17, 7, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (51, 16, 7, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (52, 18, 7, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (54, 10, 8, 'Тестовая новость 2', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (55, 5, 8, 'Тестовая новость 2', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (56, 6, 8, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (57, 23, 8, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (58, 24, 8, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (59, 25, 8, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (60, 26, 8, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (61, 27, 8, '19.04.2006, 13:59', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (77, 34, 4, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (78, 34, 5, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (79, 34, 7, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (75, 34, 2, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (76, 34, 3, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (74, 34, 1, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (80, 4, 9, 'Пример галереи', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (81, 13, 9, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (82, 1, 9, '[cp_gallery:1]', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (83, 2, 9, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (84, 17, 9, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (85, 16, 9, '16.05.2006, 14:30', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (86, 18, 9, '', 1);#inst#
INSERT INTO %%PRFX%%_document_fields VALUES (87, 34, 9, '', 1);#inst#

INSERT INTO %%PRFX%%_document_permissions VALUES (1, 1, 1, 'docread|alles|new|newnow|editown');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (2, 1, 3, 'docread|new|editown');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (3, 1, 4, 'docread');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (4, 2, 1, 'docread|alles|new|newnow|editown');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (5, 2, 3, 'docread|newnow|editown');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (6, 2, 4, 'docread');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (7, 3, 1, 'docread|alles|new|newnow|editown');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (8, 3, 3, 'docread|newnow|editown');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (9, 3, 4, 'docread');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (10, 1, 2, 'docread');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (11, 2, 2, 'docread');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (12, 3, 2, 'docread');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (13, 6, 1, 'docread|alles|new|newnow|editown');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (14, 6, 2, 'docread');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (15, 6, 3, 'docread|newnow|editown');#inst#
INSERT INTO %%PRFX%%_document_permissions VALUES (16, 6, 4, 'docread');#inst#


INSERT INTO %%PRFX%%_settings VALUES (1, 'Название Вашего сайта', 'mail', 'text/plain', 25, 'smtp.yourserver.ru', 'xxxxx', 'xxxxx', '/usr/sbin/sendmail', 50, '%%EMAIL%%', '%%USERNAME%%', 'Здравствуйте %NAME%,\r\nВаша регистрация на сайте %HOST%. \r\n\r\nТеперь Вы можете войти на %HOST% со следующими данными:: \r\n\r\nПароль: %KENNWORT%\r\nE-Mail: %EMAIL%\r\n\r\n-----------------------\r\n%EMAILFUSS%\r\n\r\n', '--------------------\r\nOverdoze Team\r\nwww.overdoze.ru\r\ninfo@overdoze.ru\r\n--------------------', 2, '<h2>Ошибка...</h2>\r\n<br />\r\nУ Вас нет прав на просмотр этого документа!.', 'Следующая страница &gt;&gt;', '&lt;&lt; Предыдущая страница', 'Страница:', 'd.m.Y, H:i', 'ru');#inst#

INSERT INTO %%PRFX%%_countries VALUES (1, 'AF', 'Афганистан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (2, 'AL', 'Албания', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (3, 'DZ', 'Алжир', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (4, 'AS', 'Американское Самоа', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (5, 'AD', 'Андорра', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (6, 'AO', 'Ангола', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (7, 'AI', 'Ангвилла', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (8, 'AQ', 'Антарктика', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (9, 'AG', 'Антигуа и Барбуда', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (10, 'AR', 'Аргентина', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (11, 'AM', 'Армения', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (12, 'AW', 'Аруба', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (13, 'AU', 'Австралия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (14, 'AT', 'Австрия', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (15, 'AZ', 'Азербайджан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (16, 'BS', 'Содружество Багамских островов', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (17, 'BH', 'Бахрейн', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (18, 'BD', 'Бангладеш', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (19, 'BB', 'Барбадос', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (20, 'BY', 'Белоруссия', 1, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (21, 'BE', 'Бельгия', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (22, 'BZ', 'Белиц', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (23, 'BJ', 'Бенин', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (24, 'BM', 'Бермудские острова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (25, 'BT', 'Бутан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (26, 'BO', 'Боливия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (27, 'BA', 'Босния и Герцеговина', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (28, 'BW', 'Ботсвана', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (29, 'BV', 'Остров Бювет', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (30, 'BR', 'Бразилия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (31, 'IO', 'Британские территории в Индийском океане', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (32, 'VG', 'Виргинские острова (Британия)', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (33, 'BN', 'Бруней Дарусаллам', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (34, 'BG', 'Болгария', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (35, 'BF', 'Буркина-Фасо', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (36, 'BI', 'Бурунди', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (37, 'KH', 'Камбоджа', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (38, 'CM', 'Камерун', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (39, 'CA', 'Канада', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (40, 'CV', 'Кейп-Верд', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (41, 'KY', 'Кайманские острова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (42, 'CF', 'Центральная Африканская Республика', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (43, 'TD', 'Чад', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (44, 'CL', 'Чили', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (45, 'CN', 'Китай', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (46, 'CX', 'Рождественские острова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (47, 'CC', 'Кокосовые острова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (48, 'CO', 'Колумбия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (49, 'KM', 'Коморос', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (50, 'CG', 'Конго', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (51, 'CK', 'Острова Кука', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (52, 'CR', 'Коста-Рика', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (53, 'CI', 'Кот-д Ивуар (Берег Слоновой Кости)', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (54, 'HR', 'Хорватия', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (55, 'CU', 'Куба', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (56, 'CY', 'Кипр', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (57, 'CZ', 'Чешская Республика', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (58, 'DK', 'Дания', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (59, 'DJ', 'Джибути', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (60, 'DM', 'Доминика', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (61, 'DO', 'Доминиканская Республика', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (62, 'TP', 'Восточный Тимор', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (63, 'EC', 'Эквадор', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (64, 'EG', 'Египет', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (65, 'SV', 'Эль-Сальвадор', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (66, 'GQ', 'Экваториальная Гвинея', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (67, 'ER', 'Эритрея', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (68, 'EE', 'Эстония', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (69, 'ET', 'Эфиопия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (70, 'FK', 'Фолклендские острова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (71, 'FO', 'Острова Фаро', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (72, 'FJ', 'Фиджи', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (73, 'FI', 'Финляндия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (74, 'FR', 'Франция', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (75, 'FX', 'Франция, Столица', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (76, 'GF', 'Французская Гвиана', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (77, 'PF', 'Французская Полинезия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (78, 'TF', 'Французские южные территории', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (79, 'GA', 'Габон', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (80, 'GM', 'Гамбия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (81, 'GE', 'Грузия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (82, 'DE', 'Германия', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (83, 'GH', 'Гана', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (84, 'GI', 'Гибралтар', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (85, 'GR', 'Греция', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (86, 'GL', 'Гренландия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (87, 'GD', 'Гренада', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (88, 'GP', 'Гваделупа', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (89, 'GU', 'Гуам', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (90, 'GT', 'Гватемала', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (91, 'GN', 'Гвинея', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (92, 'GW', 'Гвинея-Биссау', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (93, 'GY', 'Гайана', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (94, 'HT', 'Гаити', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (95, 'HM', 'Острова Хирт и Макдоналдс', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (96, 'HN', 'Гондурас', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (97, 'HK', 'Гонгконг', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (98, 'HU', 'Венгрия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (99, 'IS', 'Исландия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (100, 'IN', 'Индия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (101, 'ID', 'Индонезия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (102, 'IQ', 'Ирак', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (103, 'IE', 'Ирландия', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (104, 'IR', 'Иран', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (105, 'IL', 'Израиль', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (106, 'IT', 'Италия', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (107, 'JM', 'Ямайка', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (108, 'JP', 'Япония', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (109, 'JO', 'Иордан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (110, 'KZ', 'Казахстан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (111, 'KE', 'Кения', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (112, 'KI', 'Кирибати', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (113, 'KP', 'КНДР', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (114, 'KR', 'Республика Корея', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (115, 'KW', 'Кувейт', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (116, 'KG', 'Киргизстан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (117, 'LA', 'Лаосская НДР', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (118, 'LV', 'Латвия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (119, 'LB', 'Ливан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (120, 'LS', 'Лесото', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (121, 'LR', 'Либерия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (122, 'LY', 'Ливийская Арабская Джамахерия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (123, 'LI', 'Лихтенштейн', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (124, 'LT', 'Литва', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (125, 'LU', 'Люксембург', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (126, 'MO', 'Макао', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (127, 'MK', 'Македония', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (128, 'MG', 'Мадагаскар', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (129, 'MW', 'Малави', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (130, 'MY', 'Малайзия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (131, 'MV', 'Мальдивы', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (132, 'ML', 'Мали', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (133, 'MT', 'Мальта', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (134, 'MH', 'Маршалловы острова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (135, 'MQ', 'Мартиника', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (136, 'MR', 'Мавритания', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (137, 'MU', 'Маврикий', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (138, 'YT', 'Майотта', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (139, 'MX', 'Мексика', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (140, 'FM', 'Микронезия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (141, 'MD', 'Молдова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (142, 'MC', 'Монако', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (143, 'MN', 'Монголия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (144, 'MS', 'Монтсеррат', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (145, 'MA', 'Марокко', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (146, 'MZ', 'Мозамбик', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (147, 'MM', 'Мьянма', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (148, 'NA', 'Намибия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (149, 'NR', 'Науру', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (150, 'NP', 'Непал', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (151, 'NL', 'Нидерланды', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (152, 'AN', 'Антильские острова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (153, 'NC', 'Новая Каледония', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (154, 'NZ', 'Новая Зеландия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (155, 'NI', 'Никарагуа', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (156, 'NE', 'Нигер', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (157, 'NG', 'Нигерия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (158, 'NU', 'Нию', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (159, 'NF', 'Остров Норфолк', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (160, 'MP', 'Остров Северной марины', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (161, 'NO', 'Норвегия', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (162, 'OM', 'Оман', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (163, 'PK', 'Пакистан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (164, 'PW', 'Палау', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (165, 'PA', 'Панама', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (166, 'PG', 'Папуа-Новая Гвинея', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (167, 'PY', 'Парагвай', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (168, 'PE', 'Перу', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (169, 'PH', 'Филипинны', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (170, 'PN', 'Остров Питкаирн', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (171, 'PL', 'Польша', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (172, 'PT', 'Португалия', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (173, 'PR', 'Пуэрто-Рико', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (174, 'QA', 'Катар', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (175, 'RE', 'Остров Воссоединения', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (176, 'RO', 'Румыния', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (177, 'RU', 'Россия', 1, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (178, 'RW', 'Руанда', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (179, 'LC', 'Остров Святого Луки', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (180, 'WS', 'Самоа', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (181, 'SM', 'Сан-Марино', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (182, 'ST', 'Сан-Томе и Принсипи', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (183, 'SA', 'Саудовская Аравия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (184, 'SN', 'Сенегал', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (185, 'SC', 'Сейшельские Острова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (186, 'SL', 'Сьерра Леоне', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (187, 'SG', 'Сингапур', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (188, 'SK', 'Словацкая Республика', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (189, 'SI', 'Словения', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (190, 'SB', 'Соломоновы Острова', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (191, 'SO', 'Сомали', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (192, 'ZA', 'Южная Африка', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (193, 'ES', 'Испания', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (194, 'LK', 'Шри-Ланка', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (195, 'SH', 'Остров Святой Елены', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (196, 'KN', 'Сент-Кикс и Невис', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (197, 'PM', 'Остров Святого Петра', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (198, 'VC', 'Сент-Винсент и Гренадины', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (199, 'SD', 'Cудан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (200, 'SR', 'Суринам', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (201, 'SJ', 'Острова Свалбард и Жан-Мейен', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (202, 'SZ', 'Свазиленд', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (203, 'SE', 'Швеция', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (204, 'CH', 'Швейцария', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (205, 'SY', 'Сирийская Арабская Республика', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (206, 'TW', 'Тайвань', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (207, 'TJ', 'Таджикистан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (208, 'TZ', 'Танзания', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (209, 'TH', 'Таиланд', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (210, 'TG', 'Того', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (211, 'TK', 'Токелау', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (212, 'TO', 'Тонга', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (213, 'TT', 'Тринидад и Тобаго', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (214, 'TN', 'Тунис', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (215, 'TR', 'Турция', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (216, 'TM', 'Туркменистан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (217, 'TC', 'Острова Теркс и Кайкос', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (218, 'TV', 'Тувалу', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (219, 'UG', 'Уганда', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (220, 'UA', 'Украина', 1, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (221, 'AE', 'Объединённые Арабские Эмираты', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (222, 'GB', 'Великобритания', 2, 1);#inst#
INSERT INTO %%PRFX%%_countries VALUES (223, 'US', 'Соединённые Штаты Америки', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (224, 'VI', 'Виргинские острова (США)', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (225, 'UY', 'Уругвай', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (226, 'UZ', 'Узбекистан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (227, 'VU', 'Вануату', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (228, 'VA', 'Ватикан', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (229, 'VE', 'Венесуэла', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (230, 'VN', 'Вьетнам', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (231, 'WF', 'Острова Уэльс и Фортуны', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (232, 'EH', 'Западная Сахара', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (233, 'YE', 'Йемен', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (234, 'YU', 'Югославия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (235, 'ZR', 'Заир', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (236, 'ZM', 'Замбия', 2, 2);#inst#
INSERT INTO %%PRFX%%_countries VALUES (237, 'ZW', 'Зимбабве', 2, 2);#inst#

INSERT INTO %%PRFX%%_modul_banners VALUES (1, 1, 'banner.jpg', 'http://www.overdoze.ru', 1, 'Overdoze-Banner', 235, 2, 'Скрипты CMS, бесплатные шаблоны, форум и поддержка разработчиков', 0, 0, 0, 0, 1, '_self', 0, 0);#inst#
INSERT INTO %%PRFX%%_modul_banners VALUES (2, 1, 'banner2.gif', 'http://www.google.ru', 1, 'Google-Banner', 0, 0, 'Посетите сайт Google', 0, 0, 0, 0, 1, '_blank', 0, 0);#inst#


INSERT INTO %%PRFX%%_modul_banner_categories VALUES (1, 'Категория 1');#inst#
INSERT INTO %%PRFX%%_modul_banner_categories VALUES (2, 'Категория 2');#inst#

INSERT INTO %%PRFX%%_modul_counter VALUES (1, 'Счетчик (все)');#inst#
INSERT INTO %%PRFX%%_modul_counter VALUES (2, 'Счетчик 2 (дополнитеьный)');#inst#

INSERT INTO %%PRFX%%_modul_contacts VALUES (1, 'Обратная Связь', 20000, '%%EMAIL%%', '', 1, 120,'1','','1,2,3,4,5,6','1','У Вас недостаточно прав для использования этой формы.');#inst#

INSERT INTO %%PRFX%%_modul_contact_fields VALUES (1, 1, 'fileupload', 4, 'Прикрепить файл', 0, '', 1);#inst#
INSERT INTO %%PRFX%%_modul_contact_fields VALUES (2, 1, 'fileupload', 3, 'Прикрепить файл', 0, '', 1);#inst#
INSERT INTO %%PRFX%%_modul_contact_fields VALUES (3, 1, 'textfield', 1, 'Сообщение', 1, '', 1);#inst#
INSERT INTO %%PRFX%%_modul_contact_fields VALUES (4, 1, 'dropdown', 2, 'Как Вы оцените наш сайт?', 0, 'Плохо,Средне,Супер', 1);#inst#

INSERT INTO %%PRFX%%_modul_login VALUES (1, 'email', 1, 1, 'denieddomain.ru', 'deniedemail@domain.ru',0,0,0);#inst#

INSERT INTO %%PRFX%%_modul_search VALUES (1, 'Новости', 2, 0);#inst#
INSERT INTO %%PRFX%%_modul_search VALUES (2, 'Картинка', 3, 1);#inst#
INSERT INTO %%PRFX%%_modul_search VALUES (3, 'Контакты', 1, 1);#inst#
INSERT INTO %%PRFX%%_modul_search VALUES (4, 'Компания', 1, 1);#inst#

INSERT INTO %%PRFX%%_module VALUES (1, 'Карта сайта', '1', '\\\[cp:sitemap\\\]', '<?php cpSitemap(); ?>', 'cpSitemap', '1', 'sitemap', '1.0', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (2, 'Баннер', '1', '\\\[cp_banner:([0-9\\\]*)\]', '<?php cpBanner(''\\\\\1''); ?>', 'cpBanner', '1', 'banner', '1.01', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (3, 'Быстрый переход', '1', '\\\[cp:quickfinder\\\]', '<?php cpQuickfinder(); ?>', 'cpQuickfinder', '1', 'quickfinder', '1.0', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (4, 'Навигация', '1', '\\\[cp_navi:([0-9\\\]*)\]', '<?php cp_navi(''\\\\\1''); ?>', 'cp_navi', '1', 'navigation', '1.1', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (5, 'Поиск', '1', '\\\[cp:search\\\]', '<?php cpSearch(); ?>', 'cpSearch', '1', 'search', '1.0', 1);#inst#
INSERT INTO %%PRFX%%_module VALUES (6, 'Контакты', '1', '\\\[cp_contact:([0-9\\\]*)\]', '<?php cpContact(''\\\\\1''); ?>', 'cpContact', '1', 'contact', '2.1', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (7, 'Статистика', '1', '\\\[cp_counter:([-a-zA-Z0-9\\\]*)\]', '<?php cpCounter(''\\\\\1''); ?>', 'cpCounter', '1', 'counter', '1.1', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (8, 'Авторизация', '1', '\\\[cp:loginform\\\]', '<?php cpLogin(); ?>', 'cpLogin', '1', 'login', '2.1', 1);#inst#
INSERT INTO %%PRFX%%_module VALUES (9, 'Галерея', '1', '\\\[cp_gallery:([-0-9\\\]*)\]', '<?php cpGallery(''\\\\\1''); ?>', 'cpGallery', '1', 'gallery', '2.0.01', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (10, 'Рекомендовать', '1', '\\\[cp:recommend\\\]', '<?php cpRecommend(); ?>', 'cpRecommend', '1', 'recommend', '1.0', 1);#inst#
INSERT INTO %%PRFX%%_module VALUES (11, 'Комментарии', '1', '\\\[cp:comment\\\]', '<?php cpComment(); ?>', 'cpComment', '1', 'comment', '1.0', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (12, 'Популярные новости', '1', '\\\[cp:top_news\\\]', '<?php topNews(); ?>', 'topNews', '1', 'top_news', '1.0', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (13, 'Топ комментаторов', '1', '\\\[cp:top_users\\\]', '<?php topUsers(); ?>', 'topUsers', '1', 'top_users', '1.0', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (14, 'Последние комментарии', '1', '\\\[cp:last_comments\\\]', '<?php cpLastComments(); ?>', 'cpLastComments', '1', 'last_comments', '1.0', 0);#inst#
INSERT INTO %%PRFX%%_module VALUES (15, 'Архив документов', '1', '\\\[newsarchive:([0-9\\\]*)\]', '<?php newsarchive(''\\\\\1''); ?>', 'newsarchive', '1', 'newsarchive', '1.0', 0);#inst#

INSERT INTO %%PRFX%%_modul_newsarchive VALUES (1, 'Тестовый архив', '1,2', 1, 1);#inst#

INSERT INTO %%PRFX%%_modul_comments VALUES (1, 1000, '1,3,4', 0, 1);#inst#
INSERT INTO %%PRFX%%_modul_comment_info VALUES (1, 0, 6, '%%USERNAME%%', 1, '%%EMAIL%%', '%%ORT%%', 'http://www.overdoze.ru','127.0.0.1','1204566943',0,'','Тестовый комментарий для первой новости. Он предназначен для получения циферки в запросе.\n\nСоздатель, %%USERNAME%%.',1,0);#inst#
INSERT INTO %%PRFX%%_modul_comment_info VALUES (2, 0, 6, '%%USERNAME%%', 1, '%%EMAIL%%', '%%ORT%%', 'http://www.overdoze.ru','127.0.0.1','1204567112',0,'','Это еще один комментарий, который создан для того, чтобы в запросе для первой новости горела циферка 2 )).\n\nСоздатель, %%USERNAME%%',1,0);#inst#
INSERT INTO %%PRFX%%_modul_comment_info VALUES (3, 0, 8, '%%USERNAME%%', 1, '%%EMAIL%%', '%%ORT%%', 'http://www.overdoze.ru','127.0.0.1','1204567173',0,'','Это тестовый комментарий для второй новости. Он будет только 1 и после инсталяции в запросе должна гореть 1.\n\nСоздатель, %%USERNAME%%.',1,0);#inst#

INSERT INTO %%PRFX%%_navigation VALUES (1, 'Основное меню навигации', '<li><a class=\"first_inactive\" target=\"[cp:target]\"  href=\"[cp:link]\">[cp:linkname]</a></li>\r\n', '<li><a target=\"[cp:target]\" class=\"second_inactive\" href=\"[cp:link]\">[cp:linkname]</a></li>\r\n', '<li><a target=\"[cp:target]\" class=\"third_inactive\" href=\"[cp:link]\">[cp:linkname]</a></li>\r\n\r\n', '<li><a target=\"[cp:target]\" class=\"first_active\" href=\"[cp:link]\">[cp:linkname]</a></li>\r\n\r\n', '<li><a target=\"[cp:target]\" class=\"second_active\" href=\"[cp:link]\">[cp:linkname]</a></li>\r\n', '<li><a target=\"[cp:target]\" class=\"third_active\" href=\"[cp:link]\">[cp:linkname]</a></li>\r\n', '<ul>', '</ul>', '<ul>', '</ul>', '<ul>', '</ul>', '<!-- Навигация -->', '', '1,2,3,4', 0);#inst#

INSERT INTO %%PRFX%%_navigation_items VALUES (1, 'Главная', 0, '[cp:replacement]', '_self', '1', 1, 1, 1);#inst#
INSERT INTO %%PRFX%%_navigation_items VALUES (2, 'О компании', 0, '[cp:replacement]info[cp:replacement]', '_self', '1', 2, 1, 1);#inst#
INSERT INTO %%PRFX%%_navigation_items VALUES (3, 'Условия сделки', 0, '[cp:replacement]license[cp:replacement]', '_self', '1', 3, 1, 1);#inst#
INSERT INTO %%PRFX%%_navigation_items VALUES (4, 'Обратная Связь', 0, '[cp:replacement]contacts[cp:replacement]', '_self', '1', 4, 1, 1);#inst#
INSERT INTO %%PRFX%%_navigation_items VALUES (5, 'Архив Новостей', 0, '[cp:replacement]newsarhive[cp:replacement]', '_self', '1', 5, 1, 1);#inst#
INSERT INTO %%PRFX%%_navigation_items VALUES (7, 'Первая тестовая новость', 5, '[cp:replacement]news[cp:replacement]test[cp:replacement]1[cp:replacement]', '_self', '2', 1, 1, 1);#inst#
INSERT INTO %%PRFX%%_navigation_items VALUES (8, 'Пример галереи', 0, '[cp:replacement]gallery[cp:replacement]', '_self', '1', 8, 1, 1);#inst#


INSERT INTO %%PRFX%%_rubrics VALUES (1, 'Основные страницы', '/', '<h2>[cprub:4]</h2>\r\n<br />\r\n<em>[cprub:13]</em><br />\r\n[cprub:2][cprub:1]\r\n<br />\r\n<br />\r\n[cprub:18]\r\n<br />\r\n<em>[cprub:17] [cprub:16]</em>\r\n<br />', 1, 1, '%%ZEIT%%');#inst#
INSERT INTO %%PRFX%%_rubrics VALUES (2, 'Новости',  '/news/', '<h2>[cprub:10]</h2><br /><br />\r\n[cprub:6][cprub:5]\r\n<br />\r\n[cprub:24][cprub:23]\r\n<br />\r\n[cprub:25][cprub:26]\r\n<br />\r\n<em>[cprub:27]</em><br /><br />[cp:comment]', 1, 1, '%%ZEIT%%');#inst#
INSERT INTO %%PRFX%%_rubrics VALUES (3, 'Глоссарий',  '/', '<h2>[cprub:11]</h2>\r\n<br /><br />\r\n[cprub:12]\r\n<hr noshade=\"noshade\" size=\"1\" />\r\n[cprequest:3]', 1, 1, '%%ZEIT%%');#inst#
INSERT INTO %%PRFX%%_rubrics VALUES (6, 'Галереи',  '/gallery/', '[cprub:19]\r\n<br /><br />\r\n[cprub:20][cprub:21][cprub:22]', 1, 1, '%%ZEIT%%');#inst#


INSERT INTO %%PRFX%%_rubric_fields VALUES (1, 1, 'Содержание', 'langtext', 3, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (2, 1, 'Изображение (справа)', 'bild_rechts', 4, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (13, 1, 'Введение', 'kurztext', 2, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (4, 1, 'Заголовок', 'kurztext', 1, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (5, 2, 'Новости', 'langtext', 2, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (6, 2, 'Изображение', 'bild_rechts', 3, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (27, 2, 'Дата', 'created', 8, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (10, 2, 'Заголовок', 'kurztext', 1, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (11, 3, 'Заголовок', 'kurztext', 1, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (12, 3, 'Содержание', 'langtext', 5, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (18, 1, 'Ссылка', 'link_ex', 10, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (17, 1, 'Автор', 'author', 5, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (16, 1, 'Дата', 'created', 6, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (19, 6, 'Заголовок', 'kurztext', 1, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (20, 6, 'Изображение 1', 'bild', 2, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (21, 6, 'Изображение 2', 'bild', 3, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (22, 6, 'Изображение 3', 'bild', 4, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (23, 2, 'Видео (*.avi)', 'video_avi', 4, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (24, 2, 'Видео (*.mov)', 'video_mov', 5, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (25, 2, 'Файл', 'download', 6, '');#inst#
INSERT INTO %%PRFX%%_rubric_fields VALUES (26, 2, 'Flash (*.swf)', 'flash', 7, '');#inst#


INSERT INTO %%PRFX%%_modul_gallery VALUES (1, 'Демонстрационная галерея', 'Эта галерея создана для ознакомления с возможностями модуля', 1, '%%ZEIT%%', 120, 4, 1, 1, 0, 4, 18, '', '');#inst#
INSERT INTO %%PRFX%%_modul_gallery_images VALUES (1, 1, 'crocodile.jpg', 1, 'Крокодил', '', '.jpg', %%ZEIT%%);#inst#
INSERT INTO %%PRFX%%_modul_gallery_images VALUES (2, 1, 'dolphin.jpg', 1, 'Дельфин', '', '.jpg', %%ZEIT%%);#inst#
INSERT INTO %%PRFX%%_modul_gallery_images VALUES (3, 1, 'duck.jpg', 1, 'Утка', '', '.jpg', %%ZEIT%%);#inst#
INSERT INTO %%PRFX%%_modul_gallery_images VALUES (4, 1, 'eagle.jpg', 1, 'Орел', '', '.jpg', %%ZEIT%%);#inst#
INSERT INTO %%PRFX%%_modul_gallery_images VALUES (5, 1, 'jellyfish.jpg', 1, 'Медузы', '', '.jpg', %%ZEIT%%);#inst#
INSERT INTO %%PRFX%%_modul_gallery_images VALUES (6, 1, 'killer_whale.jpg', 1, 'Касатка', '', '.jpg', %%ZEIT%%);#inst#
INSERT INTO %%PRFX%%_modul_gallery_images VALUES (7, 1, 'leaf.jpg', 1, 'Лист', '', '.jpg', %%ZEIT%%);#inst#
INSERT INTO %%PRFX%%_modul_gallery_images VALUES (8, 1, 'spider.jpg', 1, 'Паук', '', '.jpg', %%ZEIT%%);#inst#


INSERT INTO %%PRFX%%_templates VALUES (1, 'Стандартный шаблон', '[cp_theme:default]\r\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>[cp:pagename] - [cp_doc:titel]</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\" />\r\n<meta http-equiv=\"pragma\" content=\"no-cache\" />\r\n<meta name=\"Keywords\" content=\"[cp:keywords]\" />\r\n<meta name=\"Description\" content=\"[cp:description]\" />\r\n<meta name=\"robots\" content=\"[cp:indexfollow]\" />\r\n<link href=\"[cp:mediapath]css/print.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\r\n[cp:donot_print]\r\n<link href=\"[cp:mediapath]css/style.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />\r\n[/cp:donot_print]\r\n<script src=\"[cp:mediapath]js/common.js\" type=\"text/javascript\"></script>\r\n<script src=\"[cp:mediapath]overlib/overlib.js\" type=\"text/javascript\"></script>\r\n</head>\r\n\r\n<body>\r\n<div id=\"body\">\r\n[cp:donot_print]\r\n<div id=\"topheader\">\r\n<a href=\"/\"><img src=\"[cp:mediapath]images/logo.gif\" alt=\"На главную\" border=\"0\" /></a>\r\n</div>\r\n\r\n\r\n<!-- ************* ВЕРХНЯЯ ПЛАНКА ************* -->\r\n<div id=\"topbar\">[cp:quickfinder]</div>\r\n<div id=\"container\">\r\n\r\n<!-- ************* ЛЕВАЯ СТОРОНА ************* -->\r\n<div id=\"leftnavi\">\r\n\r\n<!-- ************* ЛЕВАЯ СТОРОНА - МЕНЮ НАВИГАЦИИ ************* -->\r\n<div style=\"position:relative\">\r\n[cp_navi:1]\r\n</div>\r\n\r\n\r\n<!-- ************* ЛЕВАЯ СТОРОНА - МЕНЮ АВТОРИЗАЦИИ ************* -->\r\n[cp:loginform]\r\n<br />\r\n<br />[cp:top_news]\r\n<br />[cp:top_users]<br />\r\n[cp:last_comments]<br />\r\n[newsarchive:1]<br />\r\n<!-- ************* ЛЕВАЯ СТОРОНА - ПРОЧЕЕ ************* -->\r\n<div id=\"leftnormal\">\r\n[cp:search]\r\n<br />\r\n</div>\r\n</div>\r\n\r\n<!-- ************* СОДЕРЖАНИЕ ************* -->\r\n<div id=\"content\">\r\n[/cp:donot_print]\r\n[cp:if_print]\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n<!--\r\nwindow.resizeTo(680,600);\r\nwindow.moveTo(1,1);\r\nwindow.print();\r\n//-->\r\n</script>\r\n  <strong>Версия для печати</strong> <br />\r\n  [cp:document] <br />\r\n  <hr noshade=\"noshade\" size=\"1\" />\r\n  <br />\r\n  [/cp:if_print]\r\n  [cp:maincontent]\r\n<p>&nbsp;</p>\r\n  [cp:donot_print] \r\n </div>\r\n </div>\r\n<div class=\"clear\"></div>\r\n<div id=\"footer\">\r\n <a target=\"_blank\" href=\"[cp:printlink]\"> <img src=\"[cp:mediapath]images/printer.gif\" alt=\"\" border=\"0\" />Версия для печати</a>\r\n | \r\n[cp:recommend] | [cp:version]</div>\r\n\r\n[/cp:donot_print]\r\n</div>\r\n</body>\r\n</html>\r\n[cp_counter:1]', 1, %%ZEIT%%);#inst#
