$(document).ready(function () {
    // Play song when clicked
    $('.song-row').on('click', function () {
        const songId = $(this).data('id');

        $.ajax({
            url: 'api/songs/index.php?id=' + songId,
            type: 'GET',
            success: function (response) {
                updatePlayer(response);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

    function updatePlayer(song) {
        $('#player-title').text(song.title);
        $('#player-artist').text(song.artist);
        $('#audio-source').attr('src', song.file_path);

        const audioPlayer = document.getElementById('audio-player');
        audioPlayer.load();
        audioPlayer.play();
    }

    // Other AJAX calls for playlists, user actions, etc.
});