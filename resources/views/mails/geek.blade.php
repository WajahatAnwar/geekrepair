Hello <i>{{ $geek->receiver }}</i>,
<p>Here is License Key for the product you just bought from geekrepair,nl</p>
<?php $license_array = $geek->license_key; ?>
@foreach( $license_array as $license)
<h2><strong>{{ $license }}</strong></h2>
@endforeach
<p><u>Values passed by With method:</u></p>
 
Thank You,
<br/>
<i>{{ $geek->sender }}</i>