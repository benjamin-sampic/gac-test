<h1>Requetes</h1>
<dl class="row">
	<dt class="col-sm-6">
		Durée totale des appels passés depuis le 15/02/2012 (inclus)
	</dt>
	<dd class="col-sm-6">
		<?= \Tickets\Helper\Duration::formatSecondsToHHMMSS((int) $phonecallRealDuration->duration); ?>	
	</dd>
	<dt class="col-sm-6">
		Total des SMS envoyés
	</dt>
	<dd class="col-sm-6">
		<?= $totalSmsSent->total; ?>
	</dd>
	<dt class="col-sm-12">
		Top 10 consommation data par abonnés (hors tranche horaire 08:00 - 18:00)
	</dt>
	<dd class="col-sm-12">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>N° abonné</th>
					<th colspan="10">Top 10 consommation data</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    $currentSub = null;
                    $str = '';
                    $cellsRemaining = 10;
                    foreach ($top10DataUsageBySubscriber as $line) {
                        if ($currentSub != $line->subscriberNumber) {
                            if (null !== $currentSub) {
                                echo $str . ($cellsRemaining > 0 ? '<td colspan="' . $cellsRemaining . '"></td>' : '') . '</tr>' ;
                            }
                            $currentSub = $line->subscriberNumber;
                            $str = '<tr><td>' . number_format($currentSub, 0, ',', ' ') . '</td>';

                            $cellsRemaining = 10;
                        }
                        $cellsRemaining--;
                        $str .= '<td>' . number_format($line->qty, 0, ',', ' ') . '</td>';
                    }
                ?>
			</tbody>
		</table>
	</dd>
</dl>