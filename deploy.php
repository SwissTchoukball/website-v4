<?php
/**
 * GIT DEPLOYMENT SCRIPT
 *
 * Used for automatically deploying websites via github securely, more deets here:
 *
 *    https://gist.github.com/limzykenneth/baef1b190c68970d50e1
 */
// The header information which will be verified
$agent = $_SERVER['HTTP_USER_AGENT'];
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
$body = @file_get_contents('php://input');
// The commands
$commands = array(
    'echo $PWD',
    'whoami',
    'git pull origin master',
    'git status',
    'git submodule sync',
    'git submodule update',
    'git submodule status',
    'php -d detect_unicode=Off bin/composer install'
);

$output = "";

base64_encode($agent);
base64_encode($signature);

if (strpos($agent, 'GitHub-Hookshot') !== false) {
    if (hash_equals($signature, verify_request())) {
        // Run the commands
        foreach ($commands as $command) {
            // Run it
            $tmp = shell_exec($command);

            $output .= $command . "\n";
            $output .= htmlentities(trim($tmp)) . "\n\n";
        }
    } else {
        header('HTTP/1.1 403 Forbidden');
        echo "Invalid request.";
    }
} else {
    header('HTTP/1.1 403 Forbidden');
    echo "Invalid request.";
}
// Generate the hash verification with the request body and the key stored in your .htaccess file
function verify_request()
{
    $message = $GLOBALS['body'];
    $key = $_ENV['GIT_TOKEN'];
    $hash = hash_hmac("sha1", $message, $key);
    $hash = "sha1=" . $hash;
    return $hash;
}

echo "Deploy successful.\n\n";
echo $output;
