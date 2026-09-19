<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

require_once('config.php');

$username = $_SESSION['username'];
$user_id  = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

$referral_code = strtoupper(substr(md5($username . $user_id), 0, 8));
$referral_link = "https://" . $_SERVER['HTTP_HOST'] . "/register.php?ref=" . $referral_code;

$commission_per_referral = 10.00;

if (isset($_POST['referred_name'], $_POST['referred_email'])) {
    $referred_name  = mysqli_real_escape_string($conn, $_POST['referred_name']);
    $referred_email = mysqli_real_escape_string($conn, $_POST['referred_email']);

    $sql = "INSERT INTO referrals (referrer_user_id, referrer_username, referral_code, referred_name, referred_email, status, commission_amount)
            VALUES (?, ?, ?, ?, ?, 'pending', ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "isssss", $user_id, $username, $referral_code, $referred_name, $referred_email, $commission_per_referral);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['referral_message'] = "Referral submitted successfully! You'll earn \$" . number_format($commission_per_referral, 2) . " once they join.";
    } else {
        $_SESSION['referral_message'] = "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    header("Location: ReferralCash.php");
    exit();
}

$referrals = [];
$total_earned = 0.00;

$sql = "SELECT referred_name, referred_email, status, commission_amount, created_at
        FROM referrals WHERE referrer_user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $referrals[] = $row;
        if ($row['status'] === 'paid') {
            $total_earned += $row['commission_amount'];
        }
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SMC Referral Cash</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="header">
  <nav class="navbar">
      <h2 class="logo"><a href="index.php"><i>S M</i><span class="i">C</span></a></h2>

    <a class="toggle-button change">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </a>

    <div class="navbar-links">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="Information.php">Info</a></li>
          <li><a href="PopularApps.php"> Media Apps</a></li>
          <li><a href="ParentsHelp.php">Parent's Help</a></li>
          <li><a href="Livestreaming.php">Livestream</a></li>
          <li><a  href="Guidance.php">Guidance</a></li>
          <li class="current"><a href="ReferralCash.php">Referral Cash</a></li>
          <li><a href="Contact.php">Contact</a></li>
          <li class="dropdown">
            <a href="">Trends</a>
            <div class="dropdown-content">
            <a href="#">Social Media Apps</a>
                  <a href="User_profile.php">User Profile Update</a>
                  <a href="#">tips</a>
            </div>
        </li>
          <li><a href="login.php">Log Out</a></li>

                <li class="search-icon">
                    <input type="search" placeholder="Search">
                    <label class="icon">
                      <img src="./img/search.png" alt="search" class="search">
                    </label>
                  </li>

            </ul>
        </div>

    </nav>

  </header>

  <main class="referral-page">
    <h1>ReferralCash</h1>
    <p class="referral-tagline">Earn commissions by referring customers.</p>

    <?php if (isset($_SESSION['referral_message'])): ?>
      <p class="<?php echo strpos($_SESSION['referral_message'], 'Error') === 0 ? 'error-message' : 'success-message'; ?>">
        <?php echo htmlspecialchars($_SESSION['referral_message']); ?>
      </p>
      <?php unset($_SESSION['referral_message']); ?>
    <?php endif; ?>

    <section class="referral-summary">
      <p>You earn <strong>$<?php echo number_format($commission_per_referral, 2); ?></strong> for every friend who joins using your link.</p>

      <label for="referral_link">Your Referral Link:</label>
      <input type="text" id="referral_link" value="<?php echo htmlspecialchars($referral_link); ?>" readonly onclick="this.select();">

      <p>Your Referral Code: <strong><?php echo htmlspecialchars($referral_code); ?></strong></p>

      <p>Total Earned: <strong>$<?php echo number_format($total_earned, 2); ?></strong></p>
    </section>

    <section class="referral-form">
      <h2>Refer a Friend</h2>
      <form action="" method="post">
        <label for="referred_name">Friend's Name:</label>
        <input type="text" id="referred_name" name="referred_name" required><br>

        <label for="referred_email">Friend's Email:</label>
        <input type="email" id="referred_email" name="referred_email" required><br>

        <input type="submit" value="Send Referral">
      </form>
    </section>

    <section class="referral-history">
      <h2>Your Referrals</h2>
      <?php if (empty($referrals)): ?>
        <p>You haven't referred anyone yet. Share your link to start earning!</p>
      <?php else: ?>
        <table class="referral-table">
          <thead>
            <tr>
              <th>Friend</th>
              <th>Email</th>
              <th>Status</th>
              <th>Commission</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($referrals as $r): ?>
              <tr>
                <td><?php echo htmlspecialchars($r['referred_name']); ?></td>
                <td><?php echo htmlspecialchars($r['referred_email']); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($r['status'])); ?></td>
                <td>$<?php echo number_format($r['commission_amount'], 2); ?></td>
                <td><?php echo htmlspecialchars($r['created_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggleButton = document.querySelector('.toggle-button');
      const navbarLinks = document.querySelector('.navbar-links');

      toggleButton.addEventListener('click', () => {
          navbarLinks.classList.toggle('active');
      });
  });
  </script>
</body>
</html>
