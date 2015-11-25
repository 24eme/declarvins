<tr>
    <td>
        <?php echo $form['numero']->renderError() ?>
        <?php echo $form['numero']->render() ?>
    </td>
    <td>
        <?php echo $form['volume']->renderError() ?>
        <?php echo $form['volume']->render() ?>
    </td>
    <td>
        <?php echo $form['date']->renderError() ?>
        <?php echo $form['date']->render(array('class' => 'datepicker')) ?>
    </td>
    <!-- 
    <td class="dernier">
    	&nbsp;&nbsp;
        <a class="btn_supprimer_ligne_template" data-container="tr" href="#">X</a>
    </td>
     -->
</tr>