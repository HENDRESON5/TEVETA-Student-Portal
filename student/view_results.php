<?php
require_once '../config/auth.php';
require_login();
require_role('Student');

$studentName = $_SESSION['fullname'];
$studentId = $_SESSION['student_id'] ?? null;

$resultsData = [
  "practical"    => ["score" => null, "classification" => "Pending"],
  "occupational" => ["score" => null, "classification" => "Pending"],
  "fundamental"  => ["score" => null, "classification" => "Pending"],
];
$overall = ["classification" => "Pending", "comment" => ""];

if ($studentId) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM results WHERE student_id = ? ORDER BY updated_at DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($row) {
        $resultsData["practical"]    = ["score" => $row["practical_score"],    "classification" => $row["practical_classification"] ?? "Pending"];
        $resultsData["occupational"] = ["score" => $row["occupational_score"], "classification" => $row["occupational_classification"] ?? "Pending"];
        $resultsData["fundamental"]  = ["score" => $row["fundamental_score"],  "classification" => $row["fundamental_classification"] ?? "Pending"];
        $overall = [
            "classification" => $row["overall_classification"] ?? "Pending",
            "comment"        => $row["admin_comment"] ?? "",
        ];
    }
}

function classBadge($classification) {
    switch (strtolower($classification)) {
        case "distinction": return "badge-distinction";
        case "credit":       return "badge-credit";
        case "pass":         return "badge-pass";
        case "fail":         return "badge-fail";
        default:             return "badge-pending";
    }
}

$pageTitle  = "My Examination Results";
$activeMenu = "results";
require 'includes/header.php';
?>

    <div class="legend" aria-hidden="true">
      <span class="legend-item"><span class="legend-dot" style="background:var(--distinction)"></span> Distinction</span>
      <span class="legend-item"><span class="legend-dot" style="background:var(--credit)"></span> Credit</span>
      <span class="legend-item"><span class="legend-dot" style="background:var(--pass)"></span> Pass</span>
      <span class="legend-item"><span class="legend-dot" style="background:var(--fail)"></span> Fail</span>
    </div>

    <div class="paper-section">
      <h2>Results by Paper</h2>
      <table>
        <thead>
          <tr>
            <th>Paper</th>
            <th>Score</th>
            <th>Classification</th>
          </tr>
        </thead>
        <tbody>

          <tr>
            <td><span class="paper-name"><i class="fa fa-screwdriver-wrench"></i> Practical Paper</span></td>
            <td><span class="score-value"><?php echo $resultsData["practical"]["score"] !== null ? htmlspecialchars($resultsData["practical"]["score"]) : "—"; ?></span></td>
            <td><span class="badge <?php echo classBadge($resultsData["practical"]["classification"]); ?>"><?php echo htmlspecialchars($resultsData["practical"]["classification"]); ?></span></td>
          </tr>

          <tr>
            <td><span class="paper-name"><i class="fa fa-briefcase"></i> Occupational Paper</span></td>
            <td><span class="score-value"><?php echo $resultsData["occupational"]["score"] !== null ? htmlspecialchars($resultsData["occupational"]["score"]) : "—"; ?></span></td>
            <td><span class="badge <?php echo classBadge($resultsData["occupational"]["classification"]); ?>"><?php echo htmlspecialchars($resultsData["occupational"]["classification"]); ?></span></td>
          </tr>

          <tr>
            <td><span class="paper-name"><i class="fa fa-book"></i> Fundamental Paper</span></td>
            <td><span class="score-value"><?php echo $resultsData["fundamental"]["score"] !== null ? htmlspecialchars($resultsData["fundamental"]["score"]) : "—"; ?></span></td>
            <td><span class="badge <?php echo classBadge($resultsData["fundamental"]["classification"]); ?>"><?php echo htmlspecialchars($resultsData["fundamental"]["classification"]); ?></span></td>
          </tr>

        </tbody>
      </table>
    </div>

    <div class="overall-card">
      <h2>Overall Result</h2>

      <div class="overall-top">
        <span class="overall-label">Final classification across all papers:</span>
        <span class="overall-badge">
          <span class="badge <?php echo classBadge($overall["classification"]); ?>"><?php echo htmlspecialchars($overall["classification"]); ?></span>
        </span>
      </div>

      <div class="comment-box">
        <h3>Administration Comment</h3>
        <p class="<?php echo empty($overall["comment"]) ? "empty" : ""; ?>">
          <?php echo $overall["comment"] !== "" ? htmlspecialchars($overall["comment"]) : "No comment from administration."; ?>
        </p>
      </div>
    </div>

<?php require 'includes/footer.php'; ?>
