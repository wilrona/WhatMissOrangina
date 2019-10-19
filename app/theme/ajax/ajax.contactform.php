<?php

add_action('wp_ajax_contactusform', 'contactusform_callback');
add_action('wp_ajax_nopriv_contactusform', 'contactusform_callback');



function contactusform_callback() {
	check_ajax_referer('contactus_form_ajax', 'security');


    ob_start();

    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Email Contact</title>
        <style type="text/css">

            @media screen and (max-width: 600px) {
                table[class="container"] {
                    width: 95% !important;
                }
            }

            #outlook a {padding:0;}
            body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
            .ExternalClass {width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
            #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
            img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
            a img {border:none;}
            .image_fix {display:block;}
            p {margin: 1em 0;}
            h1, h2, h3, h4, h5, h6 {color: black !important;}

            h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}

            h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
                color: red !important;
            }

            h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
                color: purple !important;
            }

            table td {border-collapse: collapse;}

            table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

            a {color: #000;}

            @media only screen and (max-device-width: 480px) {

                a[href^="tel"], a[href^="sms"] {
                    text-decoration: none;
                    color: black; /* or whatever your want */
                    pointer-events: none;
                    cursor: default;
                }

                .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                    text-decoration: default;
                    color: orange !important; /* or whatever your want */
                    pointer-events: auto;
                    cursor: default;
                }
            }


            @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
                a[href^="tel"], a[href^="sms"] {
                    text-decoration: none;
                    color: blue; /* or whatever your want */
                    pointer-events: none;
                    cursor: default;
                }

                .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                    text-decoration: default;
                    color: orange !important;
                    pointer-events: auto;
                    cursor: default;
                }
            }

            @media only screen and (-webkit-min-device-pixel-ratio: 2) {
                /* Put your iPhone 4g styles in here */
            }

            @media only screen and (-webkit-device-pixel-ratio:.75){
                /* Put CSS for low density (ldpi) Android layouts in here */
            }
            @media only screen and (-webkit-device-pixel-ratio:1){
                /* Put CSS for medium density (mdpi) Android layouts in here */
            }
            @media only screen and (-webkit-device-pixel-ratio:1.5){
                /* Put CSS for high density (hdpi) Android layouts in here */
            }
            /* end Android targeting */
            h2{
                color:#181818;
                font-family:Helvetica, Arial, sans-serif;
                font-size:22px;
                line-height: 22px;
                font-weight: normal;
            }
            a.link1{

            }
            a.link2{
                color:#fff;
                text-decoration:none;
                font-family:Helvetica, Arial, sans-serif;
                font-size:16px;
                color:#fff;border-radius:4px;
            }
            p{
                color:#555;
                font-family:Helvetica, Arial, sans-serif;
                font-size:16px;
                line-height:160%;
            }
        </style>

        <script type="colorScheme" class="swatch active">
  {
    "name":"Default",
    "bgBody":"ffffff",
    "link":"fff",
    "color":"555555",
    "bgItem":"ffffff",
    "title":"181818"
  }
</script>

    </head>
    <body>

    <!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
    <table cellpadding="0" width="100%" cellspacing="0" border="0" id="backgroundTable" class='bgBody'>
        <tr>
            <td>
                <table cellpadding="0" width="620" class="container" align="center" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <!-- Tables are the most common way to format your email consistently. Set your table widths inside cells and in most cases reset cellpadding, cellspacing, and border to zero. Use nested tables as a way to space effectively in your message. -->


                            <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
                                <tr>
                                    <td class='movableContentContainer bgItem'>

                                        <div class='movableContent'>
                                            <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
                                                <tr height="100">
                                                    <td width="200">&nbsp;</td>
                                                    <td width="200">&nbsp;</td>
                                                    <td width="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="200" valign="top">&nbsp;</td>
                                                    <td width="200" valign="top" align="center">
                                                        <div class="contentEditableContainer contentImageEditable">
                                                            <div class="contentEditable" align='center' >
                                                                <img src="<?php echo get_template_directory_uri(); ?>/images/logo-png.png"  alt='Logo'  data-default="placeholder" />
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="200" valign="top">&nbsp;</td>
                                                </tr>
                                                <tr height="35">
                                                    <td width="200">&nbsp;</td>
                                                    <td width="200">&nbsp;</td>
                                                    <td width="200">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class='movableContent'>
                                            <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
                                                <tr>
                                                    <td width="100%" colspan="3" align="center" style="padding-bottom:10px;padding-top:25px;">
                                                        <div class="contentEditableContainer contentTextEditable">
                                                            <div class="contentEditable" align='center' >
                                                                <h2 >Prise de contact de  <?= $_POST['name'] ?></h2>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="100">&nbsp;</td>
                                                    <td width="400" align="center">
                                                        <div class="contentEditableContainer contentTextEditable">
                                                            <div class="contentEditable" align='left' >
                                                                <p >L'internaute <?= $_POST['name'] ?> souhaite votre service,
                                                                    <br/>
                                                                    Son email : <?= $_POST['email']; ?> <br>
                                                                    Son Téléphone : <?= $_POST['phone']; ?>
                                                                    <br/>
                                                                    Son message est le suivant :
                                                                    <br/>
                                                                    <strong><?= $message; ?></strong></p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="100">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>




                        </td></tr></table>

            </td>
        </tr>
    </table>
    <!-- End of wrapper table -->



    </body>
    </html>


    <?php

    $message = ob_get_contents();
    ob_end_clean();

    $sender = wp_mail( tr_options_field('pc_options.email_contact'), 'Contact depuis le site web', $message );

    if($sender): echo 1; endif;
    wp_die();
}