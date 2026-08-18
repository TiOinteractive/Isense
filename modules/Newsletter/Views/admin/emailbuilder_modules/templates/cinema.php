<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" data-conf='<?=json_encode(array('count'=>8,'cols'=>4)); ?>'>
	<tr>
		<td height="15"></td>
	</tr>
    <tr>
        <td align="center" bgcolor="#39424d">
            <table class="table800" width="800" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="30" style="font-size: 1px; line-height: 50px;">
                    </td>
                </tr>
                <tr class="cinema-nag">
                    <td class="conf-title" align="center" style="font-family: Lato, Arial, sans-serif; font-size: 30px; font-weight: 700; color: #ffffff; line-height: 30px;"><?=!empty($data) && !empty($data['title_text']) ? $data['title_text'] : 'W KINACH'; ?></td>
                </tr>
                <tr>
                    <td height="40" style="font-size: 1px; line-height: 50px;">
                    </td>
                </tr>
                <?php
                    $cols = 4;
                    if(!empty($data) && !empty($data['cols'])) {
                        $cols = $data['cols'];
                    }
                    $col_width = ($width - (($cols - 1) * 15) - 10) / $cols;
                ?>
                <?php if(!empty($cinema)): ?>
                    <?php foreach($cinema as $k=>$c): $k++;?>
                        <?php if($k%$cols == 1 || $cols == 1): ?>
                            <tr><td>
                        <?php endif; ?>
                        <table class="half-width" width="<?=$col_width; ?>" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tr>
                                <td align="center">
                                                                    <a href="<?php echo $c['link']; ?>"><img class="img-full" src="<?php echo $c['img']; ?>" alt="<?php echo $c['name']; ?>" width="<?=$col_width; ?>" style="border:none;" /></a>
                                </td>
                            </tr>
                            <tr>
                                <td height="10" style="font-size: 30px; line-height: 30px;">
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <h3 style="font-family:Lato,Arial,sans-serif;font-size:14px;font-weight:700;color:#ffffff;line-height:22px;margin:0;mso-line-height-rule:exactly;"><a href="<?php echo $c['link']; ?>" style="color:#ffffff;text-decoration:none;"><?php echo $c['name']; ?></a></h3>
                                </td>
                            </tr>
                            <tr>
                                <td height="30" style="font-size: 30px; line-height: 30px;">
                                </td>
                            </tr>
                        </table>
                        <?php if($k%$cols == 0 || $k == count($cinema)): ?>
                            <table class="half-width-space" width="1" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                <tr>
                                    <td width="1" height="30" style="font-size: 30px; line-height: 30px;"></td>
                                </tr>
                            </table>
                            </td></tr>
                        <?php else: ?>
                            <table class="<?php echo $k%2 == 1 ? 'half-width-space' : 'space-full-width'; ?>" width="15" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
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
                            <tbody><tr>
                                    <td align="center" height="42" style="font-family: Lato, Arial, sans-serif; font-size: 16px; font-weight: 400; color: #000000; line-height: 22px; padding-left: 30px; padding-right: 30px;">
                                        <a class="conf-url" href="<?=!empty($data) && !empty($data['url_href']) ? $data['url_href'] : 'https://www.resinet.pl/rozrywka/repertuar-kin'; ?>" title="<?=!empty($data) && !empty($data['url_text']) ? $data['url_text'] : 'zobacz pełny repertuar kin'; ?>" style="text-decoration: none; color: #000000;"><?=!empty($data) && !empty($data['url_text']) ? $data['url_text'] : 'zobacz pełny repertuar kin'; ?></a>
                                    </td>
                                </tr>
                            </tbody></table>
                    </td>
                </tr>
				<tr>
					<td height="30" style="font-size: 30px; line-height: 30px;"></td>
				</tr>
            </table>
        </td>
    </tr>
	<tr>
		<td height="15"></td>
	</tr>
</table>
