<!-- Hello <i>{{ $geek->receiver }}</i>,
<p>Here is License Key for the product you just bought from geekrepair,nl</p>

<h2><strong>{{ $geek->license_key }}</strong></h2>
<p><u>Values passed by With method:</u></p>
 
Thank You,
<br/>
<i>{{ $geek->sender }}</i> -->

<p><strong>Beste</strong></p>

<p>Bedankt voor je aankoop. Wij zullen deze met de grootste precisie behandelen om ervoor te zorgen dat jij tevreden bent met je product. 
Hierbij de licenties die je aangeschaft hebt:</p>
<h1>{{ $geek->product_name }}</h1>
<h2><strong>{{ $geek->license_key }}</strong></h2>

<p>Voor de installatiehandleiding en software van je producten kan je terecht op onze website <a href="www.geekrepair.nl">www.geekrepair.nl</a> of klik <a href="www.geekrepair.nl/pages/downloads">hier</a> indien je een installatie dvd hebt aangeschaft dan gaat deze dezelfde dag (indien voor 17:00 besteld) nog mee met de post</p>

<p>Graag zien we je spoedig terug bij Geekrepair. Indien je nog vragen hebt kan je altijd een mail sturen naar <a href="#">info@geekrepair.nl</a></p>

<p><strong>Met vriendelijke groet,</p></strong>
<p><strong>Team Geekrepair</p></strong>
