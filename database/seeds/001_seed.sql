-- =====================================================
-- DONNÉES DE TEST — La Cerise
-- Importer dans phpMyAdmin ou via la CLI MySQL
-- =====================================================

-- -----------------------------------------------------
-- Rubriques (thèmes de la maquette)
-- -----------------------------------------------------
INSERT INTO rubriques (nom, slug, description) VALUES
(
    'Justice Sociale, Travail et Dignité Humaine',
    'justice-sociale',
    'Droits des travailleurs, inégalités économiques et luttes pour la dignité humaine à travers le monde.'
),
(
    'Indépendance et Autodétermination des Peuples',
    'independance-autodetermination',
    'Mouvements d\'indépendance, colonialisme, décolonisation et droit des peuples à disposer d\'eux-mêmes.'
),
(
    'Humanitaire, Conflits et Protection des Civils',
    'humanitaire-conflits',
    'Crises humanitaires, zones de guerre et protection des populations civiles dans les conflits armés.'
);

-- -----------------------------------------------------
-- Auteurs
-- -----------------------------------------------------
INSERT INTO auteurs (nom, bio, email) VALUES
(
    'Lucie Le Long',
    'Journaliste indépendante spécialisée dans les droits humains et les conflits internationaux.',
    'lucie.lelong@lacerise.fr'
),
(
    'Marc Durand',
    'Correspondant international, ancien reporter de terrain en Afrique subsaharienne et au Moyen-Orient.',
    'marc.durand@lacerise.fr'
);

