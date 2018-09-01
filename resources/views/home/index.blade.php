@extends('layouts.app')

@section('content')
	<header style="background-color: #5e8f3f;color: #ffffff;">
		<img src="{{ asset('carrer.png') }}" alt="logo of stack apps" style="width:45px;">
        <h1>Stack Apps</h1>
        <h2>A Simple App which get you sales</h2>
    </header>
	</br>
	<!-- <section>
		<aside>
			<h2>Aside Heading</h2>
			<p>content</p>
		</aside>
		<article>
			<div class="card">
				<div id = "text">
					<h1 id = "h1">Grumpy wizards make toxic brew for the evil Queen and Jack.</h1>
					<h2 id = "h2">Grumpy wizards make toxic brew for the evil Queen and Jack.</h2>
					<h3 id = "h3">Grumpy wizards make toxic brew for the evil Queen and Jack.</h3>
					<h4 id = "h4">Grumpy wizards make toxic brew for the evil Queen and Jack.</h4>
					<div id = "standard">Grumpy wizards make toxic brew for the evil Queen and Jack.</div><br>
				</div>
			</div>
		</article>
	</section> -->
	<section>
		<aside>
			<h2>Options</h2>
			<p>From these options you can set the name of the Add-to-Cart button, Color of the Add-to-Cart button, Change the badges tag line and much more</p>
		</aside>
		<article>
			<div class="card">
				@if($success == 1)
				<div class="alert success">
					<dl>
						<dt>License Key Updated</dt>
						<dd>All License Keys are updated successfully.</dd>
					</dl>
				</div>
				@endif
				<form action="/save_license_key" method="POST">
					@csrf
					<div>
						<div class="" style="background-color: #5e8f3f;">
							<h2 style="color: #ffffff;padding: 21px;">Set License Key For Product</h2>
						</div>
						<input type="hidden" name="shopify_store_id" value="{{Session('shopifyId')}}">
						<div class="row">
							<label>Product</label>
							<select data-placeholder="Choose a Product..." class="chosen-select" tabindex="2" name="trigger_product" id="" required>
								@if(!empty($shop_products))
									@foreach ($shop_products as $product)
										<option value="{{ $product->id }}-{{ $product->title }}">{{ $product->title }}</option>
									@endforeach
								@endif
							</select>
						</div>
						<div class="row">
							<label>License key</label>
							<input type="text" id="license_key" name="license_key" required/>
						</div>
						<div class="row">
							<label>Resold</label>
							<input type="text" id="resold" name="resold" required/>
						</div>
					</div>
					<div class="row">
						<input type="submit" onclick="store()" class="button secondary">
					</div>	
				</form>
			</div>
		</article>
	</section>
	<section id="">
		<aside>
  			<h2>List of License Keys</h2>
			<p>This is the list of the license keys</p>
		</aside>
		<article>
			<div class="card">
				@if($success == "4")
				<div class="alert warning">
					<dl>
						<dt>Deleted Successfully</dt>
						<dd>License key Deleted</dd>
					</dl>
				</div>
				@endif
				<h5>List of License Keys</h5>
				<table>
					<thead>
						<tr>
						<th>Product Id</th>
						<th>Product Name</th>
						<th>License Key</th>
						<th>Key Resold</th>
						<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($product_license_key))
							@foreach ($product_license_key as $license_key)
								<tr>
									<td>{{ $license_key->product_id }}</td>
									<td><a href="#">{{ $license_key->product_name }}</a></td>
									<td><a href="#">{{ $license_key->license_key }}</a></td>
									<td><a href="#">{{ $license_key->resold }}</a></td>
									<td><a href="https://app.geekrepair.nl/delete_license?product_id={{ $license_key->product_id }}&license_key={{ $license_key->license_key }}" class="button secondary icon-trash"></a></td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</article>
	</section>

		<section id="">
		<aside>
  			<h2>Resold Count</h2>
			<p>This list shows how many times a license key resold</p>
		</aside>
		<article>
			<div class="card">
				<h5>How many time key Resold</h5>
				<table>
					<thead>
						<tr>
						<th>Product Name</th>
						<th>License Key</th>
						<th>Resold Count</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($resold))
							@foreach ($resold as $key)
								<tr>
									<td><a href="#">{{ $key->product_name }}</a></td>
									<td><a href="#">{{ $key->license_key }}</a></td>
									<td><a href="#">{{ $key->resold }}</a></td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</article>
	</section>

	<script>
    function store(){

        var btn_text= document.getElementById("btn_text").value;
        var btn_color= document.getElementById("btn_color").value;
        var btn_size= document.getElementById("btn_size").value;
        var pitch_size= document.getElementById("pitch_size").value;
        var btn_color_hover= document.getElementById("btn_color_hover").value;
        
        var testObject = { 'btn_text': btn_text, 'btn_color': btn_color, 'btn_size': btn_size, 'pitch_size': pitch_size, 'btn_color_hover': btn_color_hover };
        localStorage.setItem('formattributes', JSON.stringify(testObject));
        
    }

    var retrievedObject = JSON.parse(localStorage.getItem('formattributes'));
    
    var btn_size = retrievedObject.btn_size;
    var btn_text =retrievedObject.btn_text;
    var btn_color =retrievedObject.btn_color;
    var pitch_size =retrievedObject.pitch_size;
    var btn_color_hover =retrievedObject.btn_color_hover;

    document.getElementById('btn_size').value = btn_size;
    document.getElementById('btn_color').value = btn_color;
	document.getElementById('btn_text').value = btn_text;
	document.getElementById("pitch_size").value = pitch_size;
	document.getElementById("btn_color_hover").value = btn_color_hover;
    
</script>

@endsection