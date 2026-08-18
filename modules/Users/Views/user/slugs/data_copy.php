<div class="user-copy-data">
    <table>
        <tr>
            <td><?=lang('Users.account.ID'); ?></td>
            <td><b><?=$user['id']; ?></b></td>
        </tr>
        <tr>
            <td><?=lang('Users.account.Name'); ?></td>
            <td><b><?=$user['name']; ?></b></td>
        </tr>
        <tr>
            <td><?=lang('Users.account.Surname'); ?></td>
            <td><b><?=$user['surname']; ?></b></td>
        </tr>
        <tr>
            <td><?=lang('Users.account.Nick'); ?></td>
            <td><b><?=$user['nick']; ?></b></td>
        </tr>
        <tr>
            <td><?=lang('Users.account.Email'); ?></td>
            <td><b><?=$user['mail']; ?></b></td>
        </tr>
        <tr>
            <td><?=lang('Users.account.GoogleID'); ?></td>
            <td><b><?=$user['google_id']; ?></b></td>
        </tr>
        <tr>
            <td><?=lang('Users.account.FacebookID'); ?></td>
            <td><b><?=$user['fb_id']; ?></b></td>
        </tr>
        <tr>
            <td><?=lang('Users.account.Newsletter'); ?></td>
            <td><b><?=$user['newsletter'] ? lang('Users.account.Yes') : lang('Users.account.No'); ?></b></td>
        </tr>
        <tr>
            <td><?=lang('Users.account.EditedAt'); ?></td>
            <td><b><?=$user['edited_at']; ?></b></td>
        </tr>
        <tr>
            <td><?=lang('Users.account.CreatedAt'); ?></td>
            <td><b><?=$user['created_at']; ?></b></td>
        </tr>
    </table>
</div>