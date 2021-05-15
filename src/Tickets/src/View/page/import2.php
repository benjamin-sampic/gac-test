<h2>Résultats de l'import</h2>
<dl class="row">
	<dt class="col-sm-6">
		Total : 
	</dt>
	<dd class="col-sm-6">
		<?= number_format($results['total'], 0, ',', ' '); ?>
	</dd>
	<dt class="col-sm-6">
		Lignes importées : 
	</dt>
	<dd class="col-sm-6">
		<span class="badge bg-success"><?= number_format($results['nbSuccess'], 0, ',', ' '); ?></span>
	</dd>
	<dt class="col-sm-6">
		Lignes ignorées : 
	</dt>
	<dd class="col-sm-6">
		<span class="badge bg-warning"><?= number_format($results['nbIgnored'], 0, ',', ' '); ?></span>
	</dd>
	<dt class="col-sm-6">
		Erreur(s) : 
	</dt>
	<dd class="col-sm-6">
		<span class="badge bg-danger"><?= number_format($results['nbErrors'], 0, ',', ' '); ?></span>
	</dd>
</dl>
<?php if ($results['nbErrors'] > 0) {
    ?>
<h3>Détails des erreurs: </h3>
	<table class="table table-striped table-hover">
	  	<thead>
	  		<tr>
				<th>N° de ligne</th>
				<th>Erreur</th>
	  		</tr>
	  	</thead>
	  	<tbody>
			<?php foreach ($results['errors'] as $error) {
        ?>
		  	<tr>
				<td><?= $error['index']; ?></td>
				<td><?= $error['exception']; ?></td>
		  	</tr>
			<?php
    } ?>
	  	</tbody>
	</table>
<?php
} ?>