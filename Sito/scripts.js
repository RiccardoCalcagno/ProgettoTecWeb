function onLoadModificaDati(){

    // SETTARE LA DATA MASSIMA DI NASCITA
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
     if(dd<10){
            dd='0'+dd
        } 
        if(mm<10){
            mm='0'+mm
        } 
    
    today = yyyy+'-'+mm+'-'+dd;
    document.getElementById("birthdate").setAttribute("max", today);    
}


function updateCounterTextarea(numCount){
    var IDText;
    var IDCurrent;
    var max;
    switch(numCount){
        case 1:
            IDText="titoloReport";
            IDCurrent="currentCountTitolo";
            max=30;
            break;
        case 2:
            IDText="sottoTRepo";
            IDCurrent="currentCountSottotitolo";
            max=120;
            break;
        case 3:
            IDText="username";
            IDCurrent="currentCountUser";
            max=20;
            break;
        case 4:
            IDText="ctraits";
            IDCurrent="currentCountTraits";
            max=200;
            break;
        case 5:
            IDText="cideals";
            IDCurrent="currentCountIdeals";
            max=200;
            break;
        case 6:
            IDText="cbonds";
            IDCurrent="currentCountBonds";
            max=200;
            break;
        case 7:
            IDText="cflaws";
            IDCurrent="currentCountFlaws";
            max=200;
            break;
    }
    var characterCount=document.getElementById(IDText).value.length;
    var obj1=document.getElementById(IDCurrent);
    obj1.innerHTML=characterCount;
    if(characterCount>=max){
       obj1.style = "color: red;";
    } else if(characterCount == 0){
        obj1.style = "color: rgba(0, 0, 0, 0.5);";
    }else{obj1.style = "color: black;";}

}

function onLoadCreaPersonaggio(){
    setDescCaratteristica(0);
    setDescCaratteristica(1);
    setDescCaratteristica(2);
}

