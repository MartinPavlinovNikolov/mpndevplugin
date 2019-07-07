<div class="container">
	<div class="row mt-1">
		<div class="col">
			<p>
				<button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">
			    Покажи всички поръчки
				</button>
			</p>
			<div class="collapse" id="collapseExample1">
				<table style="position: relative;" class="table table-condensed table-sm table-primary table-bordered text-center">
					<thead>
					    <tr>
					    	<th scope="col"><p>номер</p></th>
					    	<th scope="col"><p>платено</p></th>
					    	<th scope="col"><p>поръчано</p></th>
					    	<th scope="col"><p>приключена</p></th>
					    	<th scope="col"><p>детайли</p></th>
					    </tr>
					</thead>
					<tbody>
						<?php 
						if(count($orders) > 0){
							foreach($orders as $order){
						?>
					    <tr class='<?php echo $order['compleated_at'] != null ? "table-success" : "table-danger"; ?>'>
					    	<th scope="row"><?php echo $order['id']; ?></th>
					    	<td><?php echo $order['payed'] ? number_format(($order['payed'] / 100), 2).'£' : 'Не'; ?></td>
					    	<td><?php echo date('d-m-Y h:m:s', $order['ordered_at']); ?></td>
					    	<td data-compleated="<?php echo $order['id']; ?>"><?php echo $order['compleated_at'] ? date('d-m-Y h:m:s', $order['compleated_at']) : '<button style="font-size: 10px;" class="btn btn-sm btn-warning mt-1" onclick="updateCompleated()" data-order="' . $order['id'] . '">маркирай като приключена</button>'; ?></td>
					    	<td>
					    		<button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">виж</button>

								<div style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseOrder<?php echo $order['id']; ?>">
									<div class="container">
										<div class="row">
											<div class="col-1 offset-11">
												<button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
											</div>
										</div>
										<div class="row">
											<div class="col text-left">
												<p>Снимка на мястото за монтаж:</p>
											</div>
										</div>
										<div class="row">
											<div class="col">
												<img class="img-fluid img-thumbnail" src="<?php echo plugins_url( '/mpndevplugin/', __DIR__ ) . $order['image_of_the_place']; ?>">
											</div>
										</div>
										<?php foreach ($order['walls'] as $w => $wall){ ?>
										<p>
											<button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#wall-collapse-<?php echo $wall['id']; ?>" aria-expanded="false" aria-controls="collapseExample">
												Стена - <?php echo ($w + 1); ?>
											</button>
										</p>
										<div class="collapse <?php echo $w == 0 ? 'show' : ''; ?>" id="wall-collapse-<?php echo $wall['id']; ?>">
											<div class="container">
												<div class="row" style="border: 2px solid #8ecdf5;">
													<hr>
													<div class="col-8 text-left">
														<div class="row">
															<div class="col">
																<p><?php echo $wall['note'] ? 'Допълнителна информация от клиента: "<i>'.$wall['note'].'</i>"' : ''; ?></p>
															</div>
														</div>
														<div class="row">
															<div class="col">
																<hr>
																<p>Избраният цвят е: <?php echo $order['color']; ?></p>
																<hr>
																<p>Има включена врата на разтояние от долният ляв ъгъл 5cm</p>
																<?php foreach($wall['dimensions'] as $dimension){ ?>
																<hr>
																<p>Страна "<?php echo $dimension['letter'] ; ?>": <?php echo $dimension['value'] ; ?><?php echo $order['measurment'] ; ?></p>
																<?php } ?>
															</div>
														</div>
													</div>
													<div class="col-4">
														<img class="img-fluid img-thumbnail mt-5" src="<?php echo plugins_url( 'mpndevplugin/assets/images/small/', __DIR__ ) . $wall['shape']; ?>">
														<p style="font-size: 10px !important;"><i>(схема на избраната стена)</i></p>
													</div>
												</div>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
					    	</td>
					    </tr>
						<?php
								}
							} else {
						?>
						<h2>Все още нямате поръчки.</h2>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row mt-1">
		<div class="col">
			<p>
				<button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample">
			    Покажи недовършените поръчки
				</button>
			</p>
			<div class="collapse" id="collapseExample2">
				<table class="table table-condensed table-sm table-primary table-bordered text-center">
					<thead>
					    <tr>
					    	<th scope="col"><p>номер</p></th>
					    	<th scope="col"><p>платено</p></th>
					    	<th scope="col"><p>поръчано</p></th>
					    	<th scope="col"><p>приключена</p></th>
					    	<th scope="col"><p>детайли</p></th>
					    </tr>
					</thead>
					<tbody>
						<?php 
						if(count($incompleatedOrders) > 0){
							foreach($incompleatedOrders as $order){
						?>
					    <tr class='<?php echo $order['compleated_at'] ? "table-success" : "table-danger"; ?>'>
					    	<th scope="row"><?php echo $order['id']; ?></th>
					    	<td><?php echo $order['payed'] ? number_format(($order['payed'] / 100), 2).'£' : 'Не'; ?></td>
					    	<td><?php echo date('d-m-Y h:m:s', $order['ordered_at']); ?></td>
					    	<td data-compleated="<?php echo $order['id']; ?>"><?php echo $order['compleated_at'] ? date('d-m-Y h:m:s', $order['compleated_at']) : '<button style="font-size: 10px;" class="btn btn-sm btn-warning mt-1" onclick="updateCompleated()" data-order="' . $order['id'] . '">маркирай като приключена</button>'; ?></td>
					    	<td>
					    	<td>
					    		<button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">виж</button>

								<div style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseOrder<?php echo $order['id']; ?>">
									<div class="container">
										<div class="row">
											<div class="col-1 offset-11">
												<button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
											</div>
										</div>
										<div class="row">
											<div class="col text-left">
												<p>Снимка на мястото за монтаж:</p>
											</div>
										</div>
										<div class="row">
											<div class="col">
												<img class="img-fluid img-thumbnail" src="<?php echo plugins_url( '/mpndevplugin/', __DIR__ ) . $order['image_of_the_place']; ?>">
											</div>
										</div>
										<?php foreach ($order['walls'] as $w => $wall){ ?>
										<p>
											<button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#wall-collapse-<?php echo $wall['id']; ?>" aria-expanded="false" aria-controls="collapseExample">
												Стена - <?php echo ($w + 1); ?>
											</button>
										</p>
										<div class="collapse" id="wall-collapse-<?php echo $wall['id']; ?>">
											<div class="container">
												<div class="row" style="border: 2px solid #8ecdf5;">
													<hr>
													<div class="col-8 text-left">
														<div class="row">
															<div class="col">
																<p><?php echo $wall['note'] ? 'Допълнителна информация от клиента: "<i>'.$wall['note'].'</i>"' : ''; ?></p>
															</div>
														</div>
														<div class="row">
															<div class="col">
																<hr>
																<p>Избраният цвят е: <?php echo $order['color']; ?></p>
																<hr>
																<p>Има включена врата на разтояние от долният ляв ъгъл 5cm</p>
																<?php foreach($wall['dimensions'] as $dimension){ ?>
																<hr>
																<p>Страна "<?php echo $dimension['letter'] ; ?>": <?php echo $dimension['value'] ; ?><?php echo $order['measurment'] ; ?></p>
																<?php } ?>
															</div>
														</div>
													</div>
													<div class="col-4">
														<img class="img-fluid img-thumbnail mt-5" src="<?php echo plugins_url( 'mpndevplugin/assets/images/small/', __DIR__ ) . $wall['shape']; ?>">
														<p style="font-size: 10px !important;"><i>(схема на избраната стена)</i></p>
													</div>
												</div>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
					    	</td>
					    </tr>
						<?php
							}
						} else {
						?>
						<h2>Нямате чакащи поръчки.</h2>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row mt-1">
		<div class="col">
			<p>
				<button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample">
			    Покажи довършените поръчки
				</button>
			</p>
			<div class="collapse" id="collapseExample3">
				<table class="table table-condensed table-sm table-primary table-bordered text-center">
					<thead>
					    <tr>
					    	<th scope="col"><p>номер</p></th>
					    	<th scope="col"><p>платено</p></th>
					    	<th scope="col"><p>поръчано</p></th>
					    	<th scope="col"><p>приключена</p></th>
					    	<th scope="col"><p>детайли</p></th>
					    </tr>
					</thead>
					<tbody>
						<?php 
						if(count($compleatedOrders > 0)){
							foreach($compleatedOrders as $order){
						?>
					    <tr class='<?php echo $order['compleated_at'] ? "table-success" : "table-danger"; ?>'>
					    	<th scope="row"><?php echo $order['id']; ?></th>
					    	<td><?php echo $order['payed'] ? number_format(($order['payed'] / 100), 2).'£' : 'Не'; ?></td>
					    	<td><?php echo date('d-m-Y h:m:s', $order['ordered_at']); ?></td>
					    	<td data-compleated="<?php echo $order['id']; ?>"><?php echo $order['compleated_at'] ? date('d-m-Y h:m:s', $order['compleated_at']) : '<button style="font-size: 10px;" class="btn btn-sm btn-warning mt-1" onclick="updateCompleated()" data-order="' . $order['id'] . '">маркирай като приключена</button>'; ?>
					    	<td>
					    		<button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">виж</button>

								<div style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseOrder<?php echo $order['id']; ?>">
									<div class="container">
										<div class="row">
											<div class="col-1 offset-11">
												<button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
											</div>
										</div>
										<div class="row">
											<div class="col text-left">
												<p>Снимка на мястото за монтаж:</p>
											</div>
										</div>
										<div class="row">
											<div class="col">
												<img class="img-fluid img-thumbnail" src="<?php echo plugins_url( '/mpndevplugin/', __DIR__ ) . $order['image_of_the_place']; ?>">
											</div>
										</div>
										<?php foreach ($order['walls'] as $w => $wall){ ?>
										<p>
											<button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#wall-collapse-<?php echo $wall['id']; ?>" aria-expanded="false" aria-controls="collapseExample">
												Стена - <?php echo ($w + 1); ?>
											</button>
										</p>
										<div class="collapse" id="wall-collapse-<?php echo $wall['id']; ?>">
											<div class="container">
												<div class="row" style="border: 2px solid #8ecdf5;">
													<hr>
													<div class="col-8 text-left">
														<div class="row">
															<div class="col">
																<p><?php echo $wall['note'] ? 'Допълнителна информация от клиента: "<i>'.$wall['note'].'</i>"' : ''; ?></p>
															</div>
														</div>
														<div class="row">
															<div class="col">
																<hr>
																<p>Избраният цвят е: <?php echo $order['color']; ?></p>
																<hr>
																<p>Има включена врата на разтояние от долният ляв ъгъл 5cm</p>
																<?php foreach($wall['dimensions'] as $dimension){ ?>
																<hr>
																<p>Страна "<?php echo $dimension['letter'] ; ?>": <?php echo $dimension['value'] ; ?><?php echo $order['measurment'] ; ?></p>
																<?php } ?>
															</div>
														</div>
													</div>
													<div class="col-4">
														<img class="img-fluid img-thumbnail mt-5" src="<?php echo plugins_url( 'mpndevplugin/assets/images/small/', __DIR__ ) . $wall['shape']; ?>">
														<p style="font-size: 10px !important;"><i>(схема на избраната стена)</i></p>
													</div>
												</div>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
					    	</td>
					    </tr>
						<?php
							}
						} else {
						?>
						<h2>Нямате довършени поръчки.</h2>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	function updateCompleated(){
		let el = event.target;
		let order_id = el.dataset.order;
		let url = '<?php echo plugins_url( "/mpndevplugin/updateOrderCompletition.php", __DIR__ ); ?>';
		$.ajax({
			type: "POST",
			url: url,
			data: {order_id: order_id},
			success: function(response){
				$('*[data-compleated="'+order_id+'"]').empty().text(response);
			}
		});
	}

	$vueOrders = new Vue({
		el: "#my-content-id",
		data: {
			
		}
	});
</script>