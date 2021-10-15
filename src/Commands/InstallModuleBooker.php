<?php

namespace Chuckbe\ChuckcmsModuleBooker\Commands;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Illuminate\Console\Command;

class InstallModuleBooker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-booker:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command installs the ChuckCMS Booker Module .';

    /**
     * The module repository implementation.
     *
     * @var ModuleRepository
     */
    protected $moduleRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ModuleRepository $moduleRepository)
    {
        parent::__construct();

        $this->moduleRepository = $moduleRepository;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = 'ChuckCMS Booker Module';
        $slug = 'chuckcms-module-booker';
        $hintpath = 'chuckcms-module-booker';
        $path = 'chuckbe/chuckcms-module-booker';
        $type = 'module';
        $version = '0.0.1';
        $author = 'Karel Brijs (karel@chuck.be)';

        $json = [];
        $json['admin']['show_in_menu'] = true;


        $json['admin']['menu'] = array(
            'name' => 'Booker',
            'icon' => "calender",
            'route' => '#',
            'has_submenu' => true,
            'submenu' => array(
                'a' => array(
                    'name' => 'Afspraken',
                    'route' => 'dashboard.module.booker.appointments.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'aa' => array(
                    'name' => 'Abonnementen',
                    'route' => 'dashboard.module.booker.subscriptions.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'aaa' => array(
                    'name' => 'Facturen',
                    'route' => 'dashboard.module.booker.invoices.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'b' => array(
                    'name' => 'Klanten',
                    'route' => 'dashboard.module.booker.customers.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'c' => array(
                    'name' => 'Locaties',
                    'route' => 'dashboard.module.booker.locations.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'd' => array(
                    'name' => 'Diensten',
                    'route' => 'dashboard.module.booker.services.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'e' => array(
                    'name' => 'Instellingen',
                    'route' => 'dashboard.module.booker.settings.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
            )
        );
        
        $json['settings'] = [];
        
        $json['settings']['general']['supported_currencies'] = ['EUR'];
        $json['settings']['general']['featured_currency'] = 'EUR';
        $json['settings']['general']['decimals'] = 2;
        $json['settings']['general']['decimals_separator'] = ',';
        $json['settings']['general']['thousands_separator'] = '.';

        $json['settings']['general']['conditions'] = '<p class="mb-5">©Algemene voorwaarden van Flow2seE bvba, Cryo Center Antwerp, Oude Baan 143, gevestigd te 2930 Brasschaat; BTW nr.0875.443.113 (hierna genoemd ‘CCA&#8217;)</p>
<h5><strong>Algemene bepalingen</strong></h5>
<ol>
<li>Deze algemene voorwaarden zijn van toepassing op alle aanbiedingen en offertes van CCA, overeenkomsten met en sessies geboekt bij CCA.</li>
<li>Alle aanbiedingen en offertes van CCA zijn vrijblijvend, tenzij uitdrukkelijk anders vermeld. Eerdere offertes, prijsopgaven en dergelijke ten behoeve van een specifieke gebruiker worden geacht te zijn herroepen na het uitbrengen van een nieuwe offerte, prijsopgave etc. aan dezelfde gebruiker. Elke overeenkomst met een of meerdere gebruiker(s) komt pas tot stand op het moment dat CCA betaling heeft ontvangen voor de boeking van een of meerdere sessie(s).</li>
<li>Afwijkingen van deze algemene voorwaarden moeten uitdrukkelijk en schriftelijk tussen CCA en de gebruiker overeengekomen zijn. Indien een gebruiker boekt voor meerdere personen (medegebruikers), volstaat de overeenkomst met deze gebruiker.</li>
<li>In geval van strijd tussen de tekst van deze algemene voorwaarden en de bijzondere bepalingen uit de overeenkomst die werd afgesloten met een specifieke gebruiker, gaan de bepalingen uit de overeenkomst voor.</li>
<li>De nietigheid, onuitvoerbaarheid of onafdwingbaarheid van één of meerdere bepalingen van deze algemene voorwaarden laat de geldigheid, de uitvoerbaarheid of afdwingbaarheid van de overige bepalingen onverlet. Partijen zullen in goed overleg de ongeldige en onverbindende bepaling(en) vervangen door een wel geldige bepaling. De rechten en verplichtingen die uit de overeenkomst met een gebruiker voortvloeien, zullen noch gedeeltelijk noch in hun geheel op derden kunnen worden overgedragen, zonder de voorafgaande en schriftelijke toestemming van CCA. De niet-uitoefening door CCA of door de gebruiker van zijn/haar rechten, op eender welk ogenblik, impliceert geenszins de verzaking aan die rechten.</li>
<li>CCA is te allen tijde bevoegd de openingstijden, huisregels, prijzen en andere voorwaarden te wijzigen.</li>
<li>Elke gebruiker is verplicht op elk ogenblik alle redelijke medewerking te verlenen om CCA in staat te stellen haar overeenkomst (op verantwoorde wijze) conform haar verplichtingen na te komen.</li>
</ol>
<h5><strong>Verplichtingen van de gebruiker bij een sessie</strong></h5>
<ol>
<li>De gebruiker waarmee door CCA een overeenkomst werd afgesloten, dient ervoor te zorgen dat iedere persoon (medegebruiker) waarvoor hij (een) sessie(s) heeft geboekt, voldoet aan de volgende voorwaarden:</li>
</ol>
<ul>
<li>Hij/zij is verplicht bij het eerste bezoek een intake gesprek te doorlopen en alle vragen volledig en naar waarheid te beantwoorden;</li>
<li>Hij/zij is verplicht tot naleving van alle aanwijzingen en instructies van en namens CCA;</li>
<li>Hij/zij is verplicht tot naleving van alle aanwijzingen van CCA zoals deze zijn vermeld op de borden in het Sauna Center, de huisregels van CCA, etc.;</li>
<li>Hij/zij dient respect te hebben voor andere bezoekers, de omgeving, het personeel van CCA en de cabines, apparatuur, etc.;</li>
<li>Hij/zij is er van op de hoogte dat de materialen, cabines, apparatuur, etc. die worden gebruikt voor de sessie en zich in het Cryo Sauna Center bevinden zeer kostbaar zijn en hij/zij dient hier dan ook op zorgvuldige wijze mee om te gaan en deze uitsluitend overeenkomstig hun bestemming en de aanwijzingen te gebruiken;</li>
<li>Hij/zij dient zich te (kunnen) legitimeren en meerderjarig te zijn.</li>
</ul>
<ol start="2">
<li>Elke gebruiker moet omwille van de veiligheid, hygiënische en gezondheidsredenen tevens voldoen aan de volgende voorwaarden alvorens een sessie bij CCA te ondergaan :</li>
</ol>
<ul>
<li>Hij/zij dient droog zwemgoed of droge sportkleding te dragen, in de stof mogen geen metalen (dus ook geen metalen beugels) zijn verwerkt;</li>
<li>Hij/zij draagt tijdens de sessie geen contactlenzen en/of bril;</li>
<li>Hij/zij draagt geen sieraden, piercings, etc.;</li>
<li>Hij/zij draagt geen gehoorapparaat;</li>
<li>Hij/zij heeft tot twee uren voorafgaand aan de sessie geen crèmes, lotions of make-up op zijn/haar gezicht en/of lichaam aangebracht;</li>
<li>Zijn/haar huid en haar zijn droog;</li>
<li>Hij/zij heeft twee uren voorafgaande aan de sessie niet gesport;</li>
</ul>
<ol start="3">
<li>De Bezoeker dient uiterlijk een half uur voor aanvang van de sessie aanwezig te zijn op de Locatie en zich daar bij het personeel van CCA aan te melden.</li>
<li>Elke gebruiker moet op het gevraagde tijdstip aanwezig zijn alsook zich gedragen naar voormelde voorschriften. Indien dit niet het geval is, heeft CCA het recht deze gebruiker te weigeren zonder dat CCA gehouden is om (een deel) van de kosten van de sessie terug te betalen. De sessie geldt als te zijn afgenomen.</li>
</ol>
<p>Indien een gebruiker een maandabonnement heeft en drie keer in een maand niet (tijdig) voor een sessie aanwezig is en/of niet voldoet aan voormelde voorwaarden, heeft CCA het recht deze gebruiker de toegang te weigeren voor het resterende gedeelte van de betreffende maand zonder tot terugbetaling van het bedrag van het maandabonnement te moeten overgaan.</p>
<ol start="5">
<li>Elke gebruiker die zodanig hinder of last oplevert of kan opleveren, kan door CCA de sessie ontzegd worden. CCA is niet gehouden tot terugbetaling van (een deel van) de kosten van de sessie. De betreffende sessie geldt als te zijn afgenomen.</li>
</ol>
<p>Het personeel van CCA is altijd bevoegd om (een) gebruiker(s) (permanent) te weigeren indien zij daarvoor aanleiding ziet.</p>
<h5><strong>In de hiernavolgende gevallen is een sessie NIET toegestaan</strong></h5>
<ol>
<li>In een aantal gevallen kan een sessie gevaarlijk voor de gezondheid zijn. In die gevallen is een sessie in principe dan ook niet toegestaan :</li>
</ol>
<p><em>strikt verboden</em></p>
<ul>
<li>onder invloed van alcohol en/of drugs;</li>
<li>medicijnen gebruiken die het algemene functioneren beïnvloeden;</li>
<li>jonger dan 18 jaar oud;</li>
<li>de gebruiker heeft een infectieziekte of open wonden;</li>
</ul>
<p><em>behoudens toestemming van de huisarts</em></p>
<ul>
<li>zwangerschap;</li>
<li>koorts;</li>
<li>bloedarmoede;</li>
<li>koude allergie;</li>
<li>nu of in het verleden acute of symptomatische hart- en/of vaatziekten gehad hebben zoals (extreme of niet behandelde) hypertensie, vernauwing van de bloedvaten, aderverkalking en/of veneuze trombose, syndroom van Raynaud, te hoge of te lage bloeddruk, hartklachten zoals hartaanval, hartinfarct, hartritmestoornissen, longembolie, myocarditis, etc.;</li>
<li>de gebruiker draagt een pacemaker;</li>
<li>een of meerdere hersenaandoeningen hebben;</li>
<li>ongecontroleerde epileptische aanvallen;</li>
<li>in de afgelopen zes maanden een chronische of acute ziekte gehad hebben;</li>
<li>de gebruiker heeft op het moment van de sessie of in het verleden een of meerdere andere lichamelijke klachten en/of ziektes (gehad) op basis waarvan hij/zij kan vermoeden dat een sessie niet bevorderlijk of mogelijk zelfs schadelijk of gevaarlijk is voor zijn/haar gezondheid.</li>
</ul>
<ol start="2">
<li>Het is de verantwoordelijkheid van de gebruiker die de overeenkomst met CCA heeft afgesloten om ervoor te zorgen dat geen van de personen (medegebruikers) waarvoor hij/zij een sessie(en) heeft geboekt voldoet aan een van bovenstaande punten dan wel dat de perso(o)n(en) (medegebruiker(s)) waar dit voor geldt goedkeuring heeft/hebben van hun huisarts alvorens een sessie te ondergaan. Bij gebreke daaraan, wordt de sessie niet uitgevoerd en wordt de prijs niet terugbetaald. De sessie geldt als te zijn afgenomen.</li>
</ol>
<h5><strong>Betalingsmodaliteiten</strong></h5>
<ol>
<li>Elke sessie en/of aanschaf van een meerbeurtenkaart wordt, indien via internet wordt gereserveerd, onmiddellijk betaald op de voorgestelde betalingswijze. Indien een sessie ter plaatse wordt gereserveerd en/of ter plaatse een meerbeurtenkaart wordt aangekocht, dient de gebruiker onmiddellijk via bancontact te betalen. CCA accepteert geen contante betalingen.</li>
<li>Indien een maandabonnement wordt overeengekomen, gaat de gebruiker ermee akkoord dat deze maandelijkse vergoeding per domiciliëring van de opgegeven bankrekening afgeschreven wordt. Hiervoor zal de gebruiker op het ogenblik van reservatie het noodzakelijke document ondertekenen dat door CCA kan worden voorgelegd aan de bank. Indien de vergoeding op betaaldatum niet kan worden afgeschreven, zal binnen veertien dagen nogmaals worden geprobeerd het bedrag af te schrijven. Indien de gebruiker vóór (of na) het verstrijken van die termijn een sessie wenst te krijgen, is CCA gerechtigd het openstaande bedrag in een keer op te eisen alvorens de gebruiker een sessie te laten ondergaan. Kosten die CCA moet maken om betaling te krijgen, worden doorberekend aan de gebruiker, evenals redelijke administratiekosten.</li>
<li>CCA heeft het recht om haar verplichtingen uit hoofde van het maandabonnement op te schorten zolang een gebruiker niet aan zijn/haar betalingsverplichtingen heeft voldaan.</li>
<li>Onverminderd het hiervoor bepaalde geldt voor alle facturen van CCA een betalingstermijn van maximaal 14 dagen. Echter, indien CCA de volledige prijs van de sessie(s) niet uiterlijk 24 uur vóór het plaatsvinden van de sessie(s) heeft ontvangen, is zij gerechtigd de gebruiker(s) te weigeren.</li>
<li>Indien (een) gebruiker(s) goederen hu(u)r(t)(en) van CCA dient de betaling van de prijs hiervoor uiterlijk onmiddellijk voorafgaande aan de sessie(en) per bancontact te zijn voldaan.</li>
</ol>
<h5><strong>Annulatiemogelijkheden</strong></h5>
<ol>
<li>Elke sessie kan tot 24 uur vóór het moment van de sessie kosteloos worden geannuleerd. Een annulering kan telefonisch (0479764194) worden gedaan of via de website cryosaunacenter.com. Indien de Gebruiker (deels) annuleert krijgt deze een annuleringscode ter bevestiging van de annulering. Deze annuleringscode dient ter bewijs van de annulering. Als de gebruiker geen annuleringscode ontvangt, geldt de annulering als niet te zijn ontvangen door CCA. Het tijdstip van ontvangst van de annuleringscode door de gebruiker is het tijdstip op basis waarvan wordt bepaald of tijdig is geannuleerd. Bij het annuleren van een losse sessie, wordt, in geval van tijdige annulering, het door de gebruiker betaalde bedrag uiterlijk binnen 14 dagen na de annulering door CCA op het bij haar bekende rekeningnummer van de gebruiker teruggestort. In geval van een beurtenkaart of maandabonnement kan bij tijdige annulering een nieuwe sessie worden gereserveerd.</li>
<li>Bij een annulering binnen 24 uur vóór de sessie, of indien de gebruiker niet komt opdagen tijdens de sessie, worden de kosten van de sessie niet terugbetaald; een sessie wordt beschouwd als te zijn afgenomen.</li>
<li>Indien tijdens de intake (voorafgaand aan de sessie) blijkt dat de gebruiker de sessie toch niet mag ondergaan vanwege zijn/haar bloeddruk, geldt de sessie als niet te zijn afgenomen. In geval het een losse boeking betreft, wordt het bedrag van de betreffende sessie aan de gebruiker terugbetaald. In geval van een beurtenkaart wordt de sessie niet van de beurtenkaart afgeschreven.</li>
</ol>
<h5><strong>Specifieke bepalingen met betrekking tot maandabonnement en beurtenkaart</strong></h5>
<ol>
<li>Indien een maandabonnement wordt afgenomen, mag de gebruiker ten behoeve van wie het maandabonnement is afgesloten, iedere dag van de maand één keer per dag een sessie ondergaan. Een sessie dient van te voren te zijn gereserveerd.</li>
<li>Het maandabonnement en een beurtenkaart zijn persoonsgebonden en niet overdraagbaar.</li>
<li>Zowel de gebruiker als CCA kunnen te allen tijde het maandabonnement opzeggen. Indien dit wordt gedaan voor het einde van een maand geldt dat het maandbedrag van de daarop volgende maand niet meer wordt afgeschreven. Het maandabonnement wordt beëindigd tegen de datum in die maand waarop de maandtermijn verloopt.</li>
</ol>
<h5><strong>Aansprakelijkheid</strong></h5>
<ol>
<li>Indien één of meerdere van de (mede)gebruiker(s) waarvoor een gebruiker de overeenkomst is aangegaan niet voldoe(t)(n) aan een van de bepalingen uit deze algemene voorwaarden is, voor zover deze gebruiker niet aansprakelijk is jegens CCA, de gebruiker waarmee de overeenkomst werd afgesloten, aansprakelijk voor de schade van CCA veroorzaakt door de gedragingen en/of het nalaten van deze gebruiker(s), te beoordelen naar de maatstaf van het gedrag van een goed huisvader.</li>
<li>Indien een persoon (medegebruiker) goederen voor de sessie huurt van CCA, is de gebruiker waarmee de overeenkomst met CCA werd afgesloten ervoor verantwoordelijk dat deze goederen in dezelfde staat als waarin deze zijn ontvangen, direct na de sessie terug worden ingeleverd. Bij gebreke daaraan, is deze laatste gebruiker gehouden de schade aan CCA te vergoeden door de nieuwprijs van het gehuurde voorwerp aan CCA te vergoeden.</li>
<li>In het CCA Brasschaat is voor iedere gebruiker een kluisje beschikbaar voor zijn/haar persoonlijke eigendommen. De gebruiker dient al zijn/haar persoonlijke eigendommen in het kluisje voor eigen risico te bewaren gedurende de sessie. CCA is niet aansprakelijk voor diefstal, verlies, etc. van persoonlijke eigendommen.</li>
<li>Het is de eigen keuze van een gebruiker om een sessie te ondergaan. CCA is nooit aansprakelijk voor rechtstreekse of onrechtstreekse schade geleden door een gebruiker als gevolg van het uitvoeren van de overeenkomst en meer specifiek het ondergaan van een sessie, behoudens eigen opzet of zware fout.</li>
</ol>
<p>CCA kan verder ook geenszins aansprakelijk worden gehouden indien de gebruiker de instructies (van het personeel) van CCA, de (huis)regels etc. van CCA niet strikt en volledig heeft opgevolgd. Hetzelfde geldt indien de gebruiker niet voldoet aan voorwaarden genoemd in deze Voorwaarden dan wel in andere door de gebruiker ondertekende documenten.</p>
<h5><strong>Herroepingsrecht voor consumenten</strong></h5>
<ol>
<li>Als de gebruiker een consument is en elektronisch een of meerdere sessie(s) heeft geboekt en betaald of als de gebruiker als consument een beurtenkaart heeft afgenomen of een maandabonnement heeft afgesloten kan de gebruiker deze binnen 14 dagen na het moment van betalen (in het geval van een maandabonnement is dit de eerste betaling) zonder opgave van redenen ontbinden op grond van het herroepingsrecht. Als de sessie eerder plaatsvindt dan het verstrijken van deze 14 dagen termijn, is dit op uitdrukkelijk verzoek van de gebruiker en kan de gebruiker na de sessie geen beroep meer doen op het herroepingsrecht.</li>
<li>Om het herroepingsrecht uit te oefenen dient de gebruiker CCA via een ondubbelzinnige verklaring (bijv. per post of e-mail) op de hoogte te stellen van zijn/haar beslissing de overeenkomst te herroepen. Deze verklaring kan naar het (e-mail) adres zoals vermeld op de website van CCA worden gestuurd. De gebruiker kan hiervoor gebruik maken van het modelformulier voor herroeping, maar is hiertoe niet verplicht. Om de herroepingstermijn na te leven volstaat het om de mededeling betreffende de uitoefening van het herroepingsrecht te verzenden voordat de herroepingstermijn is verstreken.</li>
<li>Als de gebruiker de overeenkomst herroept, ontvangt de gebruiker alle betalingen die hij/zij tot op dat moment heeft gedaan onverwijld en in ieder geval niet later dan 14 dagen nadat CCA op de hoogte is gesteld van de beslissing de overeenkomst te herroepen, van CCA terug. CCA betaalt de gebruiker terug met hetzelfde betaalmiddel als waarmee de gebruiker de oorspronkelijke transactie heeft verricht zonder dat voor de terugbetaling kosten in rekening worden gebracht.</li>
</ol>
<h5><strong>Toepasselijk recht/Geschillenbeslechting</strong></h5>
<ol>
<li>Op alle rechtsverhoudingen tussen CCA en Gebruiker is Belgisch recht van toepassing. Alle geschillen in verband met de (totstandkoming van de) overeenkomst en alle rechtsverhoudingen die daaruit voortvloeien dienen aanhangig te worden gemaakt bij de bevoegde rechter in Antwerpen tenzij uit dwingend recht een andere rechtbank voortvloeit.</li>
</ol>
<h5 class="mt-5"><strong>PRIVACYREGLEMENT:</strong></h5>
<p style="text-align: left;">Deze pagina beschrijft het beleid dat bij  Flow2seE bvba, Cryo Sauna Center, Oude Baan 143, gevestigd te 2930 Brasschaat; BTW nr.0875.443.113 (hierna genoemd ‘CCA&#8217;), wordt toegepast inzake de persoonlijke levenssfeer evenals de verwerking van alle informatie die verzameld wordt tijdens of naar aanleiding van uw bezoek aan CCA.<br />
CCA vindt jouw privacy belangrijk. Daarom geeft CCA in dit reglement een toelichting op hoe zij met jouw gegevens omgaat, wat het doel is van het gebruik daarvan en voor de verwerking van welke gegevens CCA expliciet om jouw toestemming moet vragen.<br />
Doel van de verwerking van de persoonsgegevens<br />
Bij de registratie op deze website, een bezoek aan CCA en/of plaatsing van een bestelling, zal van u een aantal persoonsgegevens gevraagd worden. Deze persoonsgegevens zijn noodzakelijk voor de correcte en vlotte werking van CCA. De gegevens worden gebruikt om de door u gevraagde goederen en diensten te kunnen leveren.<br />
Gevraagde informatie :<br />
Aan de gebruiker waarmee de overeenkomst wordt afgesloten, vraagt CCA om naam, telefoonnummer, een e-mailadres en het doen van een betaling. Deze gegevens zijn nodig om: &#8211; de boeking bij CCA financieel en administratief te kunnen afhandelen en  &#8211; de desbetreffende gebruiker te kunnen bereiken als dat nodig is.<br />
Aan een effectieve gebruiker vraagt CCA daarnaast persoonsgegevens en gegevens met betrekking tot de gezondheid (het gebruik van medicatie en een eventuele ziektegeschiedenis). Deze gegevens zijn nodig om: &#8211; jou een goede en veilige behandeling te bieden; &#8211; de verstrekte gegevens administratief te kunnen verwerken zodat CCA jouw gezondheidsgegevens kan koppelen aan jouw persoonsgegevens; &#8211; de veiligheid en gezondheid van andere bezoekers en/of het personeel van CCA en/of CCA zelf te waarborgen (bijvoorbeeld bij besmettelijke ziektes); &#8211; indien nodig te voldoen aan een wettelijke meldingsplicht.<br />
Voorafgaand aan de (eerste) sessie zal CCA bovendien een foto van elke gebruiker maken. Deze foto neemt CCA op in haar digitale administratie. Deze foto heeft CCA nodig omdat: &#8211; CCA daarmee bij ieder bezoek de identiteit van de gebruiker kan controleren; &#8211; met de aanwezigheid van de foto in haar systeem vast staat dat CCA de hierboven bedoelde gezondheidsgegevens van jou heeft ontvangen; &#8211;  deze koppeling van gegevens noodzakelijk is voor het bieden van een goede en veilige Behandeling.<br />
Facebook CCA vindt het leuk om de ervaringen van gebruikers na een behandeling op haar locatie te filmen en deze filmpjes ter promotie op haar Facebookpagina te plaatsen. Hiertoe plaatst CCA een zuil met een videocamera waar jij zelf een kort filmpje kunt opnemen. Na de opname kun je zelf beoordelen of je akkoord gaat met het plaatsen van het filmpje op Facebook. Dit kun je doen door na het terugkijken van het filmmateriaal de optie akkoord aan te vinken.<br />
Verwerking van de persoonsgegevens en de personen die toegang hebben tot de verwerkte gegevens<br />
De verstrekte gegevens worden opgenomen in de bestanden van CCA. De gegevens zullen enkel verwerkt worden in de mate dat de verwerking nodig is voor de uitvoering van de overeenkomst die u met CCA heeft afgesloten.<br />
Uw persoonlijke gegevens zullen niet verkocht of verzonden worden aan derden, noch openbaar gemaakt worden.<br />
CCA behoudt zich wel het recht voor uw gegevens te gebruiken of openbaar te maken, wanneer dit noodzakelijk is om de integriteit van haar organisatie te vrijwaren, wanneer de wet dit eist, wanneer de verwerking voor u van levensbelang is, wanneer de verwerking moet gebeuren om een taak van openbaar belang te vervullen of nog wanneer de gegevensverwerking noodzakelijk is om een gerechtvaardigd belang te behartigen.<br />
Uw rechten met betrekking tot de door ons verzamelde gegevens<br />
voor het verwerken van de gegevens met betrekking tot de gezondheid heeft CCA steeds jouw expliciete toestemming nodig. Die krijgt CCA doordat jij aangeeft akkoord te gaan met dit reglement. Indien gewenst kun je dit reglement opslaan en/of printen; &#8211; jouw persoonsgegevens kunnen enkel en alleen door het personeel van CCA worden ingezien, tenzij in dit reglement anders is bepaald. Al jouw persoonsgegevens worden door CCA beveiligd tegen onbevoegde toegang. De beveiliging bestaat uit het hebben van een persoonlijk wachtwoord voor iedere werknemer om in te loggen in het digitale systeem; &#8211; de medewerkers van CCA hebben een geheimhoudingsplicht ten aanzien van alle aan CCA verstrekte persoonsgegevens; &#8211; jouw persoonsgegevens worden niet langer bewaard dan noodzakelijk is voor een goede administratie. CCA hanteert een termijn van twee jaar na het laatste bezoek waarna de gegevens vernietigd worden.<br />
U heeft op elk ogenblik het recht uw gegevens in te kijken en te verbeteren. Tevens heeft u het recht u kosteloos te verzetten tegen het gebruik van uw gegevens voor directe marketing.<br />
Als je een klacht hebt over de wijze van verwerking van jouw persoonsgegevens kun je contact met CCA opnemen en probeert CCA er samen met jou uit te komen.</p>';

        $json['settings']['general']['medical_declaration'] = '<p class="mb-0"><small>Hebt u een hoge bloeddruk of artropathie? <b>Nee</b></small></p>
<p class="mb-0"><small>Hebt u minder dan 6 maanden geleden een hartinfarct, CVA of longontsteking gehad? <b>Nee</b></small></p>
<p class="mb-0"><small>Bent u COPD- of astmapatiënt? <b>Nee</b></small></p>
<p class="mb-0"><small>Zijn er stoornissen in de bloedsomloop (syndroom van Raynaud) vastgesteld? <b>Nee</b></small></p>
<p class="mb-0"><small>Hebt u ooit angina pectoris gehad? <b>Nee</b></small></p>
<p class="mb-0"><small>Hebt u een diepe of oppervlakkige trombose? <b>Nee</b></small></p>
<p class="mb-0"><small>Hebt u nierkolieken, of galkolieken? <b>Nee</b></small></p>
<p class="mb-0"><small>Bent u allergisch aan koude? <b>Nee</b></small></p>
<p class="mb-0"><small>Hebt u last van huidinfectie? (acuut bacterieel of viraal)? <b>Nee</b></small></p>
<p class="mb-0"><small>Lijdt u aan acute infectie? <b>Nee</b></small></p>
<p class="mb-0"><small>Hebt u recent alcohol of drugs gebruikt? <b>Nee</b></small></p>
<p class="mb-0"><small>Bent u zwanger? <b>Nee</b></small></p>
<p class="mb-0"><small>Hebt u metalen prothese in uw lichaam? <b>Nee</b></small></p>

<p class="mt-3"><small><b>Indien u minstens eenmaal een vraag met Ja beantwoordt ga dan eerst even langs uw huisarts vooraleer u een Cryosessie boekt en informeer cryocenterantwerp-team.</b></small></p>

<div class="form-group form-group-default">
    <label class="mb-0" for="cmb_medical_declaration_1">
        <small>
            <input type="checkbox" class="mr-2" name="cmb_medical_declaration_1" id="cmb_medical_declaration_1" checked disabled> Ik verklaar de inlichtingen gelezen te hebben over cryotherapie en de medische vragenlijst eerlijk te hebben beantwoord.
        </small>
    </label>

    <label class="mb-0" for="cmb_medical_declaration_2">
        <small>
            <input type="checkbox" class="mr-2" name="cmb_medical_declaration_2" id="cmb_medical_declaration_2" checked disabled> Ik stem toe deel te nemen aan de behandelingen van cryotherapie voor het hele lichaam.
        </small>
    </label>

    <label class="mb-0" for="cmb_medical_declaration_3">
        <small>
            <input type="checkbox" class="mr-2" name="cmb_medical_declaration_3" id="cmb_medical_declaration_3" checked disabled> Ik verbind mij ertoe de richtlijnen, opgelegd door de uitbater te eerbiedigen en onmidellijk ieder nieuw medisch probleem te vermelden voor elke cryosessie.
        </small>
    </label>
</div>';

        $json['settings']['appointment'] = [
            'can_guest_checkout' => true,
            'title' => 'string',
        ];

        $json['settings']['invoice']['prefix'] = '';
        $json['settings']['invoice']['number'] = 0;


        $json['settings']['appointment']['statuses'] = [
            'new' => [
                'display_name' => ['nl' => 'Nieuwe afspraak', 'en' => 'New appointment'],
                'short' => ['nl' => 'Nieuw', 'en' => 'New'],
                'send_email' => false,
                'email' => [],
                'invoice' => false,
                'paid' => false,
                'deposit_paid' => false
            ],
            'awaiting' => [
                'display_name' => ['nl' => 'In afwachting van betaling', 'en' => 'Awaiting payment'],
                'short' => ['nl' => 'Afwachting', 'en' => 'Awaiting'],
                'send_email' => false,
                'email' => [],
                'invoice' => false,
                'paid' => false,
                'deposit_paid' => false
            ],
            'canceled' => [
                'display_name' => ['nl' => 'Afspraak geannuleerd', 'en' => 'Appointment canceled'],
                'short' => ['nl' => 'Geannuleerd', 'en' => 'Canceled'],
                'send_email' => true,
                'email' => [
                    'customer' => [
                        'to' => '[%APPOINTMENT_EMAIL%]',
                        'to_name' => '[%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-booker::emails.default',
                        'logo' => true,
                        'ics' => false,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak #[%APPOINTMENT_NUMBER%] werd geannuleerd',
                                    'en' => 'Your appointment #[%APPOINTMENT_NUMBER%] was canceled'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak met bestelnummer #[%APPOINTMENT_NUMBER%] werd geannuleerd. In deze mail vindt u meer informatie terug.',
                                    'en' => 'Your appointment #[%APPOINTMENT_NUMBER%] was canceled. You can find more information in this email.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]<br><br>Uw afspraak werd geannuleerd. Indien u reeds heeft betaald wordt dit bedrag automatisch teruggestort op het rekeningnummer dat gelinkt is aan de kaart waarmee u de betaling heeft uitgevoerd. <br><br> Denkt u dat het niet de bedoeling dat deze bestelling geannuleerd werd? Geen zorgen, neem dan contact op met de klantendienst.',
                                    'en' => 'Dear [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]<br><br>Your appointment was canceled. If you have already paid then we will automatically debit the account linked to the card that has been used to pay this order. <br><br>Do you think this order was not supposed to be canceled? No worries, contact our customer support.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak',
                                    'en' => 'Your appointment'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw afspraak. <br><br> <b>Verzending:</b> [%APPOINTMENT_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%APPOINTMENT_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%APPOINTMENT_SERVICES%] <br> <b>Verzendkosten</b>: [%APPOINTMENT_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%APPOINTMENT_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br> E-mail: [%APPOINTMENT_EMAIL%] <br> Tel: [%APPOINTMENT_TELEPHONE%] <br> Bedrijf: [%APPOINTMENT_COMPANY%] <br> BTW: [%APPOINTMENT_COMPANY_VAT%] <br> Adres: <br>[%APPOINTMENT_BILLING_STREET%] [%APPOINTMENT_BILLING_HOUSENUMBER%], <br>[%APPOINTMENT_BILLING_POSTALCODE%] [%APPOINTMENT_BILLING_CITY%], [%APPOINTMENT_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br>Adres:<br>[%APPOINTMENT_SHIPPING_STREET%] [%APPOINTMENT_SHIPPING_HOUSENUMBER%], <br>[%APPOINTMENT_SHIPPING_POSTALCODE%] [%APPOINTMENT_SHIPPING_CITY%], [%APPOINTMENT_SHIPPING_COUNTRY%]',
                                    'en' => 'Below you will find another summary of your appointment. <br><br> <b>Shipping:</b> [%APPOINTMENT_CARRIER_NAME%] <br> <b>Transit time:</b> [%APPOINTMENT_CARRIER_TRANSIT_TIME%] <br><br> <b>Order: </b> <br> [%APPOINTMENT_SERVICES%] <br> <b>Shipping fees</b>: [%APPOINTMENT_SHIPPING_TOTAL%] <br><br> <b>Total</b>: [%APPOINTMENT_FINAL%] <br><br> <b>Invoice address: </b> <br> Name: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br> E-mail: [%APPOINTMENT_EMAIL%] <br> Tel: [%APPOINTMENT_TELEPHONE%] <br> Company: [%APPOINTMENT_COMPANY%] <br> VAT: [%APPOINTMENT_COMPANY_VAT%] <br> Address: <br>[%APPOINTMENT_BILLING_STREET%] [%APPOINTMENT_BILLING_HOUSENUMBER%], <br>[%APPOINTMENT_BILLING_POSTALCODE%] [%APPOINTMENT_BILLING_CITY%], [%APPOINTMENT_BILLING_COUNTRY%] <br><br> <b>Shipping address:</b><br>Name: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br>Address:<br>[%APPOINTMENT_SHIPPING_STREET%] [%APPOINTMENT_SHIPPING_HOUSENUMBER%], <br>[%APPOINTMENT_SHIPPING_POSTALCODE%] [%APPOINTMENT_SHIPPING_CITY%], [%APPOINTMENT_SHIPPING_COUNTRY%]'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw afspraak? U kan ons steeds contacteren.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Do you have any other questions about this order? You can always reach us.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name')
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
                    ]
                ],
                'invoice' => false,
                'paid' => false,
                'deposit_paid' => false
            ],
            'error' => [
                'display_name' => ['nl' => 'Betalingsfout', 'en' => 'Payment Error'],
                'short' => ['nl' => 'Betalingsfout', 'en' => 'Payment Error'],
                'send_email' => true,
                'email' => [
                    'customer' => [
                        'to' => '[%APPOINTMENT_EMAIL%]',
                        'to_name' => '[%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-ecommerce::emails.default',
                        'logo' => true,
                        'ics' => false,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak #[%APPOINTMENT_NUMBER%] is mislukt',
                                    'en' => 'Your appointment #[%APPOINTMENT_NUMBER%] has failed'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak met bestelnummer #[%APPOINTMENT_NUMBER%] is mislukt. In deze mail vindt u meer informatie over uw afspraak terug.',
                                    'en' => 'Your appointment #[%APPOINTMENT_NUMBER%] has failed. In this e-mail you will find more information on your appointment.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]<br><br>Uw afspraak is mislukt. Helaas is er iets misgegaan met de betaling. Heeft u nog vragen? Neem gerust contact met ons op.',
                                    'en' => 'Dear [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]<br><br>Your appointment has failed. Unfortunately something went wrong with your payment. Do you have any other questions? Please don\'t hesitate to contact us.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak',
                                    'en' => 'Your appointment'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw afspraak. <br><br> [%APPOINTMENT_SERVICES%] <br> <b>Verzendkosten</b>: [%APPOINTMENT_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%APPOINTMENT_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br> E-mail: [%APPOINTMENT_EMAIL%] <br> Tel: [%APPOINTMENT_TELEPHONE%] <br> Bedrijf: [%APPOINTMENT_COMPANY%] <br> BTW: [%APPOINTMENT_COMPANY_VAT%] <br> Adres: <br>[%APPOINTMENT_BILLING_STREET%] [%APPOINTMENT_BILLING_HOUSENUMBER%], <br>[%APPOINTMENT_BILLING_POSTALCODE%] [%APPOINTMENT_BILLING_CITY%], [%APPOINTMENT_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br>Adres:<br>[%APPOINTMENT_SHIPPING_STREET%] [%APPOINTMENT_SHIPPING_HOUSENUMBER%], <br>[%APPOINTMENT_SHIPPING_POSTALCODE%] [%APPOINTMENT_SHIPPING_CITY%], [%APPOINTMENT_SHIPPING_COUNTRY%]',
                                    'en' => 'Below you will find an overview of your appointment. <br><br> [%APPOINTMENT_SERVICES%] <br> <b>Shipping costs</b>: [%APPOINTMENT_SHIPPING_TOTAL%] <br><br> <b>Total</b>: [%APPOINTMENT_FINAL%] <br><br> <b>Invoice address: </b> <br> Name: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br> E-mail: [%APPOINTMENT_EMAIL%] <br> Tel: [%APPOINTMENT_TELEPHONE%] <br> Company: [%APPOINTMENT_COMPANY%] <br> VAT: [%APPOINTMENT_COMPANY_VAT%] <br> Address: <br>[%APPOINTMENT_BILLING_STREET%] [%APPOINTMENT_BILLING_HOUSENUMBER%], <br>[%APPOINTMENT_BILLING_POSTALCODE%] [%APPOINTMENT_BILLING_CITY%], [%APPOINTMENT_BILLING_COUNTRY%] <br><br> <b>Shipping address:</b><br>Name: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br>Address:<br>[%APPOINTMENT_SHIPPING_STREET%] [%APPOINTMENT_SHIPPING_HOUSENUMBER%], <br>[%APPOINTMENT_SHIPPING_POSTALCODE%] [%APPOINTMENT_SHIPPING_CITY%], [%APPOINTMENT_SHIPPING_COUNTRY%]'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw afspraak? U kan ons steeds contacteren.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Your appointment is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
                    ]
                ],
                'invoice' => false,
                'paid' => false,
                'deposit_paid' => false
            ],
            'confirmed' => [
                'display_name' => ['nl' => 'Bevestigd', 'en' => 'Confirmed'],
                'short' => ['nl' => 'Bevestigd', 'en' => 'Confirmed'],
                'send_email' => true,
                'email' => [
                    'customer' => [
                        'to' => '[%APPOINTMENT_EMAIL%]',
                        'to_name' => '[%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-booker::emails.default',
                        'logo' => true,
                        'ics' => false,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak #[%APPOINTMENT_NUMBER%] is verzonden',
                                    'en' => 'Your appointment #[%APPOINTMENT_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak met bestelnummer #[%APPOINTMENT_NUMBER%] is onderweg. In deze mail vindt u meer informatie over uw afspraak terug.',
                                    'en' => 'Your appointment #[%APPOINTMENT_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]<br><br>Uw afspraak is onderweg. Uw afspraak wordt volgende werkdag geleverd tussen 9:00u en 19:00u. Is er niemand thuis? Dan proberen we het de dag erna nog eens, maak u geen zorgen. Heeft u nog vragen? Neem gerust contact met ons op.',
                                    'en' => 'Order is shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak',
                                    'en' => 'Your appointment'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw afspraak. <br><br> <b>Verzending:</b> [%APPOINTMENT_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%APPOINTMENT_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%APPOINTMENT_SERVICES%] <br> <b>Verzendkosten</b>: [%APPOINTMENT_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%APPOINTMENT_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br> E-mail: [%APPOINTMENT_EMAIL%] <br> Tel: [%APPOINTMENT_TELEPHONE%] <br> Bedrijf: [%APPOINTMENT_COMPANY%] <br> BTW: [%APPOINTMENT_COMPANY_VAT%] <br> Adres: <br>[%APPOINTMENT_BILLING_STREET%] [%APPOINTMENT_BILLING_HOUSENUMBER%], <br>[%APPOINTMENT_BILLING_POSTALCODE%] [%APPOINTMENT_BILLING_CITY%], [%APPOINTMENT_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br>Adres:<br>[%APPOINTMENT_SHIPPING_STREET%] [%APPOINTMENT_SHIPPING_HOUSENUMBER%], <br>[%APPOINTMENT_SHIPPING_POSTALCODE%] [%APPOINTMENT_SHIPPING_CITY%], [%APPOINTMENT_SHIPPING_COUNTRY%]',
                                    'en' => 'Your appointment is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw afspraak? U kan ons steeds contacteren.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Your appointment is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
                    ]
                ],
                'invoice' => true,
                'paid' => true,
                'deposit_paid' => true
            ],
            'payment' => [
                'display_name' => ['nl' => 'Betaald', 'en' => 'Paid'],
                'short' => ['nl' => 'Betaald', 'en' => 'Paid'],
                'send_email' => true,
                'email' => [
                    'customer' => [
                        'to' => '[%APPOINTMENT_EMAIL%]',
                        'to_name' => '[%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-booker::emails.default',
                        'logo' => true,
                        'ics' => true,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak is bevestigd en betaald',
                                    'en' => 'Your appointment was confirmed and paid'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak bij XXX is bevestigd en betaald. In deze mail vindt u meer informatie over uw afspraak terug.',
                                    'en' => 'Your appointment with XXX was confirmed and paid'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%]<br><br>Uw afspraak is onderweg. Uw afspraak wordt volgende werkdag geleverd tussen 9:00u en 19:00u. Is er niemand thuis? Dan proberen we het de dag erna nog eens, maak u geen zorgen. Heeft u nog vragen? Neem gerust contact met ons op.',
                                    'en' => 'Order is shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw afspraak',
                                    'en' => 'Your appointment'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw afspraak. <br><br> <b>Verzending:</b> [%APPOINTMENT_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%APPOINTMENT_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%APPOINTMENT_SERVICES%] <br> <b>Verzendkosten</b>: [%APPOINTMENT_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%APPOINTMENT_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br> E-mail: [%APPOINTMENT_EMAIL%] <br> Tel: [%APPOINTMENT_TELEPHONE%] <br> Bedrijf: [%APPOINTMENT_COMPANY%] <br> BTW: [%APPOINTMENT_COMPANY_VAT%] <br> Adres: <br>[%APPOINTMENT_BILLING_STREET%] [%APPOINTMENT_BILLING_HOUSENUMBER%], <br>[%APPOINTMENT_BILLING_POSTALCODE%] [%APPOINTMENT_BILLING_CITY%], [%APPOINTMENT_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%APPOINTMENT_FIRST_NAME%] [%APPOINTMENT_LAST_NAME%] <br>Adres:<br>[%APPOINTMENT_SHIPPING_STREET%] [%APPOINTMENT_SHIPPING_HOUSENUMBER%], <br>[%APPOINTMENT_SHIPPING_POSTALCODE%] [%APPOINTMENT_SHIPPING_CITY%], [%APPOINTMENT_SHIPPING_COUNTRY%]',
                                    'en' => 'Your appointment is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw afspraak? U kan ons steeds contacteren.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Your appointment is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
                    ]
                ],
                'invoice' => true,
                'paid' => true,
                'deposit_paid' => true
            ]
        ];
        $json['settings']['customer'] = [
            'is_tel_required' => true,
            'title' => 'string',
        ];
        $json['settings']['integrations']['mollie'] = [];
        $json['settings']['integrations']['mollie']['key'] = null;
        $json['settings']['integrations']['mollie']['methods'] = ['bancontact', 'belfius', 'creditcard', 'ideal', 'inghomepay', 'kbc', 'paypal'];

        // create the module
        $module = $this->moduleRepository->createFromArray([
            'name' => $name,
            'slug' => $slug,
            'hintpath' => $hintpath,
            'path' => $path,
            'type' => $type,
            'version' => $version,
            'author' => $author,
            'json' => $json
        ]);


        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('.... AWESOME ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('....   JOB   ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info(' ');
        $this->info('Module installed: ChuckCMS Booker Module');
        $this->info(' ');
    }
    
}