function setDescCaratteristica(num){

    var Descrizioni = [
        {
            "Umano"     : "<h3>Umano</h3><img src='../images/icone_razze/umano.png' alt='volto di una giovane donna di colore ornata di gioielli' /> <p>Gli <strong>umani</strong> sono il meglio e il peggio che la natura ha da offrire. Tra gli umani si trovano fulgidi esempi di onore e coraggio e individui crudeli e spregevoli. È nota la loro versatilità, la loro capacità di adattamento e l’ambizione oltre ogni limite.</p>" ,
            "Elfo"      : "<h3>Elfo</h3><img src='../images/icone_razze/elfo.png' alt='volto di elfo incappucciato con una faccia truce e scura' /> <p>Gli <strong>elfi</strong> fanno parte di un popolo magico, sono snelli e aggraziati e hanno tratti fisici ancor più variabili rispetto agli umani. Per via della loro lunghissima vita dimostrano divertimento e curiosità più che agitazione e bramosia.</p>" ,
            "Nano"      : "<h3>Nano</h3><img src='../images/icone_razze/nano.png' alt='volto di una nano fantasy con capelli rossi e con un espressione arrabbiata' /> <p>Tra i bassi ma massicci <strong>nani</strong> troviamo temibili combattenti e grandi artigiani. I nani sono fortemente legati al proprio clan, sono longevi, coriacei e tenaci, inoltre hanno un’ottima memoria e una certa tendenza a farla pagare a chi fa loro qualche torto.</p>" ,
            "Halfling"  : "<h3 xml:lang='en'>Halfling</h3><img src='../images/icone_razze/halfing.png' alt='volto di una piccola donna che sta ridendo, ha un naso a punta e sopracciglia maliziose' /> <p>Gli <strong xml:lang='en'>Halfling</strong> amano starsene comodi a casa a mangiare . Sono ingegnosi e sono affascinati da ciò che non conoscono o non hanno mai visto dal vivo e la loro curiosità li spinge all’avventura. Sono generosi e di buon cuore.</p>" ,
            "Gnome"     : "<h3>Gnome</h3><img src='../images/icone_razze/gnomo.png' alt='volto di un piccolo essere dai lineamenti femminili orecchie a punta capelli lunghi al vento' /> <p>Gli <strong>gnomi</strong> amano le invenzioni, le scoperte, le esplorazioni, gli scherzi e il divertimento. Nonostante la grande longevità mantengono sempre un grande entusiasmo per la vita e sono spesso abili artigiani, orafi e inventori.</p>" ,
            "Tiefling"  : "<h3 xml:lang='en'>Tiefling</h3><img src='../images/icone_razze/tiefilng.png' alt='volto di donna con pelle di colore rossastro capelli lunghi neri e orecchie a punta, in abito nobile' /> <p>I <strong xml:lang='en'>Tiefling</strong> sono umanoidi ma provvisti di coda, corna e denti appuntiti, tendono a essere malvagi, infatti si dedicano perlopiù al crimine. Sono sospettosi di natura ma se qualcuno si guadagna la loro fiducia diventano degli alleati leali.</p>" ,
            "Dragonide" : "<h3>Dragonide</h3><img src='../images/icone_razze/dragonide.png' alt='essere dal volto simile a quello di un drago con squame rosse' /> <p>I <strong>dragonidi</strong> sono una stirpe che discende dai draghi e combina i loro tratti con quelli umanoidi. Vivono in clan che considerano importantissimi e tendono al bene, seppur tra loro i malvagi tendono al male estremo.</p>" ,
            "Mezzelfo"  : "<h3>Mezzelfo</h3><img src='../images/icone_razze/mezzelfo.png' alt='volto di un umano sereno con una corona in testa e orecchie a punta non accentuata' /> <p>I <strong>mezzelfi</strong> sono parte di due mondi e non appartengono completamente né a uno né all’altro, cosa che talvolta ne fa dei girovaghi; in loro l’ambizione e la creatività umana si legano ai modi e ai sensi più raffinati degli elfi.</p>" ,
            "Mezzorco"  : "<h3>Mezzorco</h3><img src='../images/icone_razze/mezzorco.png' alt='volto di un orchessa con lunghi capelli marroni, pelle violacea, piccole zanne alla bocca e orecchie a punta' /> <p>I <strong>mezzorchi</strong> sono dei fieri e temibili combattenti, sono zannuti, pieni di cicatrici e più grossi degli umani. I mezzorchi hanno inoltre emozioni intense: la furia prende posto alla rabbia e un semplice sogghigno diventa la più fragorosa delle risate.</p>"
        } ,
        {
            "Bardo"     : "<h3>Bardo</h3><p>I <strong>bardi</strong> sono dei maestri del canto e della magia legata alla musica, ma possono dimostrarsi anche temibili spadaccini. I bardi si uniscono volentieri ai gruppi di avventurieri sono infatti dei grandi amanti delle avventure da raccontare.</p>" ,
            "Barbaro"   : "<h3>Barbaro</h3><p>I <strong>barbari</strong> sono individui possenti e selvaggi, che cercano la battaglia portando distruzione tra i nemici. Questa collera li rende dei combattenti micidiali. I barbari  si sentono a proprio agio nei territori selvaggi e nella  mischia.</p>" ,
            "Druido"    : "<h3>Druido</h3><p>I <strong>druidi</strong> venerano la natura e dalla sua forza traggono la magia e le proprie capacità straordinarie. Sono costantemente occupati a preservare l’equilibrio degli ecosistemi naturali considerandoli l’unica salvezza che ci separa dalla distruzione.</p>" ,
            "Ladro"     : "<h3>Ladro</h3><p>I <strong>ladri</strong> amano aggirarsi furtivamente e sorprendere i loro nemici al momento giusto combattendo più con l’astuzia che con la forza. Sono molto abili nel disinnescare e piazzare trappole e nello scassinare serrature.</p>" ,
            "Chierico"  : "<h3>Chierico</h3><p>Il <strong>chierico</strong> è un avventuriero pervaso di energia divina, che può combattere e lanciare incantesimi grazie alla sua fede. I chierici proteggono gli accoliti del proprio Dio, vivono da avventurieri per recuperare reliquie e punire i malvagi.</p>" ,
            "Guerriero" : "<h3>Guerriero</h3><p>I <strong>guerrieri</strong> sono specializzati nel combattimento, sono in grado di lottare con un gran numero di armi, armature e scudi. Nella mischia c’è chi diventa sempre più esperto e possente, e chi unisce all’addestramento fisico lo studio dei testi magici.</p>" ,
            "Monaco"    : "<h3>Monaco</h3><p>Il <strong>monaco</strong> non è soltanto un abile combattente, è colui che padroneggia la magia del ki: l’energia degli esseri viventi. I monaci non sono avidi, non si danno all’avventura per saccheggiare e arricchirsi, lo fanno per difendere qualcuno.</p>" ,
            "Mago"      : "<h3>Mago</h3><p>I <strong>maghi</strong> sono costantemente alla ricerca di segreti arcani e nuovi incantesimi da lanciare, cosa che spesso li spinge all’avventura, magari alla ricerca di tomi e pergamene nelle rovine di civiltà antiche.</p>" ,
            "Warlock"   : "<h3 xml:lang='en'>Warlock</h3><p>I <strong xml:lang='en'>warlock</strong> sono abili incantatori che ottengono i propri poteri tramite un patto con un patrono. I <span xml:lang='en'>warlock</span> sono volenterosi di darsi all’avventura per sperimentare le proprie abilità e per compiere le imprese proposte dal loro patrono.</p>" ,
            "Paladino"  : "<h3>Paladino</h3><p>Il <strong>paladino</strong> è un guerriero che giura di battersi per la giustizia e la rettitudine. Lo si può immaginare come un robusto e temibile combattente legato a una divinità e infuso da un’energia divina che gli permette di lanciare incantesimi e curarsi.</p>" ,
            "Ranger"    : "<h3 xml:lang='en'>Ranger</h3><p>I <strong xml:lang='en'>ranger</strong> sono combattenti e cacciatori delle terre selvagge. Sono abilissimi nel seguire le tracce, nel muoversi nei terreni conosciuti, nel lanciare incantesimi legati alla forza della natura e nel combattere al fianco delle belve.</p>" ,
            "Stregone"  : "<h3>Stregone</h3><p>Lo <strong>stregone</strong> è un incantatore che non possiede un libro degli incantesimi. La fonte della sua magia è la forza del caos che si ribolle nel sottosuolo del nostro globo. Gli stregoni conoscono pochi incantesimi ma possono adoperarli molto frequentemente.</p>"
        } ,
        {
            "Accolito"      : "<h3>Accolito</h3><p>Un <strong>accolito</strong> ha passato la sua vita a servire un tempio dedicato a una o più divinità celebrando riti solenni e offrendo sacrifici. Non è necessariamente un chierico, in quanto non incanalala il potere divino.</p>" ,
            "Artigiano"     : "<h3>Artigiano</h3><p>Un <strong>artigiano</strong> è riconosciuto per il valore dei sui manufatti, per anni ha lavorato con un maestro del mestiere, sopportando un trattamento sprezzante al fine di ottenere le buone abilità che possiede. </p>" ,
            "Ciarlatano"    : "<h3>Ciarlatano</h3><p>Un <strong>ciarlatano</strong> è sempre stato portato per le interazioni sociali. È abile nel capire le persone, una dote che non esita a sfruttare a suo vantaggio. \"Questa lozione curerà sicuramente quella brutta irritazione\"...sicuramente.</p>" ,
            "Criminale"     : "<h3>Criminale</h3><p>Un <strong>criminale</strong> è un esperto malfattore che ha già violato la legge più volte in passato. Ha trascorso molto tempo tra gli altri criminali e mantiene ancora qualche contatto con il mondo del crimine.</p>" ,
            "Eremita"       : "<h3>Eremita</h3><p>Un <strong>eremita</strong> ha trascorso gli anni formativi della sua vita in un luogo isolato, presso una comunità separata o completamente da solo. Ambiva alla tranquillità, alla solitudine e magari ad alcune delle risposte che andava cercando.</p>" ,
            "Eroe"          : "<h3>Eroe</h3><p>Un <strong>eroe</strong> proviene dai ceti sociali più bassi, ma è destinato a qualcosa di grandioso. E presto il destino lo chiamerà a fronteggiare i tiranni e i mostri che minacciano la gente comune di ogni terra.</p>" ,
            "Forestiero"    : "<h3>Forestiero</h3><p>Un <strong>forestiero</strong> è cresciuto nelle terre selvagge, il vigore di queste terre scorre nelle sue vene, che la sua storia sia quella di un nomade, un esploratore, un'anima solitaria, un cacciatore o perfino un predone.</p>" ,
            "Intrattenitore": "<h3>Intrattenitore</h3><p>Un <strong>intrattenitore</strong> sa come entusiasmare, divertire e perfino alleviare il dolore nel cuore di chi assiste alle sue esibizioni. Qualunque sia la sua tecnica preferita, un intrattenitore vive per la sua arte.</p>" ,
            "Marinaio"      : "<h3>Marinaio</h3><p>Il <strong>marinaio</strong> ha prestato servizio a bordo di un vascello per anni. Ha affrontato violente burrasche, mostri degli abissi e predoni impazienti di affondare la sua nave. Il suo primo amore è stato l'orizzonte più lontano.</p>" ,
            "Monello"       : "<h3>Monello</h3><p>Il <strong>monello</strong> è cresciuto solo, povero e orfano per le vie della città quindi ha imparato molto presto a badare a sé steso. È sopravvissuto grazie alla sua astuzia, forza, velocità o a una combinazione di tutto questo.</p>" ,
            "Nobile"        : "<h3>Nobile</h3><p>Un <strong>nobile</strong> conosce l'importanza della ricchezza, del potere e dei privilegi. Detiene un titolo nobiliare e la sua famiglia possiede terre, riscuote tasse ed esercita una notevole influenza politica.</p>" ,
            "Sapiente"      : "<h3>Sapiente</h3><p>Un <strong>sapiente</strong> ha trascorso anni e anni a studiare i segreti del Multiverso. Ha setacciato manoscritti, studiato pergamene e consultato i più grandi esperti nelle materie di suo interesse, molto spesso.. Magia.</p>" ,
            "Soldato"       : "<h3>Soldato</h3><p>Per il <strong>soldato</strong> la guerra è un elemento che influenza costantemente la sua vita. Da giovane ha appreso le tecniche di sopravvivenza e dell’uso delle armi. Potrebbe avere fatto parte di un esercito nazionale o di una compagnia mercenaria.</p>"
        }
    ];
    var doveScrivere;
    var soggetto;
    switch(num){
        case 0:
            doveScrivere="razzaDesc";
            soggetto=document.getElementById("crace").value;
            break;
        case 1:
            doveScrivere="classeDesc";
            soggetto=document.getElementById("cclass").value;
            break;
        case 2:
            doveScrivere="backgDesc";
            soggetto=document.getElementById("cbackground").value;
            break;
    }

    document.getElementById(doveScrivere).innerHTML = Descrizioni[num][soggetto];

}


