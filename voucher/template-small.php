	            	             	            	              	            	              	            	              	            	              
<?php
session_start();
$price = isset($_COOKIE['voucherPrice']) ? $_COOKIE['voucherPrice'] : "Harga tidak tersedia";

?>
<table class="voucher" style=" width: 160px;">
  <tbody>
    <tr>
      <td style="text-align: left; font-size: 14px; font-weight:bold; border-bottom: 1px black solid;"><?php echo $hotspotname;?><span id="num"><?php echo " [$num]";?></span></td>
    </tr>
    <tr>
      <td>
    <table style=" text-align: center; width: 150px;">
  <tbody>
    <tr style="color: black; font-size: 11px;">
      <td>
        <table style="width:100%;">
<!-- Username = Password    -->
<?php if($usermode == "vc"){?>
        <tr>
          <td >Kode Voucher</td>
        </tr>
        <tr style="color: black; font-size: 14px;">
          <td style="width:100%; border: 1px solid black; font-weight:bold;"><?php echo $username;?></td>
        </tr>
        <tr>
          <td colspan="2" style="border: 1px solid black; font-weight:bold;">
            <?php echo $validity;?>
            <?php echo $timelimit;?>
            <?php echo $datalimit;?>
            <?php echo $price; ?>
        </tr>
<!-- /  -->
<!-- Username & Password  -->
<?php }elseif($usermode == "up"){?>
          <tr>
          <td style="width: 50%">Username</td>
          <td>Password</td>
        </tr>
        <tr style="color: black; font-size: 14px;">
          <td style="border: 1px solid black; font-weight:bold;"><?php echo $username;?></td>
          <td style="border: 1px solid black; font-weight:bold;"><?php echo $password;?></td>
        </tr>
        <tr>
          <td colspan="2" style="border: 1px solid black; font-weight:bold;">
            <?php echo $validity;?>
            <?php echo $timelimit;?>
            <?php echo $datalimit;?> 
            <b>Harga: <?php echo $price; ?></b>
          </td>
        </tr>
<?php }?>
<!-- /  -->
        </table>
      </td>
    </tr>
  </tbody>
    </table>
      </td>
    </tr>
  </tbody>
</table>	            	          	           	          	           	          


<!-- debugging data detail transaksi -->
<table id="transaksiTable">
    <thead>
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Password</th>
            <th>Comment</th>
            <th>Profile</th>
            <th>Price</th>
            <th>Uptime</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>


	
	
<script>
document.addEventListener("DOMContentLoaded", function () {
    let authUser = JSON.parse(localStorage.getItem("authUser"));
    const token = authUser ? authUser.token : null;

    // Ambil `agent_id` dari localStorage/sessionStorage
    let agentId = localStorage.getItem("selectedAgentId") || sessionStorage.getItem("selectedAgentId");

    // Pastikan token tersedia sebelum melakukan fetch
    if (!token) {
        console.error("Token tidak ditemukan. Pastikan pengguna sudah login.");
        return;
    }

    // Pastikan agent_id tersedia sebelum melakukan fetch
    if (!agentId) {
        console.error("Agent ID tidak ditemukan. Pilih agen terlebih dahulu.");
        return;
    }

    fetch(`http://voucher.jinom.net/api/detail-transaksi?agent_id=${agentId}`, {
        method: "GET",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
    })
        .then(async response => {
            if (!response.ok) {
                let errorText = await response.text();
                throw new Error(`HTTP ${response.status}: ${errorText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Data transaksi:", data);

            // Contoh: Menampilkan data dalam tabel
            let tableBody = document.querySelector("#transaksiTable tbody");
            tableBody.innerHTML = ""; // Kosongkan tabel sebelum diisi ulang

            data.forEach((item, index) => {
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.user}</td>
                    <td>${item.password}</td>
                    <td>${item.comment}</td>
                    <td>${item.profile}</td>
                    <td>${item.price}</td>
                    <td>${item.uptime}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error("Error:", error.message);
            alert(`Gagal mengambil data transaksi: ${error.message}`);
        });
});

</script>
