<?php
// Ensure the session is started
session_start();

// Check if user is logged in
if(!isset($_SESSION["username"])) {
    header("location:index.php?action=login");
    exit; // Make sure to exit after redirection
}

// Database connection
$connect = mysqli_connect("localhost", "root", "", "login");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all users
$query = "SELECT * FROM users";
$result = mysqli_query($connect, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connect));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body>
    <br /><br />
    <div class="container" style="width:800px;">
        <h1>Welcome - <?php echo $_SESSION["username"]; ?></h1>
        <label><a href="logout.php">Logout</a></label>
      
        <br /><br />
        <h3>Registered Users:</h3>
        <div class="table-responsive">
            <table id="userTable" class="table table-bordered">
                <tr>
                    <th>Username</th>
                    <th>Password (Encrypted)</th>
                    <th>Password (Decrypted)</th>
                </tr>
                <?php
                while($row = mysqli_fetch_array($result)) {
                    echo '
                    <tr>
                        <td>'.$row["username"].'</td>
                        <td>'.$row["password1"].'</td>
                        <td>'.$row["password"].'</td>
                    </tr>
                    ';
                }
                ?>
            </table>
        </div>
    </div>

    <script>
        function generatePDF() {
            const doc = new jsPDF();
            const table = document.getElementById('userTable');
            let header = true;
            let rows = [];

            for (let i = 0; i < table.rows.length; i++) {
                let rowData = [];
                for (let j = 0; j < table.rows[i].cells.length; j++) {
                    rowData.push(table.rows[i].cells[j].innerText);
                }
                rows.push(rowData);
            }

            doc.autoTable({
                head: header ? [table.rows[0].cells.map(cell => cell.innerText)] : [],
                body: rows.slice(header ? 1 : 0),
            });

            doc.save('users.pdf');
        }
    </script>
</body>
</html>
