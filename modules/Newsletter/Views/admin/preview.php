<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=$newsletter['subject']; ?>
            <span><?=lang('Newsletter.NewsletterPreview');; ?></span>
        </div>
        <?=view('Modules\Newsletter\Views\admin\tabs'); ?>
        <div class="newsletter-preview">
            <?= base64_decode($newsletter['html_text']); ?>
        </div>
    </div>
</div>