function hamburgerFunction(){
    var x= document.getElementById("menu");
    if(x.style.display=="block"){
        x.style.display="none";
    }else{
        x.style.display="block";
    }
}



/*
function setBannerSalvataggi(){
    var arraySalvataggi; // = php.getListaSalvataggio();

    /*arraySalvataggi = [ "Report: Titolo Report1", "Report: Titolo Report1", "Personaggio: Titolo Personaggio1 + lungooooooo"];

    if(arraySalvataggi.length >0){

        var str = "<fieldset id='bannerSalvataggio'><legend><a xml:lang='en' id='chiusuraBanner' href='#' onclick='window.location.reload(true);'>Close</a></legend><p id='titoloAvviso'>Sono stati salvati i seguenti documenti</p><ul>";
        var li;
        for(var i=0; i<arraySalvataggi.length; i++){
            str = str + "<li>" + arraySalvataggi[i] + "</li>";
        }
        str = str + "</ul><p>Li puoi trovare nella tua <a href='AreaPersonale.html'>Area Personale</a></p></fieldset>";

        document.getElementById("wrapperBannerSalvataggio").innerHTML = str;
    }
}*/

function switchCharLayout(clickedButton) {

    var content = document.getElementById("contentPersonaggio");
    var otherButton;

    if (clickedButton.id == "pergamena") {
        otherButton = document.getElementById("scheda");
        content.className = "pergamena";
    }
    else {
        otherButton = document.getElementById("pergamena");
        content.className = "scheda";
    }
    
    clickedButton.className = "disabled";
    clickedButton.setAttribute("disabled", "disabled");
    otherButton.className = "active";
    otherButton.removeAttribute("disabled");
}

