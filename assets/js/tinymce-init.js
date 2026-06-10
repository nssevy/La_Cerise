const navOffset = document.querySelector("#admin-nav")?.offsetHeight ?? 0;

tinymce.init({
  selector: "#contenu",
  language: "fr_FR",
  toolbar:
    "undo redo | bold italic | h2 | bullist numlist | blockquote | credit | link image | code",
  menubar: false,
  plugins: "autoresize lists link image code",
  autoresize_bottom_margin: 0,
  min_height: 300,
  toolbar_sticky: true,
  toolbar_sticky_offset: navOffset,

  content_style: `
    .citation-credit {
      color: #888;
      font-size: 0.875rem;
      padding-left: 0.75rem;
      border-left: 2px dashed #ccc;
      font-style: italic;
    }
  `,

  formats: {
    citation_credit: {
      block: "p",
      classes: "citation-credit",
      exact: true,
    },
  },

  setup: (editor) => {
    editor.ui.registry.addToggleButton("credit", {
      text: "crédit",
      tooltip: "Crédit de citation",
      onAction: () =>
        editor.execCommand("mceToggleFormat", false, "citation_credit"),
      onSetup: (api) => {
        const changed = editor.formatter.formatChanged(
          "citation_credit",
          (state) => {
            api.setActive(state);
          },
        );
        return () => changed.unbind();
      },
    });
  },
});
