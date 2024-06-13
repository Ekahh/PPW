<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

include 'config.php';

// Mendapatkan nilai dari URL jika tersedia
$team_id = isset($_GET['team_id']) ? $_GET['team_id'] : '';
$new_title = isset($_GET['new_title']) ? $_GET['new_title'] : '';
$new_desc = isset($_GET['new_desc']) ? $_GET['new_desc'] : '';
$new_pkm_type = isset($_GET['new_pkm_type']) ? $_GET['new_pkm_type'] : '';

// Mengambil data lama dari database jika tersedia
if (!empty($team_id)) {
    $sql = "SELECT team_name, description FROM teams WHERE team_id = '$team_id'";
    $result = mysqli_query($koneksi, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $team_name = $row['team_name'];
        $desc = $row['description'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Team</title>
</head>
<body>
    <h1>Update Team</h1>
    <form action="update_team.php" method="post">
        <input type="hidden" name="team_id" value="<?php echo $team_id; ?>">
        <input type="text" name="new_title" placeholder="New Title" value="<?php echo $new_title ? $new_title : $team_name; ?>"><br><br>
        <input type="text" name="new_desc" placeholder="New Description" value="<?php echo $new_desc ? $new_desc : $desc; ?>"><br><br>
        <select name="new_pkm_type">
            <option value="" <?php if (empty($new_pkm_type)) echo 'selected'; ?>></option>
            <option value="PKM-K" <?php if ($new_pkm_type == 'PKM-K') echo 'selected'; ?>>PKM-K</option>
            <option value="PKM-PM" <?php if ($new_pkm_type == 'PKM-PM') echo 'selected'; ?>>PKM-PM</option>
            <option value="PKM-KC" <?php if ($new_pkm_type == 'PKM-KC') echo 'selected'; ?>>PKM-KC</option>
            <option value="PKM-GFK" <?php if ($new_pkm_type == 'PKM-GFK') echo 'selected'; ?>>PKM-GFK</option>
        </select><br><br>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
