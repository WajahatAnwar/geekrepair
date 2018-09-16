<!DOCTYPE html>
<html>
<head>
    <title>Awesome Application</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="//cdn.shopify.com/s/assets/external/app.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/uptown.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/chosen.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/chosen.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/color-picker.min.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Montez|Lobster|Josefin+Sans|Shadows+Into+Light|Pacifico|Amatic+SC:700|Orbitron:400,900|Rokkitt|Righteous|Dancing+Script:700|Bangers|Chewy|Sigmar+One|Architects+Daughter|Abril+Fatface|Covered+By+Your+Grace|Kaushan+Script|Gloria+Hallelujah|Satisfy|Lobster+Two:700|Comfortaa:700|Cinzel|Courgette' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/chosen.css') }}">
    @yield('styles')
        <script type="text/javascript">

            ShopifyApp.init({
                  apiKey: "{{ env('SHOPIFY_APIKEY') }}",
                  shopOrigin: '{{ "https://" . session("domain") }}'
            });

        </script>

        <script type="text/javascript">
            
                ShopifyApp.ready(function(){

                    ShopifyApp.Bar.initialize({

                        icon : '',
                        title  : 'Awesome Application',
                        buttons : {
                            primary : {
                                label : 'Help',
                                message : 'Help'
                            }
                        }

                    });

                });

        </script>
        <style>
            .loading {
                        position: fixed;
                        z-index: 999;
                        height: 2em;
                        width: 2em;
                        overflow: show;
                        margin: auto;
                        top: 0;
                        left: 0;
                        bottom: 0;
                        right: 0;
                    }
                    
                    /* Transparent Overlay */
                    .loading:before {
                        content: '';
                        display: block;
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0,0,0,0.3);
                    }
                    
                    /* :not(:required) hides these rules from IE9 and below */
                    .loading:not(:required) {
                        /* hide "loading..." text */
                        font: 0/0 a;
                        color: transparent;
                        text-shadow: none;
                        background-color: transparent;
                        border: 0;
                    }
                    
                    .loading:not(:required):after {
                        content: '';
                        display: block;
                        font-size: 10px;
                        width: 1em;
                        height: 1em;
                        margin-top: -0.5em;
                        -webkit-animation: spinner 1500ms infinite linear;
                        -moz-animation: spinner 1500ms infinite linear;
                        -ms-animation: spinner 1500ms infinite linear;
                        -o-animation: spinner 1500ms infinite linear;
                        animation: spinner 1500ms infinite linear;
                        border-radius: 0.5em;
                        -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
                        box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
                    }
                    
                    /* Animation */
                    
                    @-webkit-keyframes spinner {
                        0% {
                        -webkit-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        transform: rotate(0deg);
                        }
                        100% {
                        -webkit-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        transform: rotate(360deg);
                        }
                    }
                    @-moz-keyframes spinner {
                        0% {
                        -webkit-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        transform: rotate(0deg);
                        }
                        100% {
                        -webkit-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        transform: rotate(360deg);
                        }
                    }
                    @-o-keyframes spinner {
                        0% {
                        -webkit-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        transform: rotate(0deg);
                        }
                        100% {
                        -webkit-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        transform: rotate(360deg);
                        }
                    }
                    @keyframes spinner {
                        0% {
                        -webkit-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        transform: rotate(0deg);
                        }
                        100% {
                        -webkit-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        transform: rotate(360deg);
                        }
                    }
        </style>
</head>
<body>
    
    <main>
        @yield('content')
    </main>
    
    <script src="http://code.jquery.com/jquery-3.2.1.min.js" 
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="{{  asset('js/app.js' )}}"></script>
    <script src="{{  asset('js/chosen.jquery.js' )}}"></script>
    <script src="{{  asset('js/init.js' )}}"></script>
    <script src="{{  asset('js/prism.js' )}}"></script>
    <script src="{{  asset('js/color-picker.min.js' )}}"></script>
    <script>
        $(document).ready(function() {
            $(".send_license_key").click(function() {
                var email = $(this).data("email");
                var id = $(this).data("id");
                var product = $(this).data("product");

                var sendIdEmail = $('#datainput-'+id);

                var original_license_key = sendIdEmail.val();
                // alert(original_license_key);
                $.ajax({
                  method: "POST",
                  url: "https://app.geekrepair.nl/send_license_email",
                  data: { "email" : email, "customerKeyId" : id, "licenseKey" : original_license_key, "productName" : product },
                  beforeSend: function() {
                    $(".loading").show();
                  },
                  success: function(response) {
                    $(".loading").hide();
                    parent.location.reload();
                  }
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>