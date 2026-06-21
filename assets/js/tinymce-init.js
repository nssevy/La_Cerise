const navOffset = document.querySelector("#admin-nav")?.offsetHeight ?? 0;

tinymce.init({
  selector: "#contenu",
  language: "fr_FR",
  toolbar:
    "undo redo | bold italic cerise | h2 | bullist numlist | blockquote | credit | code",
  menubar: false,
  plugins: "autoresize lists link image code",
  autoresize_bottom_margin: 0,
  min_height: 300,
  toolbar_location: "bottom",
  toolbar_sticky: true,
  toolbar_sticky_offset: 0,

  content_style: `
    blockquote {
      border-left: 2px dotted #000 !important;
      padding-left: 1rem !important;
      margin: 16px 40px 16px 24px !important;
    }
    .citation-credit {
      color: #888;
      font-size: 0.875rem;
      padding-left: 0.75rem;
      border-left: 2px dotted #ccc;
      margin: 16px 40px 16px 24px !important;
      font-style: italic;
    }
    .cerise-text {
      color: #d01152;
    }
  `,

  formats: {
    citation_credit: {
      block: "p",
      classes: "citation-credit",
      exact: true,
    },
    cerise_text: {
      inline: "span",
      classes: "cerise-text",
    },
  },

  setup: (editor) => {
    editor.ui.registry.addToggleButton("cerise", {
      text: "Rose",
      tooltip: "Texte en rose cerise",
      onAction: () =>
        editor.execCommand("mceToggleFormat", false, "cerise_text"),
      onSetup: (api) => {
        const changed = editor.formatter.formatChanged("cerise_text", (state) => {
          api.setActive(state);
        });
        return () => changed.unbind();
      },
    });

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
