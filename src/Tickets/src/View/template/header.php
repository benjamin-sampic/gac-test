<ul class="nav nav-pills">
	<li class="nav-item">
		<a href="?route=index" class="nav-link <?= 'index' == $route ? 'active' : '' ?> <?= 'index' == $route ? 'aria-current="page"' : '' ?>">
			Accueil
		</a>
	</li>
	<li class="nav-item">
		<a href="?route=import" class="nav-link <?= 'import' == $route ? 'active' : '' ?> <?= 'import' == $route ? 'aria-current="page"' : '' ?>">
			Import
		</a>
	</li>
	<li class="nav-item">
		<a href="?route=requetes" class="nav-link <?= 'requetes' == $route ? 'active' : '' ?> <?= 'requetes' == $route ? 'aria-current="page"' : '' ?>">
			Requêtes
		</a>
	</li>
</ul>