<?php
require_once 'includes/ce/Library.php';

function motopressCEAddTools() {
    require_once 'includes/ce/Access.php';
    $ceAccess = new MPCEAccess();

    $motopressCELibrary = new MPCELibrary();
    do_action_ref_array('mp_library', array(&$motopressCELibrary));

    $postType = get_post_type();
    $postTypes = get_option('motopress-ce-options');
    if (!$postTypes) $postTypes = array();

    if (in_array($postType, $postTypes) && post_type_supports($postType, 'editor') && $ceAccess->hasAccess()) {
        global $motopressCESettings;
        wp_localize_script('jquery', 'motopress', $motopressCESettings['motopress_localize']);
        wp_localize_script('jquery', 'motopressCE',
            array(
                'postID' => get_the_ID(),
//                'postPreviewUrl' => post_preview(),
                'nonces' => array(
                    'motopress_ce_get_wp_settings' => wp_create_nonce('wp_ajax_motopress_ce_get_wp_settings'),
                    'motopress_ce_render_content' => wp_create_nonce('wp_ajax_motopress_ce_render_content'),
                    'motopress_ce_remove_temporary_post' => wp_create_nonce('wp_ajax_motopress_ce_remove_temporary_post'),
                    'motopress_ce_get_library' => wp_create_nonce('wp_ajax_motopress_ce_get_library'),
                    'motopress_ce_render_shortcode' => wp_create_nonce('wp_ajax_motopress_ce_render_shortcode'),
                    'motopress_ce_get_attachment_thumbnail' => wp_create_nonce('wp_ajax_motopress_ce_get_attachment_thumbnail'),
                    'motopress_ce_colorpicker_update_palettes' => wp_create_nonce('wp_ajax_motopress_ce_colorpicker_update_palettes')
                )
            )
        );
        add_action('admin_head', 'motopressCEAddCEBtn');
        add_action('admin_footer', 'motopressCEHTML'); //admin_head

        wp_register_style('mpce-style', plugin_dir_url(__FILE__) . 'includes/css/style.css', null, $motopressCESettings['plugin_version']);
        wp_enqueue_style('mpce-style');

        wp_register_style('mpce', plugin_dir_url(__FILE__) . 'mp/ce/css/ce.css', null, $motopressCESettings['plugin_version']);
        wp_enqueue_style('mpce');

        wp_register_script('mpce-knob', plugin_dir_url(__FILE__) . 'knob/jquery.knob.min.js', array(), $motopressCESettings['plugin_version']);
        wp_enqueue_script('mpce-knob');

        if (get_user_meta(get_current_user_id(), 'rich_editing', true) === 'false' && !wp_script_is('editor')) {
            wp_enqueue_script('editor');
        }

        wp_enqueue_script('wp-link');
    }
}

