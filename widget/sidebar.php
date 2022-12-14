<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

?>
<div class="flex-col forum-sidebar">
    <div class="forum-block forum-block-user">
        <?php if (User::isLogin()): ?>
        <div class="flex-col">
            <h2 <?php 
                if (User::get('privilege') > 1) 
                    echo 'style="color:red"';
                else if (User::get('privilege') > 0)
                    echo 'style="color:orange"';
            ?>><?php echo User::get('name') ?></h2>
            <p><?php echo User::get('email') ?></p>
            <hr class="forum-thread-hr"/>
            <div class="rename-btn btn" data-uid="<?php echo User::get('uid') ?>">Rename</div>
            <div class="change-pass-btn btn" data-uid="<?php echo User::get('uid') ?>">Change password</div>
            <?php if (User::get('privilege') > 0): ?>
            <div class="to-admin btn">Admin</div>
            <?php endif; ?>
            <hr class="forum-thread-hr"/>
            <div class="logout-submit">Logout</div>
        </div>
        <?php else: ?>
        <div class="flex-col">
            <input id="login-username" class="login-input" type="text" placeholder="Email"/>
            <input id="login-password" class="login-input" type="password" placeholder="Password"/>
            <div class="login-warn"></div>
            <div class="login-submit">Login / Register</div>
        </div>
        <?php endif; ?>
    </div>
</div>
