<style>
	#container-recap {
		margin-left: auto;
	}

	.highlighted-row {
		background-color: #f0f0f0;
		border-radius: 10px;
		text-align: center;
		padding: 10px;
		margin: 15px 0;
	}

	.img-fluid {
		max-width: 150px;
	}

	.row-recap {
		padding: 30px 15px !important;
		background: #e8e8e8;
		border-radius: 15px;
	}

	@media (max-width: 768px) {
		.container-fluid {
			max-width: 100%;
		}
	}
</style>
<div id="container-recap" class="container-fluid">
	<div class="row-recap">
		<div class="row">
			<div class="col">
				<h1 style="color: black; font-size: 18px;">Récapitulatif</h1>
			</div>
		</div>
		<div class="row highlighted-row" style="margin: 10px 0px;">
			<div class="col" style="text-align: center;">
				<img src="/images/pays/document-open.png" alt="Imagen" class="img-fluid">
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<p>Véhicule</p>
			</div>
			<div class="col-6">
				<p id="immatriculation-value"></p>
			</div>
		</div>
		<div class="row highlighted-row">
			<div class="col">
				<span id="immatriculation-display" class="display-4"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<p>Total</p>
			</div>
			<div class="col-6">
				<p id="montant-commande-no-frais-value">€</p>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<p>Taxes(20%)</p>
			</div>
			<div class="col-6">
				<p id="montant-commande-frais-value">€</p>
			</div>
		</div>
		<div class="row highlighted-row">
			<div class="col">
				<span id="montant-commande-frais-value-display" class="display-4">€</span>
			</div>
		</div>
	</div>
</div>
<script type="module" src="/panel/Carte-grise/panier-recap/JS/panier-recapitulatif-informations.js"></script>