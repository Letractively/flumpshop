<html>
  <head>
    <link href="../jqueryui.css" rel="stylesheet" type="text/css" />
    <link href="../style-nav.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="../../js/jqueryui.js"></script>
    <script type="text/javascript" language="javascript" src="../../js/jquery.validate.min.js"></script>
    <script type="text/javascript" language="javascript" src="../../js/defaults.php"></script>
  </head>
  <body>
  <center><img src="../images/logo.jpg" />
    <div class="header">flump<span class='header2'>shop</span> setup</div>
    Powered by Flumpnet<br />
    <div id="navContainer">
      <div id="navAccordion">
        <div class="head">Welcome</div>
        <ul>
          <li><a href="?frame=leftFrame&p=1.1" id="1.1">Introduction</a></li>
          <li><a href="?frame=leftFrame&p=1.2" id="1.2">Compatibility Checker</a></li>
          <li><a href="?frame=leftFrame&p=1.3">Customisation Level</a></li>
        </ul>
        <div class="head">Settings</div>
        <ul>
          <li><a href="?frame=leftFrame&p=2.1" id="2.1">Paths and Directories</a></li>
          <li><a href="?frame=leftFrame&p=2.2" id="2.2">Database</a></li>
          <li><a href="?frame=leftFrame&p=2.3" id="2.3">About You</a></li>
        </ul>
        <div class="head">Customise</div>
        <ul><?php
if (isset($_SESSION['stage']['security']) and $_SESSION['stage']['security']) {
  echo '<li><a href="?frame=leftFrame&p=3.1" id="3.1">Security Settings</a></li>';
}
if (isset($_SESSION['stage']['shop']) and $_SESSION['stage']['shop']) {
  echo '<li><a href="?frame=leftFrame&p=3.2" id="3.2">Shop Settings</a></li>';
}
//Order Status Removed
if (isset($_SESSION['stage']['paypal']) and $_SESSION['stage']['paypal']) {
  echo '<li><a href="?frame=leftFrame&p=3.4" id="3.4">PayPal Settings</a></li>';
}
if (isset($_SESSION['stage']['messages']) and $_SESSION['stage']['messages']) {
  echo '<li><a href="?frame=leftFrame&p=3.5" id="3.5">Predefined Messages</a></li>';
}
if (isset($_SESSION['stage']['pagination']) and $_SESSION['stage']['pagination']) {
  echo '<li><a href="?frame=leftFrame&p=3.6" id="3.6">Pagination Settings</a></li>';
}
if (isset($_SESSION['stage']['account']) and $_SESSION['stage']['account']) {
  echo '<li><a href="?frame=leftFrame&p=3.7" id="3.7">User Account Settings</a></li>';
}
if (isset($_SESSION['stage']['smtp']) and $_SESSION['stage']['smtp']) {
  echo '<li><a href="?frame=leftFrame&p=3.8" id="3.8">SMTP Server Settings</a></li>';
}
if (isset($_SESSION['stage']['logs']) and $_SESSION['stage']['logs']) {
  echo '<li><a href="?frame=leftFrame&p=3.9" id="3.9">Log Settings</a></li>';
}
if (isset($_SESSION['stage']['server']) and $_SESSION['stage']['server']) {
  echo '<li><a href="?frame=leftFrame&p=3.10" id="3.10">Advanced Server Settings</a></li>';
}
if (isset($_SESSION['stage']['tabs']) and $_SESSION['stage']['tabs']) {
  echo '<li><a href="?frame=leftFrame&p=3.11" id="3.11">Tab Settings</a></li>';
}
if (isset($_SESSION['stage']['homePage']) and $_SESSION['stage']['homePage']) {
  echo '<li><a href="?frame=leftFrame&p=3.12" id="3.12">Carousel Widget Settings</a></li>';
}
if (isset($_SESSION['stage']['viewItem']) and $_SESSION['stage']['viewItem']) {
  echo '<li><a href="?frame=leftFrame&p=3.13" id="3.13">Item View Settings</a></li>';
}
?></ul>
        <div class="head">Finish</div>
        <ul>
          <li><a href="?frame=leftFrame&p=4.1" id="4.1">Save Settings</a></li>
          <li><a href="?frame=leftFrame&p=4.2" id="4.2">Goodbye, and hello!</a></li>
        </ul>
      </div>
    </div>
  </center>
  <script type="text/javascript">
    //Sets the current active accordion element
    $(document).ready(function() {$('#navAccordion').accordion({collapsible: true, active: false, autoHeight: false, icons: {'header': 'ui-icon-circle-arrow-e', 'headerSelected': 'ui-icon-circle-arrow-s'}, navigation: true, header: '.head'});});
    //Opens a little loading dialog in the main frame
    function loader(str) {
      parent.main.document.body.innerHTML = loadMsg(str);
    }
  </script>
</body>
</html>