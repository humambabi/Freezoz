<div class="main-container" style="position: relative;">
	<?php insert_hspace("4.3rem"); ?>

	<style type='text/css'>
	#items-container {
		display: grid;
		/*border: 1px solid red;*/
		grid-template-columns: repeat(3, 1fr);
		grid-row-gap: 15px;
		grid-column-gap: 15px;
	}
	.item-container {
		width: 439px;
		height: 247px;
		box-shadow: 0 10px 15px -3px rgba(0,0,0,0.07);
		border: 1px solid #e5e7eb;
		border-radius: 5px;
		background-color: #fff;
		transition: all .5s ease;

		display: flex;
		justify-content: center;
		align-items: center;
		font-size: 5rem;
		color: #ccc;
	}

	.item-container:nth-child(1), .item-container:nth-child(4), .item-container:nth-child(7), .item-container:nth-child(10), .item-container:nth-child(13) {
		justify-self: start;
	}
	.item-container:nth-child(2), .item-container:nth-child(5), .item-container:nth-child(8), .item-container:nth-child(11), .item-container:nth-child(14) {
		justify-self: center;
		position: relative;
		top: 124px;
	}
	.item-container:nth-child(3), .item-container:nth-child(6), .item-container:nth-child(9), .item-container:nth-child(12), .item-container:nth-child(15) {
		justify-self: end;
	}


	.item-container:hover {
		color: #000;
		box-shadow: 0 10px 15px -3px rgba(0,0,0,0.25), 0 4px 6px -2px rgba(0,0,0,0.05), 0 0 5px rgba(0,0,0,0.1);
	}

	h1 {
		text-align: center;
		color: #757575;
	}
	@media all and (min-width: 1500px) {
		/*
		.item-container:nth-child(1) {
			margin-top: 0;
			margin-left: 0;
		}
		*/
	}
	</style>


	<?php insert_hspace("3rem"); ?>
	<h1><i>Enjoy free templates!</i></h1>
	<?php insert_hspace("7rem"); ?>
	<div id="items-container">

	<?php $item_idx = 1; ?>
	<?php foreach ($items as $item) { ?>
		<div class="item-container">
			<?= $item_idx++ ?>
		</div>
	<?php } ?>

	</div>

	<?php insert_hspace("20rem"); ?>

	<!-- Put the body-overlay at the end of the contents -->
	<div class="overlay hidden" id="overlay-body"></div>
</div>
