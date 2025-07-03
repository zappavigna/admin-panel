<?php
// Metabox Code Generator
header('Content-Type: text/plain; charset=utf-8');

// Get form data
$title = $_POST['metaboxTitle'] ?? '';
$id = $_POST['metaboxId'] ?? '';
$postType = $_POST['metaboxPostType'] ?? 'post';
$position = $_POST['metaboxPosition'] ?? 'normal';
$priority = $_POST['metaboxPriority'] ?? 'high';
$addNonce = isset($_POST['addNonce']);

// Get fields
$fieldNames = $_POST['fieldName'] ?? [];
$fieldIds = $_POST['fieldId'] ?? [];
$fieldTypes = $_POST['fieldType'] ?? [];

// Generate the code
$code = "<?php\n";
$code .= "/**\n";
$code .= " * Custom Metabox: $title\n";
$code .= " * \n";
$code .= " * @package WordPress\n";
$code .= " * @subpackage Your_Theme\n";
$code .= " */\n\n";

// Add Metabox Function
$code .= "// Add Metabox\n";
$code .= "function add_{$id}_metabox() {\n";
$code .= "    add_meta_box(\n";
$code .= "        '$id',\n";
$code .= "        '$title',\n";
$code .= "        '{$id}_callback',\n";
$code .= "        '$postType',\n";
$code .= "        '$position',\n";
$code .= "        '$priority'\n";
$code .= "    );\n";
$code .= "}\n";
$code .= "add_action( 'add_meta_boxes', 'add_{$id}_metabox' );\n\n";

// Metabox Callback Function
$code .= "// Metabox Callback\n";
$code .= "function {$id}_callback( \$post ) {\n";

if ($addNonce) {
    $code .= "    // Add nonce for security\n";
    $code .= "    wp_nonce_field( '{$id}_nonce', '{$id}_nonce' );\n";
    $code .= "    \n";
}

// Get saved values
$code .= "    // Get saved values\n";
foreach ($fieldIds as $index => $fieldId) {
    if (!empty($fieldId)) {
        $code .= "    \$_{$fieldId} = get_post_meta( \$post->ID, '_{$fieldId}', true );\n";
    }
}

$code .= "    ?>\n";
$code .= "    <div class=\"metabox-content\">\n";

