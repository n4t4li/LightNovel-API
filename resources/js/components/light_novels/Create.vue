<template>
    <div>
        <h1>Create Light Novel</h1>

        <form @submit.prevent="createLightNovel" enctype="multipart/form-data">
            <div>
                <label>Title</label>
                <input v-model="form.titre" required />
            </div>
            <div>
                <label>Author</label>
                <input v-model="form.auteur" required />
            </div>
            <div>
                <label>Status</label>
                <input v-model="form.statut" />
            </div>
            <div>
                <label>Chapters</label>
                <input type="number" v-model="form.chapitres" min="0" />
            </div>
            <div>
                <label>Content</label>
                <textarea v-model="form.Contenu"></textarea>
            </div>

            <div>
                <label>Photo</label>

                <div
                    class="dropzone"
                    @dragover.prevent
                    @dragenter.prevent="onDragEnter"
                    @dragleave.prevent="onDragLeave"
                    @drop.prevent="onDrop"
                    :class="{ 'is-dragover': isDragOver }"
                    style="border: 2px dashed #ccc; padding: 1rem; text-align: center; cursor: pointer;"
                    @click="triggerFileSelect"
                >
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/*"
                        style="display: none"
                        @change="handleFileUpload"
                    />

                    <div v-if="photoPreview">
                        <img :src="photoPreview" alt="preview" style="max-width: 100%; max-height: 300px;" />
                        <div style="margin-top: 0.5rem">
                            <button type="button" @click.stop.prevent="removePhoto">Remove</button>
                        </div>
                    </div>
                    <div v-else>
                        <p>Drag & drop an image here, or click to select.</p>
                    </div>
                </div>
            </div>

            <button type="submit">Create</button>
        </form>
    </div>
</template>

<script>
import api from "../../axios";

export default {
    name: "CreateLightNovel",
    data() {
        return {
            form: {
                titre: "",
                auteur: "",
                statut: "",
                chapitres: 0,
                Contenu: "",
            },
            photoFile: null,
            photoPreview: null,
            isDragOver: false,
        };
    },
    methods: {
        triggerFileSelect() {
            this.$refs.fileInput.click();
        },
        handleFileUpload(e) {
            const file = e.target.files ? e.target.files[0] : e;
            if (!file) return;
            this.setPhotoFile(file);
        },
        onDragEnter() {
            this.isDragOver = true;
        },
        onDragLeave() {
            this.isDragOver = false;
        },
        onDrop(e) {
            this.isDragOver = false;
            const file = e.dataTransfer.files && e.dataTransfer.files[0];
            if (file) this.setPhotoFile(file);
        },
        setPhotoFile(file) {
            if (!file.type.startsWith("image/")) return;
            this.photoFile = file;
            if (this.photoPreview) URL.revokeObjectURL(this.photoPreview);
            this.photoPreview = URL.createObjectURL(file);
        },
        removePhoto() {
            this.photoFile = null;
            if (this.photoPreview) {
                URL.revokeObjectURL(this.photoPreview);
                this.photoPreview = null;
            }
            // clear file input value
            if (this.$refs.fileInput) this.$refs.fileInput.value = null;
        },
        async createLightNovel() {
            try {
                const formData = new FormData();
                for (const key in this.form) {
                    formData.append(key, this.form[key]);
                }
                if (this.photoFile) formData.append("photo", this.photoFile);

                await api.post("/api/lightnovels", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                this.$router.push({ name: "lightnovels" });
            } catch (err) {
                console.error(err);
            }
        },
    },
    beforeUnmount() {
        if (this.photoPreview) URL.revokeObjectURL(this.photoPreview);
    },
};
</script>
