<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Discussion Settings</h1>

    <form method="POST" action="/admin/settings/discussion" class="box" style="padding: 20px; margin-top: 20px; max-width:800px;">
        <?= \App\Support\Csrf::field() ?>

        <table class="form-table" style="width: 100%; border-collapse: collapse;">

            <!-- Default post settings -->
            <tr>
                <th style="text-align:left; padding: 14px 0; vertical-align:top; width:240px;">
                    <label style="font-weight:bold;">Default post settings</label>
                </th>
                <td style="padding: 14px 0;">
                    <label style="display:block; margin-bottom:6px;">
                        <input type="checkbox" name="allow_comments" value="1"
                            <?= ($options['allow_comments'] ?? '1') === '1' ? 'checked' : '' ?>>
                        Allow people to submit comments on new posts
                    </label>
                    <label style="display:block;">
                        <input type="checkbox" name="close_comments_days" value="1"
                            <?= ($options['close_comments_days'] ?? '0') === '1' ? 'checked' : '' ?>>
                        Automatically close comments after
                        <input type="number" name="close_comments_after" min="1" max="9999"
                            value="<?= (int)($options['close_comments_after'] ?? 14) ?>"
                            style="width:60px; padding:2px 4px; margin:0 4px;">
                        days
                    </label>
                </td>
            </tr>

            <tr><td colspan="2"><hr style="border:none; border-top:1px solid #eee;"></td></tr>

            <!-- Email me whenever -->
            <tr>
                <th style="text-align:left; padding: 14px 0; vertical-align:top;">
                    <label style="font-weight:bold;">Email me whenever</label>
                </th>
                <td style="padding: 14px 0;">
                    <label style="display:block; margin-bottom:6px;">
                        <input type="checkbox" name="notify_new_comment" value="1"
                            <?= ($options['notify_new_comment'] ?? '0') === '1' ? 'checked' : '' ?>>
                        Anyone posts a comment
                    </label>
                    <label style="display:block;">
                        <input type="checkbox" name="notify_moderation" value="1"
                            <?= ($options['notify_moderation'] ?? '0') === '1' ? 'checked' : '' ?>>
                        A comment is held for moderation
                    </label>
                </td>
            </tr>

            <tr><td colspan="2"><hr style="border:none; border-top:1px solid #eee;"></td></tr>

            <!-- Before a comment appears -->
            <tr>
                <th style="text-align:left; padding: 14px 0; vertical-align:top;">
                    <label style="font-weight:bold;">Before a comment appears</label>
                </th>
                <td style="padding: 14px 0;">
                    <label style="display:block; margin-bottom:6px;">
                        <input type="checkbox" name="comment_moderation" value="1"
                            <?= ($options['comment_moderation'] ?? '1') === '1' ? 'checked' : '' ?>>
                        Comment must be manually approved
                    </label>
                    <label style="display:block;">
                        <input type="checkbox" name="comment_previously_approved" value="1"
                            <?= ($options['comment_previously_approved'] ?? '0') === '1' ? 'checked' : '' ?>>
                        Comment author must have a previously approved comment
                    </label>
                </td>
            </tr>

            <tr><td colspan="2"><hr style="border:none; border-top:1px solid #eee;"></td></tr>

            <!-- Spam filter -->
            <tr>
                <th style="text-align:left; padding: 14px 0; vertical-align:top;">
                    <label style="font-weight:bold;">Comment spam filter</label>
                </th>
                <td style="padding: 14px 0;">
                    <label style="display:block; margin-bottom:4px;">
                        Hold a comment in queue if it contains
                        <input type="number" name="comment_max_links" min="0" max="99"
                            value="<?= (int)($options['comment_max_links'] ?? 2) ?>"
                            style="width:50px; padding:2px 4px; margin:0 4px;">
                        or more links.
                    </label>
                </td>
            </tr>

            <tr><td colspan="2"><hr style="border:none; border-top:1px solid #eee;"></td></tr>

            <!-- Comment blacklist -->
            <tr>
                <th style="text-align:left; padding: 14px 0; vertical-align:top;">
                    <label for="comment_blacklist" style="font-weight:bold;">Disallowed comment keys</label>
                </th>
                <td style="padding: 14px 0;">
                    <p style="font-size:12px; color:#555; margin-top:0;">
                        When a comment contains any of these words in its content, name, URL, email, or IP, it will be moved to trash.
                        Put each word or IP on its own line.
                    </p>
                    <textarea id="comment_blacklist" name="comment_blacklist"
                        style="width:100%; height:120px; padding:8px; box-sizing:border-box; font-family:monospace; font-size:12px;"><?= \App\Support\View::escape($options['comment_blacklist'] ?? '') ?></textarea>
                </td>
            </tr>

        </table>

        <p class="submit" style="margin-top: 20px; padding-top:15px; border-top:1px solid #eee;">
            <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer; border-radius: 3px;">
                Save Changes
            </button>
        </p>
    </form>
</div>
