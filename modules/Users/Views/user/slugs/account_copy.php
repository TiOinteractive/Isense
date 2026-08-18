
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<div class="user-account">
    <div class="container">
        <div class="account-row">
            <div class="col col-sidebar">
                <?=view('Modules\Users\Views/user/slugs/account_menu'); ?>
            </div>
            <div class="col col-content">
                <div class="login-register-box">
                    <div class="registration-form">
                        <h1><?=lang('Users.account.DataCopy'); ?></h1>
                        <form method="post">
                            <?php if(isset($data['errors']) && isset($data['errors']['result'])):?>	
                                <div class="result alert alert-error"><?=$data['errors']['result'];?></div>
                            <?php endif; ?>
                            <?php if(!empty($data['flashdata']) && !empty($data['flashdata']['result'])): ?>
                                <div class="field"><div class="result alert alert-<?=$data['flashdata']['result']['status']; ?>"><?=$data['flashdata']['result']['message']; ?></div></div>
                            <?php endif; ?>
                            
                            <div class="field copy<?php if(isset($data['errors']) && isset($data['errors']['copy'])): ?> error<?php endif; ?>">
                                <div class="input">
                                    <select name="type">
                                        <option value="html">HTML</option>
                                        <option value="csv">CSV</option>
                                    </select>
                                    <span class="placeholder"><?=lang('Users.account.DataFormat'); ?>*</span>
                                </div>
                                <?php if(isset($data['errors']) && isset($data['errors']['copy'])):?>	
                                    <div class="alert alert-error"><?=$data['errors']['copy'];?></div>
                                <?php endif; ?>
                                <p class="info"><?=lang('Users.account.DataFormatInfo'); ?></p>
                            </div>
                            <div class="field btn-box">
                                <input class="btn" type="submit" name="account_copy" value="<?=lang('Users.account.Create'); ?>" />
                            </div>
                        </form>
                        <?php if(!empty($data['files'])): ?>
                            <div class="data-copies">
                                <h2><?=lang('Users.account.PreparedCopies'); ?></h2>
                                <ul>
                                    <?php foreach($data['files'] as $file): ?>
                                    <li class="file <?=$file['extension']; ?>"><a href="<?=$file['link']; ?>" title="<?=$file['name']; ?> - <?=$file['extension'] == 'html' ? lang('Users.account.ViewData') : lang('Users.account.DownloadData'); ?>" data-close="<?=lang('Users.account.Close'); ?>" data-title="<?=$file['name']; ?>"><b><?=$file['name']; ?></b> - <?=$file['extension'] == 'html' ? lang('Users.account.ViewData') : lang('Users.account.DownloadData'); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>