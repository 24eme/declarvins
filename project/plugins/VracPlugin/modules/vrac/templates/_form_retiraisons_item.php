<tr>
    <td>
        <?php echo $form['lot_cuve']->renderError() ?>
        <?php echo $form['lot_cuve']->render() ?>
    </td>
    <td>
        <?php echo $form['date_retiraison']->renderError() ?>
        <?php echo $form['date_retiraison']->render() ?>
    </td>
    <td>
        <?php echo $form['volume_retire']->renderError() ?>
        <?php echo $form['volume_retire']->render() ?>
    </td>
    <td>
        <?php echo $form['montant_paiement']->renderError() ?>
        <?php echo $form['montant_paiement']->render() ?>
    </td>
    <td class="dernier">
        <a class="btn_supprimer_ligne_template" href="#">X</a>
    </td>
</tr>