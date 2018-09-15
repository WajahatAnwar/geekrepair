

<?php $counting = count($testVarOne); ?>
    @for ($i = 0; $i < $counting; $i++)
        <label>{{ $testVarOne[$i] }}</label>
    @endfor
<p><strong>Beste</strong></p>

<p>Bedankt voor je aankoop. Wij zullen deze met de grootste precisie behandelen om ervoor te zorgen dat jij tevreden bent met je product. 
Hierbij de licenties die je aangeschaft hebt:</p>
<h1>{{ $geek->product_name }}</h1>

<p>Voor de installatiehandleiding en software van je producten kan je terecht op onze website <a href="www.geekrepair.nl">www.geekrepair.nl</a> of klik <a href="www.geekrepair.nl/pages/downloads">hier</a> indien je een installatie dvd hebt aangeschaft dan gaat deze dezelfde dag (indien voor 17:00 besteld) nog mee met de post</p>

<p>Graag zien we je spoedig terug bij Geekrepair. Indien je nog vragen hebt kan je altijd een mail sturen naar <a href="#">info@geekrepair.nl</a></p>

<p><strong>Met vriendelijke groet,</p></strong>
<p><strong>Team Geekrepair</p></strong>
