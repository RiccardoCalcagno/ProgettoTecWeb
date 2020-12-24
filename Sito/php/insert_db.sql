INSERT INTO 
Users (username,name_surname,email,passwd,birthdate,img_path) VALUES
('ThirdEye','Maynard_Keenan','maynardjkeenan@gmail.com','1234Jambi','1964-04-17','fac_simile_img/path'),
('Grog','Travis_Baylee','travisCI@gmail.com','1WouldLikeToRage','1979-10-09','fac_simile_img/path'),
('QueenAdministrator','Ombretta_Gaggi','gaggifakemail@gmail.com','1000BimbiFucsia','1980-01-01','fac_simile_img/path'),
('Barb', 'Barbara_Aprile','aprilebarb@gmail.com','12345678Aa','1995-09-07','fac_simile_img/path'),
('babetto.eddy','Eddy_Babetto','babettoeddy@gmail.com','123Password','1999-03-18','fac_simile_img/path'),
('bestDM', 'Mike_Shinoda','atLP_M_Shinoda@gmail.com','P5hngMeA*wy','1978-05-12','fac_simile_img/path'),
('Will.I.Am.','William_Black','BEP@gmail.com','ImmaBe33','1968-04-13','fac_simile_img/path'),
('ShinigamiVII', 'Luke_Moon','shinigami@gmail.com','KDMsoon?1','1998-11-17','fac_simile_img/path'),
('user', 'Maria_Rossi','user@gmail.com','user','1992-02-15','fac_simile_img/path'),
('admin', 'Alessia_Bianchi','admin@gmail.com','admin','1993-12-16','fac_simile_img/path');

INSERT INTO
Report (id,title,subtitle,content,author,isExplorable,last_modified) VALUES
(1,'Iniziare una Locanda','Il modo più classico di iniziare una campagna può essere inaspettato?',
	'Potete immaginare la mia delusione quando sabato sera ho scoperto che la nuova campagna che avremmo iniziato col solito gruppo di amici sarebbe iniziata in una locanda, "Al Drago Verde". Solitamente questo tipo di inizio sessione è piuttosto lento e ci vogliono ore prima di iniziare a decapitare qualche goblin. Se solo avessi saputo... Appena il nostro party si è seduto al tavolo, le sedie si sono rivelate essere dei Mimic, e l\'intera banda di goblin a cui davamo la caccia è saltata fuori dalla cucina per farci a pezzi! Ci siamo divertiti un mondo! Inutile dire che della locanda alla fine è rimasto ben poco LOL',
	'Grog',1,'2020-05-14'),
(2,'Raga ma il Necromante?','Mi sa che abbiamo commesso un errore che ci sarà fatale :(',
	'L\'altra sera abbiamo esplorato a fondo la cripta ed eliminato tutti i non-morti, ma una volta arrivati alla stanza finale ci siamo fatti prendere dalla foga vedendo tutte quelle monete d\'oro, e abbiamo dato per scontato che il necromante fosse morto da tempo... E se invece fosse nella bara verticale che il Dungeon Master ci ha descritto così meticolosamente? Spero che l\'inizio della prossima sessione non sia la nostra compagnia che viene eliminata e risurretta per scopi malvagi! #TPK',
	'Barb',0,'2020-06-16'),
(3,'Ricerco una compagnia con cui giocare','So che questo non è il luogo giusto ma ne ho davvero bisogno',
	'Mi chiedevo se qualcuno di voi abita in zona Padova ed è disposto ad accettare una nuova arrivata nell\'hobby, avrei davvero voglia di giocare e condividere queste esperienze come fate tutti voi su questo sito',
	'QueenAdministrator',1,'2020-07-13'),
(4,'Snowstorm','La tempesta di neve più assurda di sempre',
	'Quando il nostro party si è incamminato verso la montagna, nessuno si aspettava che sarebbe stato così devastante. Nel giro di poche ore stavamo soffrendo tutti il fretto a causa delle temperature glaciali. Ci sembrava di essere la Compagnia dell\'Anello sulle creste di Khazad-Dum, con Saruman contro. E invece sulla cima abbiamo incontrato uno Yeti Sciamano, che chiamava le tempeste per difendere i suoi piccoli dai cacciatori di mostri... Poveretto. Gli abbiamo offerto una zuppa calda e lo abbiamo spedito nel piano elementale del Gelo, sperando che li sia più tranquillo.',
	'babetto.eddy',1,'2019-06-16'),
