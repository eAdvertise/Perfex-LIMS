<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="customer-profile-group-heading">
    <?php echo _l('customer_attachments') ?: 'Files'; ?>
</h4>

<?php if (isset($subject)) : ?>

    <?php
    // Χρησιμοποιούμε το ίδιο ID όπως στα clients, για να δουλέψει το core Dropzone JS (refresh κλπ)
    echo form_open_multipart(
        admin_url('lims/subjects/upload_attachment/' . (int) $subject->id),
        ['class' => 'dropzone', 'id' => 'client-attachments-upload']
    ); ?>
        <input type="file" name="file" multiple />
    <?php echo form_close(); ?>

    <div class="attachments">
        <div class="mtop25">
            <table class="table dt-table" data-order-col="1" data-order-type="desc">
                <thead>
                    <tr>
                        <th width="30%">
                            <?php echo _l('customer_attachments_file'); ?>
                        </th>
                        <th>
                            <?php echo _l('file_date_uploaded'); ?>
                        </th>
                        <th>
                            <?php echo _l('options'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // misc_model->get_attachments() => array of stdClass
                    $files = is_array($attachments) ? $attachments : [];

                    foreach ($files as $_att) {

                        // stdClass, ΟΧΙ array
                        $subject_id = (int) $subject->id;

                        // upload_path έρχεται από misc_model->get_attachments (όπως στον core)
                        $path = isset($_att->upload_path) ? $_att->upload_path : '';

                        // fallback αν για κάποιο λόγο είναι άδειο
                        if (empty($path) && !empty($_att->file_name)) {
                            $path = 'uploads/lims_subjects/' . $subject_id . '/' . $_att->file_name;
                        }

                        $is_image    = false;
                        $img_url     = '';
                        $lightBoxUrl = '';

                        if (!empty($path)) {
                            $is_image    = is_image($path);
                            $img_url     = site_url(
                                'download/preview_image?path='
                                . protected_file_url_by_path($path, true)
                                . '&type=' . $_att->filetype
                            );
                            $lightBoxUrl = site_url(
                                'download/preview_image?path='
                                . protected_file_url_by_path($path)
                                . '&type=' . $_att->filetype
                            );
                        }

                        // download URL: αν είναι external → external_link, αλλιώς download_url από misc_model
                        if (!empty($_att->external) && !empty($_att->external_link)) {
                            $attachment_url = $_att->external_link;
                        } else {
                            $attachment_url = isset($_att->download_url) ? $_att->download_url : '#';
                        }
                    ?>
                        <tr id="tr_file_<?php echo e($_att->id); ?>">
                            <td>
                                <?php if ($is_image) : ?>
                                    <div class="preview_image">
                                        <a href="<?php echo $lightBoxUrl ?: $img_url; ?>"
                                           data-lightbox="lims-subject-files"
                                           class="display-block mbot5">
                                            <div class="table-image">
                                                <div class="text-center">
                                                    <i class="fa fa-spinner fa-spin mtop30"></i>
                                                </div>
                                                <img src="#"
                                                     class="img-table-loading"
                                                     data-orig="<?php echo e($img_url); ?>">
                                            </div>
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <a href="<?php echo $attachment_url; ?>" class="display-block mbot5" target="_blank">
                                        <i class="<?php echo get_mime_class($_att->filetype); ?>"></i>
                                        <?php echo e($_att->file_name); ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td data-order="<?php echo e($_att->dateadded); ?>">
                                <?php echo _dt($_att->dateadded); ?>
                            </td>
                            <td>
                                <div class="tw-flex tw-items-center tw-space-x-2">
                                    <a href="<?php echo $attachment_url; ?>"
                                       class="tw-text-neutral-500 hover:tw-text-neutral-700"
                                       target="_blank">
                                        <i class="fa-regular fa-circle-down fa-lg"></i>
                                    </a>

                                    <a href="<?php echo admin_url(
                                        'lims/subjects/delete_attachment/'
                                        . $subject_id . '/'
                                        . (int) $_att->id
                                    ); ?>"
                                       class="tw-text-neutral-500 hover:tw-text-neutral-700 _delete">
                                        <i class="fa-regular fa-trash-can fa-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
