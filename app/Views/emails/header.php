<html>
    <title><?= $settings['company_name']; ?></title>
    <style>
        body,html {background:white;color:#666;font:14px;line-height:18px;font-family: arial,Helvetica;}
        .email-body {width:600px;border: 1px solid #999999;margin:30px auto;}
        h1 {font-size:20px;font-weight:bold;color:#f7831e;text-align:center;}
        a {text-decoration:none;font-weight:bold;color:#f7831e;}
        .header {width:100%;background:black;}
        .header .tab-header {color:white;width:100%;font-size:14px;}
        .msg {padding:20px;}
        .footer {padding:10px 20px;border-top:1px solid black;font-size:12px;line-height:14px;}
    </style>
    <body>
        <table class="email-body" cellspacing="0" cellpadding="0">
            <tr>
                <td class="header">	
                    <table class="tab-header" cellspacing="10" cellpadding="10">
                        <tr>
                            <td>
                                <?php /* Bez logo nie emitujemy <img src="cid:"> — klient pocztowy
                                         pokazalby ikone zepsutego obrazka. */ ?>
                                <?php if(!empty($cid_logo)): ?>
                                    <img src="cid:<?= $cid_logo; ?>" alt="" />
                                <?php endif; ?>
                            </td>
                            <td align="right">
                                <?= lang('Users.form.PhoneNumber'); ?>: <?= $settings['phone']; ?>
                                <br /><?= lang('Users.form.Email'); ?>: <a href="mailto:<?= $settings['email']; ?>" style="text-decoration:none;color:#aeacad;"><?= $settings['email']; ?></a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="msg">