function CharSheet_JS_ON() {
    
    var scrollButton = document.getElementById("pergamena");
    var sheetButton = document.getElementById("scheda");

    scrollButton.setAttribute('type', 'button');
    sheetButton.setAttribute('type', 'button');
}

function openDD(nth_dd) {

    var dl = document.getElementById("footerList");
    var dd = dl.querySelectorAll("dd")[nth_dd];

    if(dl.className === "open-footer") {    
    // Un dd e' gia' aperto

        if (dd.className === "footer-open-dd") {    // il dd corrispondente era aperto, chiudilo e ripristina dl
            dl.className = "";
            dd.className = "";
        }
        
        else {  // Un dd era aperto, chiudilo e apri il nuovo
            var dds = dl.querySelectorAll("dd");
            for(i = 0; i < 3; i++) {
                var dd_i = dds[i];
                if (dd_i.className === "footer-open-dd") {
                    dd_i.className = "";    // Chiudi dd aperto
                }
            }
            dd.className = "footer-open-dd";    // Apri nuovo dd
        }
    }
    else {  // Nessuno dd era aperto, aprilo
        dl.className = "open-footer";
        dd.className = "footer-open-dd";
    }
    
    window.scrollTo(0, document.body.scrollHeight);
}

// ---------------------------------------------------------------------------------
// ------------------------------- General Validator -------------------------------
// ---------------------------------------------------------------------------------

