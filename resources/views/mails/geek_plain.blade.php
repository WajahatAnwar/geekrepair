<!-- Hello <i>{{ $geek->receiver }}</i>,
<p>Here is License Key for the product you just bought from geekrepair,nl</p>

<h2><strong>{{ $geek->license_key }}</strong></h2>
<p><u>Values passed by With method:</u></p>
 
Thank You,
<br/>
<i>{{ $geek->sender }}</i> -->

Beste,

Bedankt voor je aankoop. Wij zullen deze met de grootste precisie behandelen om ervoor te zorgen dat jij tevreden bent met je product. 
Hierbij de licenties die je aangeschaft hebt:

<h1>{{ $geek->product_name }}</h1>
<h2><strong>{{ $geek->license_key }}</strong></h2>

Voor de installatiehandleiding en software van je producten kan je terecht op onze website <a href="www.geekrepair.nl">www.geekrepair.nl</a> of klik <a href="www.geekrepair.nl/pages/downloads">hier</a>

Graag zien we je spoedig terug bij Geekrepair. Indien je nog vragen hebt kan je altijd een mail sturen naar <a href="mailto:info@geekrepair.nl">mailto:info@geekrepair.nl</a>

Met vriendelijke groet,
Team Geekrepair
