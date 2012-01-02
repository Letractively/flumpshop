<h1><?= $data['heading'] ?></h1>
<p><?= $data['summary'] ?></h1>
<form action="<?= $data['form_action'] ?>" method="post">
  <table>
    <?php
    foreach ($data['fields'] as $field) {
      $fields = 'name="' . $field['config_key'] . '" id="' . $field['config_key'] . '" class="setup"';
      ?>
      <tr>
        <td><label for="<?= $field['config_key'] ?>"><?= $field['config_human_name'] ?></label></td>
        <td><?php
    switch ($field['input_type']) {
      case 'textarea':
        echo '<textarea ' . $fields . '>' . $field['value'] . '</textarea>';
        break;
      default:
        echo '<input type="' . $field['input_type'] . '" ' . $fields .
              ' value="' . $field['value'] . '" />';
    }
      ?></td>
        <td>
          <span class='iconbutton' onclick='$("#<?= $field['config_key'] ?>Help").dialog("open");'></span>
          <div class="ui-helper-hidden helpDialog" id="<?= $field['config_key'] ?>Help" title="<?= $field['config_human_name'] ?>"><?= $field['config_human_description'] ?></div>
        </td>
      </tr>
      <?php
    }
    ?>
  </table>
  <input type="submit" value="Continue" />
</form>
<script>
  $('.iconbutton').button({icons: {primary: 'ui-icon-help'}});
  $('.helpDialog').each(function() {$(this).dialog({autoOpen: false});});
  $('input:submit').button().width('100px').css('font-size','12px');
</script>