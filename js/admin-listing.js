document.addEventListener('DOMContentLoaded', function () {
    const artistInput = document.getElementById('listing-artist');
    const albumInput = document.getElementById('listing-album');
    const typeInput = document.getElementById('listing-type');
    const mediaInput = document.getElementById('listing-media');

    if (!artistInput || !albumInput || !typeInput || !mediaInput) {
        return;
    }

    function filterAlbums() {
        for (const option of albumInput.options) {
            if (!option.value) {
                continue;
            }

            option.hidden = option.dataset.artist !== artistInput.value;
            option.disabled = option.hidden;
        }

        if (albumInput.selectedOptions[0]?.disabled) {
            albumInput.value = '';
        }
    }

    function filterMedia() {
        mediaInput.accept = typeInput.value === 'audio'
            ? '.mp3,.m4a,.wav,.aac'
            : '.mp4,.webm,.mov';
    }

    artistInput.addEventListener('change', filterAlbums);
    typeInput.addEventListener('change', filterMedia);

    filterAlbums();
    filterMedia();
});