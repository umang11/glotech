<?php
function motopressCEOptions() {
    global $motopressCELang, $motopressCESettings;

    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        add_settings_error(
            'motopressSettings',
            esc_attr('settings_updated'),
            $motopressCELang->CEOptMsgUpdated,
            'updated'
        );
    }

    echo '<div class="wrap">';
    screen_icon('options-general');
    echo '<h2>'.$motopressCELang->motopressOptions.'</h2>';
    settings_errors('motopressSettings', false);
    echo '<form actoin="options.php" method="POST">';
//    settings_fields('motopressOptionsFields');
    do_settings_sections('motopress_options');
    echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="'.$motopressCELang->save.'" /></p>';
    echo '</form>';
    echo '</div>';
}

add_action('admin_init', 'motopressCEInitOptions');
function motopressCEInitOptions() {
    global $motopressCELang;

    register_setting('motopressLanguageOptionsFields', 'motopressLanguageOptions');
    add_settings_section('motopressLanguageOptionsFields', '', 'motopressCELanguageOptionsSecTxt', 'motopress_options');
    add_settings_field('motopressLanguageOptions', $motopressCELang->language, 'motopressCELanguageSettings', 'motopress_options', 'motopressLanguageOptionsFields');

//    register_setting('motopressCEOptionsFields', 'motopressCEOptions'/*, 'plugin_options_validate'*/);
    register_setting('motopressCEOptionsFields', 'motopressContentEditorOptions'/*, 'plugin_options_validate'*/);
    add_settings_section('motopressCEOptionsFields', '', 'motopressCEOptionsSecTxt', 'motopress_options');
    add_settings_field('motopressContentType', $motopressCELang->CEOptContentTypes, 'motopressCEContentTypeSettings', 'motopress_options', 'motopressCEOptionsFields');

    $currentUser = wp_get_current_user();
    if (in_array('administrator', $currentUser->roles)) {
        register_setting('motopressCERolesSettingsFields', 'motopressCERolesOptions');
        add_settings_section('motopressCERolesSettingsFields', '', 'motopressCERolesSettingsSecTxt', 'motopress_options');
        add_settings_field('motopressRoles', $motopressCELang->CEOptRolesSettings, 'motopressCERolesSettingsFields', 'motopress_options', 'motopressCERolesSettingsFields');
    }

    register_setting('motopressCESpellcheckSettingsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCESpellcheckSettingsFields', '', 'motopressCESpellcheckSecTxt', 'motopress_options');
    add_settings_field('motopressSpellcheck', $motopressCELang->CEOptSpellcheckSettings, 'motopressCESpellcheckFields', 'motopress_options', 'motopressCESpellcheckSettingsFields');

    register_setting('motopressCECustomCSSOptionsFields', 'motopressContentEditorOptions'/*, 'plugin_options_validate'*/);
    add_settings_section('motopressCECustomCSSOptionsFields', '', 'motopressCECustomCSSSecTxt', 'motopress_options');
    add_settings_field('motopressCustomCSS', $motopressCELang->CEOptCustomCSS, 'motopressCECustomCSSFields', 'motopress_options', 'motopressCECustomCSSOptionsFields');

    register_setting('motopressCEAutoSaveSettingsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCEAutoSaveSettingsFields', '', 'motopressCEAutoSaveSecTxt', 'motopress_options');
    add_settings_field('motopressAutoSave', $motopressCELang->CECompatibility, 'motopressCEAutoSaveFields', 'motopress_options', 'motopressCEAutoSaveSettingsFields');

    register_setting('motopressCEExcerptSettingsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCEExcerptSettingsFields', '', 'motopressCEExcerptSecTxt', 'motopress_options');
    add_settings_field('motopressExcerpt', $motopressCELang->CEOptExcerptSettings, 'motopressCEExcerptFields', 'motopress_options', 'motopressCEExcerptSettingsFields');
}

