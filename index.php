<?php
declare(strict_types=1);

// Set a friendly default timezone (change if you want)
date_default_timezone_set('America/Monterrey');

// Tiny "health check": index.php?ping=1 -> outputs "pong"
if (isset($_GET['ping'])) {
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'pong';
    exit;
}

// Safe helper
function h(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

// (Optional) quick write test to confirm filesystem permissions (to /tmp only)
$writeTestOk = null;
try {
    $tmpFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_write_test_' . uniqid() . '.txt';
    if (@file_put_contents($tmpFile, "Hello from PHP at " . date('c')) !== false) {
        $writeTestOk = true;
        @unlink($tmpFile);
    } else {
        $writeTestOk = false;
    }
} catch (Throwable $e) {
    $writeTestOk = false;
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PHP Test Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
    body { margin: 2rem; line-height: 1.5; color: #222; }
    .card { border: 1px solid #ddd; border-radius: 12px; padding: 1rem 1.25rem; margin: 1rem 0; }
    .ok { color: #0a7f2e; font-weight: 600; }
    .warn { color: #b04a00; font-weight: 600; }
    code, pre { background: #f6f8fa; padding: .2rem .4rem; border-radius: 6px; }
    small { color: #666; }
    form { display: grid; gap: .5rem; max-width: 420px; }
    input[type="text"] { padding: .5rem .6rem; border: 1px solid #bbb; border-radius: 8px; }
    button { padding: .5rem .8rem; border: 0; border-radius: 8px; background: #1f6feb; color: white; cursor: pointer; }
    button:hover { filter: brightness(1.05); }
  </style>
</head>
<body>
  <h1>✅ PHP is working!</h1>
  <p>This is a tiny test page to confirm your hosting runs PHP.</p>

  <div class="card">
    <h2>Environment</h2>
    <ul>
      <li><strong>PHP version:</strong> <?= h(PHP_VERSION) ?></li>
      <li><strong>Server software:</strong> <?= h($_SERVER['SERVER_SOFTWARE'] ?? 'n/a') ?></li>
      <li><strong>Host:</strong> <?= h($_SERVER['HTTP_HOST'] ?? 'n/a') ?></li>
      <li><strong>Server name:</strong> <?= h($_SERVER['SERVER_NAME'] ?? 'n/a') ?></li>
      <li><strong>Server address:</strong> <?= h($_SERVER['SERVER_ADDR'] ?? 'n/a') ?></li>
      <li><strong>Client IP:</strong> <?= h($_SERVER['REMOTE_ADDR'] ?? 'n/a') ?></li>
    </ul>
    <p><strong>Current time:</strong> <?= h(date('Y-m-d H:i:s T')) ?></p>
    <p>
      <strong>Temp write test:</strong>
      <?php if ($writeTestOk === true): ?>
        <span class="ok">OK (able to write to <?= h(sys_get_temp_dir()) ?>)</span>
      <?php elseif ($writeTestOk === false): ?>
        <span class="warn">Failed (cannot write to <?= h(sys_get_temp_dir()) ?>)</span>
      <?php else: ?>
        <span>Not attempted</span>
      <?php endif; ?>
    </p>
    <p><small>Health check endpoint: <code>?ping=1</code> returns <code>pong</code>.</small></p>
  </div>

  <div class="card">
    <h2>Quick GET/POST test</h2>
    <form method="post">
      <label>Message (POST): <input type="text" name="msg" placeholder="Type something…"></label>
      <button type="submit">Submit POST</button>
    </form>
    <?php if ($_POST): ?>
      <p><strong>POSTed:</strong> <code><?= h($_POST['msg'] ?? '') ?></code></p>
    <?php endif; ?>

    <p><small>Try adding <code>?q=hello</code> to the URL.</small></p>
    <?php if (isset($_GET['q'])): ?>
      <p><strong>GET param q:</strong> <code><?= h($_GET['q']) ?></code></p>
    <?php endif; ?>
  </div>

  <div class="card">
    <h2>Helpful (optional) checks</h2>
    <ul>
      <li>Document root: <code><?= h($_SERVER['DOCUMENT_ROOT'] ?? '') ?></code></li>
      <li>Script: <code><?= h($_SERVER['SCRIPT_FILENAME'] ?? '') ?></code></li>
      <li>Loaded extensions sample: <code><?= h(implode(', ', array_slice(get_loaded_extensions(), 0, 8))) ?><?= count(get_loaded_extensions()) > 8 ? ', …' : '' ?></code></li>
    </ul>
    <details>
      <summary>Show <code>$_SERVER</code></summary>
      <pre><?php print_r($_SERVER); ?></pre>
    </details>
  </div>

  <p><small>For a full dump you can temporarily enable <code>phpinfo()</code>, but avoid leaving it public.</small></p>
  <!--
  Uncomment for a one-time check, then re-comment or remove:
  <?php // phpinfo(); ?>
  -->
</body>
</html>
