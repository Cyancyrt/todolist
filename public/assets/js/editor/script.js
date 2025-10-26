document.addEventListener("DOMContentLoaded", function () {
  const editor = new EditorJS({
    holder: "editorjs",
    tools: {
      header: {
        class: Header,
        inlineToolbar: ["link"],
      },
      list: {
        class: List,
        inlineToolbar: true,
      },
    },
  });

  editor
    .save()
    .then((outputData) => {
      console.log("Article data: ", outputData);
    })
    .catch((error) => {
      console.log("Saving failed: ", error);
    });
});
