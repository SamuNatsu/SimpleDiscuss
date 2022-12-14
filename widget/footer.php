<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

$ICP = unserialize(Database::readOption('ICP'));

?>
    <footer class="flex-col flex-c-center forum-footer">
        <div>Copyright © <?php echo date('Y', time()); ?> <?php echo $this->sitename; ?></div>
        <div>All Rights Reserved</div>
        <?php if ($ICP['enable']): ?>
        <div><?php echo $ICP['content']; ?></div>
        <?php endif; ?>
    </footer>
    <script src="<?php Path::_url('js', '/script.js'); ?>"></script>
</body>
</html>
