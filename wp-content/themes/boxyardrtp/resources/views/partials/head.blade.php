<head>
  @if (!is_user_logged_in())
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-8153960-5"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-8153960-5');
    </script>
    <!-- End Google Analytics -->
  @endif
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="dns-prefetch" href="//fonts.googleapis.com" />
  @php wp_head() @endphp
</head>
