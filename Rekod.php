<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekod</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            height: 100%;
            background-color: #EDF1F7;
            overflow: auto;
            flex-direction: row;
        }

        .sidebar {
            width: 200px;
            background: #ffffff;
            color: #012970;
            height: 100vh;
            padding-top: 60px;
            border-right: 1px solid #ccc;
            flex-shrink: 0;
        }

        .sidebar a {
            text-decoration: none;
            color: #012970;
            padding: 15px 15px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: bold;
            font-size: 1.02rem;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #F6F9FF;
            color: #4154F1;
        }

        .sidebar a ion-icon {
            margin-right: 10px;
            font-weight: bold;
        }

        .sidebar-section {
            margin-top: 10px;
        }

        .sidebar-section .section-title {
            font-size: 0.7rem;
            font-weight: bold;
            color: #899BBD;
            display: block;
            margin-bottom: 5px;
            margin-left: 13px;
        }

        .sidebar a {
            text-decoration: none;
            color: #012970;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #F6F9FF;
            color: #4154F1;
        }

        .sidebar a ion-icon {
            margin-right: 10px;
        }

        .content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #ffffff;
            font-size: 1.3rem;
            font-weight: bold;
            color: #2c3e50;
            text-align: left;
            padding: 10px 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            font-size: 1.3rem;
            font-weight: bold;
        }

        header .profile {
            display: flex;
            align-items: center;
        }

        header .profile img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .title-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #delete-selected {
            background-color: rgb(207, 53, 53);
            color: white;
            border: none;
            padding: 8.5px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 15px;
            margin-right: 14px;
        }

        #delete-selected:hover {
            background-color: rgb(176, 20, 20);
        }

        .title {
            font-size: 21px;
            font-family: 'Nunito', sans-serif;
            color: #4154F1;
            margin-bottom: 23px;
            margin-top: 30px;
        }


        form {
            background: #EDF1F7;
            padding: 20px;
            border-radius: 8px;
        }

        .table-container {
            margin-top: 5px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .status {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-dot {
            height: 10px;
            width: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status.menunggu .status-dot {
            background-color: #4285F4;
        }

        .status.lulus .status-dot {
            background-color: #34A853;
        }

        .status.gagal .status-dot {
            background-color: #cf2f2f;
        }

        .controls {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 10px;
        }

        .pagination button {
            margin: 5px;
            padding: 5px 10px;
            background-color: #4154F1;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .pagination button:hover {
            background-color: #293a8b;
        }

        .pagination button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="sidebar">
        <a href="Halaman Utama.php"><ion-icon name="home-outline"></ion-icon> Halaman Utama</a>
        <a href="Tempahan.php"><ion-icon name="calendar-outline"></ion-icon> Tempah</a>
        <a href="Rekod.php"><ion-icon name="layers-outline"></ion-icon> Rekod</a>
        <a href="Manual.php"><ion-icon name="book-outline"></ion-icon> Panduan</a>
        <div class="sidebar-section">
            <span class="section-title">TETAPAN</span>
            <a href="logout.php"><ion-icon name="log-out-outline"></ion-icon> Keluar</a>
        </div>
    </div>


    <div class="content">

        <header>
            <div class="logo">e-Mawāʿid</div>
            <div class="profile">
                <img src="profile.png" alt="Profile Picture">
                <span id="username">
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>
                </span>
            </div>
        </header>

        <form id="bookingForm">
            <div class="title-container">
                <div class="title">Rekod Tempahan</div>
                <button id="delete-selected">Batal</button>
            </div>

            <div class="table-container">
                <div class="controls">
                    <label for="search-month">Cari Mengikut Bulan :</label>
                    <input type="month" id="search-month" onchange="searchByMonth()">
                </div>
                <table id="rekod-table" border="1">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Bahagian</th>
                            <th>Detail</th>
                            <th>Tujuan</th>
                            <th>Tempat</th>
                            <th>Jenis Sewaan</th>
                            <th>Tarikh</th>
                            <th>Masa Bermula</th>
                            <th>Masa Berakhir</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="tempahan-body">
                        <!-- Row akan masuk sini-->
                    </tbody>
                </table>
                <div class="pagination" id="pagination">
                    <!-- Utk pagination buttons -->
                </div>
            </div>
        </form>
    </div>

    <script>
        const rowsPerPage = 5;
        let currentPage = 1;
        let data = []; // Will hold the fetched data
        let filteredData = []; // For search functionality

        // Convert 24-hour time to 12-hour format with AM/PM
        function convertTo12HourFormat(time) {
            const [hour, minute] = time.split(':');
            const date = new Date();
            date.setHours(hour, minute);
            return date.toLocaleString('en-US', {
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            });
        }

        // Fetch records from the server
        function fetchRecords() {
            $.ajax({
                url: 'fetch_records.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    data = response.reverse();
                    filteredData = [...data];
                    renderTable(currentPage);
                },
                error: function(xhr, status, error) {
                    alert('Error fetching records: ' + error);
                }
            });
        }

        // Render the table based on the current page
        function renderTable(page = 1) {
            const tableBody = document.getElementById('tempahan-body');
            tableBody.innerHTML = '';

            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedData = filteredData.slice(start, end);

            paginatedData.forEach((item, index) => {
                const masaMula = convertTo12HourFormat(item.masa_mula.slice(0, 5));
                const masaAkhir = convertTo12HourFormat(item.masa_akhir.slice(0, 5));

                const row = document.createElement('tr');
                row.dataset.id = item.id;
                row.innerHTML = `
                <td><input type="checkbox" class="delete-checkbox" value="${item.id}"></td>
                <td>${start + index + 1}</td>
                <td>${item.bahagian}</td>
                <td>${item.lain || '-'}</td>
                <td>${item.tujuan}</td>
                <td>${item.tempat}</td>
                <td>${item.jenis}</td>
                <td>${item.tarikh}</td>
                <td>${masaMula}</td>
                <td>${masaAkhir}</td>
                <td class="status ${item.status}">
                    <div class="status-dot"></div>
                    ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                </td>
            `;
                tableBody.appendChild(row);
            });

            renderPagination();
        }

        // Render pagination buttons
        function renderPagination() {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            const pageCount = Math.ceil(filteredData.length / rowsPerPage);
            for (let i = 1; i <= pageCount; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.disabled = i === currentPage;
                button.onclick = () => {
                    currentPage = i;
                    renderTable(currentPage);
                };
                pagination.appendChild(button);
            }
        }

        // Delete selected records
        function deleteRecords(ids) {
            if (confirm('Adakah anda pasti untuk membatalkan permohonan ini?')) {
                $.ajax({
                    url: 'delete_records.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        ids: ids
                    }),
                    success: function(response) {
                        alert(response.message);
                        filteredData = filteredData.filter(item => !ids.includes(item.id));
                        renderTable(currentPage);
                    },
                    error: function(xhr) {
                        alert('Ralat untuk membuang permohonan: ' + xhr.responseJSON.message);
                    }
                });
            }
        }

        // Search records by month
        function searchByMonth() {
            const monthInput = document.getElementById('search-month').value;
            if (monthInput) {
                filteredData = data.filter(item => item.tarikh.startsWith(monthInput));
            } else {
                filteredData = [...data];
            }
            currentPage = 1;
            renderTable(currentPage);
        }

        // Handle "Delete Selected" button click
        $('#delete-selected').on('click', function() {
            const selectedIds = $('.delete-checkbox:checked').map(function() {
                return parseInt($(this).val());
            }).get();

            if (selectedIds.length === 0) {
                alert('Sila pilih tempahan untuk dibatalkan/dibuang');
                return;
            }

            deleteRecords(selectedIds);
        });

        // Load records on page load
        $(document).ready(function() {
            fetchRecords();

            // Attach event listener for search
            document.getElementById('search-button').addEventListener('click', searchByMonth);
        });
    </script>
</body>

</html>