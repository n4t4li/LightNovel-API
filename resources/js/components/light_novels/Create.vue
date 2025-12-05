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
                <input type="file" @change="handleFileUpload" />
            </div>

            <button type="submit">Create</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

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
        };
    },
    methods: {
        handleFileUpload(e) {
            this.photoFile = e.target.files[0];
        },
        async createLightNovel() {
            try {
                const formData = new FormData();
                for (const key in this.form) {
                    formData.append(key, this.form[key]);
                }
                if (this.photoFile) formData.append("photo", this.photoFile);

                await axios.post("/api/lightnovels", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                this.$router.push({ name: "lightnovels" });
            } catch (err) {
                console.error(err);
            }
        },
    },
};
</script>