(5,'Buffoni','Forse dovrei imparare a tenere la bocca chiusa di tanto in tanto...',
	'Stavo ripensando alle (dis)avventure di ieri e in effetti avevate ragione, non avrei dovuto parlare. Ma il mio personaggio è fatto così! Insomma quei mercenari se lo meritavano, non potevamo certo lasciarci dire quello che possiamo o non possiamo fare. Quando il tiefling rosso ha tirato fuori l\'ascia, il mio povero Reos non ci ha più visto. "Sei un Buffone". E giù di mazzate hahhaahah.',
	'ShinigamiVII',0,'2020-03-06');

INSERT INTO
Characters (id,name,race,class,background,alignment,traits,ideals,bonds,flaws,author,creation_date) VALUES
(1,'Ilde Battlegore','nano','warlock','marinaio','NG',
	'Amo i lavori ben fatti, specialmente se posso convincere qualcun altro a farli.',
	'Giustizia, Lavoriamo tutti assieme e godremo tutti dei benefici.',
	'Lealtà al capitano, tutto il resto viene dopo!',
	'Se inizio a bere, è difficile farmi smettere.',
	'ThirdEye','2019-10-23'),
(2,'Fenriz Wyndael','tiefling','stregone','criminale','LG',
	'Sono sempre gentile e rispettoso.',
	'Indipendenza, quando le persone seguono gli ordini ciecamente sono come soggette a tirannia.',
	'Combatto per coloro che non possono combattere per se stessi.',
	'Preferirei mangiarmi l\'armatura piuttosto che ammettere di avere torto!',
	'Grog','2019-11-21'),
(3,'Gavin Tiltathana','mezzelfo','paladino','accolito','CE',
	'Sono stato abituato ad aiutare coloro che ne hanno bisogno, ma prima o poi questo doveva cambiare.',
	'Non ci sono regole, non c\'è motivo di seguire ordini imposti dall\'alto.',
	'La mia vita è legata alla mia spada, senza di lei non sono nessuno.',
	'Non sono in grado di tenere un segreto, anche se fosse per salvarmi la pelle.',
	'QueenAdministrator','2019-06-30'),
(4,'Velroos Mlezziir','elfo','guerriero','eroe','N',
	'Se qualcuno è nei guai, sono sempre pronto ad aiutare.',
	'Uguaglianza, nessuno dovrebbe ricevere un trattamento diverso davanti alla legge.',
	'Proteggo i deboli e gli orfani.',
	'Ho difficoltà nel fidarmi dei miei alleati.',
	'Barb','2019-03-17'),
(5,'Gilbert Brightmoon','halfling','barbaro','forestiero','CN',
	'Mi sento più a mio agio intorno agli animali che alle persone.',
	'Cambiamento, la vita è come le stagioni, in costante mutamento, e noi dobbiamo cambiare con essa.',
	'Sono l\'ultimo della mia tribù e sta a me far entrare il nostro nome nella leggenda!',
	'Non aspettatevi che salvi coloro che non possono badare a se stessi. Vale la legge del più forte.',
	'babetto.eddy','2018-12-31'),
(6,'Reos Rhapsody','tiefling','monaco','eremita','CG',
	'Sono sempre gentile... Finchè non ti metti davanti al mio cammino!',
	'Forza, nella vita come nel combattimento, il più abile ne uscirà vincitore',
	'Non dimenticherò mai la sconfitta che subii da giovane, ne chi mi umiliò',
	'Il mio odio per i miei nemici è cieco e indomabile',
	'ShinigamiVII','2018-12-31');

INSERT INTO
report_giocatore (user,report) VALUES
('Barb',2),
('ShinigamiVII',2),
('ThirdEye',2),
('Will.I.Am.',1),
('ShinigamiVII',4),
('Will.I.Am.',4),
('bestDM',4),
('Will.I.Am.',5),
('bestDM',5),
('babetto.eddy',5);

SET foreign_key_checks=1;