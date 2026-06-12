
<?php
session_start();

if (!isset($_SESSION['dolgozo_id']) || $_SESSION['jogkor'] != "a") {
    echo "Hozzáférés megtagadva. Csak adminisztrátorok számára.";
    exit;
}

require_once 'db.php'; // adatbázis kapcsolat

$sql = "SELECT `dolgozo_id`,`dolgozo_nev`,`beosztas`,`email`,`telefon` FROM `dolgozok` ORDER BY `dolgozo_nev` ASC";
$result = $conn->query($sql);

echo "<h2>Dolgozók listája</h2>";

echo "<table border='1' cellpadding='5' cellspacing='0'>
    <tr>
        <th>ID</th>
        <th>Név</th>
        <th>Beosztás</th>
        <th>Email</th>
        <th>Telefon</th>
    </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>" . $row['dolgozo_id'] . "</td>
        <td>" . $row['dolgozo_nev'] . "</td>
        <td>" . $row['beosztas'] . "</td>
        <td>" . $row['email'] . "</td>
        <td>" . $row['telefon'] . "</td>
    </tr>";
}
echo "</table>";