// Abstract Validator
function validateForm(testArray) {
    var corretto = true;
    var firstError = true;
    for (var key in testArray) {
        var input = document.getElementById(key);
        var risultato = validazioneCampo(input, testArray[input.id]);
        corretto = corretto && risultato;

        if(!corretto && firstError) {
            input.previousSibling.scrollIntoView();
            window.scrollBy(0, -100); // Feedback (Doesn't work consistentely?)
            firstError = false;
        }
    }

    return corretto;
}

function validazioneCampo(input, inputArray) {

    // Elimino messaggi precedenti per evitare ripetizione
   clearErrorStatus(input);

    var regex = inputArray[0]; // Espressione regolare associata all' ID
    var text = input.value;
    if (text.search(regex) != 0) {  // seartch match
        mostraErrore(input, inputArray);
        return false;
    }
    else {
        return true;
    }
}

function clearErrorStatus(input) {
    var parent = input.parentNode;
    if (parent.children.length == 2) {
        input.classList.remove("input-errore");
        parent.removeChild(parent.children[0]);
    }
}

function mostraErrore(input, inputArray) {

    input.classList.add("input-errore");

    var elemento = document.createElement("strong");
    elemento.className = "text-errore";
    //elemento.appendChild(document.createTextNode(testArray[input.id][1])); e' piu' giusto?
    elemento.innerHTML = inputArray[1];    // Per usare tags all'interno
    
    var p = input.parentNode;
    p.insertBefore(elemento, input);
}
// ------------------------------------------------------------------------------------
// ------------------------------- validateCharCreation -------------------------------
// ------------------------------------------------------------------------------------