function motopressCELanguageOptionsSecTxt() {}
function motopressCELanguageSettings() {
    $currentLang = get_option('motopress-language');
    $languageFileList = glob(plugin_dir_path(__FILE__) . 'lang/*.json');
    echo '<select class="motopress-language" name="language" id="language">';
    foreach ($languageFileList as $path) {
        $file = basename($path);
        $fileContents = file_get_contents($path);
        $fileContentsJSON = json_decode($fileContents);
        $languageName = $fileContentsJSON->{'name'};
        $selected = ($file == $currentLang) ? ' selected' : '';
        echo '<option value="'.$file.'"'.$selected.'>' . $languageName . '</option>';
    }
    echo '</select>';
    echo '<br/><br/>';
}

function motopressCEOptionsSecTxt() {}
function motopressCEContentTypeSettings() {
    global $motopressCELang, $motopressCESettings;
    $postTypes = get_post_types(array('public' => true));
    $excludePostTypes = array('attachment' => 'attachment');
    $postTypes = array_diff_assoc($postTypes, $excludePostTypes);
    $checkedPostTypes = get_option('motopress-ce-options');
    if (!$checkedPostTypes) $checkedPostTypes = array();

    foreach ($postTypes as $key => $val) {
        if (post_type_supports($key, 'editor')) {
            $checked = '';
            if (in_array($key, $checkedPostTypes)) {
                $checked = 'checked="checked"';
            }
            echo '<label><input type="checkbox" name="post_types[]" value="'.$key.'" '.$checked.' disabled="disabled"' .' /> ' . ucfirst($val) . '</label><br/>';
        }
    }
    echo '<br/>';
    echo '<p class="description">' . str_replace('%link%', $motopressCESettings['lite_upgrade_url'], $motopressCELang->CEUpgradeText) . '</p>';
}

function motopressCERolesSettingsSecTxt(){}
function motopressCERolesSettingsFields(){
    global $motopressCELang, $motopressCESettings;
    global $wp_roles;
    if ( ! isset($wp_roles)) {
        $wp_roles = new WP_Roles();
    }
    $disabledRoles = get_option('motopress-ce-disabled-roles', array());

    $roles = $wp_roles->get_names();
    unset($roles['administrator']);

    foreach ($roles as $role => $roleName ){
        $checked = '';
        if (in_array($role, $disabledRoles)){
            $checked = 'checked="checked"';
        }
        echo '<label><input type="checkbox" name="disabled_roles[]" value="'.$role.'" '.$checked.' disabled="disabled"' .' /> '.$roleName.'</label><br/>';
    }

    echo '<p class="description">' . $motopressCELang->CEOptRolesSettingsDescription . str_replace("%link%", $motopressCESettings['lite_upgrade_url'], $motopressCELang->CEUpgradeText) . '</p>';
}


function motopressCEAutoSaveSecTxt() {}
function motopressCEAutoSaveFields() {
    global $motopressCELang;
    $autoSave = get_option('motopress-ce-autosave-autodraft', 1);

    $checked = '';
    if ($autoSave == '1'){
        $checked = 'checked="checked"';
    }
    echo '<label><input type="checkbox" name="autosave_autodraft" ' . $checked . ' />' . $motopressCELang->CEOptAutoSave . '</label><br/>';
}

function motopressCESpellcheckSecTxt(){}
function motopressCESpellcheckFields(){
    global $motopressCELang;

    $spellcheck_enable = get_option('motopress-ce-spellcheck-enable', '1');

    $checked = '';
    if ($spellcheck_enable) {
        $checked = 'checked="checked"';
    }
    echo '<label><input type="checkbox" name="spellcheck_enable" ' . $checked . ' />' . $motopressCELang->CEOptSpellcheck . '</label><br/>';
    echo '<p class="description">'.$motopressCELang->CEOptSpellcheckDescription.'</p>';
}

