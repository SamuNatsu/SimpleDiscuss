<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

Section::fetchAll();

$title = Database::readOption('site_name');
$header = unserialize(Database::readOption('header_block'));

Widget::get('header')->title = 'Admin | ' . $title;
Widget::get('header')->sitename = $title;

Widget::get('footer')->sitename = $title;

Widget::get('header')->render();

Moderator::fetchAll();
User::fetchAll();
?>

<div class="flex-row flex-m-evenly forum-main">
    <div class="flex-col forum-section">
        <div class="forum-block">
            <h2>Site options</h2>
            <hr class="forum-section-hr"/>
            <h3>Site name</h3>
            <input id="admin-sitename-input" class="login-input" type="text" value="<?php echo $title ?>"/>
            <div id="admin-site-options-submit" class="btn" style="width:fit-content">Update</div>
        </div>
        <div class="forum-block">
            <h2>Moderators</h2>
            <hr class="forum-section-hr"/>
            <div class="admin-moderator-op flex-row">
                <div class="admin-moderator-sec flex-row">Section:&emsp;<select id="admin-moderator-sid">
                    <option value="0"></option>
                <?php Section::begin(); ?>
                <?php while (Section::next()): ?>
                    <option value="<?php echo Section::get('sid')?>"><?php echo Section::get('name'); ?></option>
                <?php endwhile; ?>
                </select></div>
                <div class="admin-moderator-sec flex-row">Uid:&emsp;<input id="admin-moderator-uid" type="text"/></div>
                <div><button id="admin-moderator-submit">Add moderator</button></div>
            </div>
            <table class="admin-moderator-table">
                <tr>
                    <th>sid</th>
                    <th>Section name</th>
                    <th>uid</th>
                    <th>User name </th>
                    <th>User email</th>
                    <th>Operation</th>
                </tr>
            <?php Moderator::begin(); ?>
            <?php while (Moderator::next()): ?>
                <tr>
                    <td><?php echo Moderator::get('sid'); ?></td>
                    <td><?php echo Moderator::get('sname'); ?></td>
                    <td><?php echo Moderator::get('uid'); ?></td>
                    <td><?php echo Moderator::get('uname'); ?></td>
                    <td><?php echo Moderator::get('email'); ?></td>
                    <td><a href="#" class="admin-moderator-delete op-btn" data-sid="<?php echo Moderator::get('sid'); ?>" data-uid="<?php echo Moderator::get('uid'); ?>">Delete</a></td>
                </tr>
            <?php endwhile; ?>
            </table>
            <style>
                .admin-moderator-op {
                    margin: 20px 0;
                }
                .admin-moderator-op>div {
                    margin: 0 10px;
                }
                .admin-moderator-sec {
                    font-weight: bold;
                }
                .admin-moderator-table {
                    border: 1px solid lightgray;
                    border-collapse: collapse;
                    width: 100%;
                }
                .admin-moderator-table th {
                    border-bottom: 2px solid gray;
                    padding: 10px 0;
                }
                .admin-moderator-table td {
                    text-align: center;
                    padding: 10px 0;
                    border-botttom: 1px solid lightgray;
                }
            </style>
        </div>
        <div class="forum-block">
            <h2>Users</h2>
            <hr class="forum-section-hr"/>
            <table class="admin-moderator-table">
                <tr>
                    <th>uid</th>
                    <th>User name </th>
                    <th>User email</th>
                    <th>Operation</th>
                </tr>
            <?php User::begin(); ?>
            <?php while (User::next()): ?>
                <tr>
                    <td><?php echo User::itrget('uid'); ?></td>
                    <td><span <?php 
                        if (User::itrget('privilege') > 1) 
                            echo 'style="color:red"';
                        else if (User::itrget('privilege') > 0)
                            echo 'style="color:orange"';
                        ?>><?php echo User::itrget('name'); ?></span></td>
                    <td><?php echo User::itrget('email'); ?></td>
                    <td><a href="#" class="admin-user-delete op-btn" data-uid="<?php echo User::itrget('uid'); ?>">Delete</a> | <a href="#" class="admin-user-rename op-btn" data-uid="<?php echo User::itrget('uid'); ?>">Rename</a> | <a href="#" class="admin-user-repass op-btn" data-uid="<?php echo User::itrget('uid'); ?>">Reset password</a> | <a href="#" class="admin-user-repriv op-btn" data-uid="<?php echo User::itrget('uid'); ?>">Set privilege</a></td>
                </tr>
            <?php endwhile; ?>
            </table>
        </div>
    </div>
    <?php Widget::get('sidebar')->render(); ?>
</div>

<?php Widget::get('footer')->render(); ?>