// NameSpace ?
var charCreationTextRegex = /^.{10,}$/;
var charCreationTextErrorMessage = "Inserisci almeno 10 caratteri";
var charCreationValues = {
    "cname": [/^[a-z][a-z ,.'-]{2,20}$/i, "Il nome deve essere da 3 a 20 caratteri, iniziare con una lettera e contenero soltanto lettere, virgole, punti, apostrofi e <span xml:lang=\"en\">hypens</span>"],
    "ctraits": [charCreationTextRegex, charCreationTextErrorMessage],
    "cideals": [charCreationTextRegex, charCreationTextErrorMessage],
    "cbonds": [charCreationTextRegex, charCreationTextErrorMessage],
    "cflaws": [charCreationTextRegex, charCreationTextErrorMessage]
};

function validateCharCreation() {
    return validateForm(charCreationValues);
}

// ---------------------------------------------------------------------------------
// ------------------------------- validatereport ----------------------------------
// ---------------------------------------------------------------------------------
// NameSpace ?

var reportValues = {
    "titoloReport": [/^.{3,30}$/, "Inserisci dai 3 ai 30 caratteri"],
    "sottoTRepo": [/^.{3,120}$/, "Inserisci dai 3 ai 120 caratteri"],
    "contRepo": [/^.{3,}$/, "Inserisci almeno 3 caratteri"],
};

function validateReport() {
    return validateForm(reportValues);
}

// ---------------------------------------------------------------------------------
// ------------------------------- log-in ------------------------------------------
// ---------------------------------------------------------------------------------
// NameSpace ?

var loginValues = {
    "username": [/^.{1,}$/, "Inserisci il tuo username"],
    "password": [/^.{1,}$/, "Inserisci la tua password"],
};

function validateLogin() {
    return validateForm(loginValues);
}

// ---------------------------------------------------------------------------------
// ------------------------------- log-in e Change Data ------------------------------------------
// ---------------------------------------------------------------------------------
// NameSpace ?
var userDataValues = {
    // ID: ["TestDaEseguire", "MessaggioErrore"]
    "username": [/^.{1,}$/, "Inserisci il tuo username"],
    "NomeCognome": [/^[a-z][a-z ,.'-]{2,20}$/i, "Inserisci il tuo Nome e Cognome"], /* TO FIX ?*/
    "email": [/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i, "Inserisci un email valida"]
};
passwordRegex = /^.{3,}$/; // Non serve ?
var newPasswordValue = {
    "newPasswd": [passwordRegex, "Password non Valida (DA sistemare REGEX)"],
};

function validateUserData() {

    return validateForm(userDataValues) && validateForm(newPasswordValue) && checkPasswordMatch();
}
/* TO FIX MAKE BETTER */
function checkPasswordMatch() {

    var passwordMatchValues = { // KINDA MEH
        "newPasswd": ["", "Le password devono coincidere"],
        "PasswdAgan": ["", "Le password devono coincidere"]
    };

    var psw = document.getElementById("newPasswd");
    var conf_psw = document.getElementById("PasswdAgan");

    clearErrorStatus(psw);
    clearErrorStatus(conf_psw);

    if(psw.value != conf_psw.value) {
        mostraErrore(psw, passwordMatchValues[psw.id]);
        mostraErrore(conf_psw, passwordMatchValues[conf_psw.id]);
        window.scrollBy(0, -10); // Feedback

        return false;
    }
    else {
        return true;
    }
}

// ------------------------------- Change Data ------------------------------------------

var oldPasswordValue = {
    "password": [/^.{3,}$/, "Inserisci la tua Password corrente"] // DA NON CONTROLLARE, PASSWORD CORRENTE. min 3 chars (5?)
};

function validateChangeUserData() {
//    validateForm(futureEmail) TO FIX !!!!!! ----------------------------------------------------------------------------------------------------------
    return validateForm(userDataValues);    // Stesso di log-in MA EMAIL ID DIVERSO -----------------------------------------------------------------------
}

function validateChangeUserPassword() {
    return validateForm(oldPasswordValue) && validateForm(newPasswordValue) && checkPasswordMatch(); 
}

function updateProfileImg()
{   
    var img_path = document.getElementById("imgProfilo").value;
    var img = document.getElementsById("immagine_profilo");

    //img.classList.remove("hidden");

    if(img_path)
    {
        img.src(img_path);
    }
    else
    {
        img.src(" ");
    }
}

function removeHidden(id_name)
{
    var element = document.getElementById(id_name.toString()).classList.remove("hidden");
}