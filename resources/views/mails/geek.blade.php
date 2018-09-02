Hello <i>{{ $geek->receiver }}</i>,
<p>Here is License Key for the product you just bought from geekrepair,nl</p>
<?php $license_array = $geek->license_key; ?>
@for($i = 0; $i < $license_array.lenght; $i++)
<h2><strong>{{ $license[$i] }}</strong></h2>
@endfor
<p><u>Values passed by With method:</u></p>
 
Thank You,
<br/>
<i>{{ $geek->sender }}</i>