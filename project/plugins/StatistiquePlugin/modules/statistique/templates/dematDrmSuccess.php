<?php use_helper('Date'); ?>
<?php include_component('global', 'navBack', array('active' => 'statistiques', 'subactive' => 'demat_drm')); ?>
<section id="contenu">
    <section id="principal">
        <div class="clearfix" id="application_dr">
        
            <h1>Etat des lieux démat DRM</h1>
            
            <div class="contenu clearfix">
                <form  class="popup_form" action="<?php url_for('statistiques_demat_drm') ?>" method="get">
					<div class="ligne_form">
						<label for="select_periode" style="width: auto; padding: 0 15px 0 0;">Statistiques par période :</label>
						<select name="periode_year" style="width: auto; padding: 0 15px 0 0;">
							<?php $y=date('Y'); for ($i=$y; $i>=$y-10; $i--): ?>
							<option value="<?php echo $i; ?>"<?php if($i == $periodeYear): ?> selected="selected"<?php endif; ?>><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
						<select name="periode_month" style="width: auto; padding: 0 15px 0 0;">
							<?php for ($i=1; $i<=12; $i++): ?>
							<option value="<?php echo sprintf('%02d', $i); ?>"<?php if($i == $periodeMonth): ?> selected="selected"<?php endif; ?>><?php echo sprintf('%02d', $i); ?></option>
							<?php endfor; ?>
						</select>
						<span class="ligne_form_btn">
							<button class="btn_valider" type="submit" name="valider" style="height: 22px; line-height: 21px;">Changer</button>
						</span>
					</div>
				</form>
            </div>
            
			<div class="tableau_ajouts_liquidations">
                    <table class="tableau_recap">
                        <thead>
                            <tr>
                                <th><?php echo mb_strtoupper(format_date($periode.'-01', 'MMMM yyyy', 'fr_FR'), 'UTF-8') ?></th>
                                <th>DRM PAPIER</th>
                                <th>DRM DTI</th>
                                <th>DRM CIEL</th>
                                <th>TOTAL DRM</th>
                                <th>DEMAT. CIEL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            	<td>Nb DRM</td>
                            	<td><?php echo $stats['PAPIER'] ?></td>
                            	<td><?php echo $stats['DTI'] ?></td>
                            	<td><?php echo $stats['CIEL'] ?></td>
                            	<td><?php echo $stats['TOTAL'] ?></td>
                            	<td><?php echo $stats['DEMAT'] ?>&nbsp;%</td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
                
        </div>
    </section>
</section>