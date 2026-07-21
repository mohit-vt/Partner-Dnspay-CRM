<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Agent Created</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .copy-btn { cursor: pointer; }
  </style>
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="alert alert-success shadow-sm">
    <h4 class="mb-3">Agent Registered Successfully!</h4>

    <p><strong>Agent ID:</strong> <?= html_escape($agent_id ?? ''); ?></p>

    <?php if (!empty($password)): ?>
      <p><strong>Temporary Password:</strong> <?= html_escape($password); ?></p>
      <small class="text-muted">Agent should change password after first login.</small>
    <?php endif; ?>



  </div>
</div>

<script>
  function copyReferral() {
    const link = document.getElementById("refLink").textContent;
    navigator.clipboard.writeText(link).then(() => {
      alert("Referral link copied!");
    });
  }
</script>
</body>
</html>
