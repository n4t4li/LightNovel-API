<template>
    <div v-if="form">
        <h1>Edit Light Novel</h1>

        <form @submit.prevent="updateLightNovel" enctype="multipart/form-data">
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
                <input type="number" v-model.number="form.chapitres" min="0" />
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

            <button type="submit">Save</button>
        </form>
    </div>
</template>

<script>
import api from "../../axios";

export default {
    name: "EditLightNovel",
    data() {
        return {
            form: null,
            photoFile: null,
            photoPreview: null,
            isDragOver: false,
        };
    },
    async mounted() {
        const id = this.$route.params.id;
        try {
            const res = await api.get(`/api/lightnovels/${id}`);
            this.form = res.data.data;
            if (this.form?.photo) {
                // assume photos stored in public/images
                this.photoPreview = `/images/${this.form.photo}`;
            }
        } catch (err) {
            console.error(err);
        }
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
            if (this.photoPreview && this.photoPreview.startsWith("blob:")) {
                URL.revokeObjectURL(this.photoPreview);
            }
            this.photoPreview = URL.createObjectURL(file);
        },
        removePhoto() {
            this.photoFile = null;
            if (this.photoPreview && this.photoPreview.startsWith("blob:")) {
                URL.revokeObjectURL(this.photoPreview);
            }
            this.photoPreview = null;
            if (this.$refs.fileInput) this.$refs.fileInput.value = null;
        },
        async updateLightNovel() {
            try {
                await api.get("/sanctum/csrf-cookie");

                const formData = new FormData();
                for (const key in this.form) {
                    if (
                        this.form[key] !== null &&
                        this.form[key] !== undefined
                    ) {
                        formData.append(key, this.form[key]);
                    }
                }

                if (this.photoFile) {
                    formData.append("photo", this.photoFile);
                }

                formData.append("_method", "PUT");

                await api.post(`/api/lightnovels/${this.form.id}`, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                this.$router.push({ name: "lightnovels" });
            } catch (err) {
                console.error(err.response?.data || err);
                if (err.response?.data?.data) {
                    console.log("Validation errors:", err.response.data.data);
                }
            }
        },
    },
    beforeUnmount() {
        if (this.photoPreview && this.photoPreview.startsWith("blob:")) {
            URL.revokeObjectURL(this.photoPreview);
        }
    },
};
</script>