function motopressCECustomCSSSecTxt() {}
function motopressCECustomCSSFields() {
    global $motopressCELang, $motopressCESettings;

    if ( !$motopressCESettings['wp_upload_dir_error'] ) {
        if (!file_exists($motopressCESettings['custom_css_dir']))
            mkdir($motopressCESettings['custom_css_dir'], 0777);

        clearstatcache();
        if ( is_writable($motopressCESettings['custom_css_dir']) ) {
            $css_file = $motopressCESettings['custom_css_file_path'];
            if ( file_exists($css_file) ) {
                $cssValue = file_get_contents($css_file);
                $cssValue = esc_html( $cssValue );
            }else {
                $cssValue = '';
            }
            echo '<label><textarea name="custom_css" cols="40" rows="10" style="width:100%;max-width:1000px;">'.$cssValue.'</textarea></label>';
            echo '<p class="description">'.$motopressCELang->CETextareaCustomCSSDescription.'</p>';
        }else {
            $subdirNotWritable = $motopressCELang->CEOptMsgNotWritable;
            $subdirNotWritable = str_replace( '%dir%', $motopressCESettings['custom_css_dir'], $subdirNotWritable );
            echo $subdirNotWritable;
        }
    }else {
        $updirNotWritable = $motopressCELang->CEOptMsgNotWritable;
        $updirNotWritable = str_replace( '%dir%', $motopressCESettings['wp_upload_dir'], $updirNotWritable );
        echo $updirNotWritable;
    }
}

function motopressCEExcerptSecTxt() {}
function motopressCEExcerptFields() {
    global $motopressCELang;

    // Excerpt shortcode
    $excerptShortcode = get_option('motopress-ce-excerpt-shortcode', '1');
    $checked = '';
    if ($excerptShortcode) {
        $checked = ' checked="checked"';
    }
    echo '<label><input type="checkbox" name="excerpt_shortcode"' . $checked . '>' . $motopressCELang->CEOptExcerptShortcode . '</label><br>';

    // Save excerpt
    $saveExcerpt = get_option('motopress-ce-save-excerpt', '1');
    $checked = '';
    if ($saveExcerpt) {
        $checked = ' checked="checked"';
    }
    echo '<label><input type="checkbox" name="save_excerpt"' . $checked . '>' . $motopressCELang->CEOptSaveExcerpt . '</label>';
}

function motopressCESettingsSave() {
    if (!empty($_POST)) {
        global $motopressCESettings;

        // Language
        if (isset($_POST['language']) && !empty($_POST['language'])) {
            $language = $_POST['language'];
            update_option('motopress-language', $language);
            $motopressCESettings['lang'] = $language;
        }

        // AutoSave

        if ( isset($_POST['autosave_autodraft'])){
            $autosave = '1';
        } else {
            $autosave = '0';
        }
        update_option('motopress-ce-autosave-autodraft', $autosave);

        // Spellcheck

        if ( isset($_POST['spellcheck_enable'])){
            $spellcheck_enable = '1';
        } else {
            $spellcheck_enable = '0';
        }
        update_option('motopress-ce-spellcheck-enable', $spellcheck_enable);

        // Custom CSS
        if (isset($_POST['custom_css'])) {

            if (!file_exists($motopressCESettings['custom_css_dir']))
                mkdir($motopressCESettings['custom_css_dir'], 0777);

            $current_css = $_POST['custom_css'];

            // css file creation & rewrite
            if ( !empty($current_css) ) {
                $content = stripslashes($current_css);
                clearstatcache();
                if ( is_writable($motopressCESettings['wp_upload_dir']) )
                    file_put_contents($motopressCESettings['custom_css_file_path'], $content);
            }else {
                if (file_exists($motopressCESettings['custom_css_file_path'])) {
                    clearstatcache();
                    if ( is_writable($motopressCESettings['wp_upload_dir']) )
                        unlink($motopressCESettings['custom_css_file_path']);
                }
            }
            // css file deletion END
        }

        // Excerpt shortcode
        if (isset($_POST['excerpt_shortcode']) && $_POST['excerpt_shortcode']) {
            $excerptShortcode = '1';
        } else {
            $excerptShortcode = '0';
        }
        update_option('motopress-ce-excerpt-shortcode', $excerptShortcode);

        // Save excerpt
        if (isset($_POST['save_excerpt']) && $_POST['save_excerpt']) {
            $saveExcerpt = '1';
        } else {
            $saveExcerpt = '0';
        }
        update_option('motopress-ce-save-excerpt', $saveExcerpt);

        wp_redirect( get_admin_url() . 'admin.php?page=' . $_GET['page'] . '&settings-updated=true' );
    }
}


