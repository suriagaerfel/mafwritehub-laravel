import "./App.css";
import "./App.jsx";
import React, { useCallback } from "react";
import { EditorContent, useEditor } from "@tiptap/react";
import StarterKit from "@tiptap/starter-kit";
import Superscript from "@tiptap/extension-superscript";
import Subscript from "@tiptap/extension-subscript";
import TextAlign from "@tiptap/extension-text-align";
import Image from "@tiptap/extension-image";
import { Table } from "@tiptap/extension-table";
import { TableRow } from "@tiptap/extension-table-row";
import { TableCell } from "@tiptap/extension-table-cell";
import { TableHeader } from "@tiptap/extension-table-header";
import Placeholder from "@tiptap/extension-placeholder";
import { marked } from "marked";

export default function Editor() {
    const editor = useEditor({
        extensions: [
            StarterKit,
            Superscript,
            Subscript,
            TextAlign.configure({
                types: ["heading", "paragraph"],
            }),
            Image.configure({
                inline: false,
            }),
            Table.configure({
                resizable: true,
            }),
            TableRow,
            TableCell,
            TableHeader,
            Placeholder.configure({
                placeholder: "Start typing...",
            }),
        ],
        content: "<p>Hello world</p>",
    });

    // Drag & Drop Upload
    const handleDrop = useCallback(
        (event) => {
            event.preventDefault();
            const file = event.dataTransfer.files[0];

            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                if (file.type.startsWith("image")) {
                    editor
                        .chain()
                        .focus()
                        .setImage({ src: reader.result })
                        .run();
                } else if (file.type.startsWith("video")) {
                    editor
                        .chain()
                        .focus()
                        .insertContent(
                            `<video controls src="${reader.result}" />`,
                        )
                        .run();
                }
            };
            reader.readAsDataURL(file);
        },
        [editor],
    );

    // Upload via input
    const uploadFile = (file) => {
        const reader = new FileReader();
        reader.onload = () => {
            if (file.type.startsWith("image")) {
                editor.chain().focus().setImage({ src: reader.result }).run();
            } else {
                editor
                    .chain()
                    .focus()
                    .insertContent(`<video controls src="${reader.result}" />`)
                    .run();
            }
        };
        reader.readAsDataURL(file);
    };

    // Markdown import
    const importMarkdown = () => {
        const md = prompt("Paste Markdown:");
        if (md) {
            const html = marked(md);
            editor.commands.setContent(html);
        }
    };

    // Markdown export
    const exportMarkdown = () => {
        const html = editor.getHTML();
        alert(html); // replace with proper converter if needed
    };

    if (!editor) return null;

    return (
        <div onDrop={handleDrop} onDragOver={(e) => e.preventDefault()}>
            <Toolbar editor={editor} uploadFile={uploadFile} />
            <EditorContent editor={editor} />
            <button onClick={importMarkdown}>Import MD</button>
            <button onClick={exportMarkdown}>Export HTML</button>
        </div>
    );
}

function Toolbar({ editor, uploadFile }) {
    if (!editor) return null;

    return (
        <div className="toolbar">
            <button
                className={editor.isActive("bold") ? "active" : ""}
                onClick={() => editor.chain().focus().toggleBold().run()}
            >
                Bold
            </button>

            <button onClick={() => editor.chain().focus().toggleItalic().run()}>
                Italic
            </button>

            <button
                onClick={() => editor.chain().focus().toggleUnderline().run()}
            >
                Underline
            </button>

            <button
                onClick={() => editor.chain().focus().toggleSuperscript().run()}
            >
                Sup
            </button>

            <button
                onClick={() => editor.chain().focus().toggleSubscript().run()}
            >
                Sub
            </button>

            <button
                onClick={() =>
                    editor.chain().focus().setTextAlign("left").run()
                }
            >
                Left
            </button>

            <button
                onClick={() =>
                    editor.chain().focus().setTextAlign("center").run()
                }
            >
                Center
            </button>

            <button
                onClick={() =>
                    editor.chain().focus().setTextAlign("right").run()
                }
            >
                Right
            </button>

            <button
                onClick={() => editor.chain().focus().toggleBulletList().run()}
            >
                Bullet List
            </button>

            <button
                onClick={() => editor.chain().focus().toggleOrderedList().run()}
            >
                Numbered List
            </button>

            <button
                onClick={() =>
                    editor
                        .chain()
                        .focus()
                        .insertTable({ rows: 3, cols: 3 })
                        .run()
                }
            >
                Table
            </button>

            {/* Image URL */}
            <button
                onClick={() => {
                    const url = prompt("Image URL");
                    if (url)
                        editor.chain().focus().setImage({ src: url }).run();
                }}
            >
                Image URL
            </button>

            {/* Video URL */}
            <button
                onClick={() => {
                    const url = prompt("Video URL");
                    if (url) {
                        editor
                            .chain()
                            .focus()
                            .insertContent(`<video controls src="${url}" />`)
                            .run();
                    }
                }}
            >
                Video URL
            </button>

            {/* Upload */}
            <input
                type="file"
                onChange={(e) => uploadFile(e.target.files[0])}
            />
        </div>
    );
}
