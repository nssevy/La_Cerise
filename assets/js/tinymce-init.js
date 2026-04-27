tinymce.init({
  selector: "#contenu",
  language: "fr_FR",
  plugins: "lists link image code",
  toolbar:
    "undo redo | bold italic | h2 h3 | bullist numlist | blockquote | link image | code",
  menubar: false,
  selector: "#contenu",
  plugins: "autoresize bold italic lists link image code",
  autoresize_bottom_margin: 0,
  min_height: 300,
});
