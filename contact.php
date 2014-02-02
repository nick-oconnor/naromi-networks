<?php
    require_once "include.php";
    require_once "recaptchalib.php";

    // Process quote requests
    if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["company"]) && isset($_POST["phone"]) && isset($_POST["message"])) {

        // Check if the email address is valid
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

            // Check captcha answer
            $resp = recaptcha_check_answer("6LcRX-wSAAAAAF-EBJYhsXG7W0hBTBUTf7_FV2aQ", $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
            if ($resp->is_valid) {

                // Send the quote email
                $headers = "From: Naromi Networks <do-not-reply@naromi-networks.com>\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\n";
                $subject = "Naromi Networks contact form submission";
                $body = stripslashes("Name: {$_POST["name"]}<br/>Email: <a href=\"mailto:{$_POST["email"]}\">{$_POST["email"]}</a><br/>Company: {$_POST["company"]}<br/>Phone: {$_POST["phone"]}<br/><br/>{$_POST["message"]}");
                mail("steven.pitt@naromi-networks.com", $subject, $body, $headers);

                // Send the receipt email
                $body = "Thank you for your submission. Please retain this email for your records.<br/><br/>{$body}";
                mail($_POST["email"], $subject, $body, $headers);

                // Return a message
                return_message("success", "Request submitted, check your email for a receipt.");
            } else {

                // Return a message
                return_message("danger", "Invalid captcha response.");
            }

        } else {

            // Return a message
            return_message("danger", "Invalid email address.");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="/images/favicon.ico">

        <title>Naromi Networks - Contact</title>

        <!-- Bootstrap core CSS -->
        <link href="/css/bootstrap.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/carousel.css" rel="stylesheet">
        <link href="css/global.css" rel="stylesheet">

    </head>
    <body>

        <!-- NAVBAR
        ================================================== -->
        <div class="navbar-wrapper">
            <div class="container">
                <div class="navbar navbar-inverse navbar-static-top" role="navigation">
                    <div class="container">
                        <div class="navbar-header">
                            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="/"><img style="margin: -3px 10px -3px 0px;" src="/images/naromi_logo.png" alt="Naromi Logo">Naromi Networks, Inc.</a>
                        </div>
                        <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="index.html">Home</a></li>
                                <li class="active"><a href="contact.php">Contact</a></li>
                                <li><a href="login.php">Login</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Support <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="http://www.gigamon.com/accountsmgr/login.php?appname=extranet&amp;redirect=/customer-portal&amp;userid=gigamon&amp;password=nomagig" target="_blank">Gigamon</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Marketing messaging and featurettes
        ================================================== -->
        <!-- Wrap the rest of the page in another container to center all the content. -->
        <div class="container marketing">
            <div class="row featurette">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="featurette-heading title">Contact Us</h2>
                    <div style="float: left;">
                        <h2 class="text-muted">Mailing Address</h2>
                        <p class="lead">Naromi Networks, Inc.<br/>PO Box 576 Sherman, CT 06784-0576<br/>USA</p>
                    </div>
                    <div>
                        <h2 class="text-muted">Phone/Fax</h2>
                        <p class="lead">Phone: (800) 952-0919<br/>Fax: (860) 357-5476</p>
                    </div>
                    <div class="jumbotron">
                        <div id="alert"></div>
                        <form onsubmit="return request_quote(this);">
                            <input name="name" type="text" maxlength="64" placeholder="Name"/>
                            <input name="email" type="email" maxlength="64" placeholder="Email"/>
                            <input name="company" type="text" maxlength="64" placeholder="Company"/>
                            <input name="phone" type="text" maxlength="64" placeholder="Phone (optional)"/>
                            <textarea name="message" maxlength="4096" placeholder="Request a quote."></textarea><br/>
                            <div style="background-color: #FFFFFF; border: 1px solid gray;">
                                <script>
                                var RecaptchaOptions = {
                                    theme: "clean"
                                };
                                </script>
                                <?php echo recaptcha_get_html("6LcRX-wSAAAAAF6FaOS-O2Us8pL163zgTZqT3d6D", null, true); ?>
                            </div>
                            <input type="submit" class="btn btn-lg btn-default" value="Submit">
                        </form>
                    </div>
                </div>
            </div>

            <hr class="featurette-divider">

            <!-- /END THE FEATURETTES -->

            <!-- FOOTER -->
            <footer>
                <p class="pull-right"><a href="#">Back to top</a></p>
                <p>&copy; 2013 Naromi Networks, Inc. &middot; <a href="/privacy.html">Privacy</a></p>
            </footer>

        </div><!-- /.container -->

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="/js/jquery.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/alert.js"></script>
        <script>
            function request_quote(form) {
                if (form.name.value.length === 0) {
                    display_alert("danger", "Name required.");
                    form.name.focus();
                    return false;
                }
                if (form.email.value.length === 0) {
                    display_alert("danger", "Email address required.");
                    form.email.focus();
                    return false;
                }
                if (form.company.value.length === 0) {
                    display_alert("danger", "Company required.");
                    form.company.focus();
                    return false;
                }
                if (form.message.value.length === 0) {
                    display_alert("danger", "Message required.");
                    form.message.focus();
                    return false;
                }
                $.post(".", $(form).serialize(), function(json) {
                    display_alert(json.message.status, json.message.content);
                }, "json");
                return false;
            }
        </script>

    </body>
</html>
