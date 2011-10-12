<form action="<?php echo url_for('@validation_upload_csv') ?>" method="post" enctype="multipart/form-data">
  <table>
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" />
      </td>
    </tr>
  </table>
</form>