<?php
if (isset($_SESSION['config'])) $_SESSION['config'] = serialize($_SESSION['config']);
?><script>
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px').height('16px');
$('.helpDialog').each(function() {$(this).dialog({autoOpen: false});});
$('input:submit').button().width('100px').css('font-size','12px');
$('table').addClass('ui-widget-content');
$('input, select').addClass('ui-state-default');
</script>
</body></html>