/* License */
function motopressCELicense() {
    global $motopressCESettings, $motopressCELang;

    $license = get_option('edd_mpce_license_key');

    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        add_settings_error(
            'motopressLicense',
            esc_attr('settings_updated'),
            $motopressCELang->CEOptMsgUpdated,
            'updated'
        );
    }
?>
    <div class="wrap">
        <?php screen_icon('options-general'); ?>
        <h2><?php echo $motopressCELang->CELicenseOptions; ?></h2>
        <?php settings_errors('motopressLicense', false); ?>
        <form action="" method="POST" autocomplete="off">
            <?php wp_nonce_field('edd_mpce_nonce', 'edd_mpce_nonce'); ?>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php echo $motopressCELang->CELicenseKey . " (" . $motopressCESettings['license_type'] . ")"; ?>
                        </th>
                        <td>
                            <input id="edd_mpce_license_key" name="edd_mpce_license_key" type="password" class="regular-text" value="<?php esc_attr_e($license); ?>" />
                            <?php if ($license) { ?>
                                <i style="display:block;"><?php echo str_repeat("&#8226;", 20) . substr($license, -7); ?></i>
                            <?php } else { ?>
                                <p><?php echo strtr($motopressCELang->CELicenseHelp, array('%link%' => $motopressCESettings['lite_url'])); ?></p>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php if ($license) { ?>
                        <?php $licenseData = edd_mpce_check_license($license); ?>
                        <tr valign="top">
                            <th scope="row" valign="top">
                                <?php echo $motopressCELang->CELicenseStatus; ?>
                            </th>
                            <td>
                                <?php
                                    if ($licenseData) {
                                        if ($licenseData->license === 'inactive' || $licenseData->license === 'site_inactive') {
                                            echo $motopressCELang->CELicenseInactive;
                                        } elseif ($licenseData->license === 'valid') {
                                            $date = ($licenseData->expires) ? new DateTime($licenseData->expires) : false;
                                            $expires = ($date) ? ' ' . $date->format('d.m.Y') : '';
                                            echo $motopressCELang->CELicenseValid . $expires;
                                        } elseif ($licenseData->license === 'disabled') {
                                            echo $motopressCELang->CELicenseDisabled;
                                        } elseif ($licenseData->license === 'expired') {
                                            echo $motopressCELang->CELicenseExpired;
                                        } elseif ($licenseData->license === 'invalid') {
                                            echo $motopressCELang->CELicenseInvalid;
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row" valign="top">
                                <?php echo $motopressCELang->CELicenseAction; ?>
                            </th>
                            <td>
                                <?php
                                    if ($licenseData) {
                                        if ($licenseData->license === 'inactive' || $licenseData->license === 'site_inactive') {
                                            wp_nonce_field('edd_mpce_nonce', 'edd_mpce_nonce'); ?>
                                            <input type="submit" class="button-secondary" name="edd_license_activate" value="<?php echo $motopressCELang->CELicenseActivate; ?>" />
                                <?php
                                        } elseif ($licenseData->license === 'valid') {
                                            wp_nonce_field('edd_mpce_nonce', 'edd_mpce_nonce'); ?>
                                            <input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php echo $motopressCELang->CELicenseDeactivate; ?>" />
                                <?php
                                        } elseif ($licenseData->license === 'expired') { ?>
                                            <a href="<?php echo $motopressCESettings['renew_url']; ?>" class="button-secondary" target="_blank"><?php echo $motopressCELang->CELicenseRenew; ?></a>
                                <?php
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php submit_button($motopressCELang->save); ?>
        </form>
    </div>
<?php
}

// check a license key
function edd_mpce_check_license($license) {
    global $motopressCESettings;

    $apiParams = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_name' => urlencode($motopressCESettings['edd_mpce_item_name'])
    );

    // Call the custom API.
    $response = wp_remote_get(add_query_arg($apiParams, $motopressCESettings['edd_mpce_store_url']), array('timeout' => 15, 'sslverify' => false));

    if (is_wp_error($response)) {
        return false;
    }

    $licenseData = json_decode(wp_remote_retrieve_body($response));

    return $licenseData;
}

function motopressCELicenseSave() {
    global $motopressCESettings;

    if (!empty($_POST)) {
        if (isset($_POST['edd_mpce_license_key'])) {
            if (!check_admin_referer('edd_mpce_nonce', 'edd_mpce_nonce')) {
                return;
            }
            $licenseKey = trim($_POST['edd_mpce_license_key']);
            $oldLicenseKey = get_option('edd_mpce_license_key');
            if ($oldLicenseKey && $oldLicenseKey !== $licenseKey) {
                delete_option('edd_mpce_license_status'); // new license has been entered, so must reactivate
            }
            if (!empty($licenseKey)) {
                update_option('edd_mpce_license_key', $licenseKey);
            } else {
                delete_option('edd_mpce_license_key');
            }
        }

        //activate
        if (isset($_POST['edd_license_activate'])) {
            if (!check_admin_referer('edd_mpce_nonce', 'edd_mpce_nonce')) {
                return; // get out if we didn't click the Activate button
            }

            $licenseKey = get_option('edd_mpce_license_key');

            // data to send in our API request
            $apiParams = array(
                'edd_action' => 'activate_license',
                'license' => $licenseKey,
                'item_name' => urlencode($motopressCESettings['edd_mpce_item_name']) // the name of our product in EDD
            );

            // Call the custom API.
            $response = wp_remote_get(add_query_arg($apiParams, $motopressCESettings['edd_mpce_store_url']), array('timeout' => 15, 'sslverify' => false));

            // make sure the response came back okay
            if (is_wp_error($response)) {
                return false;
            }

            // decode the license data
            $licenseData = json_decode(wp_remote_retrieve_body($response));

            // $license_data->license will be either "active" or "inactive"

            update_option('edd_mpce_license_status', $licenseData->license);
        }

        //deactivate
        if (isset($_POST['edd_license_deactivate'])) {
            // run a quick security check
            if (!check_admin_referer( 'edd_mpce_nonce', 'edd_mpce_nonce')) {
                return; // get out if we didn't click the Activate button
            }

            // retrieve the license from the database
            $licenseKey = get_option('edd_mpce_license_key');

            // data to send in our API request
            $apiParams = array(
                'edd_action' => 'deactivate_license',
                'license' => $licenseKey,
                'item_name' => urlencode($motopressCESettings['edd_mpce_item_name']) // the name of our product in EDD
            );

            // Call the custom API.
            $response = wp_remote_get(add_query_arg($apiParams, $motopressCESettings['edd_mpce_store_url']), array('timeout' => 15, 'sslverify' => false));

            // make sure the response came back okay
            if (is_wp_error($response)) {
                return false;
            }

            // decode the license data
            $licenseData = json_decode(wp_remote_retrieve_body($response));

            // $license_data->license will be either "deactivated" or "failed"
            if($licenseData->license == 'deactivated') {
                delete_option('edd_mpce_license_status');
            }
        }

        wp_redirect(get_admin_url() . 'admin.php?page=' . $_GET['page'] . '&settings-updated=true');
    }
}
