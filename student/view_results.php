<?php
session_start();

// Temporary student name
$studentName = "Lonjezo Makhaula";

/* ============================================================
   TODO (BACKEND - your friend):
   Replace $resultsData and $overall below with a real database query.
   See the SQL shape suggested in earlier drafts of this page -
   one query for per-paper scores/classifications, one for the
   overall classification + admin comment.
   ============================================================ */

$resultsData = [
  "practical"    => ["score" => 88, "classification" => "Distinction"],
  "occupational" => ["score" => 65, "classification" => "Pass"],
  "fundamental"  => ["score" => 70, "classification" => "Credit"],
];

$overall = [
  "classification" => "Credit",
  "comment"         => "Good overall performance. Improve fundamental paper score next attempt.",
];

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
