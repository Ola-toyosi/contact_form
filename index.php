<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div>

        <?php
        // import phpMailer
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        require 'vendor/autoload.php';

        // define variables and set to empty values
        $firstnameErr = $lastnameErr = $emailErr = $phonenoErr
            = $departingErr = $returningErr = $residenceErr = $destinationErr = "";
        $firstname = $lastname = $email = $phoneno =
            $departing = $returning = $residence = $destination = $message = $submitSuccess = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["firstname"])) {
                $firstnameErr = "First Name is required";
            } else {
                $firstname = test_input($_POST["firstname"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z-' ]*$/", $firstname)) {
                    $firstnameErr = "Only letters and white space allowed";
                }
            }

            if (empty($_POST["lastname"])) {
                $lastnameErr = "Last Name is required";
            } else {
                $lastname = test_input($_POST["lastname"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z-' ]*$/", $lastname)) {
                    $lastnameErr = "Only letters and white space allowed";
                }
            }

            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
            } else {
                $email = test_input($_POST["email"]);

                // check if e-mail address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
            }

            if (empty($_POST["phoneno"])) {
                $phonenoErr = "Phone Number is required";
            } else {
                $phoneno = test_input($_POST["phoneno"]);
            }

            if (empty($_POST["departing"])) {
                $departingErr = "Departure Date is required";
            } else {
                $departing = test_input($_POST["departing"]);
            }

            if (empty($_POST["returning"])) {
                $returningErr = "Return Date is required";
            } else {
                $returning = test_input($_POST["returning"]);
            }

            if (empty($_POST["residence"])) {
                $ResidenceErr = "Residence Country is required";
            } else {
                $residence = test_input($_POST["residence"]);
            }

            if (empty($_POST["destination"])) {
                $destinationErr = "Destination Country is required";
            } else {
                $destination = test_input($_POST["destination"]);
            }

            if (empty($_POST["message"])) {
                $message = "";
            } else {
                $message = test_input($_POST["message"]);
            }

            if (
                !empty($firstname) && (!empty($lastname))
                &&  (!empty($email)) && (!empty($phoneno))
                && (!empty($departing)) && (!empty($returning))
                && (!empty($residence)) && (!empty($destination)) && (isset($_POST['checked']))
            ) {
                // SET EMAIL
                $senderEmail = 'bentoy1011@outlook.com';
                $emailPassword = 'testbentoy11';

                // send confirmation mail to customer
                $mailCus = new PHPMailer(true);
                try {
                    //Server settings
                    // $mailCus->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mailCus->isSMTP();                                            //Send using SMTP
                    $mailCus->Host       = 'smtp.office365.com';                  //Set the SMTP server to send through
                    $mailCus->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mailCus->Username   = $senderEmail;                     //SMTP username
                    $mailCus->Password   = $emailPassword;                               //SMTP password
                    $mailCus->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                    $mailCus->Port       = 587;                                    //TCP port to connect to

                    //Recipients
                    $mailCus->setFrom($senderEmail, 'WiseFares');
                    $mailCus->addAddress($email, $firstname);     //Add a recipient

                    //Content
                    $mailCus->isHTML(true);                                  //Set email format to HTML
                    $mailCus->Subject = 'Contact Form Submitted';
                    $mailCus->Body    = '<h3>Your enquiry has been recorded</h3> <br>
                                            Dear ' . $firstname . '<br> Your Enquiry has been successfully submitted.
                                            One of our agents will get back to you shortly';
                    $mailCus->AltBody = 'Your enquiry has been recorded
                                        Dear ' . $firstname . ' Your Enquiry has been successfully submitted.
                                        One of our agents will get back to you shortly';

                    $mailCus->send();
                    echo '  <div class="msg-success">
                                <p class ="msg-text"> Submitted Successfully </p>
                            </div>';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mailCus->ErrorInfo}";
                }


                // send email to admin
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = $senderEmail;                     //SMTP username
                    $mail->Password   = $emailPassword;                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                    $mail->Port       = 587;                                    //TCP port to connect to

                    //Recipients
                    $mail->setFrom($senderEmail, 'WiseFares');
                    $mail->addAddress('hhoollah11@gmail.com', 'Admin');     //Add a recipient
                    // $mail->addAddress($email);               //Name is optional
                    // $mail->addReplyTo('info@example.com', 'Information');
                    // $mail->addCC('cc@example.com');
                    // $mail->addBCC('bcc@example.com');


                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Contact Form Submitted';
                    $mail->Body    = '<h3>There has been a new visa enquiry</h3> <br>
                                    <table>
                                        <tr>
                                            <td> <b> Fullname: </b> </td>
                                            <td>  <p>' . $firstname . ' ' . $lastname . '<p> </td>
                                        </tr>
                                        <tr>
                                            <td> <b> Email: </b> </td>
                                            <td>  <p>' . $email . '<p> </td>
                                        </tr>
                                        <tr>
                                            <td> <b> Phone Number: </b> </td>
                                            <td>  <p>' . $phoneno . '<p> </td>
                                        </tr>
                                        <tr>
                                            <td> <b> Departure Date: </b> </td>
                                            <td>  <p>' . $departing . '<p> </td>
                                        </tr>
                                        <tr>
                                            <td> <b> Return Date: </b> </td>
                                            <td>  <p>' . $returning . '<p> </td>
                                        </tr>
                                        <tr>
                                            <td> <b> Resident Country: </b> </td>
                                            <td>  <p>' . $residence . '<p> </td>
                                        </tr>
                                        <tr>
                                            <td> <b> Destination Country: </b> </td>
                                            <td>  <p>' . $destination . '<p> </td>
                                        </tr>
                                        <tr>
                                            <td> <b> Message: </b> </td>
                                            <td>  <p>' . $message . '<p> </td>
                                        </tr>
                                    </table>';
                    $mail->AltBody = 'There has been a new visa enquiry
                Fullname: ' . $firstname . $lastname .
                        'Email: ' . $email .
                        'Phone number: ' . $phoneno .
                        'Departure date: ' . $departing .
                        'Return date: ' . $returning .
                        'Resident Country: ' . $residence .
                        'Destination Country: ' . $destination .
                        'Message: ' . $message;

                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                echo ' <div class="msg-failure">
                            <p class="msg-text"> Failed to submit </p>
                        </div>';
            }
        }

        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>

    </div>

    <div class="form-container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table style="margin:0; padding:0">
                <th colspan=2 style="width:100%; text-align:center; padding-bottom:5% ">
                    Kindly fiil the below form, one of our agents will contact you shortly
                </th>
                <tr>
                    <td><label for="firstname">First Name
                            <em>
                                <span class="error">*
                                    <?php echo $firstnameErr; ?>
                                </span>
                            </em>
                        </label>

                    </td>
                    <td><label for="lastName">Last Name
                            <em>
                                <span class="error">*
                                    <?php echo $lastnameErr; ?>
                                </span>

                            </em>
                        </label>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="input-container">
                            <input class="input-field" placeholder="John" type="text" name="firstname">
                            <i class="fa fa-user icon"></i>
                        </div>
                    </td>
                    <td>
                        <div class="input-container">
                            <input class="input-field" placeholder="Doe" type="text" name="lastname">
                            <i class="fa fa-user icon"></i>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="email">Email
                            <em>
                                <span class="error">*
                                    <?php echo $emailErr; ?>
                                </span>
                            </em>
                        </label>
                    </td>
                    <td>
                        <label for="phoneno">Phone Number
                            <em>
                                <span class="error">*
                                    <?php echo $phonenoErr; ?>
                                </span>
                            </em>
                        </label>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="input-container">
                            <input class="input-field" placeholder="john@example.com" type="email" name="email">
                            <i class="fa fa-envelope icon"></i>
                        </div>
                    </td>
                    <td>
                        <div class="input-container">
                            <input class="input-field" placeholder="123-456-7890" type="tel" name="phoneno">
                            <i class="fa fa-phone icon"></i>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="departing">Departing
                            <em>
                                <span class="error">*
                                    <?php echo $departingErr; ?>
                                </span>
                            </em>
                        </label>
                    </td>
                    <td>
                        <label for="returning">Returning
                            <em>
                                <span class="error">*
                                    <?php echo $returningErr; ?>
                                </span>
                            </em>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-container">
                            <input type="text" class="input-field" placeholder="DD/MM/YY" min="<?php echo date("Y-m-d"); ?>" onfocus="(this.type='date')" onblur="(this.type='text')" name="departing">
                            <i class="fa fa-calendar icon"></i>
                        </div>
                    </td>
                    <td>
                        <div class="input-container">
                            <input class="input-field" placeholder="DD/MM/YY" type="text" min="<?php echo date("Y-m-d"); ?>" onfocus="(this.type='date')" onblur="(this.type='text')" name="returning">
                            <i class="fa fa-calendar icon"></i>
                            </input>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="residence">Country of Residence
                            <em>
                                <span class="error">*
                                    <?php echo $residenceErr; ?>
                                </span>
                            </em>
                        </label>
                    </td>
                    <td>
                        <label for="residence">Destination Country
                            <em>
                                <span class="error">*
                                    <?php echo $destinationErr; ?>
                                </span>
                            </em>
                        </label>

                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="input-container">
                            <select style="width:100%; height:40px" name="residence">
                                <option value="Nigeria">Nigeria</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="input-container">
                            <select style="width:100%; height:40px" name="destination">
                                <option value="Ghana">Ghana</option>
                            </select>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Message</td>
                </tr>

                <tr colspan=2>
                    <td>
                        <textarea style="width:200%; resize:none" placeholder="type a message" name="message" id="message" rows="10"></textarea>
                    </td>
                </tr>

                <tr>
                    <td colspan=2>
                        <input type="checkbox" name="checked" value="1">Agree
                        <?php if (!isset($_POST['checked'])) { ?>
                            <span class="error">*
                                <?php echo "You must agree to terms"; ?>
                            </span>
                        <?php } ?>
                    </td>

                </tr>

                <tr>
                    <td>
                        <input type="submit" name="submit" value="Submit">
                    </td>
                </tr>

            </table>
        </form>
    </div>

</body>

</html>