function motopressCEHTML() {
    global $motopressCESettings;
    global $motopressCELang;

//    global $post;
//    $nonce = wp_create_nonce('post_preview_' . $post->ID);
//    $url = add_query_arg( array( 'preview' => 'true', 'preview_id' => $post->ID, 'preview_nonce' => $nonce ), get_permalink($post->ID) );
//    echo '<a href="' . $url . '" target="wp-preview" title="' . esc_attr(sprintf(__('Preview “%s”'), $title)) . '" rel="permalink">' . __('Preview') . '</a>';
//    echo '<a href="' . post_preview() . '" target="wp-preview" title="' . esc_attr(sprintf(__('Preview “%s”'), $title)) . '" rel="permalink">' . __('Preview') . '</a>';

//    echo '<br/>';
//    echo $url;
//    echo '<br/>';
//    echo post_preview();

?>
    <div id="motopress-content-editor" style="display: none;">
        <div class="motopress-content-editor-navbar">
            <div class="navbar-inner">
                <div id="motopress-logo">
                    <img src="<?php echo $motopressCESettings['plugin_root_url'].'/'.$motopressCESettings['plugin_name'].'/images/logo.png?ver='.$motopressCESettings['plugin_version']; ?>">
                </div>
                <div class="motopress-page-name">
                    <?php echo get_post_type() == 'page' ? $motopressCELang->CEPage : $motopressCELang->CEPost ;?>:
                    <span id="motopress-title"></span>
                    <input type="text" id="motopress-input-edit-title" class="hide" >
                </div>
                <div class="pull-left motopress-object-control-btns">
                    <!--<button class="btn-default" id="motopress-content-editor-duplicate"><?php // echo $motopressCELang->CEDuplicateBtnText; ?></button>-->
                    <button class="btn-default" id="motopress-content-editor-delete"><?php echo $motopressCELang->CEDeleteBtnText; ?></button>
                </div>
                <div class="pull-right navbar-btns">
                    <button class="btn-default btn-tutorials" id="motopress-content-editor-tutorials">?</button>
                    <button class="btn-blue" id="motopress-content-editor-publish"><?php echo $motopressCELang->CEPublishBtnText; ?></button>
                    <button class="btn-default" id="motopress-content-editor-save"><?php echo $motopressCELang->CESaveBtnText; ?></button>
                    <button class="btn-default" id="motopress-content-editor-preview"><?php echo $motopressCELang->CEPreviewBtnText; ?></button>
                    <button class="btn-default" id="motopress-content-editor-close"><?php echo $motopressCELang->CECloseBtnText; ?></button>
                    <?php ?>
                    <button class="btn-red" id="motopress-content-editor-upgrade" onclick="window.open('<?php echo $motopressCESettings['lite_upgrade_url'] ?>','_blank')"><?php echo $motopressCELang->CEUpgradeBtnText; ?></button>
                    <?php ?>
                </div>
            </div>
        </div>

        <div id="motopress-flash"></div>

        <div id="motopress-content-editor-scene-wrapper">
            <iframe id="motopress-content-editor-scene" class="motorpess-content-editor-scene" name="motopress-content-editor-scene"></iframe>
        </div>

        <!-- Video Tutorials -->
        <div id="motopress-tutorials-modal" class="modal hide fade">
            <div class="modal-header">
                <p id="tutsModalLabel"><?php echo $motopressCELang->CEHelpAndTuts; ?><button type="button" tabindex="0" class="close massive-modal-close" data-dismiss="modal" aria-hidden="true">&times;</button></p>
            </div>
            <div class="modal-body"></div>
        </div>

        <!-- Code editor -->
        <div id="motopress-code-editor-modal" class="modal hide fade" role="dialog" aria-labelledby="codeModalLabel" aria-hidden="true">
            <div class="modal-header">
                <p id="codeModalLabel"><?php echo $motopressCELang->edit . ' ' . $motopressCELang->CECodeObjName; ?></p>
            </div>
            <div class="modal-body">
                <div id="motopress-code-editor-wrapper">
                    <?php
                        wp_editor('', 'motopresscodecontent', array(
                            'textarea_rows' => false,
                            'tinymce' => array(
                                'remove_linebreaks' => false,
                                'schema' => 'html5',
                                'theme_advanced_resizing' => false
                            )
                        ));
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button id="motopress-save-code-content" class="btn-blue"><?php echo $motopressCELang->save; ?></button>
                <button class="btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $motopressCELang->cancel; ?></button>
            </div>
        </div>

        <div id="motopress-confirm-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true"></div>
    </div>

    <div id="motopress-preload">
        <input type="text" id="motopress-knob">

        <div id="motopress-error">
            <div id="motopress-error-title"><?php echo $motopressCELang->CEErrorTitle; ?></div>
            <div id="motopress-error-message">
                <div id="motopress-system">
                    <p id="motopress-browser"></p>
                    <p id="motopress-platform"></p>
                </div>
            </div>
            <div class="motopress-terminate">
                <button id="motopress-terminate" class="btn-default"><?php echo $motopressCELang->CETerminate; ?></button>
            </div>
        </div>
        <script type="text/javascript">
            var MP = {
                Error: {
                    terminate: function() {
                        jQuery('html').css({
                            overflow: 'auto',
                            paddingTop: 32
                        });
                        jQuery('#motopress-content-editor').hide();
                        jQuery('body > #wpadminbar').prependTo('#wpwrap > #wpcontent');
                        //jQuery('#wpwrap').show();
                        jQuery('#motopress-content-editor').siblings('.motopress-hide').removeClass('motopress-hide');
                        //jQuery('#wpwrap').css('height', '');
                        jQuery('#wpwrap').height('');
                        //jQuery('#wpwrap').children(':not(#wpcontent)').removeClass('motopress-wpwrap-hidden');
                        //jQuery('#wpwrap > #wpcontent').children(':not(#wpadminbar)').removeClass('motopress-wpwrap-hidden');
                        jQuery('#motopress-preload').hide();
                        var error = jQuery('#motopress-preload > #motopress-error');
                        error.find('#motopress-system').prevAll().remove();
                        error.hide();
                    },
                    log: function(e) {
                        console.group('CE error');
                            console.warn('Name: ' + e.name);
                            console.warn('Message: ' + e.message);
                            if (e.hasOwnProperty('fileName')) console.warn('File: ' + e.fileName);
                            if (e.hasOwnProperty('lineNumber')) console.warn('Line: ' + e.lineNumber);
                            console.warn('Browser: ' + navigator.userAgent);
                            console.warn('Platform: ' + navigator.platform);
                        console.groupEnd();

                        var error = jQuery('#motopress-preload > #motopress-error');
                        var text = e.name + ': ' + e.message + '.';
                        if (e.hasOwnProperty('fileName')) {
                            text += ' ' + e.fileName;
                        }
                        if (e.hasOwnProperty('lineNumber')) {
                            text += ':' + e.lineNumber;
                        }
                        error.find('#motopress-system').before(jQuery('<p />', {text: text}));
                        error.show();
                    }
                }
            };

            jQuery(document).ready(function($) {
                $('#motopress-knob').knob({
                    readOnly: true,
                    displayInput: false,
                    thickness: 0.05,
                    fgColor: '#d34937',
                    width: 136,
                    height: 136
                });

                $('#motopress-system')
                    .children('#motopress-browser').text('Browser: ' + navigator.userAgent)
                    .end()
                    .children('#motopress-platform').text('Platform: ' + navigator.platform);

                $('#motopress-terminate').on('click', function() {
                    MP.Error.terminate();
                });
            });
        </script>
    </div>

<?php ?>
    <script>
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-39340273-1']);
        _gaq.push(['_setAllowLinker', true]);

        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
<?php
}

