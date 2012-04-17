<script id="templateformsDouane" type="text/x-jquery-tmpl">
<div class="ligne_form" data-key="${index}">
	<table>
		<tbody>
			<tr>
				<td>
					<span class="error"></span>
					<label for="produit_definition_droit_douane_${index}_date">Date: </label>
					<br>
					<select id="produit_definition_droit_douane_${index}_date_day" name="produit_definition[droit_douane][${index}][date][day]">
						<option value=""></option>
						<option value="1">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5">05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select> / 
					<select id="produit_definition_droit_douane_${index}_date_month" name="produit_definition[droit_douane][${index}][date][month]">
						<option value=""></option>
						<option value="1">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5">05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select> / 
					<select id="produit_definition_droit_douane_${index}_date_year" name="produit_definition[droit_douane][${index}][date][year]">
						<option value=""></option>
						<?php for ($i = (date('Y') - 10), $max = (date('Y') + 10); $i<= $max; $i++): ?>
						<option value="<?php echo $i ?>"><?php echo $i ?></option>
						<?php endfor; ?>
					</select>
				</td>
				<td>
					<span class="error"></span>
					<label for="produit_definition_droit_douane_${index}_code">Code: </label>
					<br>
					<input type="text" id="produit_definition_droit_douane_${index}_code" name="produit_definition[droit_douane][${index}][code]">
				</td>
				<td>
					<span class="error"></span>
					<label for="produit_definition_droit_douane_${index}_taux">Taux: </label>
					<br>
					<input type="text" id="produit_definition_droit_douane_${index}_taux" class=" num num_float" autocomplete="off" name="produit_definition[droit_douane][${index}][taux]">
				</td>
				<td>
					<br>
					<a class="removeForm" href="#">X</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</script>