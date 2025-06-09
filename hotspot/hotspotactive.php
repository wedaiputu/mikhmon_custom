<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
// hide all error

error_reporting(0);


if (!isset($_SESSION["mikhmon"])) {
	header("Location:../admin.php?id=login");
} else {

	// load session MikroTik
	$session = $_GET['session'];
	$serveractive = $_GET['server'];

	// load config
	include('../include/config.php');
	include('../include/readcfg.php');

	// lang
	include('../include/lang.php');
	include('../lang/' . $langid . '.php');

	// routeros api
	include_once('../lib/routeros_api.class.php');
	include_once('../lib/formatbytesbites.php');
	$API = new RouterosAPI();
	$API->debug = false;
	if (!$API->connect($iphost, $userhost, decrypt($passwdhost))) {
		die(json_encode(["error" => "Gagal terhubung ke MikroTik"]));
	} else {
		"Berhasil terhubung ke MikroTik";
	}

	// $data = $API->comm("/ip/hotspot/active/print");

	// var_dump($data, 'ini dataaa');

	// var_dump($_GET['session'], $serveractive);





	if ($serveractive != "") {
		$gethotspotactive = $API->comm("/ip/hotspot/active/print", array("?server" => "" . $serveractive . ""));
		$TotalReg = count($gethotspotactive);

		$counthotspotactive = $API->comm("/ip/hotspot/active/print", array(
			"count-only" => "",
			"?server" => "" . $serveractive . ""
		));
	} else {
		$gethotspotactive = $API->comm("/ip/hotspot/active/print");
		$TotalReg = count($gethotspotactive);

		$counthotspotactive = $API->comm("/ip/hotspot/active/print", array(
			"count-only" => "",
		));
	}
}


?>

<div class="row">
	<div id="hotspotData" data-json='<?php echo json_encode($gethotspotactive); ?>'></div>

	<div id="reloadHotspotActive">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h3><i class="fa fa-wifi"></i> <?= $_hotspot_active ?> <?php
																			if ($serveractive == "") {
																			} else {
																				echo $serveractive . " ";
																			}
																			if ($counthotspotactive < 2) {
																				echo "$counthotspotactive item";
																			} elseif ($counthotspotactive > 1) {
																				echo "$counthotspotactive items";
																			};
																			if ($serveractive == "") {
																			} else {
																				echo " | <a href='./?hotspot=active&session=" . $session . "'> <i class='fa fa-search'></i> Show all</a>";
																			}
																			?> </h3>
				</div>
				<div class="card-body overflow">
					<table id="tFilter" class="table table-bordered table-hover text-nowrap">
						<thead>
							<tr>
								<th></th>
								<th>Server</th>
								<th>User</th>
								<th>Address</th>
								<th>Mac Address</th>
								<th class="text-right">Uptime</th>
								<th class="text-right">Bytes In</th>
								<th class="text-right">Bytes Out</th>
								<th class="text-right">Time Left</th>
								<th>Login By</th>
								<th><?= $_comment ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							for ($i = 0; $i < $TotalReg; $i++) {
								$hotspotactive = $gethotspotactive[$i];
								$id = $hotspotactive['.id'];
								$server = $hotspotactive['server'];
								$user = $hotspotactive['user'];
								$address = $hotspotactive['address'];
								$mac = $hotspotactive['mac-address'];
								$uptime = formatDTM($hotspotactive['uptime']);
								$usesstime = formatDTM($hotspotactive['session-time-left']);
								$bytesi = formatBytes($hotspotactive['bytes-in'], 2);
								$byteso = formatBytes($hotspotactive['bytes-out'], 2);
								$loginby = $hotspotactive['login-by'];
								$comment = $hotspotactive['comment'];
								$uriprocess = "'./?remove-user-active=" . $id . "&session=" . $session . "'";
								echo "<tr>";
								echo "<td style='text-align:center;'><span class='pointer'  title='Remove " . $user . "' onclick=loadpage(" . $uriprocess . ")><i class='fa fa-minus-square text-danger'></i></span></td>";
								echo "<td><a  title='filter " . $server . "' href='./?hotspot=active&server=" . $server . "&session=" . $session . "'><i class='fa fa-server'></i> " . $server . "</a></td>";
								echo "<td><a title='Open User " . $user . "' href=./?hotspot-user=" . $user . "&session=" . $session . "><i class='fa fa-edit'></i> " . $user . "</a></td>";
								echo "<td>" . $address . "</td>";
								echo "<td>" . $mac . "</td>";
								echo "<td style='text-align:right;'>" . $uptime . "</td>";
								echo "<td style='text-align:right;'>" . $bytesi . "</td>";
								echo "<td style='text-align:right;'>" . $byteso . "</td>";
								echo "<td style='text-align:right;'>" . $usesstime . "</td>";
								echo "<td>" . $loginby . "</td>";
								echo "<td>" . $comment . "</td>";
								echo "</tr>";
							}

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	function sendHotspotData() {
		let hotspotDataElement = document.getElementById("hotspotData");
		if (!hotspotDataElement) {
			console.error("Element #hotspotData tidak ditemukan!");
			return;
		}

		// Ambil token dari localStorage
		let agent_id = $comment[1]
		let authUser = localStorage.getItem("authUser");
		if (!authUser) {
			console.error("Token tidak ditemukan di localStorage!");
			return;
		}

		let token;
		try {
			token = JSON.parse(authUser).token; // Parsing JSON untuk mendapatkan token
			if (!token) {
				console.error("Token tidak valid!");
				return;
			}
		} catch (e) {
			console.error("Gagal membaca token:", e);
			return;
		}

		let hotspotData = JSON.parse(hotspotDataElement.getAttribute("data-json") || "[]");
		if (hotspotData.length === 0) {
			console.log("Tidak ada data yang dikirim.");
			return;
		}

		$.ajax({
			url: "http://127.0.0.1:8000/api/mikrotikData",
			type: "POST",
			data: JSON.stringify({
				details: hotspotData
			}),
			contentType: "application/json",
			headers: {
				"Accept": "application/json",
				"Authorization": `Bearer ${token}` // Token dikirim di sini
			},
			success: function(response) {
				console.log("Data berhasil dikirim ke database:", response);
			},
			error: function(xhr, status, error) {
				console.error("Gagal mengirim data:", xhr.responseText);
			}
		});
	}

	$(document).ready(function() {
		sendHotspotData();
		setInterval(sendHotspotData, 10000); // Kirim data setiap 10 detik
	});

	
