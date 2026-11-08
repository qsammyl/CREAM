// Disable Trix file/image attachments — the cover image is a dedicated upload,
// and dropped images would be stripped by the server-side sanitiser anyway.
document.addEventListener('trix-file-accept', function (event) {
  event.preventDefault();
});
