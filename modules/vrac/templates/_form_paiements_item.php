<tr>
    <td>
        <?php echo $form['date']->renderError() ?>
        <?php echo $form['date']->render() ?>
    </td>
    <td>
        <?php echo $form['montant']->renderError() ?>
        <?php echo $form['montant']->render() ?>
    </td>
    <td class="dernier">
        <a class="btn_supprimer_ligne_template" data-container="tr" href="#">X</a>
    </td>
</tr>