-- -----------------------------------------------------
-- Articles
-- -----------------------------------------------------
INSERT INTO articles (titre, slug, chapeau, contenu, image_principale, statut, date_publication, rubrique_id, auteur_id) VALUES
(
    'Pourquoi la France refuse-t-elle que la Cour internationale de Justice se prononce sur le statut de Mayotte ?',
    'france-cij-mayotte',
    'Mayotte est une île de l\'océan Indien, située dans l\'archipel des Comores, entre Madagascar et la côte africaine. Ancienne colonie française, elle constitue aujourd\'hui un cas unique dans l\'histoire de la décolonisation.',
    '<p>La Cour internationale de Justice, siégeant à La Haye dans le palais de la Paix, est établie par l\'article 92 de la Charte des Nations unies. Depuis 1975, la question du statut de Mayotte oppose la France à l\'Union des Comores devant la communauté internationale.</p><p>Alors que l\'ensemble de l\'archipel des Comores a accédé à l\'indépendance en 1975, Mayotte est restée sous souveraineté française à la suite d\'un référendum controversé. L\'Union des Comores n\'a jamais reconnu ce résultat et continue de revendiquer l\'île.</p><p>La France, membre permanent du Conseil de sécurité de l\'ONU, bloque systématiquement toute tentative de saisine de la CIJ sur ce sujet. Une position qui soulève des questions fondamentales sur l\'application du droit international selon les rapports de force entre États.</p>',
    NULL,
    'publie',
    '2026-02-24 08:00:00',
    2,
    1
),
(
    'Au Bangladesh, les ouvrières du textile se battent pour un salaire décent',
    'bangladesh-ouvrieres-textile-salaire',
    'Dans les ateliers de Dacca, des milliers de femmes cousent les vêtements vendus en Europe. Elles gagnent moins de 100 euros par mois et réclament une revalorisation urgente de leur salaire minimum.',
    '<p>Chaque matin à 6h, Fatema Begum prend le bus bondé qui la mène jusqu\'à l\'atelier de confection où elle travaille depuis douze ans. Elle y passe dix heures à assembler des chemises destinées aux grandes enseignes européennes.</p><p>Depuis 2023, les ouvrières du textile bangladais manifestent régulièrement pour exiger un salaire minimum de 208 dollars par mois. Les employeurs, soutenus par les grandes marques internationales, n\'ont accordé qu\'une hausse de 56 %... depuis une base extrêmement basse.</p><p>Derrière chaque vêtement à bas prix vendu en Europe se cache une réalité sociale que les grandes marques préfèrent taire. Le mouvement des ouvrières bangladaises force pourtant à regarder en face le coût humain de la fast fashion.</p>',
    NULL,
    'publie',
    '2026-03-01 09:00:00',
    1,
    1
),
(
    'Gaza : un an après, le système de santé est à genoux',
    'gaza-systeme-sante-effondrement',
    'Les bombardements ont détruit plus de 80 % des infrastructures médicales de Gaza. Médecins et infirmières continuent de travailler dans des conditions inhumaines, sans médicaments, sans électricité.',
    '<p>L\'hôpital Al-Shifa, autrefois le plus grand complexe médical de Gaza, n\'est plus qu\'une carcasse. Ses couloirs, autrefois animés, sont désormais vides ou transformés en abris de fortune pour des familles déplacées.</p><p>Selon l\'Organisation mondiale de la santé, moins de 20 % des hôpitaux gazaouis fonctionnent encore partiellement. Les équipes médicales qui tiennent encore opèrent sans anesthésie, sans antibiotiques, parfois à la lumière des téléphones portables.</p><p>Le droit international humanitaire protège explicitement les structures médicales en temps de guerre. Sa violation systématique à Gaza constitue, pour de nombreux juristes, un crime de guerre qui appelle une réponse urgente de la communauté internationale.</p>',
    NULL,
    'publie',
    '2026-03-10 10:00:00',
    3,
    2
),
(
    'En Nouvelle-Calédonie, la question de l\'indépendance reste entière',
    'nouvelle-caledonie-independance',
    'Après trois référendums et des émeutes meurtrières en 2024, l\'avenir institutionnel de la Nouvelle-Calédonie est toujours en suspens. Les partisans de l\'indépendance kanak dénoncent un processus biaisé.',
    '<p>Le troisième référendum d\'autodétermination de 2021, organisé en pleine pandémie de Covid-19, avait été boycotté par les indépendantistes kanak. Un boycott que la France a refusé de prendre en compte, validant un résultat de 96 % pour le maintien dans la République.</p><p>Depuis, les tensions n\'ont cessé de monter. En mai 2024, des émeutes éclatent après une réforme constitutionnelle du corps électoral jugée illégitime par les partis indépendantistes. Bilan : treize morts, des centaines de millions d\'euros de dégâts.</p><p>Le droit à l\'autodétermination, garanti par la Charte des Nations unies, exige un processus libre et équitable. Beaucoup estiment que les conditions n\'ont jamais été réunies en Nouvelle-Calédonie.</p>',
    NULL,
    'publie',
    '2026-03-15 11:00:00',
    2,
    2
),
(
    'Travail des enfants : derrière le chocolat, une réalité amère',
    'travail-enfants-chocolat-cacao',
    'En Côte d\'Ivoire et au Ghana, qui produisent 60 % du cacao mondial, des centaines de milliers d\'enfants travaillent dans les plantations. Les grandes marques chocolatières peinent à tenir leurs promesses.',
    '<p>Il s\'appelle Koffi, il a onze ans et ne va plus à l\'école depuis deux ans. Chaque matin, il aide son père à cueillir les cabosses de cacao dans la plantation familiale, seul moyen de survie depuis que les prix ont chuté.</p><p>Selon l\'OIT, près de 1,5 million d\'enfants travaillent dans les plantations de cacao en Afrique de l\'Ouest. Un chiffre en hausse malgré les engagements répétés des industriels du chocolat à éradiquer ce fléau d\'ici 2025.</p><p>Les certifications Fairtrade et Rainforest Alliance ne suffisent pas. Les ONG réclament une législation contraignante imposant aux entreprises une traçabilité complète de leur chaîne d\'approvisionnement, sous peine de sanctions.</p>',
    NULL,
    'publie',
    '2026-03-20 08:30:00',
    1,
    1
);
