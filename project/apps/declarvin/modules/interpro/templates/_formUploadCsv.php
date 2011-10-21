<form action="<?php echo url_for('interpro_upload_csv', array('id' => $interpro->get('_id'))) ?>" method="post" enctype="multipart/form-data">
  <table>
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" />
      </td>
    </tr>
  </table>
</form>