// Generate HTML for each field
foreach ($fieldNames as $index => $fieldName) {
    if (!empty($fieldName) && !empty($fieldIds[$index])) {
        $fieldId = $fieldIds[$index];
        $fieldType = $fieldTypes[$index] ?? 'text';
        
        $code .= "        <div class=\"form-field\" style=\"margin-bottom: 20px;\">\n";
        $code .= "            <label for=\"{$fieldId}\" style=\"display: block; font-weight: bold; margin-bottom: 5px;\">\n";
        $code .= "                {$fieldName}:\n";
        $code .= "            </label>\n";
        
        switch ($fieldType) {
            case 'text':
                $code .= "            <input type=\"text\" id=\"{$fieldId}\" name=\"{$fieldId}\" \n";
                $code .= "                   value=\"<?php echo esc_attr( \$_{$fieldId} ); ?>\" \n";
                $code .= "                   class=\"widefat\" />\n";
                break;
                
            case 'textarea':
                $code .= "            <textarea id=\"{$fieldId}\" name=\"{$fieldId}\" \n";
                $code .= "                      class=\"widefat\" rows=\"4\"><?php echo esc_textarea( \$_{$fieldId} ); ?></textarea>\n";
                break;
                
            case 'select':
                $code .= "            <select id=\"{$fieldId}\" name=\"{$fieldId}\" class=\"widefat\">\n";
                $code .= "                <option value=\"\">Seleziona...</option>\n";
                $code .= "                <option value=\"option1\" <?php selected( \$_{$fieldId}, 'option1' ); ?>>Opzione 1</option>\n";
                $code .= "                <option value=\"option2\" <?php selected( \$_{$fieldId}, 'option2' ); ?>>Opzione 2</option>\n";
                $code .= "                <option value=\"option3\" <?php selected( \$_{$fieldId}, 'option3' ); ?>>Opzione 3</option>\n";
                $code .= "            </select>\n";
                break;
                
            case 'checkbox':
                $code .= "            <input type=\"checkbox\" id=\"{$fieldId}\" name=\"{$fieldId}\" \n";
                $code .= "                   value=\"1\" <?php checked( \$_{$fieldId}, 1 ); ?> />\n";
                break;
                
            case 'radio':
                $code .= "            <div>\n";
                $code .= "                <label><input type=\"radio\" name=\"{$fieldId}\" value=\"option1\" \n";
                $code .= "                       <?php checked( \$_{$fieldId}, 'option1' ); ?> /> Opzione 1</label><br>\n";
                $code .= "                <label><input type=\"radio\" name=\"{$fieldId}\" value=\"option2\" \n";
                $code .= "                       <?php checked( \$_{$fieldId}, 'option2' ); ?> /> Opzione 2</label><br>\n";
                $code .= "                <label><input type=\"radio\" name=\"{$fieldId}\" value=\"option3\" \n";
                $code .= "                       <?php checked( \$_{$fieldId}, 'option3' ); ?> /> Opzione 3</label>\n";
                $code .= "            </div>\n";
                break;
                
            case 'media':
                $code .= "            <input type=\"hidden\" id=\"{$fieldId}\" name=\"{$fieldId}\" \n";
                $code .= "                   value=\"<?php echo esc_attr( \$_{$fieldId} ); ?>\" />\n";
                $code .= "            <input type=\"button\" class=\"button button-secondary\" \n";
                $code .= "                   value=\"Seleziona Media\" \n";
                $code .= "                   onclick=\"mediaUploader('{$fieldId}')\" />\n";
                $code .= "            <div id=\"{$fieldId}_preview\" style=\"margin-top: 10px;\">\n";
                $code .= "                <?php if ( \$_{$fieldId} ) : \n";
                $code .= "                    \$image = wp_get_attachment_image_src( \$_{$fieldId}, 'thumbnail' );\n";
                $code .= "                    if ( \$image ) : ?>\n";
                $code .= "                        <img src=\"<?php echo esc_url( \$image[0] ); ?>\" style=\"max-width: 200px;\" />\n";
                $code .= "                    <?php endif;\n";
                $code .= "                endif; ?>\n";
                $code .= "            </div>\n";
                break;
                
            case 'color':
                $code .= "            <input type=\"text\" id=\"{$fieldId}\" name=\"{$fieldId}\" \n";
                $code .= "                   value=\"<?php echo esc_attr( \$_{$fieldId} ); ?>\" \n";
                $code .= "                   class=\"color-field\" />\n";
                break;
                
            case 'date':
                $code .= "            <input type=\"date\" id=\"{$fieldId}\" name=\"{$fieldId}\" \n";
                $code .= "                   value=\"<?php echo esc_attr( \$_{$fieldId} ); ?>\" \n";
                $code .= "                   class=\"widefat\" />\n";
                break;
                
            case 'number':
                $code .= "            <input type=\"number\" id=\"{$fieldId}\" name=\"{$fieldId}\" \n";
                $code .= "                   value=\"<?php echo esc_attr( \$_{$fieldId} ); ?>\" \n";
                $code .= "                   class=\"widefat\" />\n";
                break;
                
            case 'email':
                $code .= "            <input type=\"email\" id=\"{$fieldId}\" name=\"{$fieldId}\" \n";
                $code .= "                   value=\"<?php echo esc_attr( \$_{$fieldId} ); ?>\" \n";
                $code .= "                   class=\"widefat\" />\n";
                break;
                
            case 'url':
                $code .= "            <input type=\"url\" id=\"{$fieldId}\" name=\"{$fieldId}\" \n";
                $code .= "                   value=\"<?php echo esc_url( \$_{$fieldId} ); ?>\" \n";
                $code .= "                   class=\"widefat\" />\n";
                break;
                
            case 'wysiwyg':
                $code .= "            <?php\n";
                $code .= "            wp_editor( \$_{$fieldId}, '{$fieldId}', array(\n";
                $code .= "                'textarea_name' => '{$fieldId}',\n";
                $code .= "                'media_buttons' => true,\n";
                $code .= "                'textarea_rows' => 10,\n";
                $code .= "                'teeny' => false,\n";
                $code .= "                'quicktags' => true\n";
                $code .= "            ) );\n";
                $code .= "            ?>\n";
                break;
        }
        
        $code .= "        </div>\n";
    }
}

$code .= "    </div>\n";
$code .= "    <?php\n";
$code .= "}\n\n";

// Save Metabox Function
$code .= "// Save Metabox\n";
$code .= "function save_{$id}_metabox( \$post_id ) {\n";

if ($addNonce) {
    $code .= "    // Check nonce\n";
    $code .= "    if ( ! isset( \$_POST['{$id}_nonce'] ) ) {\n";
    $code .= "        return;\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    if ( ! wp_verify_nonce( \$_POST['{$id}_nonce'], '{$id}_nonce' ) ) {\n";
    $code .= "        return;\n";
    $code .= "    }\n";
    $code .= "    \n";
}

$code .= "    // Check autosave\n";
$code .= "    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {\n";
$code .= "        return;\n";
$code .= "    }\n";
$code .= "    \n";
$code .= "    // Check permissions\n";
$code .= "    if ( ! current_user_can( 'edit_post', \$post_id ) ) {\n";
$code .= "        return;\n";
$code .= "    }\n";
$code .= "    \n";

