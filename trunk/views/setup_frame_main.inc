<html>
  <head><link href="../style-main.css" rel="stylesheet" type="text/css" /><script type="text/javascript" language="javascript" src="../../js/jquery.js"></script></head>
  <body>
    <h1>Flumpshop Setup</h1>
    <p>Welcome to the Flumpshop setup wizard. I'm the Flumpnet robot, and I'll be here throughout the setup process to guide you through it.</p>
    <p>Setup can take anything from a few seconds, to half an hour, depending on how experienced you are with the system, and the level of customisation you require. In a moment, you will be asked what optional information you want to fill in, in order to customise and streamline your setup experience. After all, the first step towards a wonderful and popular ecommerce such as myself is a wonderful, friendly introduction, and a smooth, clean, clear first configuration experience, for the best first impression.</p>
    <p>In order to complete the setup wizard, you're going to, as a minimum, need this: </p>
    <ul>
      <li>Give me write access to at least config.php in the top directory of my installation</li>
      <li>Server and login details for a MySQL Server</li>
      <li>Some information about your business:<ul>
          <li>It's main country of operations</li>
          <li>A contact email address</li>
          <li>A contact address</li>
        </ul>
      </li>
    </ul>
    <details>
    <summary>These would also be helpful if you can:</summary>
    <ul>
      <li>An SMTP server I can send emails from</li>
      <li>Access to a Memcached server</li>
      <li>Ability to configure cron jobs</li>
      <li>.htaccess enabled on your Apache setup, or the ability to set up rewrite rules</li>
      <li>This site accessible using SSL</li>
      <li>Where it goes when we flush</li>
    </ul>
    </details>
    <p>The full list of information goes on and on, but will become clear soon enough.</p>
    <p>Once you've scoured through your brain, and possibly called tech support, click the button below and I'll perform a few basic tests to make sure that Flumpshop is compatible with the server environment.</p>
    <button onclick="parent.leftFrame.window.location='./index.php?frame=leftFrame&p=1.2'; window.location = './welcome/legal.php';">Continue</button>
    <script type="text/javascript">
      $(parent.frames[0].document.getElementById('1.1')).addClass('ui-state-active');
    </script>
  </body>
</html>