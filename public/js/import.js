function applyImportBehaviour() {
	let importButton = document.getElementById('import-launch');
	if (importButton) {
		importButton.addEventListener("click", function() { launchImport() });
	}
}

function launchImport() {
	document.getElementById('import-launch').disabled = true;
	document.getElementById('import-loader').style.display = "block";
	document.getElementById("import-results").innerHTML = "";
	fetch('?route=import2', {})
		.then(res => res.text())
		.then(response => {
			document.getElementById("import-results").innerHTML = response;
			document.getElementById('import-loader').style.display = "none";
			document.getElementById('import-launch').disabled = false;
		})
		.catch(err => {
			alert("sorry, there was an error fetching the request.");
			document.getElementById('import-launch').disabled = false;
		});
}

export { applyImportBehaviour, launchImport };