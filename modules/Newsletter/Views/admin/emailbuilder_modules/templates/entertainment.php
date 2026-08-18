                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" data-conf='<?=json_encode(array('count'=>6,'cols'=>2)); ?>'>
    <tr>
        <td align="center" bgcolor="#ffffff">
            <table class="table800" width="800" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="15" style="font-size: 1px; line-height: 15px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="entertainment-nag-line-full-width" width="213" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tr>
                                <td height="8" style="font-size: 1px; line-height: 8px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="16" bgcolor="#ffc100"></td>
                            </tr>
                            <tr>
                                <td height="8" style="font-size: 1px; line-height: 8px;">&nbsp;</td>
                            </tr>
                        </table>
                        <table class="entertainment-nag-full-width" width="370" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tr>
                                <td class="conf-title" align="center" style="font-family: Lato, Arial, sans-serif; font-size: 30px; font-weight: 700; color: #000000; line-height: 30px;"><?=!empty($data) && !empty($data['title_text']) ? $data['title_text'] : 'ROZRYWKA I KULTURA'; ?></td>
                            </tr>
                        </table>
                        <table class="entertainment-nag-line-full-width" width="213" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tr>
                                <td height="8" style="font-size: 1px; line-height: 8px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="16" bgcolor="#ffc100"></td>
                            </tr>
                            <tr>
                                <td height="8" style="font-size: 1px; line-height: 8px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="30" style="font-size: 1px; line-height: 50px;">
                    </td>
                </tr>
                <?php
                    $cols = 2;
                    if(!empty($data) && !empty($data['cols'])) {
                        $cols = $data['cols'];
                    }
                    $col_width = ($width - (($cols - 1) * 15) - 5) / $cols;
                ?>
                <?php if(!empty($entertainment)): ?>
                    <?php foreach($entertainment as $k=>$e): $k++;?>
                        <?php if($k%$cols == 1 || $cols == 1): ?>
                            <tr><td>
                        <?php endif; ?>
                        <table class="full-width" width="<?=$col_width; ?>" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $e['link']; ?>"><img class="img-full" src="<?php echo $e['img']; ?>" alt="<?php echo $e['name']; ?>" width="<?=$col_width; ?>" style="border:none;" /></a>
                                </td>
                            </tr>
                            <tr>
                                <td height="10" style="font-size: 30px; line-height: 30px;">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                        switch($k%4) {
                                            case 0: $color = '#43bdf6';
                                                break;
                                            case 1: $color = '#ffc100';
                                                break;
                                            case 2: $color = '#fd4646';
                                                break;
                                            case 3: $color = '#65d55f';
                                                break;
                                            default: $color = '#ffc100';
                                                break;
                                        }


                                    ?>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo $color; ?>">
                                        <tbody>
                                                                                    <tr>
                                                                                            <td align="center" height="26" style="font-family: Lato, Arial, sans-serif; font-size: 12px; font-weight: 700; color: #000000; line-height: 14px; padding-left: 20px; padding-right: 20px; padding-top:4px; padding-bottom: 4px;">
                                                                                                    <a href="<?php echo $e['link']; ?>" style="text-decoration: none; color: #000000;">
                                                                                                            KONCERT
                                                                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15" style="font-size: 30px; line-height: 30px;">
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><h3 style="font-family:Lato,Arial,sans-serif;font-size:22px;font-weight:700;color:#000000;line-height:32px;margin:0;mso-line-height-rule:exactly;"><a href="<?php echo $e['link']; ?>" style="color:#000000;text-decoration:none;"><?php echo $e['name']; ?></a></h3></td>
                            </tr>
                        </table>
                        <?php if($k%$cols == 0 || $k == count($entertainment)): ?>
                            <table class="space-full-width-1" width="1" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                <tr>
                                    <td width="1" height="30" style="font-size: 30px; line-height: 30px;"></td>
                                </tr>
                            </table>
                            </td></tr>
                            <tr>
                                <td width="1" height="30" style="font-size: 30px; line-height: 30px;"></td>
                            </tr>
                        <?php else: ?>
                            <table class="space-full-width" width="15" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                <tr>
                                    <td width="15" height="30" style="font-size: 30px; line-height: 30px;"></td>
                                </tr>
                            </table>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td>
                        <table class="button-full" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffc100">
                            <tbody>
                                <tr>
                                    <td align="center" height="42" style="font-family: Lato, Arial, sans-serif; font-size: 16px; font-weight: 400; color: #000000; line-height: 22px; padding-left: 30px; padding-right: 30px;">
                                        <a class="conf-url" href="<?=!empty($data) && !empty($data['url_href']) ? $data['url_href'] : 'https://www.resinet.pl/rozrywka/aktualnosci'; ?>" title="<?=!empty($data) && !empty($data['url_text']) ? $data['url_text'] : 'zobacz wszystkie aktualności'; ?>" style="text-decoration: none; color: #000000;"><?=!empty($data) && !empty($data['url_text']) ? $data['url_text'] : 'zobacz wszystkie aktualności'; ?></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="15" style="font-size: 15px; line-height: 15px;"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>