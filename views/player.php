<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/navbar.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar with playlists -->
            <div class="list-group">
                <?php foreach ($playlists as $playlist): ?>
                    <a href="#" class="list-group-item list-group-item-action"><?= htmlspecialchars($playlist['name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-9">
            <!-- Song list -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($songs as $index => $song): ?>
                        <tr class="song-row" data-id="<?= $song['id'] ?>">
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($song['title']) ?></td>
                            <td><?= htmlspecialchars($song['artist']) ?></td>
                            <td><?= $song['duration'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'partials/player.php'; ?>
<?php require_once 'partials/footer.php'; ?>