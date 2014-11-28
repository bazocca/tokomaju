<?php
	$this->Get->create($data);
	extract($data , EXTR_SKIP);
?>
<html>
  <body>
    <form action="#" method="post">
<?php
App::import('Vendor', 'recaptchalib');

// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = RECAPTCHA_PUBLIC_KEY;
$privatekey = RECAPTCHA_PRIVATE_KEY;

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
                echo "You got it!";
        } else {
                # set the error code so that we can display it
                $error = $resp->error;
                echo $error;
        }
}
echo recaptcha_get_html($publickey, $error);
?>
    <br/>
    <input type="submit" value="submit" />
    </form>
  </body>
</html>