function motopressCEAddCEBtn() {
    global $motopressCESettings;
    global $motopressCELang;
    global $post;
    global $motopressCEIsjQueryVer;
    $post_status = get_post_status( get_the_ID() );
    $isAutosaveEnabled = get_option('motopress-ce-autosave-autodraft', 1);
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            if (!Browser.IE && !Browser.Opera) {
                var motopressCEButton = $('<input />', {
                    type: 'button',
                    id: 'motopress-ce-btn',
                    'class': 'wp-core-ui button-primary',
                    value: '<?php echo $motopressCELang->CEBtn; ?>',
                    'data-post-id' : '<?php echo $post->ID?>',
                    disabled: 'disabled'
                }).insertAfter($('div#titlediv'));
                <?php if ($motopressCEIsjQueryVer) { ?>
                    var preloader = $('#motopress-preload');
                    motopressCEButton.on('click', function() {
                        //console.time('ce');
                        //console.profile();

                        _gaq.push(['_trackEvent', 'lite', 'mpce-lite-start', '<?php echo urlencode(wp_get_theme()); ?>']);

                        preloader.show();
                        <?php if ($post_status == 'auto-draft' && $isAutosaveEnabled) { ?>
                            var postData = wp.autosave.getPostData();

                            if (postData.content.length || postData.excerpt.length || postData.post_title.length) {
                                var pluginAutoSaved = sessionStorage.getItem('motopressPluginAutoSaved');
                                pluginAutoSaved = (pluginAutoSaved && pluginAutoSaved === 'true') ? true : false;

                                if (!pluginAutoSaved){
                                    sessionStorage.setItem('motopressPluginAutoOpen', true);
                                    sessionStorage.setItem('motopressPluginAutoSaved', true);
                                    window.onbeforeunload = null
                                    jQuery('form#post').submit();
                                    return false;
                                }
                            }
                        <?php } ?>
                        sessionStorage.setItem('motopressPluginAutoSaved', false);

                        if (typeof CE === 'undefined') {
                            var head = $('head')[0];
                            var stealVerScript = $('<script />', {
                                text: 'var steal = { production: "mp/ce/production.js" + motopress.pluginVersionParam };'
                            })[0];
                            head.appendChild(stealVerScript);
                            var script = $('<script />', {
                                src: '<?php echo $motopressCESettings["plugin_root_url"]; ?>' + '/' + '<?php echo $motopressCESettings["plugin_name"]; ?>' + '/steal/steal.production.js?mp/ce'
                            })[0];
                            head.appendChild(script);
                        }
                    });

                    function mpceOnEditorInit() {
                        motopressCEButton.removeAttr('disabled');
                        if (pluginAutoOpen) {
                            sessionStorage.setItem('motopressPluginAutoOpen', false);
                            motopressCEButton.click();
                        }
                    }

                    var editorState = "<?php echo get_user_setting('editor', 'html'); ?>";
                    var pluginAutoOpen = sessionStorage.getItem('motopressPluginAutoOpen');
                    var paramPluginAutoOpen = ('<?php if (isset($_GET['motopress-ce-auto-open']) && $_GET['motopress-ce-auto-open']) echo $_GET['motopress-ce-auto-open']; ?>' === 'true') ? true : false; //fix different site (WordPress Address) and home (Site Address) url for sessionStorage
                    pluginAutoOpen = ((pluginAutoOpen && pluginAutoOpen === 'true') || paramPluginAutoOpen) ? true : false;
                    if (pluginAutoOpen) preloader.show();

                    if (typeof tinyMCE !== 'undefined' && editorState === 'tinymce') {
                        if (tinyMCE.majorVersion === '4') {
                            tinyMCE.on('AddEditor', function(args){
                                if(args.editor.id === 'content'){
                                    args.editor.on('init', function(ed){
                                        mpceOnEditorInit();
                                    });
                                }
                            });
                        } else {
                            tinyMCE.onAddEditor.add(function(mce, ed) {
                                if (ed.editorId === 'content') {
                                    ed.onInit.add(function(ed) {
                                        mpceOnEditorInit();
                                    });
                                }
                            });
                        }
                    } else {
                        mpceOnEditorInit();
                    }
                <?php } else {
                    add_action('admin_notices', 'motopressCEIsjQueryVerNotice');
                } ?>
            }
        });
    </script>
    <?php
}

