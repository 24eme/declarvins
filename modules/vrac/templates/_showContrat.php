<ul>
    <li>
        <h2>Les soussignés</h2>
        
        <div id="soussigne_recapitulatif">
	        <?php
	        	include_partial('soussigneRecapitulatif', array('vrac' => $vrac));
	        ?>
        </div>  
        <?php
	        if($isValidation)
	        {
        ?>
        <div class="btnModification">
            <a href="<?php echo url_for('vrac_soussigne',$vrac); ?>" class="btn_majeur btn_orange">Modifier</a>
        </div> 
        <?php 
            }
        ?>
    </li>
    <li>
        <h2>Le marché</h2>            
        <div id="marche_recapitulatif">
	        <?php
	        	include_partial('marcheRecapitulatif', array('vrac' => $vrac));
	        ?>
        </div>
        <?php
	        if($isValidation)
	        {
        ?>
        <div class="btnModification">
            <a href="<?php echo url_for('vrac_marche',$vrac); ?>" class="btn_majeur btn_orange">Modifier</a>
        </div>
        <?php 
        	}
        ?>
    </li>
    <li>
        <h2>Les conditions</h2>            
        <div id="conditions_recapitulatif">
	        <?php
	        	include_partial('conditionsRecapitulatif', array('vrac' => $vrac));
	        ?>
        </div>
        <?php
	        if($isValidation)
	        {
        ?>
        <div class="btnModification">
            <a href="<?php echo url_for('vrac_condition',$vrac); ?>" class="btn_majeur btn_orange">Modifier</a>
        </div>
        <?php 
            }
        ?>
    </li>
</ul>