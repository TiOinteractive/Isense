<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($newsletter) && !empty($newsletter['id'])): ?>
                <?=$newsletter['name']; ?>
                <span><?=lang('Newsletter.EmailBuilder'); ?></span>
            <?php else: ?>
                <?=lang('Newsletter.EmailBuilder'); ?>
            <?php endif; ?>
        </div>
        <?=view('Modules\Newsletter\Views\admin\tabs'); ?>
        
        <div id="email-builder">
            <!-- Main Sidebar -->
            <div class="sidebar-main">
                
                <ul class="nav" data-type="nav">
                    <li class="nav-item active" data-nav="has-child">
                        <span class="item"><i class="icon icon-sliders"></i> <?=lang('Newsletter.emailbuilder.Edit');?></span>
                        <ul class="subnav" data-type="subnav">
                            <li class="subnav-item" data-target="modules"><?=lang('Newsletter.emailbuilder.Modules');?></li>
                            <li class="subnav-item" data-target="styles"><?=lang('Newsletter.emailbuilder.Styles');?></li>
                        </ul>
                    <li class="nav-item" data-nav="has-child">
                        <span class="item"><i class="icon icon-download"></i> <?=lang('Newsletter.emailbuilder.Export');?></span>
                        <ul class="subnav" data-type="subnav">
                            <li class="subnav-item" data-action="export-zip"><?=lang('Newsletter.emailbuilder.ZipArchive');?></li>
                            <li class="subnav-item" data-action="export-html"><?=lang('Newsletter.emailbuilder.HtmlOnly');?></li>
                        </ul>
                    </li>
                </ul>
                <div class="sidebar-footer">
                    <a class="btn-main" href="#" data-action="campaign-settings"><?=lang('Newsletter.emailbuilder.SaveCampaign');?></a>
                    <?php /*<a class="btn-second" href="theme.php"><?=lang('Newsletter.emailbuilder.ChooseTemplate');?></a>*/ ?>
                </div>
            </div>
            <!-- Main Container -->
            <div class="main-container">
                <!-- Second Sidebar -->
                <div class="sidebar-second" data-type="sidebar-second">
                    <!-- Modules -->
                    <div class="sidebar-inner" data-sidebar="modules">
                        <div class="inner-header"><?=lang('Newsletter.emailbuilder.DragAndDropModules');?></div>
                        <div class="inner-content" data-type="modules-container">
                        </div>
                    </div>
                    <!-- Styles -->
                    <div class="sidebar-inner" data-sidebar="styles">
                        <div class="inner-header">
                        </div>
                        <div class="inner-content">
                            <div data-type="module-options">
                            </div><!-- module-options -->
                        </div>
                    </div>
                </div>
                <div class="pre-holder">
                    <div class="pre-holder-container">
                        <!-- Holder Container -->
                        <div class="holder-container">
                            <!-- Top Sidebar -->
                            <div class="sidebar-top">
                                <span class="item template-preview-mobile"><i class="icon-mobile"></i></span>
                                <span class="item template-preview-tablet"><i class="icon-tablet"></i></span>
                                <span class="item template-clear item-last"><i class="icon-close"></i> <?=lang('Newsletter.emailbuilder.Clear');?></span>
                            </div>
                            <!-- Editor Container -->
                            <div class="editor-container">
                                <!-- Editor Area -->
                                <div class="editor" data-type="editor">
                                    <?php if(!empty($newsletter) && !empty($newsletter['html_text'])): ?>
                                        <?= preg_replace('~<head(.*?)</head>~Usi', "", base64_decode($newsletter['html_text'])); ?>
                                    <?php endif; ?>
                                </div> <!-- /.editor -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form id="export-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/export" method="POST">
                <input type="hidden" name="type" value="html"/>
                <input type="hidden" name="theme" value="1"/>
                <input id="templateHTML" type="hidden" name="templateHTML" value="" />
            </form> <!-- / Template Export Form  -->
        </div>


        <div id="linkeditor" class="modal-container">
            <div class="input-group">
                <input class="input-control" type="text" data-link="text" value="" placeholder="<?=lang('Newsletter.emailbuilder.Text'); ?>">
                <input class="input-control" type="text" data-link="url" value="" placeholder="<?=lang('Newsletter.emailbuilder.LinkExample'); ?>">
            </div>
            <div class="modal-controls">
                <button class="modal-btn-cancel"><?=lang('Newsletter.emailbuilder.Cancel');?></button>
                <button class="modal-btn-ok"><?=lang('Newsletter.emailbuilder.OK');?></button>
            </div>
        </div>

        <div id="imageeditor" class="modal-container">
            <div class="modal-container-inner">
                <div class="input-group">
                    <input class="input-control" type="text" data-image="url" value="" placeholder="<?=lang('Newsletter.emailbuilder.Url'); ?>">
                    <input class="input-control" type="text" data-image="alt" value="" placeholder="<?=lang('Newsletter.emailbuilder.Alt'); ?>">
                    <input class="input-control input-control-small" type="text" data-image="width" value="" placeholder="Width">px X
                    <input class="input-control input-control-small" type="text" data-image="height" value="" placeholder="Height">px
                </div>
                <div>
                    <div class="slim" id="modal-image-uploader">
                        <input type="file" >
                        <img src="" alt="" data-image="src">
                    </div>
                </div>
            </div>
            <div class="modal-controls">
                <button class="modal-btn-cancel"><?=lang('Newsletter.emailbuilder.Cancel');?></button>
                <button class="modal-btn-ok"><?=lang('Newsletter.emailbuilder.OK');?></button>
            </div>
        </div>
        <div id="bgeditor" class="modal-container">
            <div class="slim" id="modal-bg-uploader">
                <input type="file" >
                <img src="" alt="" data-image="src">
            </div>
            <div class="modal-controls">
                <button class="modal-btn-cancel"><?=lang('Newsletter.emailbuilder.Cancel');?></button>
                <button class="modal-btn-ok"><?=lang('Newsletter.emailbuilder.OK');?></button>
            </div>
        </div>
        <div id="campaignmodal" class="modal-container">
            <div class="input-row">
                <div class="input-group">
                    <input class="input-control" type="text" name="subject" value="<?=!empty($newsletter) ? $newsletter['subject'] : ''; ?>" placeholder="Subject">
                </div>
            </div>
            <div class="input-row">
                <div class="input-group">
                    <textarea name="plain_text"><?=!empty($newsletter) ? $newsletter['plain_text'] : ''; ?></textarea>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group checkbox-wrapper" id="subscribers-list-container">
                    
                </div>
            </div>
            <div class="modal-controls">
                <button class="modal-btn-cancel"><?=lang('Newsletter.emailbuilder.Cancel');?></button>
                <button class="modal-btn-ok"><?=lang('Newsletter.emailbuilder.OK');?></button>
            </div>
        </div>
    </div>
</div>