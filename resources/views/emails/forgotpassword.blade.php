<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div style="font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif;">
    <table style="width: 100%;">
        <tr>
            <td></td>
            <td bgcolor="#FFFFFF ">
                <div style="padding: 15px; max-width: 600px;margin: 0 auto;display: block; border-radius: 0px;padding: 0px; border: 1px solid lightseagreen;">
                    <table style="width: 100%;background: #b546aa ;">
                        <tr>
                            <td></td>
                            <td>
                                <div>
                                    <table width="100%">
                                        <tr>
                                            <td rowspan="2" style="text-align:center;padding:10px;">
                                                <span style="color:white;font-size: 13px;margin-top: 00px; padding:10px; font-size: 14px; font-weight:normal;">
                                                    <strong>JobPics</strong><span></span>
                                                </span>
                                                
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                    <table style="padding: 10px;font-size:14px; width:100%;">
                        <tr>
                            <td style="padding:10px;font-size:14px; width:100%;">
                                <p>Hi {{ $user_name }},</p>
                                <p><br /> You have requested a password reset. Below is your 6 digit new password for JobPics App.</p>
                                <p><strong>Code:</strong> {{$code}}</p>
                                <p>
                                    After login with this password once, Please reset your password that you want!.
                                </p>
                                <p> </p>
                                <p>Best Regards,</p>
                                <p>JobPics Team</p>
                                <!-- /Callout Panel -->
                                <!-- FOOTER -->
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div align="center" style="font-size:12px; margin-top:20px; padding:5px; width:100%; background:#eee;">
                                    © {{date('Y')}} <!-- <a href="http://seedoconline.com" target="_blank" style="color:#333; text-decoration: none;"> -->JobPics<!-- </a> -->
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>