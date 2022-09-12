<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <metan name="viewport" content="width=device-width">
        <title>Dream Home Seller.com</title>
    </head>
    <body>
        <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="e8e8e8">
            <tbody>
                <tr>
                    <td height="60" style="font-size:60px;line-height:60px">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" bgcolor="ffffff">
                            <tbody>
                                <tr>
                                    <td>
                                        <table border="0" align="center" width="480" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td height="10" style="font-size:50px;line-height:10px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="center"><img src="http://portal.dream-homeseller.com/images/DHG_logo.png" width="100" height="100"/></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="10" style="font-size:40px;line-height:10px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table align="center" width="250" border="0" cellpadding="0" cellspacing="0" bgcolor="ededed">
                                                            <tbody>
                                                                <tr>
                                                                    <td height="1" style="font-size:1px;line-height:1px">&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="40" style="font-size:40px;line-height:40px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table border="0" width="480" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="left" style="color:#2d2e2f;font-size:18px;font-family:Arial,Helvetica,sans-serif;font-weight:700;line-height:22px">
                                                                        <div style="line-height:22px">
                                                                            Hello {{ $emails['username'] }},
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="10" style="font-size:10px;line-height:10px">&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="20" style="font-size:20px;line-height:20px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table border="0" width="480" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse" bgcolor="f5f5f5">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table border="0" width="420" align="center" cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td height="20" style="font-size:20px;line-height:20px">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left" style="color:#6b6f70;font-size:13px;font-family:Arial,Helvetica,sans-serif;line-height:24px">
                                                                                        @if($emails['type'] != 'deleted_ticket')
                                                                                            <p><b>{{ $emails['submit_message'] }}</b></p>
                                                                                            @if($emails['type'] == 'new_ticket')
                                                                                                <p>{{ $emails['sub_title'] }}</p>
                                                                                            @endif
                                                                                            <p><b>Details:</b></p>
                                                                                            <ul>
                                                                                                <li><b>Due Date:</b> {{ $emails['due_date'] }}</li>
                                                                                                <li><b>Time:</b> {{ $emails['time'] }}</li>
                                                                                                <li><b>Priority:</b> {{ $emails['priority'] }}</li>
                                                                                                <li><b>Description:</b> {{ $emails['message'] }}</li>
                                                                                            </ul>
                                                                                            <p>Click <a href="http://portal.dream-homeseller.com/tasks/overview/{{$emails['id']}}" target="_BLANK">here</a> to {{ $emails['view_ticket'] }}.</p>
                                                                                        @else
                                                                                            <p><b>{{ $emails['submit_message'] }}</b></p>
                                                                                        @endif
                                                                                        <p>If you need further assistance, kindly contact <b>{{ $emails['created_by'] }}</b></p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="20" style="font-size:20px;line-height:20px">&nbsp;</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="40" style="font-size:40px;line-height:40px">
                                                        <p style="color:#6b6f70;font-size:10px;font-family:Arial,Helvetica,sans-serif;line-height:14px">
                                                            <i>&nbsp; **A direct reply to this email will be automatically recorded to the Dream Home Guide Messaging System.</i>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="30" style="font-size:30px;line-height:30px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table align="center" width="250" border="0" cellpadding="0" cellspacing="0" bgcolor="ededed">
                                                            <tbody>
                                                                <tr>
                                                                    <td height="1" style="font-size:1px;line-height:1px">&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="30" style="font-size:30px;line-height:30px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <table border="0" align="center" width="480" cellpadding="0" cellspacing="0">
                                                        <tbody><tr>
                                                            <td>
                                                                <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse">

                                                                    <tbody><tr>
                                                                        <td align="center">
                                                                            <?php echo date('Y'); ?> &copy; by <a href="http://portal.dream-homeseller.com/login" target="_blank">Dream Home Guide</a> <br>
                                                                        </td>
                                                                    </tr>
<!-- end tr -->
                                                                </tbody></table>

                                                                <table border="0" align="left" width="5" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
                                                                    <tbody><tr>
                                                                        <td height="20" width="5" style="font-size:20px;line-height:20px">&nbsp;</td>
                                                                    </tr>
                                                                </tbody></table>

                                                            </td>
                                                        </tr>
                                                    </tbody></table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="30" style="font-size:30px;line-height:30px">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="50" style="font-size:50px;line-height:50px">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>