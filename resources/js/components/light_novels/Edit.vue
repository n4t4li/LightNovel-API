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
                <input type="file" @change="handleFileUpload" />
            </div>

            <button type="submit">Save</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "EditLightNovel",
    data() {
        return {
            form: null,
            photoFile: null,
        };
    },
    async mounted() {
        const id = this.$route.params.id;
        try {
            const res = await axios.get(`/api/lightnovels/${id}`);
            this.form = res.data.data;
        } catch (err) {
            console.error(err);
        }
    },
    methods: {
        handleFileUpload(e) {
            this.photoFile = e.target.files[0];
        },
        async updateLightNovel() {
            try {
                await axios.get("http://127.0.0.1:8000/sanctum/csrf-cookie");

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

                await axios.post(`/api/lightnovels/${this.form.id}`, formData, {
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
};
</script>
