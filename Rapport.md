# Inledning

[Körbar applikation](http://www.nexso-programmer.net)

Jag har valt att göra en mashup-applikation som använder tre olika api:er. Jag använder google maps för att få en karta i min applikation. I kartan placerade jag information från boolis api. Boolis api ger information om bostäder som är till salu. Sista api:et som jag använde var SCB. Av scb tog jag information om län och kommuner för att presentera ett lätt sätt att söka på dessa. Av scb tog jag även statistik om olika typer av bostäder för att presenteras för varje kommun användaren sökt på. 
##Schema
Jag har använt mvc arkitektur när jag skrev applikationen.

[Sekvensdiagram](https://github.com/ss223ck/1dv449_ss223ck_projekt/blob/master/Booli.png)

[Classdiagram](https://github.com/ss223ck/1dv449_ss223ck_projekt/blob/master/Class-diagram.png)


## Optimering
Jag har minifierat javascriptfilerna och cssfilerna. Detta för att spara utrymme. Jag använder bara en javascriptfil som jag skrivit själv. Detta för att bara behöva ladda in javascriptresursen en gång. 

## Säkerhet
Applikationen hanterar ingen känslig data och har därför inte stort behov av säkerhet. Code injections går inte att göra på sidan eftersom användaren inte kan posta någon till servern som ska sparas. CSRF attack går inte att göra eftersom användaren inte loggar in på applikationen. Applikationen använder ingen databas därför går det inte att göra sql-inject. Datan kan skickas okrypterat mellan klienten och servern eftersom inte känslig data behandlas.

## Offline first
Jag har valt att spara information från SCB med komuner och län på serverns cache och sparat kommuner för län som användaren har sökt på i deras klient i localstorage. Serverns cache uppdaterar jag en gång om dagen. Det kanske är onödigt ofta eftersom kommuner och deras koder inte ändras speciellt ofta. Jag sparar även kommuners bostadsdata på localstorage. Så om användaren är offline men tidigare hämtat denna statistik presenteras den igen. 

## Risker med applikationen
### Tekniska
Slutar google maps att fungera så är applikationen i princip obrukbar. Jag har ännu inte hittat en lösning till detta problem. Jag skulle vilja ha någon form av cache av google maps. Dock så tror jag det skulle ta mycket utrymme. 
Booli:s api bör också fungera för att applikationen ska vara intressant. Jag funderade på om jag skulle spara informationen av booli:s api i localstorage. Även om informationen som anges inte direkt är den senaste så skulle den fortfarande kunna vara av intresse eftersom bostäder ligger ute längre än vad booli:s api förmodas vara nedstängt. 

### Etiskt
Applikationen är väldigt lik booli:s egna applikation. Jag har lagt till lite information i applikationen som gör den annourlunda från booli:s vanliga applikation. 

### Egna reflektioner
Skulle jag haft mer tid skulle jag lagt till mer information om kommunen man söker bostäder i. Jag skulle även gett användaren möjlighet att välja vilken form av information applikationen ska publicera för användaren. Skulle jag implementerat dessa funktioner skulle applikationen verka mycket bättre. Jag tycker grunden jag har lagt är bra och har potential att utökas. 
###Problem
Jag har haft svårt för hur jag ska använda api:erna. Dokumentationen från SCB var svår att hitta och svår att förstå. När man förstod hur man skulle göra blev det api:et enkelt att använda. Jag har lärt mig att ju mer invecklat ett api är desto viktigare är det med en bra dokumentation. 

Jag hade problem i början med ajax-anrop till servern. Jag först använde jag inte j-query för att anropa servern. När jag började använda j-query blev det mycket lättare. Jag har inte så stor vana av j-query och det var intressant att lära mig mer om det. J-query underlättar verkligen i utvecklingen av javascript och jag förstår nu varför man väljer att utveckla biliotek. 

Jag var osäker på hur och vilken information jag skulle lagra i cache. Jag hade svårt att veta vilken som skulle passa bäst på servern och vilken som skulle passa bäst på klienten. Jag skulle möjlightvis kunna lagra booli-objekten på servern. Det skulle spara mycket anrop till deras api. Samtidigt är det svårt att veta hur länge man ska lagra det i cache:n, dom skriver explicit i deras vision att booli publicerar dom senaste objekten mycket snabbare än deras konkurrenter. 

