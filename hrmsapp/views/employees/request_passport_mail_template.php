<meta http-equiv=\ "content-type\" content=\ "text/html; charset=UTF-8\">
<meta name=\ "”viewport”\" content=\ "”width=device-width\">
<link href=\ "https://fonts.googleapis.com/css?family=Roboto:400,900\" rel=\ "stylesheet\">
<title>AWOK Human Resource Management System</title>
<table width=\ "100%\" cellspacing=\ "0\" cellpadding=\ "0\" border=\ "0\" align=\ "center\">
    <tbody>
        <tr>
            <td height=\ "30\"></td>
        </tr>
        <tr>
            <!--Main Awok.com Newsletter Start-->
            <td>
                <table style=\ "background-color:#ffffff; border: solid 1px #f0f0f1;\" width=\ "600\" cellspacing=\ "0\" cellpadding=\ "0\" border=\ "0\" align=\ "center\">
                    <!--Header Section Start-->
                    <tbody>
                        <tr>
                            <td valign=\ "top\">
                                <table width=\ "600\" cellspacing=\ "0\" cellpadding=\ "0\" border=\ "0\" align=\ "center\">
                                    <tbody>
                                        <tr>
                                            <td style=\ "background-color:#D02E37;border-left:0;border-bottom:0px;border-right:0;border-top:0;\" valign=\ "top\">
                                                <table width=\ "598\" cellspacing=\ "0\" cellpadding=\ "0\" border=\ "0\" align=\ "center\">
                                                    <tbody>
                                                        <tr>
                                                            <td height=\ "5\"></td>
                                                        </tr>
                                                        <tr>
                                                            <td width=\ "15\"></td>
                                                            <td height=\ "54\">
                                                                <table width=\ "100%\" cellspacing=\ "0\" cellpadding=\ "0\" border=\ "0\">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width=\ "30\" align=\ "left\">
                                                                                <a href=\ "http://www.awok.com/\" target=\ "_blank\" style=\ "text-decoration:none;text-align:center;\"> <img src=\ "http://m1.awokcdn.com/nl/hrms/awok-logo.png\" alt=\ "AWOK Human Resource Management System\" style=\ "border:0;\" width=\ "30\" height=\ "31\"></a>
                                                                            </td>
                                                                            <td style=\ "font-family:Arial, Helvetica, sans-serif; font-size:20px; color: #fff;\" valign=\ "center\" align=\ "left\">HR Management System</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td width=\ "15\"></td>
                                                        </tr>
                                                        <tr>
                                                            <td height=\ "5\"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!--Header Section End-->
                        <!--Main Content Start-->
                        <tr>
                            <td valign=\ "top\">
                                <table width=\ "580\" cellspacing=\ "0\" cellpadding=\ "0\" border=\ "0\" align=\ "center\">
                                    <tbody>
                                        <tr>
                                            <td colspan=\ "2\" style=\ "font-family:Arial, Helvetica, sans-serif;font-size:13px;line-height:25px\">
                                                <table style=\ "padding:10px;border-bottom:none\" width=\ "100%\" cellspacing=\ "0\" cellpadding=\ "0\" border=\ "0\" bgcolor=\ "#FFFFFF\" align=\ "center\">
                                                    <tbody>
                                                        <tr>
                                                            <td height=\ "10\"></td>
                                                        </tr>
                                                        <tr>
                                                            <td height=\ "10\"></td>
                                                        </tr>
                                                        <?php if($request_type == 'passport_request'){?>

                                                            <?php if($request_data == ''){?>
                                                                <tr>
                                                                    <td><span style=\ "font-family:Arial, Helvetica, sans-serif;font-size:16px; font-weight:bold;\">Dear <?=$reporting_manager?>,</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td height=\ "10\"></td>
                                                                </tr>
                                                            <?php }else{?>
                                                                <tr>
                                                                    <td><span style=\ "font-family:Arial, Helvetica, sans-serif;font-size:16px; font-weight:bold;\">The below request is approved by <?=$reporting_manager?>,</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td height=\ "10\"></td>
                                                                </tr>
                                                            <?php }?>
                                                        <?php }else{?>

                                                            <tr>
                                                                <td><span style=\ "font-family:Arial, Helvetica, sans-serif;font-size:16px; font-weight:bold;\"><b><?=$emp_data->first_name.' '.$emp_data->middle_name.' '.$emp_data->last_name;?></b> is raised a request for <b><?=$request_type?></b></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td height=\ "10\"></td>
                                                            </tr>

                                                        <?php }?>

                                                        <?php if($request_type == 'passport_request'){?>
                                                        <tr>
                                                            <td style=\ "font-family:Arial, Helvetica, sans-serif; line-height:20px;\">
                                                                <p style=\ "font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333; padding:0; margin: 0px;\">I, <b><?=$emp_data->first_name.' '.$emp_data->middle_name.' '.$emp_data->last_name;?></b>, presently residing at <b><?=$emp_data->residing_address1.' '.$emp_data->residing_address2;?></b> hereby acknowledge the receipt of the passport from <b><?=$visa_type?></b>, for the purpose of <b><?=$purpose?></b></p>
                                                                
                                                                <p style=\ "font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333; padding:0; margin: 0px;\">I promise that I will turned-over to <b><?=$visa_type?></b> on <b><?=date('d-m-Y',strtotime($return_date))?></b>.</p>
                                                                <p style=\ "font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333; padding:0; margin: 0px;\">
                                                                I hereby bind myself liable and the company may opt to take administrative and/or legal actions against me shall I fail to turn-over the passport date mentioned above.<br> Done this <b><?=date('d');?> of <?=date('F');?>, <?=date('Y');?></b> at Dubai UAE.
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <?php }else{?>

                                                        <tr>
                                                            <td style=\ "font-family:Arial, Helvetica, sans-serif; line-height:20px;\">
                                                                <p style=\ "font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333; padding:0; margin: 0px;\">Purpose : <b><?=$purpose?></b></p>
                                                                
                                                                <p style=\ "font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333; padding:0; margin: 0px;\">Address to : <b><?=$address_to?></b></p>
                                                            </td>
                                                        </tr>

                                                        <?php }?>

                                                        <?php if($request_data == ''){?>
                                                        <tr>
                                                            <td style=\ "padding: 30px 10px;\">
                                                                <table width=\ "100%\" cellspacing=\ "0\" cellpadding=\ "0\" border=\ "0\" align=\ "center\">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td valign=\ "top\" align=\ "center\">
                                                                                <table width=\ "100%\" cellspacing=\ "0\" cellpadding=\ "0\" border=\ "0\" align=\ "center\">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td><a href="<?php echo site_url();?>approval/passport_request_approval/<?=base64_encode(1)?>/<?=base64_encode($insert_id)?>" target=\ "_blank\" style=\ "width: 250px; border-radius: 3px; display: block; background-color: #4CAF50; text-align: center; padding: 10px 0px; margin: 0 auto; text-transform: capitalize; text-decoration: none; color: #fff; font-size: 16px; font-family:Arial, Helvetica, sans-serif;\">Approve</a></td>
                                                                                            <td><a href="<?php echo site_url();?>approval/passport_request_approval/<?=base64_encode(2)?>/<?=base64_encode($insert_id)?>" target=\ "_blank\" style=\ "width: 250px; border-radius: 3px; display: block; background-color: #f02626; text-align: center; padding: 10px 0px; margin: 0 auto; text-transform: capitalize; text-decoration: none; color: #fff; font-size: 16px; font-family:Arial, Helvetica, sans-serif;\">Reject</a></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                        <tr>
                                                            <td>
                                                                <p style=\ "font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#333;padding:0;margin:0px;\">We would also appreciate any feedback you might have in regards and the same could be sent over to <a href=\ "mailto:hl@awok.com\">hl@awok.com</a></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height=\ "30\"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style=\ "font-size:14px\"> <strong>Sincerely,</strong> </td>
                                                        </tr>
                                                        <tr>
                                                            <td style=\ "font-size:13px\">AWOK.com HR Team
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height=\ "20\"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!--Main Content End-->
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td height=\ "20\"></td>
        </tr>
        <tr>
            <td colspan=\ "2\" style=\ "font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#999;line-height:18px;text-align:center;\"> Copyright © {var year} AWOK.com. All rights reserved.</td>
        </tr>
        <tr>
            <td height=\ "20\"></td>
        </tr>
        <!--Main Awok.com Newsletter End-->
    </tbody>
</table>
