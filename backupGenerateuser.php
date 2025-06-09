<form autocomplete="off" method="post" action="">
					<div>
						<?php if ($_SESSION['ubp'] != "") {
							echo "    <a class='btn bg-warning' href='./?hotspot=users&profile=" . $_SESSION['ubp'] . "&session=" . $session . "'> <i class='fa fa-close'></i> " . $_close . "</a>";
						} elseif ($_SESSION['vcr'] = "active") {
							echo "    <a class='btn bg-warning' href='./?hotspot=users-by-profile&session=" . $session . "'> <i class='fa fa-close'></i> " . $_close . "</a>";
						} else {
							echo "    <a class='btn bg-warning' href='./?hotspot=users&profile=all&session=" . $session . "'> <i class='fa fa-close'></i> " . $_close . "</a>";
						}

						?>
						<a class="btn bg-pink" title="Open User List by Profile 
<?php if ($_SESSION['ubp'] == "") {
	echo "all";
} else {
	echo $uprofile;
} ?>" href="./?hotspot=users&profile=
<?php if ($_SESSION['ubp'] == "") {
	echo "all";
} else {
	echo $uprofile;
} ?>&session=<?= $session; ?>"> <i class="fa fa-users"></i> <?= $_user_list ?></a>
						<!-- generate and submit -->
						<button type="submit" name="save" onclick="loader(), redirectToGoogle()" class="btn bg-primary" title="Generate User"> <i class="fa fa-save"></i> <?= $_generate ?></button>

						<a class="btn bg-secondary" title="Print Default" href="./voucher/print.php?id=<?= $urlprint; ?>&qr=no&session=<?= $session; ?>" target="_blank"> <i class="fa fa-print"></i> <?= $_print ?></a>
						<a class="btn bg-danger" title="Print QR" href="./voucher/print.php?id=<?= $urlprint; ?>&qr=yes&session=<?= $session; ?>" target="_blank"> <i class="fa fa-qrcode"></i> <?= $_print_qr ?></a>
						<a class="btn bg-info" title="Print Small" href="./voucher/print.php?id=<?= $urlprint; ?>&small=yes&session=<?= $session; ?>" target="_blank"> <i class="fa fa-print"></i> <?= $_print_small ?></a>
					</div>
					<table class="table">
						<tr>
							<td class="align-middle"><?= $_qty ?></td>
							<td>
								<div><input class="form-control " type="number" name="qty" min="1" max="500" value="1" required="1"></div>
							</td>
						</tr>

						<tr>
							<td class="align-middle">Server</td>
							<td>
								<select class="form-control " name="server" required="1">
									<option>all</option>
									<?php $TotalReg = count($srvlist);
									for ($i = 0; $i < $TotalReg; $i++) {
										echo "<option>" . $srvlist[$i]['name'] . "</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_user_mode ?></td>
							<td>
								<select class="form-control " onchange="defUserl();" id="user" name="user" required="1">
									<option value="up"><?= $_user_pass ?></option>
									<option value="vc"><?= $_user_user ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_user_length ?></td>
							<td>
								<select class="form-control " id="userl" name="userl" required="1">
									<option>4</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_prefix ?></td>
							<td><input class="form-control " type="text" size="6" maxlength="6" autocomplete="off" name="prefix" value=""></td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_character ?></td>
							<td>
								<select class="form-control " name="char" required="1">
									<option id="lower" style="display:block;" value="lower"><?= $_random ?> abcd</option>
									<option id="upper" style="display:block;" value="upper"><?= $_random ?> ABCD</option>
									<option id="upplow" style="display:block;" value="upplow"><?= $_random ?> aBcD</option>
									<option id="lower1" style="display:none;" value="lower"><?= $_random ?> abcd2345</option>
									<option id="upper1" style="display:none;" value="upper"><?= $_random ?> ABCD2345</option>
									<option id="upplow1" style="display:none;" value="upplow"><?= $_random ?> aBcD2345</option>
									<option id="mix" style="display:block;" value="mix"><?= $_random ?> 5ab2c34d</option>
									<option id="mix1" style="display:block;" value="mix1"><?= $_random ?> 5AB2C34D</option>
									<option id="mix2" style="display:block;" value="mix2"><?= $_random ?> 5aB2c34D</option>
									<option id="num" style="display:none;" value="num"><?= $_random ?> 1234</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_profile ?></td>
							<td>
								<select class="form-control " onchange="GetVP();" id="uprof" name="profile" required="1">
									<?php if ($genprof != "") {
										echo "<option>" . $genprof . "</option>";
									} else {
									}
									$TotalReg = count($getprofile);
									for ($i = 0; $i < $TotalReg; $i++) {
										echo "<option>" . $getprofile[$i]['name'] . "</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_time_limit ?></td>
							<td><input class="form-control " type="text" size="4" autocomplete="off" name="timelimit" value=""></td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_data_limit ?></td>
							<td>
								<div class="input-group">
									<div class="input-group-10 col-box-9">
										<input class="group-item group-item-l" type="number" min="0" max="9999" name="datalimit" value="<?= $udatalimit; ?>">
									</div>
									<div class="input-group-2 col-box-3">
										<select style="padding:4.2px;" class="group-item group-item-r" name="mbgb" required="1">
											<option value=1048576>MB</option>
											<option value=1073741824>GB</option>
										</select>
									</div>
								</div>
							</td>
						</tr>
						<!-- <form action=""> -->
						<tr>
							<td class="align-middle">Agent</td>
							<td>
								<select class="form-control" id="dropdown" name="adcomment">
									<option value="">Muat Data Agent...</option>
								</select>
							</td>
						</tr>
						<!-- </form> -->
						<tr>
							<td class="align-middle"><?= $_price ?></td>
							<td>
								<div class="input-group">
									<div class="input-group-10 col-box-9">
										<input class="group-item group-item-l form-control" type="number" name="price" value="<?= isset($uprice) ? $uprice : ''; ?>">
									</div>
									<div class="input-group-2 col-box-3">
										<span class="group-item group-item-r"><?= $currency ?></span>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4" class="align-middle w-12" id="GetValidPrice">
								<?php if ($genprof != "") {
									
									echo $ValidPrice;
								} ?>
							</td>
						</tr>

					</table>
				</form>