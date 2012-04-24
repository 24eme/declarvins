<div class="error_list">
    <h3>Points bloquants</h3>
    <ol>
        <?php foreach ($drmValidation->getErrors() as $error): ?>
            <li><?php echo $error->getRawValue(); ?></li>
        <?php endforeach; ?>
    </ol>
</div>
<br />