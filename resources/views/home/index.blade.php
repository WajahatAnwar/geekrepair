@extends('layouts.app')

@section('content')
<header style="background-color: #5e8f3f;color: #ffffff;">
	<img src="{{ asset('carrer.png') }}" alt="logo of stack apps" style="width:45px;">
	<h1>Geek Repair Store</h1>
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
<!--
		@Array using: $product_license_key
		@Purpose: This section show the list of license key associated with the respective product.
		-->
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
						<input type="text" id="license_key" name="license_key" required />
					</div>
					<div class="row">
						<label>Resold</label>
						<input type="text" id="resold" name="resold" required />
					</div>
				</div>
				<div class="row">
					<input type="submit" onclick="store()" class="button secondary">
				</div>
			</form>
		</div>
	</article>
</section>
<!--
		@Array using: $product_license_key
		@Purpose: This section show the list of license key associated with the respective product.
	-->
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
						<td><a href="https://app.geeksoftware.nl/delete_license?product_id={{ $license_key->product_id }}&license_key={{ $license_key->license_key }}" class="button secondary icon-trash"></a></td>
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
		<a href="https://app.geeksoftware.nl/count" class="button">Count</a>
	</aside>
	<article>
		<div class="card">
			@if($success == "5")
			<div class="alert success">
				<dl>
					<dt>License Key Resold Counting Complete</dt>
					<dd>Below you can see the results</dd>
				</dl>
			</div>
			@endif
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

<section id="">
	<!-- <aside>
  			<h2>Customer Who Didn't Got Keys Email</h2>
			<p>This list shows how many customers didn't got a license key</p>
		</aside> -->
	<article>
		<div class="card">
			<div class="loading" style="display: none;">Loading&#8230;</div>
			<h5>Customer Who Didn't Got Keys Email</h5>
			<table>
				<thead>
					<tr>
						<th>Product Name</th>
						<th>Customer Email</th>
						<th>Reason</th>
						<th>License Key</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($customers_withno_keys))
					@foreach ($customers_withno_keys as $key)
					<tr>
						<td><a href="#">{{ $key->product_name }}</a></td>
						<td><a href="#">{{ $key->customer_email }}</a></td>
						<td><a href="#">{{ $key->reason }}</a></td>
						<td><input type="text" placeholder="Enter License Key to Send" id="datainput-{{ $key->id }}" data-email="{{ $key->customer_email }}" data-id="{{ $key->id }}"></td>
						<td><a href="#" class="button secondary icon-send send_license_key" data-id="{{ $key->id }}" data-email="{{ $key->customer_email }}" data-product="{{ $key->product_name }}">Send</a></td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</article>
</section>


@endsection