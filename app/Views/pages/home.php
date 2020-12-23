<div class="main-container" style="position: relative;">
	<?php insert_hspace("4.3rem"); ?>

	<style type='text/css'>
	#items-container {
		display: flex;
		justify-content: center;
		flex-wrap: wrap;
	}
	.item-container {
		width: 350px;
		height: 250px;
		margin: 19px 19px;
		box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05), 0 0 5px rgba(0,0,0,0.1);
		border: 0 solid #e5e7eb;
		border-radius: 5px;
		background-color: #fff;
	}
	h1 {
		text-align: center;
		color: #757575;
	}
	</style>


	<?php insert_hspace("2.5rem"); ?>
	<h1><i>Enjoy free templates!</i></h1>
	<br/>
	<div id="items-container">
	<?php foreach ($items as $item) { ?>
		<div class="item-container">

		</div>
	<?php } ?>
	</div>

	<br/><br/><br/>

	<!-- Put the body-overlay at the end of the contents -->
	<div class="overlay hidden" id="overlay-body"></div>
</div>