// Save each field
$code .= "    // Save fields\n";
foreach ($fieldIds as $index => $fieldId) {
    if (!empty($fieldId)) {
        $fieldType = $fieldTypes[$index] ?? 'text';
        
        if ($fieldType === 'checkbox') {
            $code .= "    \$_{$fieldId} = isset( \$_POST['{$fieldId}'] ) ? 1 : 0;\n";
            $code .= "    update_post_meta( \$post_id, '_{$fieldId}', \$_{$fieldId} );\n";
            $code .= "    \n";
        } elseif ($fieldType === 'wysiwyg' || $fieldType === 'textarea') {
            $code .= "    if ( isset( \$_POST['{$fieldId}'] ) ) {\n";
            $code .= "        update_post_meta( \$post_id, '_{$fieldId}', wp_kses_post( \$_POST['{$fieldId}'] ) );\n";
            $code .= "    }\n";
            $code .= "    \n";
        } elseif ($fieldType === 'url') {
            $code .= "    if ( isset( \$_POST['{$fieldId}'] ) ) {\n";
            $code .= "        update_post_meta( \$post_id, '_{$fieldId}', esc_url_raw( \$_POST['{$fieldId}'] ) );\n";
            $code .= "    }\n";
            $code .= "    \n";
        } elseif ($fieldType === 'email') {
            $code .= "    if ( isset( \$_POST['{$fieldId}'] ) ) {\n";
            $code .= "        update_post_meta( \$post_id, '_{$fieldId}', sanitize_email( \$_POST['{$fieldId}'] ) );\n";
            $code .= "    }\n";
            $code .= "    \n";
        } else {
            $code .= "    if ( isset( \$_POST['{$fieldId}'] ) ) {\n";
            $code .= "        update_post_meta( \$post_id, '_{$fieldId}', sanitize_text_field( \$_POST['{$fieldId}'] ) );\n";
            $code .= "    }\n";
            $code .= "    \n";
        }
    }
}

$code .= "}\n";
$code .= "add_action( 'save_post', 'save_{$id}_metabox' );\n";

// Add media uploader script if needed
$hasMediaField = false;
foreach ($fieldTypes as $type) {
    if ($type === 'media') {
        $hasMediaField = true;
        break;
    }
}

if ($hasMediaField) {
    $code .= "\n// Media Uploader Script\n";
    $code .= "function {$id}_admin_scripts() {\n";
    $code .= "    if ( ! did_action( 'wp_enqueue_media' ) ) {\n";
    $code .= "        wp_enqueue_media();\n";
    $code .= "    }\n";
    $code .= "    ?>\n";
    $code .= "    <script type=\"text/javascript\">\n";
    $code .= "    function mediaUploader(fieldId) {\n";
    $code .= "        var mediaUploader;\n";
    $code .= "        \n";
    $code .= "        if (mediaUploader) {\n";
    $code .= "            mediaUploader.open();\n";
    $code .= "            return;\n";
    $code .= "        }\n";
    $code .= "        \n";
    $code .= "        mediaUploader = wp.media({\n";
    $code .= "            title: 'Seleziona Media',\n";
    $code .= "            button: {\n";
    $code .= "                text: 'Usa questo media'\n";
    $code .= "            },\n";
    $code .= "            multiple: false\n";
    $code .= "        });\n";
    $code .= "        \n";
    $code .= "        mediaUploader.on('select', function() {\n";
    $code .= "            var attachment = mediaUploader.state().get('selection').first().toJSON();\n";
    $code .= "            jQuery('#' + fieldId).val(attachment.id);\n";
    $code .= "            \n";
    $code .= "            // Update preview\n";
    $code .= "            if (attachment.type === 'image') {\n";
    $code .= "                jQuery('#' + fieldId + '_preview').html('<img src=\"' + attachment.sizes.thumbnail.url + '\" style=\"max-width: 200px;\" />');\n";
    $code .= "            }\n";
    $code .= "        });\n";
    $code .= "        \n";
    $code .= "        mediaUploader.open();\n";
    $code .= "    }\n";
    $code .= "    </script>\n";
    $code .= "    <?php\n";
    $code .= "}\n";
    $code .= "add_action( 'admin_footer', '{$id}_admin_scripts' );\n";
}

// Add color picker script if needed
$hasColorField = false;
foreach ($fieldTypes as $type) {
    if ($type === 'color') {
        $hasColorField = true;
        break;
    }
}

if ($hasColorField) {
    $code .= "\n// Color Picker Script\n";
    $code .= "function {$id}_color_picker_scripts( \$hook ) {\n";
    $code .= "    if ( 'post.php' !== \$hook && 'post-new.php' !== \$hook ) {\n";
    $code .= "        return;\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    wp_enqueue_style( 'wp-color-picker' );\n";
    $code .= "    wp_enqueue_script( 'wp-color-picker' );\n";
    $code .= "    ?>\n";
    $code .= "    <script type=\"text/javascript\">\n";
    $code .= "    jQuery(document).ready(function(\$) {\n";
    $code .= "        \$('.color-field').wpColorPicker();\n";
    $code .= "    });\n";
    $code .= "    </script>\n";
    $code .= "    <?php\n";
    $code .= "}\n";
    $code .= "add_action( 'admin_enqueue_scripts', '{$id}_color_picker_scripts' );\n";
}

$code .= "\n?>";

// Output the generated code
echo $code;