function motopressCEIsjQueryVerNotice() {
    global $motopressCELang;
    echo '<div class="error"><p>' . strtr($motopressCELang->jQueryVerNotSupported, array('%minjQueryVer%' => MPCERequirements::MIN_JQUERY_VER, '%minjQueryUIVer%' => MPCERequirements::MIN_JQUERYUI_VER)) . '</p></div>';
}

require_once $motopressCESettings['plugin_root'].'/'.$motopressCESettings['plugin_name'].'/includes/getWpSettings.php';
add_action('wp_ajax_motopress_ce_get_wp_settings', 'motopressCEGetWpSettings');
require_once $motopressCESettings['plugin_root'].'/'.$motopressCESettings['plugin_name'].'/includes/ce/renderContent.php';
add_action('wp_ajax_motopress_ce_render_content', 'motopressCERenderContent');
require_once $motopressCESettings['plugin_root'].'/'.$motopressCESettings['plugin_name'].'/includes/ce/removeTemporaryPost.php';
add_action('wp_ajax_motopress_ce_remove_temporary_post', 'motopressCERemoveTemporaryPost');
require_once $motopressCESettings['plugin_root'].'/'.$motopressCESettings['plugin_name'].'/includes/ce/getLibrary.php';
add_action('wp_ajax_motopress_ce_get_library', 'motopressCEGetLibrary');
require_once $motopressCESettings['plugin_root'].'/'.$motopressCESettings['plugin_name'].'/includes/ce/renderShortcode.php';
add_action('wp_ajax_motopress_ce_render_shortcode', 'motopressCERenderShortcode');
require_once $motopressCESettings['plugin_root'].'/'.$motopressCESettings['plugin_name'].'/includes/ce/getAttachmentThumbnail.php';
add_action('wp_ajax_motopress_ce_get_attachment_thumbnail', 'motopressCEGetAttachmentThumbnail');
require_once $motopressCESettings['plugin_root'].'/'.$motopressCESettings['plugin_name'].'/includes/ce/updatePalettes.php';
add_action('wp_ajax_motopress_ce_colorpicker_update_palettes', 'motopressCEupdatePalettes');