</script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	function generateDataHash(data) {
		return JSON.stringify(data);
	}

	function sendHotspotData() {
		let hotspotDataElement = document.getElementById("hotspotData");
		if (!hotspotDataElement) {
			console.error("Element #hotspotData tidak ditemukan!");
			return;
		}

		// Ambil authUser dari localStorage
		let authUser = localStorage.getItem("authUser");
		if (!authUser) {
			console.error("authUser tidak ditemukan di localStorage!");
			return;
		}

		let token, agent_id;
		try {
			let authData = JSON.parse(authUser);
			token = authData.token;
			agent_id = parseInt(localStorage.getItem("selectedAgentId"), 10);

			if (!token) {
				console.error("Token tidak valid!");
				return;
			}
			if (!agent_id) {
				console.error("agent_id tidak ditemukan atau tidak valid!");
				return;
			}
		} catch (e) {
			console.error("Gagal membaca authUser:", e);
			return;
		}

		// Ambil data hotspot dari element
		let hotspotData = JSON.parse(hotspotDataElement.getAttribute("data-json") || "[]");
		if (hotspotData.length === 0) {
			console.log("Tidak ada data yang dikirim.");
			return;
		}

		// Cek apakah data sudah pernah dikirim sebelumnya
		let currentDataHash = generateDataHash(hotspotData);
		let lastDataHash = localStorage.getItem("lastSentHotspotData");

		if (currentDataHash === lastDataHash) {
			console.log("Data sudah dikirim sebelumnya. Tidak perlu mengirim ulang.");
			return;
		}

		// Susun data sesuai format API
		let requestData = {
			transaksi_id: null,
			agent_id: agent_id,
			details: hotspotData.map(data => ({
				server: data.server || "Unknown",
				user: data.user || "Unknown",
				address: data.address || "0.0.0.0",
				mac: data.mac || "00:00:00:00:00:00",
				uptime: data.uptime || "0m",
				bytes_in: data.bytes_in || "0",
				bytes_out: data.bytes_out || "0",
				time_left: data.time_left || "0m",
				login_by: data.login_by || "unknown",
				comment: data.comment || "No comment"
			}))
		};

		// Kirim data ke API Laravel
		$.ajax({
			url: "http://voucher.jinom.net/api/mikrotikData",
			type: "POST",
			data: JSON.stringify(requestData),
			contentType: "application/json",
			headers: {
				"Accept": "application/json",
				"Authorization": `Bearer ${token}`
			},
			success: function(response) {
				console.log("Data berhasil dikirim:", response);

				// Simpan hash data terbaru agar tidak dikirim ulang
				localStorage.setItem("lastSentHotspotData", currentDataHash);
			},
			error: function(xhr, status, error) {
				console.error("Gagal mengirim data:", xhr.responseText);
			}
		});
	}

	$(document).ready(function() {
		sendHotspotData();
		setInterval(sendHotspotData, 60000); // Cek setiap 10 detik, tetapi hanya kirim jika ada data baru
	});
</script>
