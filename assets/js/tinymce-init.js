const navOffset = document.querySelector('#admin-nav')?.offsetHeight ?? 0;

tinymce.init({
  selector: "#contenu",
  language: "fr_FR",
  toolbar: "undo redo | bold italic | h2 | bullist numlist | blockquote | link image | code",
  menubar: false,
  plugins: "autoresize lists link image code",
  autoresize_bottom_margin: 0,
  min_height: 300,
  toolbar_sticky: true,
  toolbar_sticky_offset: navOffset,
});
