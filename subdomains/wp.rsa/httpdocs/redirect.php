<?php
header("Cache-Control: max-age=-1, no-store");
?>
<html>
<title>Redirect</title>
<head>
    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-8330106-6']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body >

<a onclick="_gaq.push(['_trackEvent', 'redirect', 'clicked'])" href='http://<?php echo $_GET['url']?>'> Click here to goto http://<?php echo $_GET['url']?></a>

<script type="text/javascript">

_gaq.push(['_trackEvent', 'redirect', 'clicked'])
setTimeout(document.location.href='http://<?php echo $_GET['url']?>',500);

</script>

</body>
</html>