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
                            <th scope="col"><p>прати</br>имейл</p></th>
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
                                <button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseMail<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">виж</button>
                                <div style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseMail<?php echo $order['id']; ?>">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-1 offset-11">
                                                <button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#collapseMail<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
                                            </div>
                                            <div class="col">
                                            <?php
                                            foreach($email_templates as $it => $email_template){
                                            ?>
                                                <button style="width: 90%;margin: 5px auto;" class="btn btn-block btn-sm btn-info" data-toggle="collapse" data-target="#emailWriter<?php echo $order['id'] . $email_template['id']; ?>" aria-expanded="false" aria-controls="collapseExample"><?= $email_template['title'] ?></button>
                                                <div id="emailWriter<?php echo $order['id'] . $email_template['id']; ?>" style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseMail<?php echo $order['id'] . $email_template['id']; ?>">
                                                    <div class="row">
                                                        <div class="col-6 text-left">
                                                            <p class="ml-3 mt-2 p-2" style="background-color: #ccc">Вид на имейла: "<i><?= $email_template['title'] ?></i>"</p>
                                                        </div>
                                                        <div class="col-3 offset-3">
                                                            <button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 ml-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#emailWriter<?php echo $order['id'] . $email_template['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h3 class="ml-3">Легенда на заместителите:</h3>
                                                            <ul style="display: inline-block;" class="ml-1 text-left">
                                                                <li>[username] - името на клиента;</li>
                                                                <li>[phone] - телефон на клиента;</li>
                                                                <li>[customer_email] - имейл на клиента;</li>
                                                                <li>[address] - адрес на клиента;</li>
                                                                <li>[peyed_at] - дата на плащане;</li>
                                                            </ul>
                                                            <ul style="display: inline-block;" class="ml-1 text-left">
                                                                <li>[order] - номер на поръчката;</li>
                                                                <li>[peyed] - платена сума;</li>
                                                                <li>[ordered_at] - дата на поръчване;</li>
                                                                <li>[finished_at] - дата на приключване;</li>
                                                            </ul>
                                                        </div>
                                                        <form name="form<?php echo $order['id'] . $email_template['id']; ?>" action="/adminSendEmail.php" method="POST" class="mpn-dev-email-form">
                                                            <div class="col-6 offset-3">
                                                                <hr>
                                                                <?php if($it == 0){ ?>
                                                                	<span style="font-size: 10px;" class="text-danger">*клиента е получил такъв имейл, когато е направил поръчката! Може би не е редно да бъде изпратен отново...</span>
                                                                <?php } ?>
                                                                <input data-target="<?php echo $order['id'] . $email_template['id']; ?>" type="submit" name="mail_submit" class="btn btn-block btn-success" value="изпрати" onclick="return mail_was_submited();">
                                                            </div>
                                                            <div class="col-12">
                                                                <hr>
                                                                <label class="d-block ml-5 mt-5 text-left">Шаблон</label>
                                                                <textarea data-subject="<?= $email_template['subject'] ?>" data-order_id="<?= $order['id'] ?>" data-customer_id="<?= $order['user_id'] ?>" id="textarea<?php echo $order['id'] . $email_template['id']; ?>A" name="email_body" rows="10" cols="65"><?= $email_template['body'] ?></textarea>
                                                            </div>
                                                            <div class="col-4 offset-7">
                                                                <button class="btn btn-warning btn-block m-3" onclick="return updateMailTemplate(<?= $email_template['id'] ?>);">запази</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
					    	<td>
					    		<button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">виж</button>

								<div style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseOrder<?php echo $order['id']; ?>">
									<div class="container">
										<div class="row">
											<div class="col-1 offset-11">
												<button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
											</div>
										</div>
										<div class="row mt-2">
											<ul class="col text-left pt-3 pb-3" style="background-color: #ccc">
							    				<li>
							    					<span>Име:</span>
							    					<span><?php echo $order['username'] ?></span>
							    				</li>
							    				<li>
							    					<span>Имейл:</span>
							    					<span><?php echo $order['email'] ?></span>
							    				</li>
							    				<li>
							    					<span>Адрес:</span>
							    					<span><?php echo $order['address'] ?></span>
							    				</li>
							    				<li>
							    					<span>Телефон:</span>
							    					<span><?php echo $order['phone'] ?></span>
							    				</li>
											</ul>
										</div>
										<div class="row">
											<div class="col text-left">
												<p>Снимка на мястото за монтаж:</p>
											</div>
										</div>
										<div class="row">
											<div class="col">
												<img class="img-fluid img-thumbnail" src="<?php echo plugins_url( '/src/', __DIR__ ) . $order['image_of_the_place']; ?>">
											</div>
										</div>
										<?php foreach ($order['walls'] as $w => $wall){ ?>
										<p>
											<button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#wall-collapse-<?php echo $wall['id']; ?>" aria-expanded="false" aria-controls="collapseExample">
												Стена - <?php echo ($w + 1); ?>
											</button>
										</p>
										<div class="collapse <?php echo $w == 0 ? 'show' : ''; ?>" id="wall-collapse-<?php echo $wall['id']; ?>">
											<div class="container mb-3">
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
																<?php foreach($wall['dimensions'] as $d => $dimension){ ?>
																<?php if($d == 0 && $wall['door_starts_from'] !== null){ ?>
																<p>Има включена врата на разтояние <?= $wall['door_starts_from'] ?><?= $order['measurment'] ?> от долният ляв ъгъл</p>
																<hr>
																<?php } elseif($d == 0) { ?>
																<p>Няма включена врата за тази стена.</p>
																<?php } ?>
																<hr>
																<p>Страна "<?php echo $dimension['letter'] ; ?>": <?php echo $dimension['value'] ; ?><?php echo $order['measurment'] ; ?></p>
																<?php } ?>
															</div>
														</div>
													</div>
													<div class="col-4">
														<img class="img-fluid img-thumbnail mt-5" src="<?php echo plugins_url( 'src/assets/images/small/', __DIR__ ) . $wall['shape']; ?>">
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
                            <th scope="col"><p>прати</br>имейл</p></th>
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
					    	<td data-compleated="<?php echo $order['id']; ?>"><?php echo $order['compleated_at'] ? date('d-m-Y h:m:s', $order['compleated_at']) : '<button style="font-size: 10px;" class="btn btn-sm btn-warning mt-1" onclick="updateCompleated()" data-order="' . $order['id'] . '">маркирай като приключена</button>'; ?>
					    	<td>
                                <button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseMail<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">виж</button>
                                <div style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseMail<?php echo $order['id']; ?>">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-1 offset-11">
                                                <button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#collapseMail<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
                                            </div>
                                            <div class="col">
                                            <?php
                                            foreach($email_templates as $it => $email_template){
                                            ?>
                                                <button style="width: 90%;margin: 5px auto;" class="btn btn-block btn-sm btn-info" data-toggle="collapse" data-target="#emailWriter<?php echo $order['id'] . $email_template['id']; ?>" aria-expanded="false" aria-controls="collapseExample"><?= $email_template['title'] ?></button>
                                                <div id="emailWriter<?php echo $order['id'] . $email_template['id']; ?>" style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseMail<?php echo $order['id'] . $email_template['id']; ?>">
                                                    <div class="row">
                                                        <div class="col-6 text-left">
                                                            <p class="ml-3 mt-2 p-2" style="background-color: #ccc">Вид на имейла: "<i><?= $email_template['title'] ?></i>"</p>
                                                        </div>
                                                        <div class="col-3 offset-3">
                                                            <button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 ml-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#emailWriter<?php echo $order['id'] . $email_template['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h3 class="ml-3">Легенда на заместителите:</h3>
                                                            <ul style="display: inline-block;" class="ml-1 text-left">
                                                                <li>[username] - името на клиента;</li>
                                                                <li>[phone] - телефон на клиента;</li>
                                                                <li>[customer_email] - имейл на клиента;</li>
                                                                <li>[address] - адрес на клиента;</li>
                                                                <li>[peyed_at] - дата на плащане;</li>
                                                            </ul>
                                                            <ul style="display: inline-block;" class="ml-1 text-left">
                                                                <li>[order] - номер на поръчката;</li>
                                                                <li>[peyed] - платена сума;</li>
                                                                <li>[ordered_at] - дата на поръчване;</li>
                                                                <li>[finished_at] - дата на приключване;</li>
                                                            </ul>
                                                        </div>
                                                        <form name="form<?php echo $order['id'] . $email_template['id']; ?>" action="/adminSendEmail.php" method="POST" class="mpn-dev-email-form">
                                                            <div class="col-6 offset-3">
                                                                <hr>
                                                                <?php if($it == 0){ ?>
                                                                	<span style="font-size: 10px;" class="text-danger">*клиента е получил такъв имейл, когато е направил поръчката! Може би не е редно да бъде изпратен отново...</span>
                                                                <?php } ?>
                                                                <input data-target="<?php echo $order['id'] . $email_template['id']; ?>" type="submit" name="mail_submit" class="btn btn-block btn-success" value="изпрати" onclick="return mail_was_submited();">
                                                            </div>
                                                            <div class="col-12">
                                                                <hr>
                                                                <label class="d-block ml-5 mt-5 text-left">Шаблон</label>
                                                                <textarea data-subject="<?= $email_template['subject'] ?>" data-order_id="<?= $order['id'] ?>" data-customer_id="<?= $order['user_id'] ?>" id="textarea<?php echo $order['id'] . $email_template['id']; ?>A" name="email_body" rows="10" cols="65"><?= $email_template['body'] ?></textarea>
                                                            </div>
                                                            <div class="col-4 offset-7">
                                                                <button class="btn btn-warning btn-block m-3" onclick="return updateMailTemplate(<?= $email_template['id'] ?>);">запази</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
					    	<td>
					    		<button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">виж</button>

								<div style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseOrder<?php echo $order['id']; ?>">
									<div class="container">
										<div class="row">
											<div class="col-1 offset-11">
												<button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
											</div>
										</div>
										<div class="row mt-2">
											<ul class="col text-left pt-3 pb-3" style="background-color: #ccc">
							    				<li>
							    					<span>Име:</span>
							    					<span><?php echo $order['username'] ?></span>
							    				</li>
							    				<li>
							    					<span>Имейл:</span>
							    					<span><?php echo $order['email'] ?></span>
							    				</li>
							    				<li>
							    					<span>Адрес:</span>
							    					<span><?php echo $order['address'] ?></span>
							    				</li>
							    				<li>
							    					<span>Телефон:</span>
							    					<span><?php echo $order['phone'] ?></span>
							    				</li>
											</ul>
										</div>
										<div class="row">
											<div class="col text-left">
												<p>Снимка на мястото за монтаж:</p>
											</div>
										</div>
										<div class="row">
											<div class="col">
												<img class="img-fluid img-thumbnail" src="<?php echo plugins_url( '/src/', __DIR__ ) . $order['image_of_the_place']; ?>">
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
														<img class="img-fluid img-thumbnail mt-5" src="<?php echo plugins_url( 'src/assets/images/small/', __DIR__ ) . $wall['shape']; ?>">
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
                            <th scope="col"><p>прати</br>имейл</p></th>
					    	<th scope="col"><p>детайли</p></th>
					    </tr>
					</thead>
					<tbody>
						<?php
						if(count($compleatedOrders) > 0){
							foreach($compleatedOrders as $order){
						?>
					    <tr class='<?php echo $order['compleated_at'] ? "table-success" : "table-danger"; ?>'>
					    	<th scope="row"><?php echo $order['id']; ?></th>
					    	<td><?php echo $order['payed'] ? number_format(($order['payed'] / 100), 2).'£' : 'Не'; ?></td>
					    	<td><?php echo date('d-m-Y h:m:s', $order['ordered_at']); ?></td>
					    	<td data-compleated="<?php echo $order['id']; ?>"><?php echo $order['compleated_at'] ? date('d-m-Y h:m:s', $order['compleated_at']) : '<button style="font-size: 10px;" class="btn btn-sm btn-warning mt-1" onclick="updateCompleated()" data-order="' . $order['id'] . '">маркирай като приключена</button>'; ?>
					    	<td>
                                <button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseMail<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">виж</button>
                                <div style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseMail<?php echo $order['id']; ?>">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-1 offset-11">
                                                <button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#collapseMail<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
                                            </div>
                                            <div class="col">
                                            <?php
                                            foreach($email_templates as $it => $email_template){
                                            ?>
                                                <button style="width: 90%;margin: 5px auto;" class="btn btn-block btn-sm btn-info" data-toggle="collapse" data-target="#emailWriter<?php echo $order['id'] . $email_template['id']; ?>" aria-expanded="false" aria-controls="collapseExample"><?= $email_template['title'] ?></button>
                                                <div id="emailWriter<?php echo $order['id'] . $email_template['id']; ?>" style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseMail<?php echo $order['id'] . $email_template['id']; ?>">
                                                    <div class="row">
                                                        <div class="col-6 text-left">
                                                            <p class="ml-3 mt-2 p-2" style="background-color: #ccc">Вид на имейла: "<i><?= $email_template['title'] ?></i>"</p>
                                                        </div>
                                                        <div class="col-3 offset-3">
                                                            <button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 ml-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#emailWriter<?php echo $order['id'] . $email_template['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h3 class="ml-3">Легенда на заместителите:</h3>
                                                            <ul style="display: inline-block;" class="ml-1 text-left">
                                                                <li>[username] - името на клиента;</li>
                                                                <li>[phone] - телефон на клиента;</li>
                                                                <li>[customer_email] - имейл на клиента;</li>
                                                                <li>[address] - адрес на клиента;</li>
                                                                <li>[peyed_at] - дата на плащане;</li>
                                                            </ul>
                                                            <ul style="display: inline-block;" class="ml-1 text-left">
                                                                <li>[order] - номер на поръчката;</li>
                                                                <li>[peyed] - платена сума;</li>
                                                                <li>[ordered_at] - дата на поръчване;</li>
                                                                <li>[finished_at] - дата на приключване;</li>
                                                            </ul>
                                                        </div>
                                                        <form name="form<?php echo $order['id'] . $email_template['id']; ?>" action="/adminSendEmail.php" method="POST" class="mpn-dev-email-form">
                                                            <div class="col-6 offset-3">
                                                                <hr>
                                                                <?php if($it == 0){ ?>
                                                                	<span style="font-size: 10px;" class="text-danger">*клиента е получил такъв имейл, когато е направил поръчката! Може би не е редно да бъде изпратен отново...</span>
                                                                <?php } ?>
                                                                <input data-target="<?php echo $order['id'] . $email_template['id']; ?>" type="submit" name="mail_submit" class="btn btn-block btn-success" value="изпрати" onclick="return mail_was_submited();">
                                                            </div>
                                                            <div class="col-12">
                                                                <hr>
                                                                <label class="d-block ml-5 mt-5 text-left">Шаблон</label>
                                                                <textarea data-subject="<?= $email_template['subject'] ?>" data-order_id="<?= $order['id'] ?>" data-customer_id="<?= $order['user_id'] ?>" id="textarea<?php echo $order['id'] . $email_template['id']; ?>A" name="email_body" rows="10" cols="65"><?= $email_template['body'] ?></textarea>
                                                            </div>
                                                            <div class="col-4 offset-7">
                                                                <button class="btn btn-warning btn-block m-3" onclick="return updateMailTemplate(<?= $email_template['id'] ?>);">запази</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
					    	<td>
					    		<button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">виж</button>

								<div style="position: absolute;top: 0;left: 50%;transform: translateX(-50%);width: 100%;min-height: 100%;z-index: 2000;border: 2px solid #8ecdf5;" class="collapse bg-light" id="collapseOrder<?php echo $order['id']; ?>">
									<div class="container">
										<div class="row">
											<div class="col-1 offset-11">
												<button style="width: 35px;height: 35px;border-radius: 50%" class="mt-2 btn btn-danger btn-sm" type="button" data-toggle="collapse" data-target="#collapseOrder<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapseExample">X</button>
											</div>
										</div>
										<div class="row mt-2">
											<ul class="col text-left pt-3 pb-3" style="background-color: #ccc">
							    				<li>
							    					<span>Име:</span>
							    					<span><?php echo $order['username'] ?></span>
							    				</li>
							    				<li>
							    					<span>Имейл:</span>
							    					<span><?php echo $order['email'] ?></span>
							    				</li>
							    				<li>
							    					<span>Адрес:</span>
							    					<span><?php echo $order['address'] ?></span>
							    				</li>
							    				<li>
							    					<span>Телефон:</span>
							    					<span><?php echo $order['phone'] ?></span>
							    				</li>
											</ul>
										</div>
										<div class="row">
											<div class="col text-left">
												<p>Снимка на мястото за монтаж:</p>
											</div>
										</div>
										<div class="row">
											<div class="col">
												<img class="img-fluid img-thumbnail" src="<?php echo plugins_url( '/src/', __DIR__ ) . $order['image_of_the_place']; ?>">
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
														<img class="img-fluid img-thumbnail mt-5" src="<?php echo plugins_url( 'src/assets/images/small/', __DIR__ ) . $wall['shape']; ?>">
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

    function updateMailTemplate(id){
        event.preventDefault();
        let new_template = jQuery(event.target).parent().parent().find('textarea').val();
        let mail_template_id = id;
        let url = '<?php echo plugins_url( "/src/updateMailTemplate.php", __DIR__ ); ?>';

        jQuery.ajax({
            type: "POST",
            url: url,
            data: {
                "new_template": new_template,
                "mail_template_id": mail_template_id
            },
            success: function(response){
                if(response === 'success'){
                    alert("Шаблона е успешно записан.");
                    jQuery('.mpn-dev-email-form').find('textarea').val(new_template);
                }
                else {
                    alert(response);
                }
            }
        });
    }

    function mail_was_submited(){

        event.preventDefault();
        let email_body = jQuery(event.target).parent().parent().find('textarea').val();
        let order_id = jQuery(event.target).parent().parent().find('textarea').data('order_id');
        let user_id = jQuery(event.target).parent().parent().find('textarea').data('customer_id');
        let subject = jQuery(event.target).parent().parent().find('textarea').data('subject');
        let url = '<?php echo plugins_url( "/src/adminSendEmail.php", __DIR__ ); ?>';
        jQuery.ajax({
            type: "POST",
            url: url,
            data: {
                "email_body": email_body,
                "order_id": order_id,
                "user_id": user_id,
                "subject": subject
            },
            success: function(response){
                if(response === 'success'){
                    alert("Успешно изпратен!");
                }
                else {
                    alert(response);
                }
            }
        });
    }

	function updateCompleated(){
		let el = event.target;
		let order_id = el.dataset.order;
		let url = '<?php echo plugins_url( "/src/updateOrderCompletition.php", __DIR__ ); ?>';
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