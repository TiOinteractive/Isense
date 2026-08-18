<?=view('emails/header'); ?>
<h4>Podsumowanie importu imprez KupBilecik.pl</h4>
<table>
    <tr>
        <td>Status:</td>
        <td><b><?=$stats['result'] ? 'OK' : 'Błąd'; ?></b></td>
    </tr>
    <?php if($stats['result']): ?>
        <tr>
            <td>Utworzono:</td>
            <td><b><?=count($stats['created']); ?></b></td>
        </tr>
        <tr>
            <td>Zaktualizowano:</td>
            <td><b><?=count($stats['updated']); ?></b></td>
        </tr>
        <tr>
            <td>Usunięto (odpublikowano):</td>
            <td><b><?=count($stats['removed']); ?></b></td>
        </tr>
        <tr>
            <td>Zdublowanych:</td>
            <td><b><?=count($stats['duplicated']); ?></b></td>
        </tr>
        <tr>
            <td>Pominiętych (są w innym bloku):</td>
            <td><b><?=!empty($stats['skipped_count']) ? $stats['skipped_count'] : 0; ?></b></td>
        </tr>
    <?php endif; ?>
</table>
<?php if($stats['result']): ?>
    <?php if(!empty($stats['created'])): ?>
        <h4>Imprezy utworzone</h4>
        <table>
            <tr>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Nazwa</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Rodzaj</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Miejsce</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Edycja</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Kalendarium</td>
            </tr>
            <?php foreach($stats['created'] as $event): ?>
                <tr>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;" href="<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['type']) ? $event['type']['name'] : ''; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['place']) ? $event['place']['name'] : $event['custom_place']; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['adm_link']; ?>" title="Edytuj: <?=esc($event['name']); ?>">Edytuj</a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['calendar_link']; ?>" title="Kalendarium: <?=esc($event['name']); ?>">Kalendarium</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if(!empty($stats['updated'])): ?>
        <h4 style="margin: 20px 0 5px 0;">Imprezy zaktualizowane</h4>
        <table style="border-collapse: collapse; font-size: 12px; line-height: 14px;">
            <tr>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Nazwa</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Rodzaj</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Miejsce</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Edycja</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Kalendarium</td>
            </tr>
            <?php foreach($stats['updated'] as $event): ?>
                <tr style="color:<?=empty($event['type']) || empty($event['place']) ? '#de0403' : '#000'; ?>">
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:<?=empty($event['type']) || empty($event['place']) ? '#de0403' : '#000'; ?>;text-decoration:none;" href="<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['type']) ? $event['type']['name'] : ''; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['place']) ? $event['place']['name'] : $event['custom_place']; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['adm_link']; ?>" title="Edytuj: <?=esc($event['name']); ?>">Edytuj</a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['calendar_link']; ?>" title="Kalendarium: <?=esc($event['name']); ?>">Kalendarium</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if(!empty($stats['removed'])): ?>
        <h4>Imprezy usunięte (odpublikowane)</h4>
        <table>
            <tr>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Nazwa</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Rodzaj</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Miejsce</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Edycja</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Kalendarium</td>
            </tr>
            <?php foreach($stats['removed'] as $event): ?>
                <tr>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;" href="<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['type']) ? $event['type']['name'] : ''; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['place']) ? $event['place']['name'] : $event['custom_place']; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['adm_link']; ?>" title="Edytuj: <?=esc($event['name']); ?>">Edytuj</a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['calendar_link']; ?>" title="Kalendarium: <?=esc($event['name']); ?>">Kalendarium</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if(!empty($stats['duplicated'])): ?>
        <h4>Imprezy zduplikowane</h4>
        <table>
            <tr>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Nazwa</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Rodzaj</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Miejsce</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Edycja</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Kalendarium</td>
            </tr>
            <?php foreach($stats['duplicated'] as $event): ?>
                <tr>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;" href="<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['type']) ? $event['type']['name'] : ''; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['place']) ? $event['place']['name'] : $event['custom_place']; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['adm_link']; ?>" title="Edytuj: <?=esc($event['name']); ?>">Edytuj</a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['calendar_link']; ?>" title="Kalendarium: <?=esc($event['name']); ?>">Kalendarium</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if(!empty($stats['skipped'])): ?>
        <h4>Imprezy pominięte (są już w innym bloku)</h4>
        <p style="margin: 0 0 5px 0; font-size: 12px; line-height: 14px;">Nie zostały zaimportowane, bo to samo wydarzenie zajął już wcześniejszy feed. Poniższe linki prowadzą do oryginału, który został bez zmian.</p>
        <table>
            <tr>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Nazwa</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Rodzaj</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Miejsce</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Blok</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Edycja</td>
                <td style="background: #000; color: #fff; padding: 5px 2px;">Kalendarium</td>
            </tr>
            <?php foreach($stats['skipped'] as $event): ?>
                <tr>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;" href="<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['type']) ? $event['type']['name'] : ''; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=!empty($event['place']) ? $event['place']['name'] : $event['custom_place']; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><?=$event['id_page_cont']; ?></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['adm_link']; ?>" title="Edytuj: <?=esc($event['name']); ?>">Edytuj</a></td>
                    <td style="padding: 2px 2px; border-bottom: 1px solid #000;"><a style="color:#000;text-decoration:none;font-weight:400;" href="<?=$event['calendar_link']; ?>" title="Kalendarium: <?=esc($event['name']); ?>">Kalendarium</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
<?php endif; ?>
<?=view('emails/footer'); ?>